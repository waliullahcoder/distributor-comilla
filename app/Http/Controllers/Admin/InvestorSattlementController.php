<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Wallet;
use App\Models\Invest;
use App\Models\Investor;
use App\Models\CoaSetup;
use Illuminate\Http\Request;
use App\Models\AdminSetting;
use App\Models\InvestorSattlement;
use Illuminate\Support\Facades\DB;
use App\Models\AccountTransaction;
use App\Models\Scopes\CompanyScope;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\AccountTransactionAuto;
use App\Models\InvestorSattlementList;
use Yajra\DataTables\Facades\DataTables;
use App\Services\ActionButtons\ActionButtons;

class InvestorSattlementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->ajax()) {
            $model = InvestorSattlement::with(['company', 'investor'])->orderBy('id', 'desc');
            $type = request('type');
            if (!empty($type) && $type == 'trash') {
                $model->onlyTrashed();
            }
            return DataTables::eloquent($model)
                ->addColumn('checkbox', function ($row) {
                    $checkbox = '<div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input ' . (!empty(request('type')) && request('type') == "trash" ? 'trash_multi_checkbox' : 'multi_checkbox') . '" id="' . $row->id . '" name="multi_checkbox[]" value="' . $row->id . '"><label for="' . $row->id . '" class="custom-control-label"></label></div>';
                    return $checkbox;
                })
                ->addColumn('date', function ($row) {
                    return date('d-m-Y', strtotime(@$row->date));
                })
                ->addColumn('actions', function ($row) {
                    $type = request('type');
                    $data = [
                        'id' => $row->id,
                        'edit' => !empty($type) && $type == 'trash' ? false : true,
                    ];

                    $transaction = AccountTransaction::where('voucher_no', $row->serial_no)
                        ->where('credit_amount', '>', 0)
                        ->where('voucher_type', 'Sattlement')
                        ->first();
                    if ($row->approved == 0 && is_null($transaction)) {
                        return ActionButtons::actions($data);
                    }
                })
                ->rawColumns(['checkbox', 'actions'])
                ->make(true);
        }

        $title = "Investor Sattlement";
        return view('admin.investor_sattlement.index', compact('title'));
    }

    public function invoice()
    {
        $first = date('Y-m-01');
        $last = new Carbon('last day of this month');
        $data = InvestorSattlement::withoutGlobalScope(CompanyScope::class)->withTrashed()->select(['serial_no'])->whereDate('created_at', '>=', $first)->whereDate('created_at', '<=', $last)->latest('id')->first();
        if ($data) {
            $trim = str_replace("STSM", '', $data->serial_no);
            $dataPrefix = (int)$trim + 1;
            $invoice = "STSM" . $dataPrefix;
        } else {
            $invoice = "STSM" . date('y') . date('m') . '000001';
        }
        return $invoice;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        if ($request->ajax()) {
            $data = Invest::where('investor_id', $request->investor_id)->where('sattled', 0)->get();
            info($data);
            return response()->json(['status' => 'success', 'data' => $data]);
        }

        $title = 'Add New Sattlement';
        $serial_no = $this->invoice();
        $cash_heads = CoaSetup::with('parent')->whereHas('parent', function ($query) {
            $query->where('head_name', 'Cash In Hand')->orWhere('head_name', 'Cash In Bank');
        })->get();
        $investors = Investor::where('status', 1)->get();
        return view('admin.investor_sattlement.create', compact('title', 'serial_no', 'cash_heads', 'investors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'investor_id' => 'required',
            'coa_setup_id' => 'required',
            'serial_no' => 'required',
            'invest_id' => 'required',
            'date' => 'required',
        ]);

        DB::transaction(function () use ($request) {
            $amount = 0;
            foreach ($request->invest_id as $invest_id) {
                $invest = Invest::findOrFail($invest_id);
                $amount += $invest->amount;
            }

            $data = InvestorSattlement::create([
                'company_id' => Auth::user()->company_id ?? 1,
                'investor_id' => $request->investor_id,
                'date' => date('Y-m-d', strtotime($request->date)),
                'serial_no' => $this->invoice(),
                'amount' => $amount,
                'created_by' => Auth::user()->id,
            ]);

            foreach ($request->invest_id as $invest_id) {
                $invest = Invest::findOrFail($invest_id);
                $invest->update(['sattled' => 1]);
                InvestorSattlementList::create([
                    'investor_sattlement_id' => $data->id,
                    'invest_id' => $invest_id,
                    'amount' => $invest->amount
                ]);
            }

            Wallet::create([
                'investor_id' => $request->investor_id,
                'sattlement_id' => $data->id,
                'date' => date('Y-m-d', strtotime($request->date)),
                'amount_out' => $amount,
                'type' => 'Sattlement',
                'approved' => 1,
                'created_by' => Auth::user()->id,
            ]);

            $investor = Investor::findOrFail($request->investor_id);
            $admin_setting = AdminSetting::first();
            if (@$investor->coa && @$admin_setting->accounting == 1) {
                $cash_head = CoaSetup::findOrFail($request->coa_setup_id);
                $headCode = collect([
                    '0' => $investor->coa->head_code,
                    '1' => $cash_head->head_code,
                ]);
                $countHead = count($headCode);

                $debit_amount = collect([
                    '0' => $amount,
                    '1' => 0.00
                ]);

                $credit_amount = collect([
                    '0' => 0.00,
                    '1' => $amount,
                ]);

                $postData = [];
                for ($i = 0; $i < $countHead; $i++) {
                    $coa = CoaSetup::where('company_id', Auth::user()->company_id ?? 1)->where('head_code', $headCode[$i])->first();
                    $postData[] = [
                        'company_id' => Auth::user()->company_id ?? 1,
                        'voucher_no' => $data->serial_no,
                        'voucher_type' => "Sattlement",
                        'voucher_date' => date('Y-m-d', strtotime($request->date)),
                        'coa_setup_id' => $coa->id,
                        'coa_head_code' => $headCode[$i],
                        'narration' => 'Investor Sattled against PAYMENT NO - ' . $data->serial_no,
                        'debit_amount' => $debit_amount[$i],
                        'credit_amount' => $credit_amount[$i],
                        'created_by' => Auth::user()->id,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()
                    ];
                }
                AccountTransactionAuto::insert($postData);
            }
        });

        return redirect()->route('admin.investor-sattlement.index')->withSuccessMessage('Added Successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, string $id)
    {
        if ($request->ajax()) {
            $old = InvestorSattlement::findOrFail($id);
            $old_data = [];
            $data = Invest::with('product')->where('investor_id', $request->investor_id)->where('sattled', 0)->get();
            if ($request->investor_id == $old->investor_id) {
                $old_data = InvestorSattlementList::with(['invest', 'product'])->where('investor_sattlement_id', $id)->get();
            }
            return response()->json(['status' => 'success', 'data' => $data, 'old_data' => $old_data]);
        }

        $title = 'Update Sattlement';
        $data = InvestorSattlement::findOrFail($id);
        $link = Route('admin.investor-sattlement.update', $id);
        $cash_heads = CoaSetup::with('parent')->whereHas('parent', function ($query) {
            $query->where('head_name', 'Cash In Hand')->orWhere('head_name', 'Cash In Bank');
        })->get();
        $investors = Investor::where('status', 1)->get();
        return view('admin.investor_sattlement.edit', compact('title', 'data', 'link', 'cash_heads', 'investors'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'investor_id' => 'required',
            'coa_setup_id' => 'required',
            'serial_no' => 'required',
            'invest_id' => 'required',
            'date' => 'required',
        ]);

        DB::transaction(function () use ($request, $id) {
            $amount = 0;
            foreach ($request->invest_id as $invest_id) {
                $invest = Invest::findOrFail($invest_id);
                $amount += $invest->amount;
            }

            $data = InvestorSattlement::findOrFail($id);
            $data->update([
                'investor_id' => $request->investor_id,
                'date' => date('Y-m-d', strtotime($request->date)),
                'amount' => $amount,
                'updated_by' => Auth::user()->id,
            ]);

            // Delete Old Data
            $list = InvestorSattlementList::where('investor_sattlement_id', $id)->get();
            foreach ($list as $item) {
                $invest = Invest::findOrFail($item->invest_id);
                $invest->update(['sattled' => 0]);
                $item->delete();
            }

            foreach ($request->invest_id as $invest_id) {
                $invest = Invest::findOrFail($invest_id);
                $invest->update(['sattled' => 1]);
                InvestorSattlementList::create([
                    'investor_sattlement_id' => $data->id,
                    'invest_id' => $invest_id,
                    'product_id' => $invest->product_id,
                    'amount' => $invest->amount
                ]);
            }

            $wallet = Wallet::where('sattlement_id', $id)->first();
            $wallet->update([
                'investor_id' => $request->investor_id,
                'sattlement_id' => $id,
                'date' => date('Y-m-d', strtotime($request->date)),
                'amount_out' => $amount,
                'updated_by' => Auth::user()->id,
            ]);

            // Delete Old Data
            AccountTransactionAuto::where('voucher_no', $data->serial_no)->where('voucher_type', 'Sattlement')->forceDelete();

            $investor = Investor::findOrFail($request->investor_id);
            $admin_setting = AdminSetting::first();
            if (@$investor->coa && @$admin_setting->accounting == 1) {
                $cash_head = CoaSetup::findOrFail($request->coa_setup_id);
                $headCode = collect([
                    '0' => $investor->coa->head_code,
                    '1' => $cash_head->head_code,
                ]);
                $countHead = count($headCode);

                $debit_amount = collect([
                    '0' => $amount,
                    '1' => 0.00
                ]);

                $credit_amount = collect([
                    '0' => 0.00,
                    '1' => $amount,
                ]);

                $postData = [];
                for ($i = 0; $i < $countHead; $i++) {
                    $coa = CoaSetup::where('company_id', Auth::user()->company_id ?? 1)->where('head_code', $headCode[$i])->first();
                    $postData[] = [
                        'company_id' => Auth::user()->company_id ?? 1,
                        'voucher_no' => $data->serial_no,
                        'voucher_type' => "Sattlement",
                        'voucher_date' => date('Y-m-d', strtotime($request->date)),
                        'coa_setup_id' => $coa->id,
                        'coa_head_code' => $headCode[$i],
                        'narration' => 'Investor Sattled against PAYMENT NO - ' . $data->serial_no,
                        'debit_amount' => $debit_amount[$i],
                        'credit_amount' => $credit_amount[$i],
                        'created_by' => Auth::user()->id,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()
                    ];
                }
                AccountTransactionAuto::insert($postData);
            }
        });

        return redirect()->route('admin.investor-sattlement.index')->withSuccessMessage('Updated Successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = InvestorSattlement::findOrFail($id);
        $list = InvestorSattlementList::where('investor_sattlement_id', $id)->get();
        foreach ($list as $item) {
            $invest = Invest::findOrFail($item->invest_id);
            $invest->update(['sattled' => 0]);
            $item->delete();
        }
        Wallet::where('sattlement_id', $id)->forceDelete();
        AccountTransactionAuto::where('voucher_no', $data->serial_no)->where('voucher_type', 'Sattlement')->forceDelete();
        $data->forceDelete();
        return response()->json(['status' => 'success']);
    }
}
