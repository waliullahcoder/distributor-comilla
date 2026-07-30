<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\Store;
use App\Models\CoaSetup;
use App\Models\AdminSetting;
use Illuminate\Http\Request;
use App\Models\DeliveryAgent;
use Illuminate\Support\Facades\DB;
use App\Models\AccountTransaction;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\AccountTransactionAuto;

class OrderCollectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Order::with(['area', 'agent']);
            if ($request->store_id) {
                $query->where('store_id', $request->store_id);
            }
            if ($request->delivery_agent_id) {
                $query->where('delivery_agent_id', $request->delivery_agent_id);
            }
            $orders = $query->where('status', 'Delivered')->where('collected', 0)->whereDate('delivered_at', '<=', date('Y-m-d', $request->date))->get();
            return response()->json(['status' => 'success', 'orders' => $orders]);
        }

        $title = 'Bulk Collection';
        $disable_back = true;
        $stores = Store::where('status', 1)->orderBy('name', 'asc')->get();
        $agents = DeliveryAgent::where('status', 1)->orderBy('name', 'asc')->get();
        $orders = Order::with(['area', 'delivery_man'])->where('status', 'Delivered')->where('collected', 0)->whereDate('delivered_at', '<=', date('Y-m-d'))->get();
        $cash_heads = CoaSetup::with('parent')->whereHas('parent', function ($query) {
            $query->whereIn('head_name', ['Cash In Hand', 'Cash at Bank']);
        })->get();
        return view('admin.order_collection.create', compact('title', 'stores', 'disable_back', 'orders', 'agents', 'cash_heads'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required',
            'coa_setup_id' => 'required',
        ]);

        DB::transaction(function () use ($request) {
            $orders = Order::whereIn('id', $request->order_id)->get();
            $admin_setting = AdminSetting::first();
            $client_head = CoaSetup::where('head_type', 'A')->where('head_name', 'Retail Client')->first();
            $cash_head = CoaSetup::find($request->coa_setup_id);
            foreach ($orders as $item) {
                if (@$admin_setting->accounting == 1) {
                    $courier_head = CoaSetup::where('head_name', 'Courier Expense')->where('transaction', true)->where('head_type', 'E')->first();
                    $headCode = collect([
                        '0' => $cash_head->head_code,
                        '1' => $client_head->head_code,
                        '2' => $courier_head->head_code
                    ]);

                    $debit_amount = collect([
                        '0' => round($item->due - $item->delivery_cost),
                        '1' => 0.00,
                        '2' => round($item->delivery_cost)
                    ]);

                    $credit_amount = collect([
                        '0' => 0.00,
                        '1' => round($item->due),
                        '2' => 0.00,
                    ]);

                    $trim = str_replace("STOS", '', $item->invoice);
                    $invoice = "STOC" . $trim;

                    $countHead = count($headCode);
                    $postData = [];
                    for ($i = 0; $i < $countHead; $i++) {
                        $coa = CoaSetup::where('company_id', Auth::user()->company_id ?? 1)->where('head_code', $headCode[$i])->first();
                        $postData[] = [
                            'company_id' => Auth::user()->company_id ?? 1,
                            'voucher_no' => $invoice,
                            'voucher_type' => "Retail Collection",
                            'voucher_date' => date('Y-m-d', strtotime($request->collected_at)),
                            'coa_setup_id' => $coa->id,
                            'coa_head_code' => $headCode[$i],
                            'narration' => 'Retail Collection Against PAYMENT NO - ' . $invoice,
                            'debit_amount' => $debit_amount[$i],
                            'credit_amount' => $credit_amount[$i],
                            'created_by' => Auth::user()->id,
                            'posted' => 1,
                            'approve' => 1,
                            'approve_by' => Auth::user()->id,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now()
                        ];
                    }
                    AccountTransactionAuto::insert($postData);
                    $transactions = AccountTransactionAuto::where('voucher_no', $invoice)->where('voucher_type', 'Retail Collection')->get();

                    foreach ($transactions as $single) {
                        AccountTransaction::create([
                            'company_id' => $single->company_id,
                            'account_transaction_auto_id' => $single->id,
                            'voucher_no' => $single->voucher_no,
                            'voucher_type' => $single->voucher_type,
                            'voucher_date' => $single->voucher_date,
                            'coa_setup_id' => $single->coa_setup_id,
                            'coa_head_code' => $single->coa_head_code,
                            'narration' => $single->narration,
                            'debit_amount' => $single->debit_amount,
                            'credit_amount' => $single->credit_amount,
                            'posted' => $single->posted,
                            'approve' => $single->approve,
                            'approve_by' => $single->approve_by,
                            'created_by' => $single->created_by,
                            'updated_by' => $single->updated_by
                        ]);
                    }
                }
                $item->update(['collected' => 1, 'status' => 'Collected', 'collected_at' => date('Y-m-d', strtotime($request->collected_at))]);
            }
        });

        return redirect()->back()->withSuccessMessage('Collected Successfully!');
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
