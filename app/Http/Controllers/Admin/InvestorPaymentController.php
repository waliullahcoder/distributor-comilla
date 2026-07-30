<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\HelperClass;
use App\Models\Investor;
use App\Models\CoaSetup;
use App\Models\AdminSetting;
use Illuminate\Http\Request;
use App\Models\InvestorPayment;
use Illuminate\Support\Facades\DB;
use App\Models\InvestorPaymentList;
use App\Models\Scopes\CompanyScope;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\AccountTransactionAuto;
use App\Models\ProfitDistributionList;

class InvestorPaymentController extends Controller
{
    public $path;
    public $title;
    public $create_title;
    public $edit_title;
    public $model;
    public function __construct()
    {
        $this->path = 'investor-payment';
        $this->title = 'Investor Payment';
        $this->create_title = 'Add Payment';
        $this->edit_title = 'Update Payment';
        $this->model = InvestorPayment::class;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $addition_btns = [[
            'parameter' => true,
            'target' => '_self',
            'title' => 'View Payment',
            'route' => 'admin.investor-payment.show',
            'icon' => '<i class="fas fa-eye"></i>',
            'class' => 'btn btn-sm btn-primary mw-fit',
        ]];

        return HelperClass::resourceDataView($this->model::with(['investor'])->orderBy('id', 'desc'), null, $addition_btns, $this->path, $this->title, 'transactions');
    }

