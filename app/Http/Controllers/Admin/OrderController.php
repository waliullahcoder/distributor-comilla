<?php

namespace App\Http\Controllers\Admin;

use Exception;
use Carbon\Carbon;
use App\Models\Store;
use App\Models\Order;
use App\Models\Product;
use App\Models\Company;
use App\Models\CoaSetup;
use App\Models\ProductSku;
use App\Models\DeliveryMan;
use App\Models\OrderProduct;
use Illuminate\Http\Request;
use App\Models\AdminSetting;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\AccountTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\DeliveryAgentPackage;
use App\Http\Controllers\Controller;
use App\Models\AccountTransactionAuto;
use Yajra\DataTables\Facades\DataTables;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->ajax()) {
            $model = Order::with(['company', 'products', 'customer'])->orderBy('id', 'desc');
            if (Auth::user()->hasRole('Moderator')) {
                $model->where('created_by', Auth::user()->id);
            }
            if (Auth::user()->hasRole('Store Keeper')) {
                $model->whereIn('store_id', Auth::user()->stores);
            }
            $date_range = explode('to', request('date_range'));
            $start_date = !is_null(request('date_range')) ? date('Y-m-d', strtotime($date_range[0])) : date('Y-m-01');
            $end_date = !is_null(request('date_range')) ? date('Y-m-d', strtotime($date_range[1])) : date('Y-m-t');
            if (!is_null($start_date) && !is_null($end_date)) {
                $model->where('date', '>=', $start_date)->where('date', '<=', $end_date);
            }

            session(['status' => request('status')]);
            if (!is_null(request('status'))) {
                $model->where('status', request('status'));
            }

            // $sumValue = OrderProduct::whereHas('order', function ($q) {
            //     if (Auth::user()->hasRole('Moderator')) {
            //         $q->where('created_by', Auth::user()->id);
            //     }
            //     if (Auth::user()->hasRole('Store Keeper')) {
            //         $q->whereIn('store_id', Auth::user()->stores);
            //     }
            //     $date_range = explode('to', request('date_range'));
            //     $start_date = !is_null(request('date_range')) ? date('Y-m-d', strtotime($date_range[0])) : date('Y-m-01');
            //     $end_date = !is_null(request('date_range')) ? date('Y-m-d', strtotime($date_range[1])) : date('Y-m-t');
            //     if (!is_null($start_date) && !is_null($end_date)) {
            //         $q->where('date', '>=', $start_date)->where('date', '<=', $end_date);
            //     }
            //     if (!is_null(request('status'))) {
            //         $q->where('status', request('status'));
            //     }
            // })->sum(DB::raw('subtotal - return_amount'));

            $sumValue = $model->sum('due');

            return DataTables::eloquent($model)
                ->with('sumValue', number_format($sumValue))
                ->addIndexColumn()
                ->addColumn('order_products', function ($row) {
                    return count($row->products);
                })
                ->addColumn('net_amount', function ($row) {
                    return $row->due + $row->shipping_charge;
                })
                ->addColumn('order_date', function ($row) {
                    return date('d M Y', strtotime($row->date));
                })
                ->addColumn('order_status', function ($row) {
                    if ($row->status == 'Cancelled') {
                        $status = '<span class="btn btn-xs text-white px-2 bg-danger" style="min-width: 80px;">Cancelled</span>';
                    } elseif ($row->status == 'Collected') {
                        $status = '<span class="btn btn-xs text-white px-2 bg-success" style="min-width: 80px;">Collected</span>';
                    } else {
                        $status = '<a class="btn btn-xs text-white px-2 ';
                        if ($row->status == 'Pending') {
                            $status .= 'bg-primary';
                        } elseif ($row->status == 'Forward') {
                            $status .= 'bg-info';
                        } elseif ($row->status == 'On Route') {
                            $status .= 'bg-route';
                        } elseif ($row->status == 'Delivered') {
                            $status .= 'bg-delivered';
                        } elseif ($row->status == 'Returned') {
                            $status .= 'bg-danger';
                        }
                        $status .= '" style="min-width: 80px;" href="' . (!Auth::user()->hasRole('Moderator') ? Route('admin.order-dashboard.edit', $row->id) : '') . '">' . $row->status . '</a>';
                    }
                    return $status;
                })
                ->addColumn('courier_assign', function ($row) {
                    if ($row->courier_assigned == 1) {
                        return '<span class="text-nowrap btn btn-xs text-white px-2 bg-success" style="min-width: 80px;">Courier Assigned</span>';
                    } elseif (in_array($row->status, ['Pending', 'On Route', 'Forward'])) {
                        return '<a class="text-nowrap btn btn-xs text-white px-2 bg-primary assign_btn" style="min-width: 80px;" href="' . Route('admin.online-order.edit', $row->id) . '">Assign to Courier</a>';
                    }
                })
                ->addColumn('actions', function ($row) {
                    $actionBtn = '<div class="btn-group">
                        <a href="' . Route('admin.online-order.edit', $row->id) . '" class="btn btn-sm btn-success border-0 px-10px tt fs-15" data-bs-toggle="tooltip" data-bs-placement="top" title="Show Order"><i class="fas fa-eye"></i></a>
                        <a href="' . Route('admin.online-order.show', $row->id) . '" target="_blank" class="btn btn-sm btn-info text-white border-0 px-10px tt fs-15" data-bs-toggle="tooltip" data-bs-placement="top" title="Print Voucher"><i class="fas fa-print"></i></a>
                    </div>';
                    return $actionBtn;
                })
                ->rawColumns(['checkbox', 'order_status', 'courier_assign', 'actions'])
                ->make(true);
        }
        return view('admin.order.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function exportProcess()
    {
        if (Auth::user()->company_id) {
            $company = Company::find(Auth::user()->company_id);
            $logo = $company->logo;
            $title = $company->name;
            $informations = $company->address . '</br>' . $company->phone . ', ' . $company->email . ', ' . $company->website;
        } else {
            $logo = NULL;
            $title = 'Company Name Goes Here.';
            $informations = 'Company address will goes here </br> Mobile: 0967XXXXXX, Email: youremail@gmail.com, www.website.com';
        }
        $items = OrderProduct::with(['order', 'product'])->whereHas('order', function ($query) {
            $query->where('status', 'Processing')->where('order_type', 'online');
        })
            ->select(['product_id', DB::raw('SUM(quantity) as qty')])
            ->groupBy('product_id')->get();

        $invoices = Order::where('status', 'Processing')->where('order_type', 'online')->get(['order_code']);
        $report_title = 'Processing Order List';
        return view('admin.order.processing_orders_print', compact('title', 'logo', 'informations', 'report_title', 'items', 'invoices'));
        $pdf = Pdf::loadView('admin.order.processing_orders_print', compact('title', 'logo', 'informations', 'report_title', 'items', 'invoices'));
        return $pdf->stream('getepass_chalan_' . date('d_m_Y_H_i_s') . '.pdf');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $order = Order::findOrFail($id);
        return view('admin.order.print', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, string $id)
    {
        if ($request->ajax() && $request->has('assign_courier')) {
            // The endpoint you are hitting
            $url = 'https://quickexpressbd.com/api/v1/booking';

            // API Key and Secret Key for authentication
            $apiKey = 'EF6UqUzMN9GF61B5Aa6qxhMtj6sh3TCY';
            $secretKey = 'nmpkNyHCjte0mTAaK2YvFP04';

            // Request body payload
            $data = \App\Models\Order::whereIn('id', [$id])->select('invoice as customer_invoice', 'user_name as customer_name', 'user_phone as customer_phone', 'shipping_address as customer_address', 'due as cod_amount', 'total as invoice_amount', 'order_note as remarks')->get();

            // Send the POST request
            $response = \Http::withHeaders([
                'Api-Key' => $apiKey,
                'Secret-Key' => $secretKey,
                'Content-Type' => 'application/json',
            ])->post($url, $data);

            if ($response->successful()) {
                foreach ($response['data'] as $item) {
                    $order = \App\Models\Order::where('invoice', $item['customer_invoice'])->first();
                    $order->update(['courier_assigned' => 1]);
                    if ($order->status == 'Pending') {
                        $order->update(['status' => 'On Route']);
                    }
                    if (is_null($order->store_id)) {
                        $store = Store::first();
                        $order->update(['store_id' => @$store->id]);
                    }
                }
                return response()->json(['status' => 'success']);
            } else {
                return response()->json(['status' => 'error']);
            }
        }

        if ($request->ajax()) {
            $delivery_men = DeliveryMan::where('store_id', $request->store_id)->where('status', 1)->orderBy('name', 'asc')->get();
            return response()->json(['status' => 'success', 'delivery_men' => $delivery_men]);
        }

        $order = Order::findOrFail($id);
        $stores = Store::where('status', 1)->get();
        $previousUrl = url()->previous();
        return view('admin.order.view', compact('order', 'stores', 'previousUrl'));
    }

    public static function stock($product_id, $store_id, $product_type = 'Consumer')
    {
        if ($product_type == 'Consumer') {
            $liftings = DB::table('view_liftings')->where('product_type', $product_type ?? 'Consumer')->where('store_id', $store_id)->where('product_id', $product_id)->sum('qty');
            $lifting_returns = DB::table('view_lifting_returns')->where('product_type', $product_type ?? 'Consumer')->where('store_id', $store_id)->where('product_id', $product_id)->sum('qty');
            $sales = DB::table('view_sales')->where('product_type', $product_type ?? 'Consumer')->where('store_id', $store_id)->where('product_id', $product_id)->sum('qty');
            $sales_returns = DB::table('view_sales_returns')->where('product_type', $product_type ?? 'Consumer')->where('store_id', $store_id)->where('product_id', $product_id)->sum('qty');
            $online_sales = DB::table('view_online_sales')->where('is_stock', 1)->where('product_type', $product_type ?? 'Consumer')->where('store_id', $store_id)->whereIn('status', ['On Route', 'Delivered', 'Collected'])->where('product_id', $product_id)->sum('qty');
            $transfers = DB::table('view_transfers')->where('product_type', $product_type)->where('product_id', $product_id)->where('host_id', $store_id)->sum('qty');
            $receives = DB::table('view_transfers')->where('product_type', $product_type)->where('product_id', $product_id)->where('destination_id', $store_id)->sum('qty');
        }
        if ($product_type == 'Fashion') {
            $liftings = DB::table('view_liftings')->where('product_type', $product_type)->where('store_id', $store_id)->where('sku_id', $product_id)->sum('qty');
            $lifting_returns = DB::table('view_lifting_returns')->where('product_type', $product_type)->where('store_id', $store_id)->where('sku_id', $product_id)->sum('qty');
            $sales = DB::table('view_sales')->where('product_type', $product_type)->where('store_id', $store_id)->where('sku_id', $product_id)->sum('qty');
            $sales_returns = DB::table('view_sales_returns')->where('product_type', $product_type)->where('store_id', $store_id)->where('sku_id', $product_id)->sum('qty');
            $online_sales = DB::table('view_online_sales')->where('is_stock', 1)->where('product_type', $product_type)->where('store_id', $store_id)->whereIn('status', ['On Route', 'Delivered', 'Collected'])->where('sku_id', $product_id)->sum('qty');
            $transfers = DB::table('view_transfers')->where('product_type', $product_type)->where('sku_id', $product_id)->where('host_id', $store_id)->sum('qty');
            $receives = DB::table('view_transfers')->where('product_type', $product_type)->where('sku_id', $product_id)->where('destination_id', $store_id)->sum('qty');
        }

        return $liftings + $sales_returns + $receives - $lifting_returns - $sales - $online_sales - $transfers;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = Order::findOrFail($id);
        if ($request->status == 'Forward') {
            $request->validate([
                'store_id' => 'required',
            ]);
            $data->update(['status' => $request->status, 'store_id' => $request->store_id]);
        }

        if ($request->store_id) {
            $data->update(['store_id' => $request->store_id]);
        }

        if ($request->status == 'Pending') {
            DB::transaction(function () use ($request, $data) {
                $trim = str_replace("STOS", '', $data->invoice);
                $invoice = "STOC" . $trim;
                AccountTransactionAuto::withTrashed()->where('voucher_no', $invoice)->where('voucher_type', 'Retail Collection')->forceDelete();
                AccountTransaction::where('voucher_no', $data->invoice)->where('voucher_type', 'Retail Sales')->forceDelete();
                AccountTransactionAuto::where('voucher_no', $data->invoice)->where('voucher_type', 'Retail Sales')->update(['posted' => 0, 'approve' => 0, 'approve_by' => NULL]);
                $data->update(['store_id' => NULL, 'status' => $request->status, 'collected' => 0, 'delivery_agent_id' => NULL]);
            });
        }

        if ($request->status == 'On Route') {
            $request->validate([
                'delivery_agent_id' => 'required',
            ]);

            $package = DeliveryAgentPackage::where('delivery_agent_id', $request->delivery_agent_id)->where($request->area_type, true)->first();
            if (is_null($package)) {
                return redirect()->back()->withErrors('No suitable package found!');
            }

            try {
                DB::transaction(function () use ($request, $data, $package) {
                    foreach ($data->products as $product) {
                        $store_id = $data->store_id;
                        $product_id = $product->variant_id ?? $product->product_id;
                        $stock = $this->stock($product_id, $store_id, $product->product->product_type);
                        $productInfo = Product::find($product->product_id);
                        $variant = ProductSku::find($product->variant_id);
                        if ($product->quantity > $stock) {
                            throw new Exception('stock not available for ' . @$productInfo->name . ' - ' . (@$variant->sku ?? @$productInfo->code));
                        }
                    }

                    $totalWeight = $data->products->sum('quantity');
                    $additionalWeight = 0;
                    if ($totalWeight > $package->base_weight) {
                        $additionalWeight = $totalWeight - $package->base_weight;
                        if ($additionalWeight < 1 && $additionalWeight > 0) {
                            $additionalWeight = 1;
                        }
                    }
                    $deliveryCost = $package->base_rate + $additionalWeight * $package->additional_rate;
                    $data->update(['status' => 'On Route', 'delivery_agent_id' => $request->delivery_agent_id, 'delivery_package_id' => $package->id, 'delivery_cost' => $deliveryCost, 'area_type' => $request->area_type]);
                });
            } catch (\Exception $e) {
                return back()->withErrors($e->getMessage());
            }
        }

        if ($request->delivery_agent_id && is_null($request->status)) {
            $data->update(['delivery_agent_id' => $request->delivery_agent_id]);
        }

        if ($request->status == 'Delivered') {
            $data->update(['status' => $request->status, 'receive' => $request->receive ?? $data->due, 'delivered_at' => date('Y-m-d')]);
        }

        if ($request->status == 'Cancelled') {
            $data->update(['status' => $request->status, 'canceled_at' => date('Y-m-d H:i:s')]);
        }

        if ($request->collected) {
            DB::transaction(function () use ($request, $data) {
                $admin_setting = AdminSetting::first();
                $client_head = CoaSetup::where('head_type', 'A')->where('head_name', 'Retail Client')->first();
                $cash_head = CoaSetup::where('head_type', 'A')->where('head_code', '1010201')->first();

                if (@$admin_setting->accounting == 1) {
                    $headCode = collect([
                        '0' => $cash_head->head_code,
                        '1' => $client_head->head_code
                    ]);

                    $debit_amount = collect([
                        '0' => $data->due,
                        '1' => 0.00
                    ]);

                    $credit_amount = collect([
                        '0' => 0.00,
                        '1' => $data->due,
                    ]);

                    $trim = str_replace("STOS", '', $data->invoice);
                    $invoice = "STOC" . $trim;

                    $countHead = count($headCode);
                    $postData = [];
                    for ($i = 0; $i < $countHead; $i++) {
                        $coa = CoaSetup::where('company_id', Auth::user()->company_id ?? 1)->where('head_code', $headCode[$i])->first();
                        $postData[] = [
                            'company_id' => Auth::user()->company_id ?? 1,
                            'voucher_no' => $invoice,
                            'voucher_type' => "Retail Collection",
                            'voucher_date' => date('Y-m-d', strtotime($data->date)),
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

                $data->update(['collected' => $request->collected, 'status' => 'Collected']);
            });
        }

        $sales_voucher = AccountTransactionAuto::where('voucher_no', $data->invoice)->where('voucher_type', 'Retail Sales')->first();

        $admin_setting = AdminSetting::first();
        if (@$admin_setting->accounting == 1 && is_null($sales_voucher)) {
            $client_head = CoaSetup::where('head_type', 'A')->where('head_name', 'Retail Client')->first();
            $income_head = CoaSetup::where('head_type', 'I')->where('head_name', 'Retail Sale')->first();
            $headCode = collect([
                '0' => $client_head->head_code,
                '1' => $income_head->head_code,
            ]);

            $debit_amount = collect([
                '0' => $data->total - $data->discount,
                '1' => 0.00
            ]);

            $credit_amount = collect([
                '0' => 0.00,
                '1' => $data->total - $data->discount,
            ]);

            $countHead = count($headCode);
            $postData = [];
            for ($i = 0; $i < $countHead; $i++) {
                $coa = CoaSetup::where('company_id', (Auth::user()->company_id ?? 1))->where('head_code', $headCode[$i])->first();
                $postData[] = [
                    'company_id' => Auth::user()->company_id ?? 1,
                    'voucher_no' => $data->invoice,
                    'voucher_type' => "Retail Sales",
                    'voucher_date' => date('Y-m-d', strtotime($data->date)),
                    'coa_setup_id' => $coa->id,
                    'coa_head_code' => $headCode[$i],
                    'narration' => 'Retail Sales Against Invoice No - ' . $data->invoice,
                    'debit_amount' => $debit_amount[$i],
                    'credit_amount' => $credit_amount[$i],
                    'created_by' => Auth::user()->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ];
            }
            AccountTransactionAuto::insert($postData);
        }

        if ($request->prev_url) {
            return redirect($request->prev_url)->withSuccessMessage('Status Updated Successfully!');
        }
        return redirect()->route('admin.dashboard')->withSuccessMessage('Status Updated Successfully!');
    }

    public function CancelOrder()
    {
        if (request()->ajax()) {
            $model = Order::with(['company', 'products', 'customer'])->where('status', 'Cancelled')->where('cancel_approve', 0);
            return DataTables::eloquent($model)
                ->addIndexColumn()
                ->addColumn('order_products', function ($row) {
                    return count($row->products);
                })
                ->addColumn('order_date', function ($row) {
                    return date('d M Y h:i A', strtotime($row->date));
                })
                ->addColumn('order_status', function ($row) {
                    if ($row->status == 'Cancelled') {
                        $status = '<span class="btn btn-xs text-white px-2 bg-danger">Cancelled</span>';
                    } elseif ($row->status == 'Successed') {
                        $status = '<span class="btn btn-xs text-white px-2 bg-success">Successed</span>';
                    } else {
                        $status = '<span class="btn btn-xs text-white px-2 bg-primary">' . $row->status . '</span>';
                    }
                    return $status;
                })
                ->addColumn('actions', function ($row) {
                    return '<button data-url="' . Route('admin.cancel-order.approve', $row->id) . '" class="btn btn-xxs tt ms-1 btn-outline-success approve text-nowrap" data-bs-toggle="tooltip" data-bs-placement="top" title="Approve Cancelation">Approve</button>';
                })
                ->rawColumns(['checkbox', 'order_status', 'actions'])
                ->make(true);
        }
        return view('admin.cancelled_approval.index');
    }

    public function approveCancelOrder($id)
    {
        $data = Order::findOrFail($id);
        if ($data->status == 'Cancelled') {
            $data->update(['cancel_approve' => 1]);
        }
        return response()->json(['status' => 'success']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
