<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use App\Models\CoaSetup;
use App\Models\AdminSetting;
use Illuminate\Http\Request;
use App\Models\DeliveryAgent;
use App\Models\AccountTransaction;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\AccountTransactionAuto;

class OrderStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $orders = Order::with('area')->where('delivery_agent_id', $request->delivery_agent_id)->where('status', 'On Route')->get();
            return response()->json(['status' => 'success', 'orders' => $orders, 'total_shipping' => $orders->sum('shipping_charge'), 'total_amount' => $orders->sum('sub_total'), 'total_receivable' => $orders->sum('total')]);
        }

        $title = 'Order Status Update';
        $agents = DeliveryAgent::where('status', 1)->orderBy('name', 'asc')->get();
        $disable_back = true;
        return view('admin.order_status.create', compact('title', 'agents', 'disable_back'));
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
        ]);

        DB::transaction(function () use ($request) {
            foreach ($request->order_id as $order_id) {
                if (in_array($request->status[$order_id], ['Delivered', 'Cancelled'])) {
                    $order = Order::findOrFail($order_id);
                    $order->update(['status' => $request->status[$order_id]]);
                    if ($request->status[$order_id] == 'Delivered') {
                        $order->update(['delivered_at' => date('Y-m-d H:i:s'), 'receive' => $request->receive[$order_id]]);
                        
                        $transactions = AccountTransactionAuto::where('voucher_no', $order->invoice)->where('voucher_type', 'Retail Sales')->count();
                        if($transactions == 0){
                            $admin_setting = AdminSetting::first();
                            if (@$admin_setting->accounting == 1) {
                                $client_head = CoaSetup::where('head_type', 'A')->where('head_name', 'Retail Client')->first();
                                $income_head = CoaSetup::where('head_type', 'I')->where('head_name', 'Retail Sale')->first();
                                $headCode = collect([
                                    '0' => $client_head->head_code,
                                    '1' => $income_head->head_code,
                                ]);

                                $debit_amount = collect([
                                    '0' => $request->receive[$order_id],
                                    '1' => 0.00
                                ]);

                                $credit_amount = collect([
                                    '0' => 0.00,
                                    '1' => $request->receive[$order_id],
                                ]);

                                $countHead = count($headCode);
                                $postData = [];
                                for ($i = 0; $i < $countHead; $i++) {
                                    $coa = CoaSetup::where('company_id', (Auth::user()->company_id ?? 1))->where('head_code', $headCode[$i])->first();
                                    $postData[] = [
                                        'company_id' => Auth::user()->company_id ?? 1,
                                        'voucher_no' => $order->invoice,
                                        'voucher_type' => "Retail Sales",
                                        'voucher_date' => date('Y-m-d'),
                                        'coa_setup_id' => $coa->id,
                                        'coa_head_code' => $headCode[$i],
                                        'narration' => 'Retail Sales Against Invoice No - ' . $order->invoice,
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

                                $transactions = AccountTransactionAuto::where('voucher_no', $order->invoice)->where('voucher_type', 'Retail Sales')->get();

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
                        }
                    }
                    if ($request->status[$order_id] == 'Cancelled') {
                        $data->update(['store_id' => null, 'collected' => 0, 'delivery_agent_id' => null, 'canceled_at' => date('Y-m-d H:i:s')]);
                        AccountTransactionAuto::withTrashed()->where('voucher_no', $data->invoice)->where('voucher_type', 'Retail Sales')->forceDelete();               
                        $trim = str_replace("STOS", '', $data->invoice);
                        $invoice = "STOC" . $trim;
                        AccountTransactionAuto::withTrashed()->where('voucher_no', $invoice)->where('voucher_type', 'Retail Collection')->forceDelete();
                    }
                }
            }
        });

        return redirect()->back()->withSuccessMessage('Updated Successfully!');
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
    public function edit(string $id)
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