    public function paymentNo()
    {
        $prefix = 'IP';
        $datePart = date('ym'); // e.g. 2507

        $data = $this->model::withoutGlobalScope(CompanyScope::class)
            ->withTrashed()
            ->select(['payment_no'])
            ->where('created_at', '>=', date('Y-m-01'))
            ->where('created_at', '<=', date('Y-m-t'))
            ->orderBy('id', 'desc')
            ->first();

        if (is_null($data)) {
            $number = '001';
        } else {
            // Remove prefix and convert the remaining part to an integer
            $latestNo = (int)substr($data->payment_no, strlen($prefix));
            $number = str_pad($latestNo + 1, 3, '0', STR_PAD_LEFT); // e.g. 002, 010, 123
        }

        return $prefix . $datePart . $number;
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        if ($request->ajax()) {
            $list = ProfitDistributionList::where('paid', 0)->where('investor_id', $request->investor_id)->get();
            return response()->json([
                'status' => 'success',
                'data'  => view('admin.investor-payment.table', compact('list'))->render(),
            ]);
        }

        $title = $this->create_title;
        $investors = Investor::where('status', 1)->orderBy('name', 'asc')->get();
        $cash_heads = CoaSetup::with('parent')->whereHas('parent', function ($query) {
            $query->where('head_name', 'Cash In Hand')->orWhere('head_name', 'Cash In Bank');
        })->get();
        $payment_no = $this->paymentNo();
        return view("admin.{$this->path}.create", compact('title', 'investors', 'cash_heads', 'payment_no'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'investor_id' => 'required',
            'payment_no' => 'required',
            'date' => 'required',
            'total_amount' => 'required',
            'profit_distribution_list_id' => 'required'
        ]);

        DB::transaction(function () use ($request) {
            $data = $this->model::create([
                'investor_id' => $request->investor_id,
                'coa_setup_id' => $request->coa_setup_id,
                'payment_no' => $this->paymentNo(),
                'date' => date('Y-m-d', strtotime($request->date)),
                'amount' => $request->total_amount,
                'created_by' => Auth::user()->id
            ]);

            foreach ($request->profit_distribution_list_id as $list_id) {
                $list = ProfitDistributionList::findOrFail($list_id);
                $list->update(['paid' => 1]);
                InvestorPaymentList::create([
                    'investor_payment_id' => $data->id,
                    'profit_distribution_list_id' => $list_id,
                    'month' => $list->month,
                    'year' => $list->year,
                    'invest_qty' => $list->invest_qty,
                    'invest_amount' => $list->invest_amount,
                    'profit_amount' => $list->profit_amount,
                ]);
            }

            $investor = Investor::findOrFail($request->investor_id);
            $admin_setting = AdminSetting::first();
            if (@$investor->coa && @$admin_setting->accounting == 1) {
                $cash_head = CoaSetup::findOrFail($request->coa_setup_id);
                $headCode = collect([
                    '0' => $investor->profit_account->head_code,
                    '1' => $cash_head->head_code,
                ]);
                $countHead = count($headCode);

                $debit_amount = collect([
                    '0' => $request->total_amount,
                    '1' => 0.00
                ]);

                $credit_amount = collect([
                    '0' => 0.00,
                    '1' => $request->total_amount,
                ]);

                $postData = [];
                for ($i = 0; $i < $countHead; $i++) {
                    $coa = CoaSetup::where('company_id', Auth::user()->company_id ?? 1)->where('head_code', $headCode[$i])->first();
                    $postData[] = [
                        'company_id' => Auth::user()->company_id ?? 1,
                        'voucher_no' => $data->payment_no,
                        'voucher_type' => "Investor Payment",
                        'voucher_date' => date('Y-m-d', strtotime($request->date)),
                        'coa_setup_id' => $coa->id,
                        'coa_head_code' => $headCode[$i],
                        'narration' => 'Investor Payment against PAYMENT NO - ' . $data->payment_no,
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

        return redirect()->route("admin.{$this->path}.index")->withSuccessMessage('Created Successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $title = 'View Investor Payment';
        $data = $this->model::findOrFail($id);
        return view("admin.{$this->path}.view", compact('data', 'title'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, string $id)
    {
        if ($request->ajax()) {
            $data = $this->model::findOrFail($id);
            $list = ProfitDistributionList::where('investor_id', $request->investor_id)->where(function ($query) use ($request, $data) {
                $query->where('paid', 0);
                if ($data->investor_id == $request->investor_id) {
                    $query->orWhereHas('payments', function ($q) use ($data) {
                        $q->where('investor_payment_id', $data->id);
                    });
                }
            })->get();
            return response()->json([
                'status' => 'success',
                'data'  => view('admin.investor-payment.table', compact('list'))->render(),
            ]);
        }

        $data = $this->model::findOrFail($id);
        $additionalData = [
            'investors' => Investor::where('status', 1)->orderBy('name', 'asc')->get(),
            'list' => ProfitDistributionList::where('investor_id', $data->investor_id)->where(function ($query) use ($data) {
                $query->where('paid', 0)->orWhereHas('payments', function ($q) use ($data) {
                    $q->where('investor_payment_id', $data->id);
                });
            })->get(),
            'cash_heads' => CoaSetup::with('parent')->whereHas('parent', function ($query) {
                $query->where('head_name', 'Cash In Hand')->orWhere('head_name', 'Cash In Bank');
            })->get()
        ];
        return HelperClass::resourceDataEdit($this->model, $id, $this->path, $this->edit_title, $additionalData);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'investor_id' => 'required',
            'date' => 'required',
            'total_amount' => 'required',
            'profit_distribution_list_id' => 'required'
        ]);

        DB::transaction(function () use ($request, $id) {
            $data = $this->model::findOrFail($id);
            $data->update([
                'investor_id' => $request->investor_id,
                'coa_setup_id' => $request->coa_setup_id,
                'date' => date('Y-m-d', strtotime($request->date)),
                'amount' => $request->total_amount,
                'updated_by' => Auth::user()->id
            ]);

            AccountTransactionAuto::where('voucher_no', $data->payment_no)->where('voucher_type', 'Investor Payment')->forceDelete();
            ProfitDistributionList::whereHas('payments', function ($q) use ($data) {
                $q->where('investor_payment_id', $data->id);
            })->update(['paid' => 0]);
            InvestorPaymentList::where('investor_payment_id', $id)->delete();

            foreach ($request->profit_distribution_list_id as $list_id) {
                $list = ProfitDistributionList::findOrFail($list_id);
                $list->update(['paid' => 1]);
                InvestorPaymentList::create([
                    'investor_payment_id' => $data->id,
                    'profit_distribution_list_id' => $list_id,
                    'month' => $list->month,
                    'year' => $list->year,
                    'invest_qty' => $list->invest_qty,
                    'invest_amount' => $list->invest_amount,
                    'profit_amount' => $list->profit_amount
                ]);
            }
            $investor = Investor::findOrFail($request->investor_id);
            $admin_setting = AdminSetting::first();
            if (@$investor->coa && @$admin_setting->accounting == 1) {
                $cash_head = CoaSetup::findOrFail($request->coa_setup_id);
                $headCode = collect([
                    '0' => $investor->profit_account->head_code,
                    '1' => $cash_head->head_code,
                ]);
                $countHead = count($headCode);

                $debit_amount = collect([
                    '0' => $request->total_amount,
                    '1' => 0.00
                ]);

                $credit_amount = collect([
                    '0' => 0.00,
                    '1' => $request->total_amount,
                ]);

                $postData = [];
                for ($i = 0; $i < $countHead; $i++) {
                    $coa = CoaSetup::where('company_id', Auth::user()->company_id ?? 1)->where('head_code', $headCode[$i])->first();
                    $postData[] = [
                        'company_id' => Auth::user()->company_id ?? 1,
                        'voucher_no' => $data->payment_no,
                        'voucher_type' => "Investor Payment",
                        'voucher_date' => date('Y-m-d', strtotime($request->date)),
                        'coa_setup_id' => $coa->id,
                        'coa_head_code' => $headCode[$i],
                        'narration' => 'Investor Payment against PAYMENT NO - ' . $data->payment_no,
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

        return redirect()->route("admin.{$this->path}.index")->withSuccessMessage('Updated Successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Delete Single Item
        DB::transaction(function () use ($id) {
            $data = $this->model::findOrFail($id);
            ProfitDistributionList::whereHas('payments', function ($q) use ($data) {
                $q->where('investor_payment_id', $data->id);
            })->update(['paid' => 0]);
            $data->update(['deleted_by' => Auth::user()->id]);
            AccountTransactionAuto::where('voucher_no', $data->payment_no)->where('voucher_type', 'Investor Payment')->forceDelete();
            $data->forceDelete();
        });

        return response()->json(['status' => 'success']);
    }
}
