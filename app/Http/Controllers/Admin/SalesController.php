<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AccessLog;
use App\Models\AccountTransaction;
use App\Models\AccountTransactionAuto;
use App\Models\AdminMenu;
use App\Models\AdminMenuAction;
use App\Models\AdminSetting;
use App\Models\Client;
use App\Models\ClientPrice;
use App\Models\CoaSetup;
use App\Models\Collection;
use App\Models\CollectionData;
use App\Models\Company;
use App\Models\DeliveryList;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\Sales;
use App\Models\SalesList;
use App\Models\SalesDelivery;
use App\Models\SalesDeliveryList;
use App\Models\SalesReturn;
use App\Models\SalesReturnList;
use App\Models\Scopes\CompanyScope;
use App\Models\Staff;
use App\Models\Store;
use App\Models\Vendor;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\ActionButtons\ActionButtons;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use Exception;
use Spatie\Permission\Models\Permission;
use Throwable;

const API_TOKEN = "zabcxpps-3p2u0j8t-poamlcuh-vfukis8d-gveezohu";
const SID = "BONTONBULK";
const DOMAIN = "https://smsplus.sslwireless.com";

class SalesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->ajax()) {
            $model = Sales::with(['company', 'store', 'client', 'staff'])->where('product_type', 'Consumer')->whereNotIn('sales_type', ['POS', 'running'])->latest('id');
            if (!is_null(request('invoice'))) {
                $model->where('invoice', request('invoice'));
            } elseif (request('sales_date')) {
                $date =  date('Y-m-d', strtotime(request('sales_date')));
                $model->where('date', $date);
            }
            $type = request('type');
            if (!empty($type) && $type == 'trash') {
                $model->onlyTrashed();
            }
            return DataTables::eloquent($model)
                ->addColumn('checkbox', function ($row) {
                    $transaction = AccountTransaction::withTrashed()->where('voucher_no', $row->invoice)->where('voucher_type', 'Sales')->first();
                    if (is_null($transaction)) {
                        $checkbox = '<div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input ' . (!empty(request('type')) && request('type') == "trash" ? 'trash_multi_checkbox' : 'multi_checkbox') . '" id="' . $row->id . '" name="multi_checkbox[]" value="' . $row->id . '"><label for="' . $row->id . '" class="custom-control-label"></label></div>';
                        return $checkbox;
                    }
                })
                ->addColumn('date', function ($row) {
                    return date('d-m-Y', strtotime($row->date));
                })
                ->addColumn('actions', function ($row) {
                    $type = request('type');
                    $collection_data = CollectionData::with('collection')->whereHas('collection')->where('sales_id', $row->id)->first();
                    $data = [
                        'id' => $row->id,
                        'edit' => !is_null($collection_data) || !empty($type) && $type == 'trash' ? false : true,
                    ];
                    $addiotional_buttons = '';
                    $addiotional_buttons .= '<a class="btn btn-sm border-0 px-10px fs-15 text-white tt btn-print-1" href="' . Route('admin.sales.show', $row->id) . '" target="_blank"  data-bs-toggle="tooltip" data-bs-placement="top" title="Chalan"><i class="fal fa-print"></i></a>';
                    $addiotional_buttons .= '<a class="btn btn-sm border-0 px-10px fs-15 text-white tt btn-print-2" href="' . Route('admin.sales.invoice', $row->id) . '" target="_blank"  data-bs-toggle="tooltip" data-bs-placement="top" title="Invoice"><i class="fal fa-file-pdf"></i></a>';
                    if (@$row->client->is_vat == 1) {
                        $addiotional_buttons .= '<a class="btn btn-sm border-0 px-10px fs-15 text-white tt btn-print-3" href="' . Route('admin.sales.vat', $row->id) . '" target="_blank"  data-bs-toggle="tooltip" data-bs-placement="top" title="Vat Chalan"><i class="fal fa-print-search"></i></a>';
                    }
                    $transaction = AccountTransaction::withTrashed()->where('voucher_no', $row->invoice)->where('voucher_type', 'Sales')->first();
                    if (is_null($transaction)) {
                        return ActionButtons::actions($data, $addiotional_buttons);
                    }
                    return '<div class="btn-group">' . $addiotional_buttons . '</div>';
                })
                ->rawColumns(['checkbox', 'actions'])
                ->make(true);
        }

        $title = "Daily Sales";
        $params = '';
        $currentRouteName = \Request::route()->getName();
        $menu = AdminMenu::where('route', $currentRouteName)->first();
        $edit = str_replace('index', 'edit', $menu->route);
        $menuAction = AdminMenuAction::where('route', $edit)->first();
        $currentRoutePermission = Permission::findById($menuAction->permission_id);
        if (!is_null($currentRoutePermission)) {
            if (auth()->user()->can($currentRoutePermission->name)) {
                $params .= '<form class="d-inline-flex gap-2" method="get" target="_blank" action="' . Route('admin.sales.search-edit') . '"><input type="text" class="form-control px-2 py-1" id="invoice" name="invoice" style="width: 150px; min-height: auto;" placeholder="Invoice No."><button type="submit" class="btn btn-sm btn-warning tt"><i class="far fa-pencil-alt"></i></button></form>';
            }
        }
        $params .= '<input type="text" class="form-control date px-2 py-1" id="sales_date" name="sales_date" style="width: 150px; min-height: auto;" placeholder="Sales Date">';
        return view('admin.sales.index', compact('title', 'params'));
    }

    public function deliveryList(Request $request)
    {
        $title = "Delivery";
        $filter_link = Route('admin.delivery.list');

        $date_range = explode('to', $request->date_range);
        $start_date = isset($date_range[0]) ? date('Y-m-d', strtotime(trim($date_range[0]))) : null;
        $end_date   = isset($date_range[1]) ? date('Y-m-d', strtotime(trim($date_range[1]))) : null;

        $client_id = $request->client_id;

        $clients = Client::where('status', 1)
            ->orderBy('name', 'asc')
            ->get();

        $query = DB::table('clients as cl')
            ->leftJoin('sales_lists as sl', 'cl.id', '=', 'sl.client_id')
            ->select(
                'cl.id',
                'cl.name',
                DB::raw('MAX(sl.created_at) as sale_date'),
                DB::raw('SUM(sl.qty) as total_qty'),
                DB::raw('SUM(sl.delivery) as total_delivery')
            );

        if (!empty($request->client_id)) {
            $query->where('cl.id', $request->client_id);
        }

        if (!empty($start_date) && !empty($end_date)) {
            $query->whereBetween(DB::raw('DATE(sl.created_at)'), [$start_date, $end_date]);
        }

        $data = $query
            ->groupBy('cl.id', 'cl.name')
            ->orderBy('cl.name')
            ->get();

        // Grand Total
        $grand_total_qty = $data->sum('total_qty');
        $grand_total_delivery = $data->sum('total_delivery');

        return view(
            'admin.sales-delivery.deliveryList',
            compact(
                'title',
                'filter_link',
                'clients',
                'data',
                'start_date',
                'end_date',
                'client_id',
                'grand_total_qty',
                'grand_total_delivery'
            )
        );
    }

    public function deliveryPrint(string $clientid)
    {
        // Client-এর সব invoice
        $sales = Sales::where('client_id', $clientid)
            ->orderBy('id', 'desc')
            ->get();

        if ($sales->isEmpty()) {
            abort(404, 'No sales found for this client.');
        }

        // Client
        $client = $sales->first()->client;

        // Vendor / Company info
        $vendor = $sales->first()->vendor;
// dd($sales->first(),$clientid);
        if ($vendor) {
            $company = $vendor->name;
            $hotline = $vendor->phone;
            $logo = $vendor->logo;
            $title = $vendor->name;

            $informations = $vendor->address . '</br>' .
                $vendor->phone . ', ' .
                $vendor->email . ', ' .
                $vendor->contact_person;
        } else {
            $logo = null;
            $hotline = '01xxxxx-xxxxx';
            $title = 'Company Name Goes Here.';
            $informations = 'Company address will goes here </br>
                Mobile: 0967XXXXXX, Email: youremail@gmail.com, www.website.com';
        }

        /*
        |--------------------------------------------------------------------------
        | Client-এর সব Sales List
        |--------------------------------------------------------------------------
        */
        $lists = SalesList::with('product')
            ->where('client_id', $clientid)
            ->where('delivery', '>', 0)
            ->orderBy('id', 'asc')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Client-wise Delivery Amount
        |--------------------------------------------------------------------------
        */
        $client_total_delivery_amount = SalesList::where('client_id', $clientid)
            ->sum(DB::raw('delivery * rate'));

        /*
        |--------------------------------------------------------------------------
        | Client-wise Discount
        |--------------------------------------------------------------------------
        */
        $total_discount_amount = SalesList::where('client_id', $clientid)
            ->sum('discount');

        /*
        |--------------------------------------------------------------------------
        | Client-এর Total Sales Amount
        |--------------------------------------------------------------------------
        */
        $client_total_amount = SalesList::where('client_id', $clientid)
            ->sum(DB::raw('qty * rate'));

        /*
        |--------------------------------------------------------------------------
        | Total Paid Amount
        |--------------------------------------------------------------------------
        */
        $total_paid_amount = Collection::where('client_id', $clientid)
            ->where('collection_type', '!=', 'adjust')
            ->sum('amount');

        /*
        |--------------------------------------------------------------------------
        | Opening Balance
        |--------------------------------------------------------------------------
        |
        | Previous payment বাদ দিয়ে client-এর total delivery amount
        |
        */
        $opening = $client_total_delivery_amount
            - $total_discount_amount
            - $total_paid_amount;

        /*
        |--------------------------------------------------------------------------
        | Latest / Main Data Object
        |--------------------------------------------------------------------------
        |
        | Blade-এর existing structure ঠিক রাখার জন্য
        | প্রথম Sales object-এর সাথে client-wise list attach করছি।
        |
        */
        $data = $sales->first();

        $data->list = $lists;

        $data->client = $client;

        /*
        |--------------------------------------------------------------------------
        | Client-wise total delivery
        |--------------------------------------------------------------------------
        */
        $data->total_delivery_amount = $client_total_delivery_amount;

        /*
        |--------------------------------------------------------------------------
        | Client-wise total quantity
        |--------------------------------------------------------------------------
        */
        $data->total_qty = SalesList::where('client_id', $clientid)
            ->sum('qty');

        /*
        |--------------------------------------------------------------------------
        | Client-wise total delivered quantity
        |--------------------------------------------------------------------------
        */
        $data->total_delivery_qty = SalesList::where('client_id', $clientid)
            ->sum('delivery');

        /*
        |--------------------------------------------------------------------------
        | Client-wise pending quantity
        |--------------------------------------------------------------------------
        */
        $data->total_pending_qty = $data->total_qty - $data->total_delivery_qty;

        $data->invoice = 'CLIENT-WISE DELIVERY HISTORY';
        $data->date = $sales->max('date');
        $data->updated_at = $sales->max('updated_at');

        $report_title = 'Delivery';

        return view(
            'admin.sales-delivery.deliveryPrint',
            compact(
                'title',
                'total_discount_amount',
                'logo',
                'informations',
                'hotline',
                'report_title',
                'data',
                'opening',
                'client_total_delivery_amount'
            )
        );
    }

    
     public function pendingDetails(string $clientid)
    {
        $client = DB::table('clients')
            ->where('id', $clientid)
            ->first();

        if (!$client) {
            return redirect()->back()->with('error', 'Client not found.');
        }

        // Client wise invoice/sales
        $sales = DB::table('sales as s')
            ->leftJoin('clients as c', 'c.id', '=', 's.client_id')
            ->where('s.client_id', $clientid)
            ->select(
                's.id',
                's.invoice',
                's.date',
                's.client_id',
                'c.name as client_name'
            )
            ->orderBy('s.date', 'desc')
            ->get();

        // Client wise delivery
        $deliveries = DB::table('sales_lists as sl')
            ->leftJoin('products as p', 'p.id', '=', 'sl.product_id')
            ->leftJoin('sales as s', 's.id', '=', 'sl.sales_id')
            ->where('sl.client_id', $clientid)
            ->whereColumn('sl.delivery', '<>', 'sl.qty')
            ->select(
                'sl.*',
                's.invoice',
                's.date as invoice_date',
                'p.name as product_name',
                'p.code as product_code',
                'p.id as product_id'
            )
            ->orderBy('s.date', 'desc')
            ->get()
            ->groupBy('sales_id');

        return view('admin.sales-delivery.pendingDetails', compact(
            'client',
            'sales',
            'deliveries'
        ));
    }

    public function deliveryPending(Request $request)
    {
        $title = "Delivery Pending";
        $filter_link = Route('admin.delivery.list');

        $date_range = explode('to', $request->date_range);
        $start_date = isset($date_range[0]) ? date('Y-m-d', strtotime(trim($date_range[0]))) : null;
        $end_date   = isset($date_range[1]) ? date('Y-m-d', strtotime(trim($date_range[1]))) : null;

        $client_id = $request->client_id;

        $clients = Client::where('status', 1)
            ->orderBy('name', 'asc')
            ->get();

        $query = DB::table('clients as cl')
            ->leftJoin('sales_lists as sl', 'cl.id', '=', 'sl.client_id')
            ->select(
                'cl.id',
                'cl.name',
                DB::raw('MAX(sl.created_at) as sale_date'),
                DB::raw('SUM(sl.qty) as total_qty'),
                DB::raw('SUM(sl.delivery) as total_delivery')
            );

        if (!empty($request->client_id)) {
            $query->where('cl.id', $request->client_id);
        }

        if (!empty($start_date) && !empty($end_date)) {
            $query->whereBetween(DB::raw('DATE(sl.created_at)'), [$start_date, $end_date]);
        }

        $data = $query
            ->groupBy('cl.id', 'cl.name')
            ->orderBy('cl.name')
            ->get();

        // Grand Total
        $grand_total_qty = $data->sum('total_qty');
        $grand_total_delivery = $data->sum('total_delivery');

        return view(
            'admin.sales-delivery.pending',
            compact(
                'title',
                'filter_link',
                'clients',
                'data',
                'start_date',
                'end_date',
                'client_id',
                'grand_total_qty',
                'grand_total_delivery'
            )
        );
    }

    public function getOrderNo()
    {
        $first = date('Y-m-01');
        $last = new Carbon('last day of this month');
        $order = Sales::withoutGlobalScope(CompanyScope::class)->withTrashed()->select(['invoice'])->where('created_at', '>=', $first)->where('created_at', '<=', $last)->latest('id')->first();
        if ($order) {
            $trim = str_replace("STS", '', $order->invoice);
            $orderPrefix = (int)$trim + 1;
            $invoice = "STS" . $orderPrefix;
        } else {
            $invoice = "STS" . date('y') . date('m') . '000001';
        }
        return $invoice;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (request()->ajax() && request()->has('get_products')) {
            $query = Product::query();
            if (!empty(request('selected_product_ids'))) {
                $query->whereNotIn('id', request('selected_product_ids'));
            }
            if (!is_null(request('vendor_id'))) {
                $query->whereHas('vendors', function ($q) {
                    $q->where('vendor_id', request('vendor_id'));
                });
            }
            $products = $query->where('status', 1)->orderBy('name', 'asc')->get();
            return response()->json(['status' => 'success', 'products' => $products]);
        }

        if (request()->ajax() && request()->has('client_orders')) {
            $client_id = request('client_id');
            $client = Client::findOrFail($client_id);
            $salesAmount = Sales::where('client_id', $client_id)->sum(DB::raw('total_amount - discount'));
            $paymentAmount = Collection::where('client_id', $client_id)->where('collection_type', '!=', 'adjust')->sum('amount');
            $returnAmount = SalesReturn::where('client_id', $client_id)->sum('amount');
            $balance = ($returnAmount + $paymentAmount + $client->credit_limit) - $salesAmount;

            $orders = Order::where('client_id', $client_id)->where('order_type', 'offline')->whereHas('products', function ($query) {
                $query->where('delivered', 0);
            })->get();
            return response()->json(['status' => 'success', 'orders' => $orders, 'balance' => $balance, 'client' => $client]);
        }

        if (request()->ajax() && request()->has('order_products')) {
            $query = OrderProduct::with(['product']);
            if (!empty(request('selected_product_ids'))) {
                $query->whereNotIn('product_id', request('selected_product_ids'));
            }
            $products = $query->where('order_id', request('order_id'))->where('delivered', 0)->get();
            return response()->json(['status' => 'success', 'products' => $products]);
        }

        if (request()->ajax() && request()->has('get_product')) {
            if (!is_null(request('order_product_id'))) {
                $product = OrderProduct::findOrFail(request('order_product_id'));
                $quantity = $product->quantity;
                $product_id = $product->product_id;
            }
            if (!is_null(request('product_id'))) {
                $quantity = 0;
                $product_id = request('product_id');
            }

            $store_id = request('store_id');
            $stock = $this->stock($product_id, $store_id);
            return response()->json(['status' => 'success', 'quantity' => $quantity, 'stock' => $stock]);
        }

        if (request()->ajax() && request()->has('get_stock')) {
            $store_id = request('store_id');
            if (!is_null(request('order_product_id'))) {
                $product = OrderProduct::findOrFail(request('order_product_id'));
                $product_id = $product->product_id;
            } else {
                $product_id = request('product_id');
            }

            $stock = $this->stock($product_id, $store_id);
            // if (request('quantity') > $stock) {
            //     return response()->json(['status' => 'error', 'data' => 'stock not available please decrease quantity!']);
            // } else {
                if (!is_null(request('product_id'))) {
                    $product = Product::find(request('product_id'));
                    $client_price = ClientPrice::where('client_id', request('client_id'))->where('product_id', request('product_id'))->first();
                    $pre_order_product = 'false';
                    $order_product_id = '';
                } elseif (!is_null(request('order_product_id'))) {
                    $order_product = OrderProduct::find(request('order_product_id'));
                    $product = Product::find($order_product->product_id);
                    $client_price = ClientPrice::where('client_id', request('client_id'))->where('product_id', $order_product->product_id)->first();
                    $pre_order_product = 'true';
                    $order_product_id = $order_product->id;
                }
                $price = $client_price ? $client_price->client_price : $product->price->sale_price;
                $amount = request('quantity') * $price;
                $unit = @$product->attribute->name;
                $vendor = @$product->vendors->pluck('vendor.name');
                return response()->json(['status' => 'success', 'product' => $product, 'unit' => $unit, 'quantity' => request('quantity'), 'stock' => $stock, 'pre_order_product' => $pre_order_product, 'order_product_id' => $order_product_id, 'price' => $price, 'amount' => $amount, 'vendor' => $vendor]);
           // }
        }

        $products = Product::where('product_type', 'Consumer')->where('status', 1)->orderBy('name', 'asc')->get();
        $title = 'Add New Sales';
        $clients = Client::where('status', 1)->orWhere('is_chain', 1)->orderBy('name', 'asc')->get();
        $vendors = Vendor::where('status', 1)->orderBy('name', 'asc')->get();
        $stores = Store::where('status', 1)->get();
        $staffs = Staff::where('status', 1)->orderBy('name', 'asc')->get();
        $invoice = $this->getOrderNo();
        $cash_heads = CoaSetup::with('parent')->whereHas('parent', function ($query) {
            $query->where('head_name', 'Cash In Hand')->orWhere('head_name', 'Cash In Bank');
        })->get();
        return view('admin.sales.create', compact('title', 'clients', 'invoice', 'vendors', 'stores', 'staffs', 'products', 'cash_heads'));
    }

    public static function stock($product_id, $store_id, $product_type = 'Consumer')
    {
        if ($product_type == 'Consumer') {
            //VIew
            // $liftings = DB::table('view_liftings')->where('product_type', $product_type)->where('store_id', $store_id)->where('product_id', $product_id)->sum('qty');
            // $lifting_returns = DB::table('view_lifting_returns')->where('product_type', $product_type)->where('store_id', $store_id)->where('product_id', $product_id)->sum('qty');
            // $sales = DB::table('view_sales')->where('product_type', $product_type)->where('store_id', $store_id)->where('product_id', $product_id)->sum('qty');
            // $sales_returns = DB::table('view_sales_returns')->where('product_type', $product_type)->where('store_id', $store_id)->where('product_id', $product_id)->sum('qty');
            // $online_sales = DB::table('view_online_sales')->where('product_type', $product_type)->whereIn('status', ['On Route', 'Delivered', 'Collected'])->where('store_id', $store_id)->where('product_id', $product_id)->sum('qty');
            // $transfers = DB::table('view_transfers')->where('product_type', $product_type)->where('product_id', $product_id)->where('host_id', $store_id)->sum('qty');
            // $receives = DB::table('view_transfers')->where('product_type', $product_type)->where('product_id', $product_id)->where('destination_id', $store_id)->sum('qty');

            //Table
            $liftings =DB::table('lifting_products')->where('product_type', $product_type)->where('product_id', $product_id)->sum('qty');
             $lifting_returns = DB::table('lifting_return_lists')->where('product_type', $product_type)->where('store_id', $store_id)->where('product_id', $product_id)->sum('qty');
            $sales = DB::table('sales_lists')->where('product_type', $product_type)->where('store_id', $store_id)->where('product_id', $product_id)->sum('qty');
            $sales_returns = DB::table('sales_return_lists')->where('product_type', $product_type)->where('store_id', $store_id)->where('product_id', $product_id)->sum('qty');
            $online_sales = DB::table('order_products')->where('product_id', $product_id)->sum('quantity');
            $transfers = DB::table('transfer_products')->where('product_type', $product_type)->where('product_id', $product_id)->sum('qty');
            $receives = DB::table('transfer_products')->where('product_type', $product_type)->where('product_id', $product_id)->sum('qty');
        }
        if ($product_type == 'Fashion') {
            $liftings = DB::table('view_liftings')->where('product_type', $product_type)->where('store_id', $store_id)->where('sku_id', $product_id)->sum('qty');
            $lifting_returns = DB::table('view_lifting_returns')->where('product_type', $product_type)->where('store_id', $store_id)->where('sku_id', $product_id)->sum('qty');
            $sales = DB::table('view_sales')->where('product_type', $product_type)->where('store_id', $store_id)->where('sku_id', $product_id)->sum('qty');
            $sales_returns = DB::table('view_sales_returns')->where('product_type', $product_type)->where('store_id', $store_id)->where('sku_id', $product_id)->sum('qty');
            $online_sales = DB::table('view_online_sales')->where('product_type', $product_type)->whereIn('status', ['On Route', 'Delivered', 'Collected'])->where('store_id', $store_id)->where('sku_id', $product_id)->sum('qty');
            $transfers = DB::table('view_transfers')->where('product_type', $product_type)->where('sku_id', $product_id)->where('host_id', $store_id)->sum('qty');
            $receives = DB::table('view_transfers')->where('product_type', $product_type)->where('sku_id', $product_id)->where('destination_id', $store_id)->sum('qty');
        }
          
        return $liftings + $sales_returns + $receives - $lifting_returns - $sales - $online_sales - $transfers;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'sales_type' => 'required',
            'invoice' => 'required',
            'date' => 'required',
            'client_id' => 'required',
            'store_id' => 'required',
            'product_id' => 'required',
            'qty' => 'required',
        ]);

        $client = Client::find($request->client_id);
        $admin_setting = AdminSetting::first();
        if (@$admin_setting->accounting == 1 && is_null($client->coa)) {
            return redirect()->back()->withErrors('Please Setup a clients account!');
        }

        $client_id = $request->client_id;
        $client = Client::findOrFail($client_id);
        $salesAmount = Sales::where('client_id', $client_id)->sum(DB::raw('total_amount - discount'));
        $paymentAmount = Collection::where('client_id', $client_id)->where('collection_type', '!=', 'adjust')->sum('amount');
        $returnAmount = SalesReturn::where('client_id', $client_id)->sum('amount');
        $balance = ($returnAmount + $paymentAmount + $client->credit_limit) - $salesAmount;

        if ($request->sales_type == 'credit' && $balance < $request->net_payable) {
            return redirect()->back()->withErrors('Insufficient Credit Limit please check client limitation');
        }

        try {
            DB::transaction(function () use ($request, $admin_setting, &$sales) {
                $total_amount = 0;
                foreach ($request->amount as $amount) {
                    $total_amount += $amount;
                }

                $invoice = $this->getOrderNo();
                $store_id = $request->store_id;
                $sales = Sales::create([
                    'company_id' => Auth::user()->company_id ?? 1,
                    'store_id' => $store_id,
                    'client_id' => $request->client_id,
                    'vendor_id' => $request->vendor_id,
                    'invoice' => $invoice,
                    'date' => date('Y-m-d', strtotime($request->date)),
                    'sales_type' => $request->sales_type,
                    'total_amount' => $total_amount,
                    'total_delivery_amount' => 0,
                    'discount' => $request->discount,
                    'total_paid' => $request->net_payable,
                    'created_by' => Auth::user()->id,
                    'staff_id' => $request->staff_id,
                ]);

                foreach ($request->product_id as $key => $product_id) {
                    $stock = $this->stock($product_id, $store_id);
                    //stock validation removed
                    // if ($request->qty[$key] > $stock) {
                    //     $product = Product::find($product_id);
                    //     throw new Exception('stock not available please decrease quantity for ' . $product->name);
                    // } else {
                        $order_product = OrderProduct::find($request->order_product_id[$key]);
                        if (!is_null($order_product)) {
                            $order_product->update(['delivered' => 1]);
                        }

                        $discount = ($request->discount / $request->total_amount) * $request->amount[$key];
                        $product = Product::find($product_id);
                        $tradediscount= 0;
                        if($product->type==1){
                            $offerqty=0;
                            $freeqty=0;
                            $offer_subtotal=0;
                            $freeqty= floor($request->qty[$key]/(int)$product->do_ratio) ;
                            $offerqty = $request->qty[$key]-$freeqty;
                            $offer_subtotal=$offerqty*$request->rate[$key];
                           $tradediscount=$freeqty*$request->rate[$key];
                        }
                       
                        SalesList::create([
                            'company_id' => Auth::user()->company_id ?? 1,
                            'sales_id' => $sales->id,
                            'store_id' => $request->store_id,
                            'client_id' => $request->client_id,
                            'product_id' => $product_id,
                            'order_product_id' => $request->order_product_id[$key],
                            'do_ratio' => $product->do_ratio,
                            'rate' => $request->rate[$key],
                            'qty' => $request->qty[$key],
                            'offer_qty' => $request->offer_qty[$key],
                            'trade_discount' => $request->trade_discount[$key],
                            'amount' => $request->amount[$key],
                            'delivery_amount' => 0,
                            'discount' => $discount+$tradediscount,
                            'collection' => $request->sales_type == 'cash' ? ($request->amount[$key] - $discount) : 0.00,
                        ]);
                    //}
                }

                $client = Client::find($request->client_id);
                if (@$admin_setting->accounting == 1 && $client->coa) {
                    $income_head = CoaSetup::where('head_type', 'I')->where('head_name', 'Whole Sale')->first();
                    $headCode = collect([
                        '0' => $client->coa->head_code,
                        '1' => $income_head->head_code,
                    ]);

                    $debit_amount = collect([
                        '0' => $total_amount - $request->discount,
                        '1' => 0.00
                    ]);

                    $credit_amount = collect([
                        '0' => 0.00,
                        '1' => $total_amount - $request->discount,
                    ]);

                    $countHead = count($headCode);
                    $postData = [];
                    for ($i = 0; $i < $countHead; $i++) {
                        $coa = CoaSetup::where('company_id', (Auth::user()->company_id ?? 1))->where('head_code', $headCode[$i])->first();
                        $postData[] = [
                            'company_id' => Auth::user()->company_id ?? 1,
                            'voucher_no' => $invoice,
                            'voucher_type' => "Sales",
                            'voucher_date' => date('Y-m-d', strtotime($request->date)),
                            'coa_setup_id' => $coa->id,
                            'coa_head_code' => $headCode[$i],
                            'narration' => 'Product Sales Against Invoice No - ' . $invoice,
                            'debit_amount' => $debit_amount[$i],
                            'credit_amount' => $credit_amount[$i],
                            'created_by' => Auth::user()->id,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now()
                        ];
                    }
                    AccountTransactionAuto::insert($postData);
                }

                // if ($request->sales_type == 'cash') {
                //     $first = date('Y-m-01');
                //     $last = new Carbon('last day of this month');
                //     $pay_data = Collection::withoutGlobalScope(CompanyScope::class)->withTrashed()->select(['payment_no'])->where('created_at', '>=', $first)->where('created_at', '<=', $last)->latest('id')->first();
                //     if ($pay_data) {
                //         $trim = str_replace("STC", '', $pay_data->payment_no);
                //         $dataPrefix = (int)$trim + 1;
                //         $payment_no = "STC" . $dataPrefix;
                //     } else {
                //         $payment_no = "STC" . date('y') . date('m') . '000001';
                //     }

                //     $collection = Collection::create([
                //         'company_id' => Auth::user()->company_id ?? 1,
                //         'client_id' => $request->client_id,
                //         'payment_no' => $payment_no,
                //         'amount' => $total_amount - $request->discount,
                //         'payment_date' => date('Y-m-d', strtotime($request->date)),
                //         'collection_type' => 'collection',
                //         'payment_type' => $request->sales_type,
                //         'remarks' => 'Paid on Sale',
                //         'sales_id' => $sales->id,
                //         'created_by' => Auth::user()->id,
                //     ]);

                //     CollectionData::create([
                //         'collection_id' => $collection->id,
                //         'sales_id' => $sales->id,
                //         'amount' => $total_amount - $request->discount,
                //     ]);

                //     if (@$admin_setting->accounting == 1 && $client->coa) {
                //         $cash_head = CoaSetup::findOrFail($request->coa_setup_id);
                //         $headCode = collect([
                //             '0' => $cash_head->head_code,
                //             '1' => $client->coa->head_code
                //         ]);

                //         $postData = [];
                //         for ($i = 0; $i < $countHead; $i++) {
                //             $coa = CoaSetup::where('company_id', Auth::user()->company_id ?? 1)->where('head_code', $headCode[$i])->first();
                //             $postData[] = [
                //                 'company_id' => Auth::user()->company_id ?? 1,
                //                 'voucher_no' => $payment_no,
                //                 'voucher_type' => "Collection",
                //                 'voucher_date' => date('Y-m-d', strtotime($request->date)),
                //                 'coa_setup_id' => $coa->id,
                //                 'coa_head_code' => $headCode[$i],
                //                 'narration' => 'Collection Against PAYMENT NO - ' . $payment_no,
                //                 'debit_amount' => $debit_amount[$i],
                //                 'credit_amount' => $credit_amount[$i],
                //                 'created_by' => Auth::user()->id,
                //                 'created_at' => Carbon::now(),
                //                 'updated_at' => Carbon::now()
                //             ];
                //         }
                //         AccountTransactionAuto::insert($postData);
                //     }
                // }

                $store = Store::find($request->store_id);
                $products_info = '';
                foreach ($request->product_id as $key => $product_id) {
                    $product = Product::find($product_id);
                    $products_info .= ' ' . $product->name . ' Quanity : ' . $request->qty[$key] . ' ' . $product->attribute->name;
                }
                AccessLog::create([
                    'date_time' => Carbon::now(),
                    'page' => 'Sales',
                    'action' => 'Add',
                    'description' => 'Create a new sales with invoice no ' . $sales->invoice . ' to client ' . $client->name . ' from store ' . $store->name . ' sales amount is ' . $sales->total_amount . ' sales discount ' . $sales->discount . ' products ' . $products_info . ' on ' . ($request->sales_type == 'cash' ? 'cash sale' : 'credit sale'),
                    'user_id' => Auth::user()->id,
                ]);
            });
        } catch (Throwable $caught) {
            dd($caught);
            if ($caught) {
                return redirect()->back()->withErrors('Something went wrong cought!');
            }
        }
        return redirect()->route('admin.sales.index')->withSuccessMessage('Created Successfully!');
    }

    function singleSms($msisdn, $messageBody, $csmsId)
    {
        $params = [
            "api_token" => API_TOKEN,
            "sid" => SID,
            "msisdn" => $msisdn,
            "sms" => $messageBody,
            "csms_id" => $csmsId
        ];
        $url = trim(DOMAIN, '/') . "/api/v3/send-sms";
        $params = json_encode($params);

        return $this->callApi($url, $params);
    }

    public static function callApi($url, $params)
    {
        $ch = curl_init(); // Initialize cURL
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($params),
            'accept:application/json'
        ));

        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        
        $data = Sales::findOrFail($id);
        if ($data) {
            $company = $data->vendor->name;
            $hotline = $data->vendor->phone;
            $logo = $data->vendor->logo;
            $title = $data->vendor->name;
            $informations = $data->vendor->address . '</br>' . $data->vendor->phone . ', ' . $data->vendor->email . ', ' . $data->vendor->contact_person;
        } else {
            $logo = NULL;
            $hotline = '01xxxxx-xxxxx';
            $title = 'Company Name Goes Here.';
            $informations = 'Company address will goes here </br> Mobile: 0967XXXXXX, Email: youremail@gmail.com, www.website.com';
        }
        $report_title = 'Chalan';
         return view('admin.sales.chalan', compact('title', 'logo', 'informations', 'report_title', 'data'));
        $pdf = Pdf::loadView('admin.sales.chalan', compact('title', 'logo', 'informations', 'report_title', 'data'));
        // $pdf->setPaper('A4', 'landscape');
        return $pdf->stream('chalan_' . date('d_m_Y_H_i_s') . '.pdf');
    }

    /**
     * Display the specified resource.
     */
    public function invoice(string $id)
    {
        $data = Sales::findOrFail($id);
        if ($data) {
            $company = $data->vendor->name;
            $hotline = $data->vendor->phone;
            $logo = $data->vendor->logo;
            $title = $data->vendor->name;
            $informations = $data->vendor->address . '</br>' . $data->vendor->phone . ', ' . $data->vendor->email . ', ' . $data->vendor->contact_person;

        } else {
            $logo = NULL;
            $hotline = '01xxxxx-xxxxx';
            $title = 'Company Name Goes Here.';
            $informations = 'Company address will goes here </br> Mobile: 0967XXXXXX, Email: youremail@gmail.com, www.website.com';
        }
      
        $date = now()->toDateString();
        $date = Carbon::parse($date);
        $clientId= $data->client_id;
        /*
        |--------------------------------------------------------------------------
        | Opening Balance
        |--------------------------------------------------------------------------
        | Selected date এর আগের সব Sales এবং Collection
        */

        $totalSalesBefore = Sales::where('client_id', $clientId)
            ->whereDate('date', '<', $date)
            ->sum('total_paid');

        $totalCollectionBefore = Collection::where('client_id', $clientId)
            ->whereDate('payment_date', '<', $date)
            ->sum('amount');

        $openingBalance = $totalCollectionBefore - $totalSalesBefore;


        /*
        |--------------------------------------------------------------------------
        | Selected Date Transaction
        |--------------------------------------------------------------------------
        */

        $todaySales = Sales::where('client_id', $clientId)
            ->whereDate('date', $date)
            ->sum('total_paid');

        $todayCollection = Collection::where('client_id', $clientId)
            ->whereDate('payment_date', $date)
            ->sum('amount');


        /*
        |--------------------------------------------------------------------------
        | Closing Balance
        |--------------------------------------------------------------------------
        */

        $closingBalance = $openingBalance
            + $todayCollection
            - $todaySales;


        $report_title = 'Invoice';
        
         return view('admin.sales.invoice', compact('title', 'logo', 'informations', 'hotline', 'report_title', 'data', 'openingBalance','closingBalance'));
        // $pdf = Pdf::loadView('admin.sales.invoice', compact('title', 'logo', 'informations', 'hotline', 'report_title', 'data', 'opening'));
        // $pdf->setPaper('A4', 'landscape');
        return $pdf->stream('sales_invoice_' . date('d_m_Y_H_i_s') . '.pdf');
    }
     

    /**
     * Display the specified resource.
     */
    public function vat(string $id)
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
        $data = Sales::findOrFail($id);

        $report_title = 'Vat Chalan';
        return view('admin.sales.vat', compact('title', 'logo', 'informations', 'report_title', 'data'));
        $pdf = Pdf::loadView('admin.sales.vat', compact('title', 'logo', 'informations', 'report_title', 'data'));
        $pdf->setPaper('A4');
        return $pdf->stream('sales_invoice_' . date('d_m_Y_H_i_s') . '.pdf');
    }
   public function deliveryEdit(string $clientid)
    {
        $client = DB::table('clients')
            ->where('id', $clientid)
            ->first();

        if (!$client) {
            return redirect()->back()->with('error', 'Client not found.');
        }

        // Client wise invoice/sales
        $sales = DB::table('sales as s')
            ->leftJoin('clients as c', 'c.id', '=', 's.client_id')
            ->where('s.client_id', $clientid)
            ->select(
                's.id',
                's.invoice',
                's.date',
                's.client_id',
                'c.name as client_name'
            )
            ->orderBy('s.date', 'desc')
            ->get();

        // Client wise delivery
        $deliveries = DB::table('sales_lists as sl')
            ->leftJoin('products as p', 'p.id', '=', 'sl.product_id')
            ->leftJoin('sales as s', 's.id', '=', 'sl.sales_id')
            ->where('sl.client_id', $clientid)
            ->whereColumn('sl.delivery', '<>', 'sl.qty')
            ->select(
                'sl.*',
                's.invoice',
                's.date as invoice_date',
                'p.name as product_name',
                'p.code as product_code',
                'p.id as product_id'
            )
            ->orderBy('s.date', 'desc')
            ->get()
            ->groupBy('sales_id');

        return view('admin.sales-delivery.delivery_edit', compact(
            'client',
            'sales',
            'deliveries'
        ));
    }

   public function deliveryUpdate(Request $request, string $clientid)
{
    DB::beginTransaction();

    try {

        $deliveryDate = now()->format('Y-m-d');

        // প্রতিটি invoice-এর জন্য আলাদা SalesDelivery রাখবে
        $salesDeliveries = [];

        foreach ($request->delivery_id ?? [] as $key => $deliveryId) {

            $deliveryQty = (float) ($request->delivery_qty[$key] ?? 0);

            if ($deliveryQty <= 0) {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Sales List
            |--------------------------------------------------------------------------
            */
            $salesList = SalesList::where('id', $deliveryId)
                ->where('client_id', $clientid)
                ->first();

            if (!$salesList) {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Sales / Invoice
            |--------------------------------------------------------------------------
            */
            $sales = Sales::find($salesList->sales_id);

            if (!$sales) {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Product
            |--------------------------------------------------------------------------
            */
            $productId = $request->product_id[$key]
                ?? $salesList->product_id;

            $product = Product::find($productId);

            if (!$product) {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Rate & Qty
            |--------------------------------------------------------------------------
            */
            $rate = (float) (
                $request->rate[$key]
                ?? $salesList->rate
                ?? 0
            );

            $qty = (float) (
                $request->qty[$key]
                ?? $salesList->qty
                ?? 0
            );

            $deliveryAmount = $deliveryQty * $rate;

            /*
            |--------------------------------------------------------------------------
            | Vendor
            |--------------------------------------------------------------------------
            */
            $vendorId = $sales->vendor
                ? $sales->vendor->vendor_id
                : null;

            /*
            |--------------------------------------------------------------------------
            | Trade Discount
            |--------------------------------------------------------------------------
            */
            $totalQty = SalesList::where('sales_id', $sales->id)
                ->sum('qty');

            $tradeDiscount = 0;

            if ($totalQty > 0) {

                $perDiscount =
                    (float) $sales->discount / $totalQty;

                $tradeDiscount =
                    $perDiscount * $deliveryQty;
            }

            /*
            |--------------------------------------------------------------------------
            | SalesDelivery
            |--------------------------------------------------------------------------
            |
            | একই invoice হলে একই SalesDelivery
            | ভিন্ন invoice হলে নতুন SalesDelivery
            |
            */
            if (!isset($salesDeliveries[$sales->id])) {

                $salesDeliveries[$sales->id] = SalesDelivery::create([
                    'vendor_id'              => $vendorId,
                    'sales_id'               => $sales->id,
                    'client_id'              => $clientid,
                    'delivery_date'          => $deliveryDate,
                    'total_amount'           => $sales->total_amount,
                    'total_delivery_amount' => 0,
                    'discount'               => 0,
                    'total_paid'             => $sales->total_paid,
                    'status'                 => 1,
                    'created_by'             => auth()->id(),
                ]);
            }

            $salesDelivery = $salesDeliveries[$sales->id];

            /*
            |--------------------------------------------------------------------------
            | Duplicate Product Check
            |--------------------------------------------------------------------------
            |
            | একই invoice + একই product + একই variant
            | এই delivery transaction-এর মধ্যে duplicate হবে না
            |
            */
            $alreadyExists = SalesDeliveryList::where(
                'sales_delivery_id',
                $salesDelivery->id
            )
            ->where('product_id', $productId)
            ->where('variant_id', $salesList->variant_id)
            ->exists();

            if ($alreadyExists) {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Sales Delivery List
            |--------------------------------------------------------------------------
            */
            SalesDeliveryList::create([
                'sales_delivery_id' => $salesDelivery->id,
                'client_id'         => $clientid,
                'product_id'        => $productId,
                'delivery_date'     => $deliveryDate,
                'variant_id'        => $salesList->variant_id ?? null,
                'do_ratio'          => $product->do_ratio ?? 0,
                'offer_qty'         => 0,
                'trade_discount'    => $tradeDiscount,
                'rate'              => $rate,
                'qty'               => $qty,
                'delivery'          => $deliveryQty,
                'delivery_amount'   => $deliveryAmount,
            ]);

            /*
            |--------------------------------------------------------------------------
            | sales_lists.delivery Update
            |--------------------------------------------------------------------------
            */
            $salesList->increment('delivery', $deliveryQty);
        }

        /*
        |--------------------------------------------------------------------------
        | Update Each Invoice
        |--------------------------------------------------------------------------
        */
        foreach ($salesDeliveries as $salesId => $salesDelivery) {

            /*
            |--------------------------------------------------------------------------
            | Sales Delivery Total
            |--------------------------------------------------------------------------
            */
            $totalDeliveryAmount = SalesDeliveryList::where(
                'sales_delivery_id',
                $salesDelivery->id
            )->sum('delivery_amount');

            $totalDiscount = SalesDeliveryList::where(
                'sales_delivery_id',
                $salesDelivery->id
            )->sum('trade_discount');

            $salesDelivery->update([
                'total_delivery_amount' => $totalDeliveryAmount,
                'discount'              => $totalDiscount,
            ]);

            /*
            |--------------------------------------------------------------------------
            | Sales.delivery_amount
            |--------------------------------------------------------------------------
            */
            $sales = Sales::find($salesId);

            if ($sales) {

                $salesDeliveryAmount = SalesDelivery::where(
                    'sales_id',
                    $salesId
                )->sum('total_delivery_amount');

                $sales->update([
                    'delivery_amount' => $salesDeliveryAmount,
                ]);
            }
        }

        DB::commit();

        return redirect()
            ->route('admin.delivery.list')
            ->withSuccessMessage('Delivery saved successfully.');

    } catch (\Exception $e) {
         dd($e);
        DB::rollBack();

        return redirect()
            ->back()
            ->withInput()
            ->with('error', $e->getMessage());
    }
}
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        if (request()->ajax() && request()->has('get_products')) {
            $query = Product::query();
            if (!empty(request('selected_product_ids'))) {
                $query->whereNotIn('id', request('selected_product_ids'));
            }
            if (!is_null(request('vendor_id'))) {
                $query->whereHas('vendors', function ($q) {
                    $q->where('vendor_id', request('vendor_id'));
                });
            }
            $products = $query->where('status', 1)->orderBy('name', 'asc')->get();
            return response()->json(['status' => 'success', 'products' => $products]);
        }

        if (request()->ajax() && request()->has('client_orders')) {
            $client_id = request('client_id');
            $client = Client::findOrFail($client_id);
            $salesAmount = Sales::where('client_id', $client_id)->where('id', '!=', $id)->sum(DB::raw('total_amount - discount'));
            $paymentAmount = Collection::where('client_id', $client_id)->where('sales_id', '!=', $id)->where('collection_type', '!=', 'adjust')->sum('amount');
            $returnAmount = SalesReturn::where('client_id', $client_id)->sum('amount');
            $balance = ($returnAmount + $paymentAmount + $client->credit_limit) - $salesAmount;
            $orders = Order::where('client_id', $client_id)->where('order_type', 'offline')->whereHas('products', function ($query) {
                $query->where('delivered', 0);
            })->get();
            return response()->json(['status' => 'success', 'orders' => $orders, 'balance' => $balance]);
        }

        if (request()->ajax() && request()->has('order_products')) {
            $query = OrderProduct::with(['product']);
            if (!empty(request('selected_product_ids'))) {
                $query->whereNotIn('product_id', request('selected_product_ids'));
            }
            $products = $query->where('order_id', request('order_id'))->where('delivered', 0)->get();
            return response()->json(['status' => 'success', 'products' => $products]);
        }

        if (request()->ajax() && request()->has('get_product')) {
            if (!is_null(request('order_product_id'))) {
                $product = OrderProduct::findOrFail(request('order_product_id'));
                $quantity = $product->quantity;
                $product_id = $product->product_id;
            }
            if (!is_null(request('product_id'))) {
                $quantity = 0;
                $product_id = request('product_id');
            }

            $store_id = request('store_id');
            $sales = SalesList::where('sales_id', $id)->where('store_id', $store_id)->where('product_id', $product_id)->first();
            //$stock = $this->stock($product_id, $store_id) + @$sales->qty;
            $stock = $this->stock($product_id, $store_id);
            return response()->json(['status' => 'success', 'quantity' => $quantity, 'stock' => $stock]);
        }

        if (request()->ajax() && request()->has('get_stock')) {
            $store_id = request('store_id');
            if (!is_null(request('order_product_id'))) {
                $product = OrderProduct::findOrFail(request('order_product_id'));
                $product_id = $product->product_id;
            } else {
                $product_id = request('product_id');
            }

            $sales = SalesList::where('sales_id', $id)->where('store_id', $store_id)->where('product_id', $product_id)->first();
            //$stock = $this->stock($product_id, $store_id) + @$sales->qty;
            $stock = $this->stock($product_id, $store_id);
            // if (request('quantity') > $stock) {
            //     return response()->json(['status' => 'error', 'data' => 'stock not available please decrease quantity!']);
            // } else {
                if (!is_null(request('product_id'))) {
                    $product = Product::find(request('product_id'));
                    $client_price = ClientPrice::where('client_id', request('client_id'))->where('product_id', request('product_id'))->first();
                    $pre_order_product = 'false';
                    $order_product_id = '';
                } elseif (!is_null(request('order_product_id'))) {
                    $order_product = OrderProduct::find(request('order_product_id'));
                    $product = Product::find($order_product->product_id);
                    $client_price = ClientPrice::where('client_id', request('client_id'))->where('product_id', $order_product->product_id)->first();
                    $pre_order_product = 'true';
                    $order_product_id = $order_product->id;
                }
                $price = $client_price ? $client_price->client_price : $product->price->sale_price;
                $amount = request('quantity') * $price;
                $unit = $product->attribute->name;
                $vendor = $product->vendors->pluck('vendor.name');
               
                return response()->json(['status' => 'success', 'product' => $product, 'unit' => $unit, 'quantity' => request('quantity'), 'stock' => $stock, 'pre_order_product' => $pre_order_product, 'order_product_id' => $order_product_id, 'price' => $price, 'amount' => $amount, 'vendor' => $vendor]);
           // }
        }

        $title = 'Update Sales';
        $clients = Client::where('status', 1)->orWhere('is_chain', 1)->orderBy('name', 'asc')->get();
        $vendors = Vendor::where('status', 1)->orderBy('name', 'asc')->get();
        $stores = Store::where('status', 1)->get();
        $staffs = Staff::where('status', 1)->orderBy('name', 'asc')->get();
        $data = Sales::findOrFail($id);

        $client_id = $data->client_id;
        $client = Client::findOrFail($client_id);
        $salesAmount = Sales::where('client_id', $client_id)->where('id', '!=', $id)->sum(DB::raw('total_amount - discount'));
        $paymentAmount = Collection::where('client_id', $client_id)->where('sales_id', '!=', $id)->where('collection_type', '!=', 'adjust')->sum('amount');
        $returnAmount = SalesReturn::where('client_id', $client_id)->sum('amount');
        $balance = ($returnAmount + $paymentAmount + $client->credit_limit) - $salesAmount;

        $products = Product::where('product_type', 'Consumer')->where('status', 1)->orderBy('name', 'asc')->get();
        $link = Route('admin.sales.update', $id);
        $cash_heads = CoaSetup::with('parent')->whereHas('parent', function ($query) {
            $query->where('head_name', 'Cash In Hand')->orWhere('head_name', 'Cash In Bank');
        })->get();
        return view('admin.sales.edit', compact('title', 'clients', 'vendors', 'stores', 'staffs', 'data', 'link', 'balance', 'products', 'cash_heads'));
    }

    public function searchEdit(Request $request)
    {
        if (is_null($request->invoice)) {
            return redirect()->route('admin.sales.index');
        }

        $title = 'Update Sales';
        $clients = Client::where('status', 1)->orWhere('is_chain', 1)->orderBy('name', 'asc')->get();
        $vendors = Vendor::where('status', 1)->orderBy('name', 'asc')->get();
        $stores = Store::where('status', 1)->get();
        $staffs = Staff::where('status', 1)->orderBy('name', 'asc')->get();
        $data = Sales::where('product_type', 'Consumer')->where('invoice', $request->invoice)->latest('id')->first();
        $collection_data = CollectionData::with('collection')->whereHas('collection')->where('sales_id', $data->id)->first();
        if (!is_null($collection_data)) {
            return redirect()->back()->withErrors('Has Collection!');
        }

        $client_id = $data->client_id;
        $client = Client::findOrFail($client_id);
        $salesAmount = Sales::where('client_id', $client_id)->where('id', '!=', $data->id)->sum(DB::raw('total_amount - discount'));
        $paymentAmount = Collection::where('client_id', $client_id)->where('sales_id', '!=', $data->id)->where('collection_type', '!=', 'adjust')->sum('amount');
        $returnAmount = SalesReturn::where('client_id', $client_id)->sum('amount');
        $balance = ($returnAmount + $paymentAmount + $client->credit_limit) - $salesAmount;

        $products = Product::where('product_type', 'Consumer')->where('status', 1)->orderBy('name', 'asc')->get();
        $link = Route('admin.sales.update', $data->id);
        return view('admin.sales.edit', compact('title', 'clients', 'vendors', 'stores', 'staffs', 'data', 'link', 'balance', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'sales_type' => 'required',
            'invoice' => 'required',
            'date' => 'required',
            'client_id' => 'required',
            'store_id' => 'required',
            'product_id' => 'required',
            'qty' => 'required',
        ]);

        $client = Client::find($request->client_id);
        $admin_setting = AdminSetting::first();
        if (@$admin_setting->accounting == 1 && is_null($client->coa)) {
            return redirect()->back()->withErrors('Please Setup a clients account!');
        }

        $client_id = $request->client_id;
        $client = Client::findOrFail($client_id);
        $salesAmount = Sales::where('client_id', $client_id)->where('id', '!=', $id)->sum(DB::raw('total_amount - discount'));
        $paymentAmount = Collection::where('client_id', $client_id)->where('sales_id', '!=', $id)->where('collection_type', '!=', 'adjust')->sum('amount');
        $returnAmount = SalesReturn::where('client_id', $client_id)->sum('amount');
        $balance = ($returnAmount + $paymentAmount + $client->credit_limit) - $salesAmount;

        // For Next time Calculate Due for and Deduct with Client Credit Limitation;
        // if ($request->sales_type == 'credit' && $balance < $request->net_payable) {
        //     return redirect()->back()->withErrors('Insufficient Credit Limit please check client limitation');
        // }

        try {
            DB::transaction(function () use ($request, $id, $admin_setting) {
                $sales = Sales::findOrFail($id);
                $collection = Collection::withTrashed()->where('sales_id', $id)->first();
                AccountTransactionAuto::withTrashed()->where('voucher_no', $sales->invoice)->where('voucher_type', 'Sales')->forceDelete();
                if ($collection) {
                    AccountTransactionAuto::withTrashed()->where('voucher_no', $collection->payment_no)->where('voucher_type', 'Collection')->forceDelete();
                    $collection->forceDelete();
                }
                SalesList::where('sales_id', $id)->delete();

               $total_amount = 0;

                foreach ($request->amount as $key => $amount) {

                    $total_amount += $amount;

                   
                    $rate = $request->rate[$key] ?? 0;
                }
                $store_id = $request->store_id;
                $sales->update([
                    'store_id' => $store_id,
                    'client_id' => $request->client_id,
                    'vendor_id' => $request->vendor_id,
                    'date' => date('Y-m-d', strtotime($request->date)),
                    'sales_type' => $request->sales_type,
                    'total_amount' => $total_amount,
                    'total_delivery_amount' => 0,
                    'discount' => $request->discount,
                    'total_paid' => $request->net_payable,
                    'updated_by' => Auth::user()->id,
                    'staff_id' => $request->staff_id,
                ]);

                foreach ($request->product_id as $key => $product_id) {
                    $old_sales = SalesList::where('sales_id', $id)->where('store_id', $store_id)->where('product_id', $product_id)->first();
                    $stock = $this->stock($product_id, $store_id) + @$old_sales->qty;

                    // if ($request->qty[$key] > $stock) {
                    //     $product = Product::find($product_id);
                    //     throw new Exception('stock not available please decrease quantity for ' . $product->name);
                    // } else {
                        $order_product = OrderProduct::find($request->order_product_id[$key]);
                        if (!is_null($order_product)) {
                            $order_product->update(['delivered' => 1]);
                        }

                        $discount = ($request->discount / $request->total_amount) * $request->amount[$key];
                         $product=Product::find($product_id);
                        SalesList::create([
                            'company_id' => Auth::user()->company_id ?? 1,
                            'sales_id' => $sales->id,
                            'store_id' => $request->store_id,
                            'client_id' => $request->client_id,
                            'product_id' => $product_id,
                            'order_product_id' => $request->order_product_id[$key],
                            'rate' => $request->rate[$key],
                            'qty' => $request->qty[$key],
                            'delivery' => 0,
                            'amount' => $request->amount[$key],
                            'offer_qty' => $request->offer_qty[$key],
                            'trade_discount' => $request->trade_discount[$key],
                            'delivery_amount' => 0,
                            'discount' => $discount,
                            'collection' => $request->sales_type == 'cash' ? ($request->amount[$key] - $discount) : 0.00,
                        ]);
                   // }
                }

                $client = Client::find($request->client_id);
                if (@$admin_setting->accounting == 1 && $client->coa) {
                    $income_head = CoaSetup::where('head_type', 'I')->where('head_name', 'Whole Sale')->first();
                    $headCode = collect([
                        '0' => $client->coa->head_code,
                        '1' => $income_head->head_code,
                    ]);

                    $debit_amount = collect([
                        '0' => $total_amount - $request->discount,
                        '1' => 0.00
                    ]);

                    $credit_amount = collect([
                        '0' => 0.00,
                        '1' => $total_amount - $request->discount,
                    ]);

                    $countHead = count($headCode);
                    $postData = [];
                    for ($i = 0; $i < $countHead; $i++) {
                        $coa = CoaSetup::where('company_id', Auth::user()->company_id ?? 1)->where('head_code', $headCode[$i])->first();
                        $postData[] = [
                            'company_id' => Auth::user()->company_id ?? 1,
                            'voucher_no' => $sales->invoice,
                            'voucher_type' => "Sales",
                            'voucher_date' => date('Y-m-d', strtotime($request->date)),
                            'coa_setup_id' => $coa->id,
                            'coa_head_code' => $headCode[$i],
                            'narration' => 'Product Sales Against Invoice No - ' . $sales->invoice,
                            'debit_amount' => $debit_amount[$i],
                            'credit_amount' => $credit_amount[$i],
                            'created_by' => Auth::user()->id,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now()
                        ];
                    }
                    AccountTransactionAuto::insert($postData);
                }

                // if ($request->sales_type == 'cash') {
                //     $first = date('Y-m-01');
                //     $last = new Carbon('last day of this month');
                //     $pay_data = Collection::withoutGlobalScope(CompanyScope::class)->withTrashed()->select(['payment_no'])->where('created_at', '>=', $first)->where('created_at', '<=', $last)->latest('id')->first();
                //     if ($pay_data) {
                //         $trim = str_replace("STC", '', $pay_data->payment_no);
                //         $dataPrefix = (int)$trim + 1;
                //         $payment_no = "STC" . $dataPrefix;
                //     } else {
                //         $payment_no = "STC" . date('y') . date('m') . '000001';
                //     }

                //     $collection = Collection::create([
                //         'company_id' => Auth::user()->company_id ?? 1,
                //         'client_id' => $request->client_id,
                //         'payment_no' => $payment_no,
                //         'amount' => $request->net_payable,
                //         'payment_date' => date('Y-m-d', strtotime($request->date)),
                //         'collection_type' => 'collection',
                //         'payment_type' => $request->sales_type,
                //         'remarks' => 'Paid on Sale',
                //         'sales_id' => $sales->id,
                //         'created_by' => Auth::user()->id,
                //     ]);

                //     CollectionData::create([
                //         'collection_id' => $collection->id,
                //         'sales_id' => $sales->id,
                //         'amount' => $request->net_payable,
                //     ]);

                //     if (@$admin_setting->accounting == 1 && $client->coa) {
                //         $cash_head = CoaSetup::findOrFail($request->coa_setup_id);
                //         $headCode = collect([
                //             '0' => $cash_head->head_code,
                //             '1' => $client->coa->head_code
                //         ]);

                //         $postData = [];
                //         for ($i = 0; $i < $countHead; $i++) {
                //             $coa = CoaSetup::where('company_id', Auth::user()->company_id ?? 1)->where('head_code', $headCode[$i])->first();
                //             $postData[] = [
                //                 'company_id' => Auth::user()->company_id ?? 1,
                //                 'voucher_no' => $payment_no,
                //                 'voucher_type' => "Collection",
                //                 'voucher_date' => date('Y-m-d', strtotime($request->date)),
                //                 'coa_setup_id' => $coa->id,
                //                 'coa_head_code' => $headCode[$i],
                //                 'narration' => 'Collection Against PAYMENT NO - ' . $payment_no,
                //                 'debit_amount' => $debit_amount[$i],
                //                 'credit_amount' => $credit_amount[$i],
                //                 'created_by' => Auth::user()->id,
                //                 'created_at' => Carbon::now(),
                //                 'updated_at' => Carbon::now()
                //             ];
                //         }
                //         AccountTransactionAuto::insert($postData);
                //     }
                // }

                $store = Store::find($request->store_id);
                $products_info = '';
                foreach ($request->product_id as $key => $product_id) {
                    $product = Product::find($product_id);
                    $products_info .= ' ' . $product->name . ' Quanity : ' . $request->qty[$key] . ' ' . $product->attribute->name;
                }

                AccessLog::create([
                    'date_time' => Carbon::now(),
                    'page' => 'Sales',
                    'action' => 'Update',
                    'description' => 'Update sales against invoice no ' . $sales->invoice . ' to client ' . $client->name . ' from store ' . $store->name . ' sales amount is ' . $sales->total_amount . ' sales discount ' . $sales->discount . ' products ' . $products_info . ' on ' . (($request->sales_type == 'cash') ? 'cash sale' : 'credit sale'),
                    'user_id' => Auth::user()->id,
                ]);
            });

            return redirect()->Route('admin.sales.index')->withSuccessMessage('Created Successfully!');
        } catch (Throwable $caught) {
            dd ($caught);
            if ($caught) {
                return redirect()->back()->withErrors('Something is wrong!');
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Recovery Deleted Data
        if (request()->has('recovery') && request('recovery') == 'true') {
           // $data = Sales::onlyTrashed()->findOrFail($id);
           $data = Sales::find($id);
            if (!$data) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Sales record not found or already deleted.'
                ], 404);
            }
            $collection = Collection::onlyTrashed()->where('sales_id', $id)->first();
            AccountTransactionAuto::onlyTrashed()->where('voucher_no', $data->invoice)->where('voucher_type', 'Sales')->restore();
            if ($collection) {
                AccountTransactionAuto::onlyTrashed()->where('voucher_no', $collection->payment_no)->where('voucher_type', 'Collection')->restore();
                $collection->restore();
            }
            $data->restore();
            return response()->json(['status' => 'success']);
        }

        // Delete Multiple Items Permanent
        if (request()->has('id') && request()->has('parmanent') && request('parmanent') == 'true') {
            foreach (request('id') as $id) {
                $data = Sales::onlyTrashed()->findOrFail($id);
                $collection = Collection::onlyTrashed()->where('sales_id', $id)->first();
                AccountTransactionAuto::onlyTrashed()->where('voucher_no', $data->invoice)->where('voucher_type', 'Sales')->forceDelete();
                if ($collection) {
                    AccountTransactionAuto::onlyTrashed()->where('voucher_no', $collection->payment_no)->where('voucher_type', 'Collection')->forceDelete();
                    $collection->forceDelete();
                }
                $data->forceDelete();
            }
            return response()->json(['status' => 'success']);
        }

        // Delete Single Item Permanent
        if (request()->has('parmanent') && request('parmanent') == 'true') {
           $data = Sales::find($id);
            if (!$data) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Sales record not found or already deleted.'
                ], 404);
            }
            $collection = Collection::onlyTrashed()->where('sales_id', $id)->first();
            AccountTransactionAuto::onlyTrashed()->where('voucher_no', $data->invoice)->where('voucher_type', 'Sales')->forceDelete();
            if ($collection) {
                AccountTransactionAuto::onlyTrashed()->where('voucher_no', $collection->payment_no)->where('voucher_type', 'Collection')->forceDelete();
                $collection->forceDelete();
            }
            $data->forceDelete();
            return response()->json(['status' => 'success']);
        }

        // Delete Multiple Items
        if (request()->has('id')) {
            foreach (request('id') as $id) {
                $data = Sales::findOrFail($id);
                AccountTransactionAuto::where('voucher_no', $data->invoice)->where('voucher_type', 'Sales')->update(['deleted_by' => Auth::user()->id]);
                AccountTransactionAuto::where('voucher_no', $data->invoice)->where('voucher_type', 'Sales')->delete();

                $collection = Collection::where('sales_id', $id)->first();
                AccessLog::create([
                    'date_time' => Carbon::now(),
                    'page' => 'Sales',
                    'action' => 'Delete',
                    'description' => 'Sales delete invoice no ' . $data->invoice . (!is_null($collection) ? ' Collection delete ' . $collection->payment_no  : ''),
                    'user_id' => Auth::user()->id,
                ]);
                if (!is_null($collection)) {
                    AccountTransactionAuto::where('voucher_no', $collection->payment_no)->where('voucher_type', 'Collection')->update(['deleted_by' => Auth::user()->id]);
                    AccountTransactionAuto::where('voucher_no', $collection->payment_no)->where('voucher_type', 'Collection')->delete();
                    $collection->update(['deleted_by' => Auth::user()->id]);
                    $collection->delete();
                }

                $data->update(['deleted_by' => Auth::user()->id]);
                $data->delete();
            }
            return response()->json(['status' => 'success']);
        }

        $data = Sales::findOrFail($id);
        AccountTransactionAuto::where('voucher_no', $data->invoice)->where('voucher_type', 'Sales')->update(['deleted_by' => Auth::user()->id]);
        AccountTransactionAuto::where('voucher_no', $data->invoice)->where('voucher_type', 'Sales')->delete();
        $collection = Collection::where('sales_id', $id)->first();

        AccessLog::create([
            'date_time' => Carbon::now(),
            'page' => 'Sales',
            'action' => 'Delete',
            'description' => 'Sales delete invoice no ' . $data->invoice . (!is_null($collection) ? ' Collection delete ' . $collection->payment_no  : ''),
            'user_id' => Auth::user()->id,
        ]);
        if (!is_null($collection)) {
            AccountTransactionAuto::where('voucher_no', $collection->payment_no)->where('voucher_type', 'Collection')->update(['deleted_by' => Auth::user()->id]);
            AccountTransactionAuto::where('voucher_no', $collection->payment_no)->where('voucher_type', 'Collection')->delete();

            $collection->update(['deleted_by' => Auth::user()->id]);
            $collection->delete();
        }

        $data->update(['deleted_by' => Auth::user()->id]);
        $data->delete();

        return response()->json(['status' => 'success']);
    }
}
