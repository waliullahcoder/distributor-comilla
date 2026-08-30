<?php

namespace App\Http\Controllers\Admin;

use ZipArchive;
use Carbon\Carbon;
use App\Models\Store;
use App\Models\Vendor;
use App\Models\Company;
use App\Models\Lifting;
use App\Models\Product;
use App\Models\CoaSetup;
use App\Models\AccessLog;
use App\Models\AdminSetting;
use Illuminate\Http\Request;
use App\Models\VendorPayment;
use App\Models\LiftingReceive;
use App\Models\LiftingReceiveProduct;
use App\Models\LiftingProduct;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\LiftingDocument;
use App\Models\LiftingReturnList;
use App\Models\VendorPaymentData;
use Illuminate\Support\Facades\DB;
use App\Models\AccountTransaction;
use App\Models\Scopes\CompanyScope;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\AccountTransactionAuto;
use Yajra\DataTables\Facades\DataTables;
use App\Services\ActionButtons\ActionButtons;

class LiftingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->ajax()) {
            $model = Lifting::with(['company', 'store', 'staff', 'vendor'])->latest('id')->where('product_type', 'Consumer');
            $type = request('type');
            if (!empty($type) && $type == 'trash') {
                $model->onlyTrashed();
            }
            return DataTables::eloquent($model)
                ->addColumn('checkbox', function ($row) {
                    $payemnt = VendorPaymentData::where('lifting_id', $row->id)->whereHas('payment')->first();
                    $transaction = AccountTransaction::withTrashed()->where('voucher_no', $row->lifting_no)->where('voucher_type', 'Product Purchase')->first();
                    $pay_transaction = AccountTransaction::withTrashed()->where('voucher_no', @$payemnt->payment->payment_no)->where('voucher_type', 'Vendor Payment')->first();
                    if (is_null($payemnt) && is_null($transaction) && is_null($pay_transaction)) {
                        $checkbox = '<div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input ' . (!empty(request('type')) && request('type') == "trash" ? 'trash_multi_checkbox' : 'multi_checkbox') . '" id="' . $row->id . '" name="multi_checkbox[]" value="' . $row->id . '"><label for="' . $row->id . '" class="custom-control-label"></label></div>';
                        return $checkbox;
                    }
                })
                ->addColumn('lifting_date', function ($row) {
                    return date('d-m-Y', strtotime($row->lifting_date));
                })
                ->addColumn('actions', function ($row) {
                    $type = request('type');
                    $data = [
                        'id' => $row->id,
                        'edit' => !empty($type) && $type == 'trash' ? false : true,
                    ];
                    $actionBtn = '<a class="btn btn-sm border-0 px-10px fs-15 btn-info tt" href="' . Route('admin.lifting.show', $row->id) . '" data-bs-toggle="tooltip" data-bs-placement="top" title="Print Voucher" target="_blank"><i class="fal fa-print"></i></a>';

                    $payemnt = VendorPaymentData::where('lifting_id', $row->id)->whereHas('payment')->first();
                    $transaction = AccountTransaction::withTrashed()->where('voucher_no', $row->lifting_no)->where('voucher_type', 'Product Purchase')->first();
                    if (is_null($payemnt) && is_null($transaction) || !is_null($payemnt) && $row->payment_type == 'cash' && is_null($transaction)) {
                        return ActionButtons::actions($data, $actionBtn);
                    }
                    return '<div class="btn-group">' . $actionBtn . '</div>';
                })
                ->rawColumns(['checkbox', 'actions'])
                ->make(true);
        }

        $title = "Product Lifting";
        return view('admin.lifting.index', compact('title'));
    }

    public function receivedList(Request $request)
    {
        $title = "received";
        $filter_link = Route('admin.received.list');

        $date_range = explode('to', $request->date_range);
        $start_date = isset($date_range[0]) ? date('Y-m-d', strtotime(trim($date_range[0]))) : null;
        $end_date   = isset($date_range[1]) ? date('Y-m-d', strtotime(trim($date_range[1]))) : null;

        $vendor_id = $request->vendor_id;

        $vendors = Vendor::where('status', 1)
            ->orderBy('name', 'asc')
            ->get();

        $query = DB::table('vendors as v')
            ->leftJoin('lifting_products as lp', 'v.id', '=', 'lp.vendor_id')
            ->select(
                'v.id',
                'v.name',
                DB::raw('MAX(lp.created_at) as sale_date'),
                DB::raw('SUM(lp.qty) as total_qty'),
                DB::raw('SUM(lp.delivery) as total_delivery')
            );

        if (!empty($request->vendor_id)) {
            $query->where('v.id', $request->vendor_id);
        }

        if (!empty($start_date) && !empty($end_date)) {
            $query->whereBetween(DB::raw('DATE(lp.created_at)'), [$start_date, $end_date]);
        }

        $data = $query
            ->groupBy('v.id', 'v.name')
            ->orderBy('v.name')
            ->get();

        // Grand Total
        $grand_total_qty = $data->sum('total_qty');
        $grand_total_delivery = $data->sum('total_delivery');

        return view(
            'admin.received.receivedList',
            compact(
                'title',
                'filter_link',
                'vendors',
                'data',
                'start_date',
                'end_date',
                'vendor_id',
                'grand_total_qty',
                'grand_total_delivery'
            )
        );
    }

    public function receivePendingList(Request $request)
    {
        $title = "receive Pending";
        $filter_link = Route('admin.receive.pending.list');

        $date_range = explode('to', $request->date_range);
        $start_date = isset($date_range[0]) ? date('Y-m-d', strtotime(trim($date_range[0]))) : null;
        $end_date   = isset($date_range[1]) ? date('Y-m-d', strtotime(trim($date_range[1]))) : null;

        $vendor_id = $request->vendor_id;

        $vendors = Vendor::where('status', 1)
            ->orderBy('name', 'asc')
            ->get();

        $query = DB::table('vendors as v')
            ->leftJoin('lifting_products as lp', 'v.id', '=', 'lp.vendor_id')
            ->select(
                'v.id',
                'v.name',
                DB::raw('MAX(lp.created_at) as sale_date'),
                DB::raw('SUM(lp.qty) as total_qty'),
                DB::raw('SUM(lp.delivery) as total_delivery')
            );

        if (!empty($request->vendor_id)) {
            $query->where('v.id', $request->vendor_id);
        }

        if (!empty($start_date) && !empty($end_date)) {
            $query->whereBetween(DB::raw('DATE(lp.created_at)'), [$start_date, $end_date]);
        }

        $data = $query
            ->groupBy('v.id', 'v.name')
            ->orderBy('v.name')
            ->get();

        // Grand Total
        $grand_total_qty = $data->sum('total_qty');
        $grand_total_delivery = $data->sum('total_delivery');

        return view(
            'admin.received.receivePendingList',
            compact(
                'title',
                'filter_link',
                'vendors',
                'data',
                'start_date',
                'end_date',
                'vendor_id',
                'grand_total_qty',
                'grand_total_delivery'
            )
        );
    }

    public function receivePendingDetails(string $vendorid)
    {
        $vendor = DB::table('vendors')
            ->where('id', $vendorid)
            ->first();

        if (!$vendor) {
            return redirect()->back()->with('error', 'Vendor not found.');
        }

        // Vendor wise Purchase
        $purchases = DB::table('liftings as l')
            ->leftJoin('vendors as v', 'v.id', '=', 'l.vendor_id')
            ->where('l.vendor_id', $vendorid)
            ->select(
                'l.id',
                'l.lifting_no',
                'l.lifting_date',
                'l.vendor_id',
                'v.name as vendor_name'
            )
            ->orderBy('l.lifting_date', 'desc')
            ->get();

        // Vendor wise Receives
        $receives = DB::table('lifting_products as lp')
            ->leftJoin('products as p', 'p.id', '=', 'lp.product_id')
            ->leftJoin('liftings as l', 'l.id', '=', 'lp.lifting_id')
            ->where('lp.vendor_id', $vendorid)
            ->whereColumn('lp.delivery', '<>', 'lp.qty')
            ->select(
                'lp.*',
                'l.lifting_no',
                'l.lifting_date as purchase_date',
                'p.name as product_name',
                'p.code as product_code',
                'p.id as product_id'
            )
            ->orderBy('l.lifting_date', 'desc')
            ->get()
            ->groupBy('lifting_id');

        return view('admin.received.pendingDetails', compact(
            'vendor',
            'purchases',
            'receives'
        ));
    }


    public function receiveEdit(string $vendorid)
    {
        $vendor = DB::table('vendors')
            ->where('id', $vendorid)
            ->first();

        if (!$vendor) {
            return redirect()->back()->with('error', 'Vendor not found.');
        }

        // Vendor wise Purchase
        $purchases = DB::table('liftings as l')
            ->leftJoin('vendors as v', 'v.id', '=', 'l.vendor_id')
            ->where('l.vendor_id', $vendorid)
            ->select(
                'l.id',
                'l.lifting_no',
                'l.lifting_date',
                'l.vendor_id',
                'v.name as vendor_name'
            )
            ->orderBy('l.lifting_date', 'desc')
            ->get();

        // Vendor wise Receives
        $receives = DB::table('lifting_products as lp')
            ->leftJoin('products as p', 'p.id', '=', 'lp.product_id')
            ->leftJoin('liftings as l', 'l.id', '=', 'lp.lifting_id')
            ->where('lp.vendor_id', $vendorid)
            ->whereColumn('lp.delivery', '<>', 'lp.qty')
            ->select(
                'lp.*',
                'l.lifting_no',
                'l.lifting_date as purchase_date',
                'p.name as product_name',
                'p.code as product_code',
                'p.id as product_id'
            )
            ->orderBy('l.lifting_date', 'desc')
            ->get()
            ->groupBy('lifting_id');

        return view('admin.received.receive_edit', compact(
            'vendor',
            'purchases',
            'receives'
        ));
    }

    public function receiveUpdate(Request $request, string $vendorid)
{
    DB::beginTransaction();

    try {

        $receiveDate = now()->format('Y-m-d');

        // প্রতিটি invoice-এর জন্য আলাদা SalesDelivery রাখবে
        $liftingReceives = [];

        foreach ($request->receives_id ?? [] as $key => $receivesId) {

            $receiveQty = (float) ($request->receive_qty[$key] ?? 0);

            if ($receiveQty <= 0) {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Sales List
            |--------------------------------------------------------------------------
            */
            $liftingProduct = LiftingProduct::where('id', $receivesId)
                ->where('vendor_id', $vendorid)
                ->first();

            if (!$liftingProduct) {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | liftings / Invoice
            |--------------------------------------------------------------------------
            */
            $liftings = Lifting::find($liftingProduct->lifting_id);

            if (!$liftings) {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Product
            |--------------------------------------------------------------------------
            */
            $productId = $request->product_id[$key]
                ?? $liftingProduct->product_id;

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
                ?? $liftingProduct->rate
                ?? 0
            );

            $qty = (float) (
                $request->qty[$key]
                ?? $liftingProduct->qty
                ?? 0
            );

            $receiveAmount = $receiveQty * $rate;
            /*
            |--------------------------------------------------------------------------
            | Trade Discount
            |--------------------------------------------------------------------------
            */
            $totalQty = LiftingProduct::where('lifting_id', $liftings->id)
                ->sum('qty');

            $tradeDiscount = 0;

            if ($totalQty > 0) {

                $perDiscount =
                    (float) $liftings->discount / $totalQty;

                $tradeDiscount =
                    $perDiscount * $receiveQty;
            }
            /*
            |--------------------------------------------------------------------------
            | LiftingReceive
            |--------------------------------------------------------------------------
            |
            | একই invoice হলে একই LiftingReceive
            | ভিন্ন invoice হলে নতুন LiftingReceive
            |
            */
            if (!isset($liftingReceives[$liftings->id])) {

                $liftingReceives[$liftings->id] = LiftingReceive::create([
                    'vendor_id'              => $vendorid,
                    'lifting_id'               => $liftings->id,
                    'receive_date'          => $receiveDate,
                    'total_amount'           => $liftings->total_cost,
                    'total_receive_amount' => 0,
                    'discount'               => 0,
                    'total_paid'             => $liftings->total_paid,
                    'status'                 => 1,
                    'created_by'             => auth()->id(),
                ]);
            }

            $lifting_receives = $liftingReceives[$liftings->id];

            /*
            |--------------------------------------------------------------------------
            | Duplicate Product Check
            |--------------------------------------------------------------------------
            |
            | একই invoice + একই product + একই variant
            | এই delivery transaction-এর মধ্যে duplicate হবে না
            |
            */
            $alreadyExists = LiftingReceiveProduct::where(
                'lifting_receives_id',
                $lifting_receives->id
            )
            ->where('product_id', $productId)
            ->exists();

            if ($alreadyExists) {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Sales Delivery List
            |--------------------------------------------------------------------------
            */
            LiftingReceiveProduct::create([
                'lifting_receives_id' => $lifting_receives->id,
                'vendor_id'         => $vendorid,
                'product_id'        => $productId,
                'receive_date'     => $receiveDate,
                'do_ratio'          => $product->do_ratio ?? 0,
                'offer_qty'         => 0,
                'trade_discount'    => $tradeDiscount,
                'rate'              => $rate,
                'qty'               => $qty,
                'receive'          => $receiveQty,
                'receive_amount'   => $receiveAmount,
            ]);

            /*
            |--------------------------------------------------------------------------
            | sales_lists.delivery Update
            |--------------------------------------------------------------------------
            */
            $liftingProduct->increment('delivery', $receiveQty);
        }

        /*
        |--------------------------------------------------------------------------
        | Update Each Invoice
        |--------------------------------------------------------------------------
        */
        foreach ($liftingReceives as $liftingId => $liftingReceive) {

            /*
            |--------------------------------------------------------------------------
            | Sales Delivery Total
            |--------------------------------------------------------------------------
            */
            $totalReceiveAmount = LiftingReceiveProduct::where(
                'lifting_receives_id',
                $liftingReceive->id
            )->sum('receive_amount');

            $totalDiscount = LiftingReceiveProduct::where(
                'lifting_receives_id',
                $liftingReceive->id
            )->sum('trade_discount');

            $liftingReceive->update([
                'total_receive_amount' => $totalReceiveAmount,
                'discount'              => $totalDiscount,
            ]);

            /*
            |--------------------------------------------------------------------------
            | Sales.delivery_amount
            |--------------------------------------------------------------------------
            */
            $liftings = Lifting::find($liftingId);

            if ($liftings) {

                $liftingReceiveAmount = LiftingReceive::where(
                    'lifting_id',
                    $liftingId
                )->sum('total_receive_amount');

                // $liftings->update([
                //     'receive_amount' => $liftingReceiveAmount,
                // ]);
            }
        }

        DB::commit();

        return redirect()
            ->route('admin.received.list')
            ->withSuccessMessage('Receive saved successfully.');

    } catch (\Exception $e) {
         dd($e);
        DB::rollBack();

        return redirect()
            ->back()
            ->withInput()
            ->with('error', $e->getMessage());
    }
}


    public function receivePrint(string $vendorid)
{
    /*
    |--------------------------------------------------------------------------
    | Current Delivery Date
    |--------------------------------------------------------------------------
    */

    $receiveDate = Carbon::today()->toDateString();
    $date = Carbon::today()->toDateString();


    /*
    |--------------------------------------------------------------------------
    | Current Date + Client Wise All Deliveries
    |--------------------------------------------------------------------------
    |
    | একই দিনে multiple delivery / invoice থাকতে পারে
    |
    */

    $lifitngreceives = LiftingReceive::with([
            'vendor',
            'lifting'
        ])
        ->where('vendor_id', $vendorid)
        ->whereDate('receive_date', $receiveDate)
        ->orderBy('id', 'asc')
        ->get();


    /*
    |--------------------------------------------------------------------------
    | যদি আজকের কোন Delivery না থাকে
    |--------------------------------------------------------------------------
    */

    if ($lifitngreceives->isEmpty()) {
         return redirect()
            ->route('admin.received.list')
            ->withErrors('No Receive found for this Vendor today.');
    }

    /*
    |--------------------------------------------------------------------------
    | Main Delivery
    |--------------------------------------------------------------------------
    |
    | Blade compatibility-এর জন্য প্রথম delivery
    |
    */

    $receive = $lifitngreceives->first();
   

    /*
    |--------------------------------------------------------------------------
    | Vendor / Company Info
    |--------------------------------------------------------------------------
    */

    $vendor = $receive->vendor;
    if ($vendor) {

        $hotline = $vendor->phone;
        $logo = $vendor->logo;
        $title = $vendor->name;

        $informations =
            $vendor->address . '<br>' .
            $vendor->phone . ', ' .
            $vendor->email . ', ' .
            $vendor->contact_person;

    } else {

        $logo = null;
        $hotline = '01xxxxx-xxxxx';
        $title = 'Company Name Goes Here.';

        $informations = '
            Company address will goes here <br>
            Mobile: 0967XXXXXX,
            Email: youremail@gmail.com,
            www.website.com
        ';
    }


    /*
    |--------------------------------------------------------------------------
    | আজকের Client-এর সব Delivery ID
    |--------------------------------------------------------------------------
    */

    $lifting_receives_id = $lifitngreceives->pluck('id');


    /*
    |--------------------------------------------------------------------------
    | Current Date + Current Client Delivery Product List
    |--------------------------------------------------------------------------
    |
    | আজকের সব delivery record
    | Multiple invoice থাকতে পারবে
    |
    */

    $lists = LiftingReceiveProduct::with([
            'product',
            'liftingreceives'
        ])
        ->whereIn('lifting_receives_id', $lifting_receives_id)
        ->where('vendor_id', $vendorid)
        ->whereDate('receive_date', $receiveDate)
        ->orderBy('id', 'asc')
        ->get();


    /*
    |--------------------------------------------------------------------------
    | যদি Delivery List না থাকে
    |--------------------------------------------------------------------------
    */

    if ($lists->isEmpty()) {
        abort(404, 'No Receive items found.');
    }


    /*
    |--------------------------------------------------------------------------
    | Current Date Total Delivery Amount
    |--------------------------------------------------------------------------
    */

    $total_receive_amount = $lists->sum(function ($item) {

        return $item->receive * $item->rate;

    });


    /*
    |--------------------------------------------------------------------------
    | Current Date Total Discount
    |--------------------------------------------------------------------------
    */

    $total_discount_amount = $lists->sum('trade_discount');


    /*
    |--------------------------------------------------------------------------
    | Current Date Total Quantity
    |--------------------------------------------------------------------------
    */

    $total_qty = $lists->sum('qty');


    /*
    |--------------------------------------------------------------------------
    | Current Date Total Delivered Quantity
    |--------------------------------------------------------------------------
    */

    $total_receive_qty = $lists->sum('receive');


    /*
    |--------------------------------------------------------------------------
    | Current Date Pending Quantity
    |--------------------------------------------------------------------------
    */

    $total_pending_qty = $lists->sum(function ($item) {

        return $item->qty - $item->receive;

    });


    /*
    |--------------------------------------------------------------------------
    | Opening Balance
    |--------------------------------------------------------------------------
    */

   /*
    |--------------------------------------------------------------------------
    | Opening & Closing Balance
    |--------------------------------------------------------------------------
    */

    $openingBalance = 0;
    $closingBalance = 0;


    /*
    |--------------------------------------------------------------------------
    | Previous Sales
    |--------------------------------------------------------------------------
    | আজকের আগের সব delivery
    */

    $totalPurchaseBefore = LiftingReceive::where('vendor_id', $vendorid)
        ->whereDate('receive_date', '<', $receiveDate)
        ->sum(DB::raw('total_receive_amount - discount'));


    /*
    |--------------------------------------------------------------------------
    | Previous Collection
    |--------------------------------------------------------------------------
    | আজকের আগের সব payment
    */

    $totalCollectionBefore = VendorPayment::where('vendor_id', $vendorid)
        ->whereDate('payment_date', '<', $receiveDate)
        ->sum('amount');


    /*
    |--------------------------------------------------------------------------
    | Opening Balance
    |--------------------------------------------------------------------------
    */

    $openingBalance = $totalCollectionBefore - $totalPurchaseBefore;
    

    /*
    |--------------------------------------------------------------------------
    | Today's Sales
    |--------------------------------------------------------------------------
    | আজকের সব delivery
    */

    $todayPurchases = LiftingReceive::where('vendor_id', $vendorid)
        ->whereDate('receive_date', $receiveDate)
        ->sum(DB::raw('total_receive_amount - discount'));


    /*
    |--------------------------------------------------------------------------
    | Today's Collection
    |--------------------------------------------------------------------------
    */

    $todayCollection = VendorPayment::where('vendor_id', $vendorid)
        ->whereDate('payment_date', $date)
        ->sum('amount');


    /*
    |--------------------------------------------------------------------------
    | Closing Balance
    |--------------------------------------------------------------------------
    */

    $closingBalance = $openingBalance
        + $todayCollection
        - $todayPurchases;
    
    /*
    |--------------------------------------------------------------------------
    | Main Data Object
    |--------------------------------------------------------------------------
    */

    $data = $receive;

    /*
    |--------------------------------------------------------------------------
    | আজকের সব invoice/product list
    |--------------------------------------------------------------------------
    */

    $data->list = $lists;

    $data->total_receive_amount = $total_receive_amount;

    $data->total_qty = $total_qty;

    $data->total_receive_qty = $total_receive_qty;

    $data->total_pending_qty = $total_pending_qty;

    $data->invoice = 'DELIVERY HISTORY';

    $data->date = $receiveDate;

    $data->receive_date = $receiveDate;


    /*
    |--------------------------------------------------------------------------
    | Blade Compatibility
    |--------------------------------------------------------------------------
    */

    $vendor_total_receive_amount = $total_receive_amount- $total_discount_amount;

    $report_title = 'Receive';


    return view(
        'admin.received.receivePrint',
        compact(
            'title',
            'total_discount_amount',
            'logo',
            'informations',
            'hotline',
            'report_title',
            'data',
            'openingBalance',
            'closingBalance',
            'vendor_total_receive_amount'
        )
    );
}

    public function invoice()
    {
        $first = date('Y-m-01');
        $last = new Carbon('last day of this month');
        $data = Lifting::withoutGlobalScope(CompanyScope::class)->withTrashed()->select(['lifting_no'])->whereDate('created_at', '>=', $first)->whereDate('created_at', '<=', $last)->latest('id')->first();
        if ($data) {
            $trim = str_replace("STL", '', $data->lifting_no);
            $dataPrefix = (int)$trim + 1;
            $invoice = "STL" . $dataPrefix;
        } else {
            $invoice = "STL" . date('y') . date('m') . '000001';
        }
        return $invoice;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (request()->ajax() && request('get_product')) {
            $product = Product::with(['price', 'category'])->where('id', request('product_id'))->first();
            return response()->json(['status' => 'success', 'product' => $product]);
        }

        if (request()->ajax()) {
            $products = Product::whereHas('vendors', function ($q) {
                $q->where('vendor_id', request('vendor_id'));
            })->where('status', 1)->orderBy('name', 'asc')->get();
            return response()->json(['status' => 'success', 'products' => $products]);
        }

        $title = 'Add Product Lifting';
        $lifting_no = $this->invoice();
        $vendors = Vendor::where('status', 1)->orderBy('name', 'asc')->get();
        $stores = Store::where('status', 1)->get();
        $cash_heads = CoaSetup::with('parent')->whereHas('parent', function ($query) {
            $query->where('head_name', 'Cash In Hand')->orWhere('head_name', 'Cash In Bank');
        })->get();
        return view('admin.lifting.create', compact('title', 'lifting_no', 'vendors', 'stores', 'cash_heads'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'lifting_no' => 'required',
            'lifting_date' => 'required',
            'vendor_id' => 'required',
            'product_id' => 'required',
            'lifting_price' => 'required',
            'quantity' => 'required',
        ]);

        $admin_setting = AdminSetting::first();
        if (@$admin_setting->accounting == 1 && $request->payment_type == 'cash') {
            $request->validate([
                'coa_setup_id' => 'required',
            ]);
        }

        $vendor = Vendor::find($request->vendor_id);
        if (@$admin_setting->accounting == 1 && is_null($vendor->coa)) {
            return redirect()->back()->withErrors('Please Setup a vendors account!');
        }

        DB::transaction(function () use ($request, $admin_setting) {
            $lifting_no = $this->invoice();
            $lifting = Lifting::create([
                'company_id' => Auth::user()->company_id ?? 1,
                'store_id' => $request->store_id,
                'vendor_id' => $request->vendor_id,
                'coa_setup_id' => $request->coa_setup_id,
                'lifting_no' => $lifting_no,
                'voucher_no' => $request->voucher_no,
                'payment_type' => $request->payment_type,
                'lifting_date' => date('Y-m-d', strtotime($request->lifting_date)),
                'total_cost' => $request->total_cost ?? 0,
                'discount' => $request->discount ?? 0,
                'net_amount' => ($request->total_cost ?? 0) - ($request->discount ?? 0),
                'total_paid' => $request->net_payable,
                'created_by' => Auth::user()->id,
            ]);

            $log_data = '';
            foreach ($request->product_id as $key => $product_id) {
                $product = Product::where('id', $product_id)->first();
                    $discount = $request->trade_discount[$key]??0;
                    $total_paid = $request->amount[$key] - $discount;
               
                LiftingProduct::create([
                    'lifting_id' => $lifting->id,
                    'company_id' => Auth::user()->company_id ?? 1,
                    'store_id' => $request->store_id,
                    'vendor_id' => $request->vendor_id,
                    'product_id' => $product_id,
                    'total_amount' => $request->amount[$key],
                    'total_paid' => $total_paid,
                    'lifting_price' => $request->lifting_price[$key],
                    'offer_qty' => $request->offer_qty[$key],
                    'discount' => $request->trade_discount[$key],
                    'do_ratio'  =>  $request->do_ratio[$key],
                    'net_amount' => $request->amount[$key] - $discount,
                    'expiry_date' => !is_null(@$request->expiry_date[$key]) ? date('Y-m-d', strtotime(@$request->expiry_date[$key])) : null,
                    'qty' => $request->quantity[$key],
                    'created_by' => Auth::user()->id,
                ]);
                $log_data .= ' ' . $product->name . ' ' . $request->quantity[$key] . ' ' . $product->attribute->name . ' ';
            }

            $vendor = Vendor::find($request->vendor_id);
            if ($vendor->coa && @$admin_setting->accounting == 1) {
                $expense_head = CoaSetup::where('head_type', 'E')->where('head_name', 'Product Purchase')->first();
                $headCode = collect([
                    '0' => $expense_head->head_code,
                    '1' => $vendor->coa->head_code
                ]);

                $debit_amount = collect([
                    '0' => $request->net_payable,
                    '1' => 0.00
                ]);

                $credit_amount = collect([
                    '0' => 0.00,
                    '1' => $request->net_payable,
                ]);

                $countHead = count($headCode);
                $postData = [];
                for ($i = 0; $i < $countHead; $i++) {
                    $coa = CoaSetup::where('company_id', Auth::user()->company_id ?? 1)->where('head_code', $headCode[$i])->first();
                    $postData[] = [
                        'company_id' => Auth::user()->company_id ?? 1,
                        'voucher_no' => $lifting->lifting_no,
                        'voucher_type' => "Product Purchase",
                        'voucher_date' => date('Y-m-d', strtotime($request->lifting_date)),
                        'coa_setup_id' => $coa->id,
                        'coa_head_code' => $headCode[$i],
                        'narration' => 'Product Purchase Against Purchase No - ' . $lifting->lifting_no,
                        'debit_amount' => $debit_amount[$i],
                        'credit_amount' => $credit_amount[$i],
                        'created_by' => Auth::user()->id,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()
                    ];
                }
                AccountTransactionAuto::insert($postData);
            }

            if ($request->payment_type == 'cash') {
                $first = date('Y-m-01');
                $last = new Carbon('last day of this month');
                $pay_data = VendorPayment::withoutGlobalScope(CompanyScope::class)->withTrashed()->select(['payment_no'])->whereDate('created_at', '>=', $first)->whereDate('created_at', '<=', $last)->orderBy('id', 'desc')->first();
                if ($pay_data) {
                    $trim = str_replace("STP", '', $pay_data->payment_no);
                    $dataPrefix = (int)$trim + 1;
                    $payment_no = "STP" . $dataPrefix;
                } else {
                    $payment_no = "STP" . date('y') . date('m') . '000001';
                }

                $vendor_payment = VendorPayment::create([
                    'company_id' => Auth::user()->company_id ?? 1,
                    'vendor_id' => $request->vendor_id,
                    'lifting_id' => $lifting->id,
                    'payment_no' => $payment_no,
                    'payment_date' => date('Y-m-d', strtotime($request->lifting_date)),
                    'payment_type' => $request->payment_type,
                    'type' => 'payment',
                    'amount' => $request->total_cost - $request->discount,
                    'remarks' => 'Cash Purchase',
                    'created_by' => Auth::user()->id,
                ]);

                VendorPaymentData::create([
                    'vendor_payment_id' => $vendor_payment->id,
                    'lifting_id' => $lifting->id,
                    'paid' => $request->total_cost - $request->discount,
                ]);

                if ($vendor->coa && @$admin_setting->accounting == 1) {
                    $cash_head = CoaSetup::findOrFail($request->coa_setup_id);
                    $headCode = collect([
                        '0' => $vendor->coa->head_code,
                        '1' => $cash_head->head_code,
                    ]);

                    $postData = [];
                    for ($i = 0; $i < $countHead; $i++) {
                        $coa = CoaSetup::where('company_id', Auth::user()->company_id ?? 1)->where('head_code', $headCode[$i])->first();
                        $postData[] = [
                            'company_id' => Auth::user()->company_id ?? 1,
                            'voucher_no' => $payment_no,
                            'voucher_type' => "Vendor Payment",
                            'voucher_date' => date('Y-m-d', strtotime($request->lifting_date)),
                            'coa_setup_id' => $coa->id,
                            'coa_head_code' => $headCode[$i],
                            'narration' => 'Payment Vendor Against PAYMENT NO - ' . $payment_no,
                            'debit_amount' => $debit_amount[$i],
                            'credit_amount' => $credit_amount[$i],
                            'created_by' => Auth::user()->id,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now()
                        ];
                    }
                    AccountTransactionAuto::insert($postData);
                }
            }

            $store = Store::findOrFail($request->store_id);
            AccessLog::create([
                'date_time' => Carbon::now(),
                'page' => 'Purchase',
                'action' => 'Add',
                'description' => 'Create a new purhcase with purchase no ' . $lifting->lifting_no . ' to ' . $store->name . ' products ' . $log_data . ' on ' . $request->payment_type,
                'user_id' => Auth::user()->id,
            ]);
        });

        return redirect()->route('admin.lifting.index')->withSuccessMessage('Created Successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
       $data = Lifting::findOrFail($id);
      
        if ( $data) {
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
        $report_title = 'Lifting Voucher';
         return view('admin.lifting.print', compact('title', 'informations', 'report_title', 'data','logo'));
        //$pdf = Pdf::loadView('admin.lifting.print', compact('title', 'informations', 'report_title', 'data'));
        // $pdf->setPaper('A4', 'landscape');
        return $pdf->stream('product_lifting_chalan_' . date('d_m_Y_H_i_s') . '.pdf');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        if (request()->ajax() && request('get_product')) {
            $product = Product::with(['price', 'category'])->where('id', request('product_id'))->first();
            return response()->json(['status' => 'success', 'product' => $product]);
        }

        if (request()->ajax()) {
            $products = Product::whereHas('vendors', function ($q) {
                $q->where('vendor_id', request('vendor_id'));
            })->where('status', 1)->orderBy('name', 'asc')->get();
            return response()->json(['status' => 'success', 'products' => $products]);
        }

        $title = 'Update Lifting Products';
        $vendors = Vendor::where('status', 1)->orderBy('name', 'asc')->get();
        $stores = Store::where('status', 1)->get();
        $data = Lifting::findOrFail($id);
        $lifting_products = LiftingProduct::with(['product'])->where('lifting_id', $id)->get();
        $link = Route('admin.lifting.update', $id);
        $products = Product::whereHas('vendors', function ($q) use ($data) {
            $q->where('vendor_id', $data->vendor_id);
        })->orderBy('name', 'asc')->get();
        $cash_heads = CoaSetup::with('parent')->whereHas('parent', function ($query) {
            $query->where('head_name', 'Cash In Hand')->orWhere('head_name', 'Cash In Bank');
        })->get();
        return view('admin.lifting.edit', compact('title', 'vendors', 'stores', 'data', 'lifting_products', 'link', 'products', 'cash_heads'));
    }

    public function showDocument(string $id)
    {
        $lifting_documents =  LiftingDocument::where('lifting_id', $id)->get();
        $zip = new ZipArchive();
        $fileName = 'downloads.zip';
        // Add File in ZipArchive
        if ($zip->open(public_path($fileName), ZipArchive::CREATE) === TRUE) {
            foreach ($lifting_documents as $file) {
                $path =  public_path($file->document);
                $relativeName = basename($path);
                $zip->addFile($path, $relativeName);
            }
        }
        // Close ZipArchive
        $zip->close();
        return response()->download($fileName);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'lifting_no' => 'required',
            'lifting_date' => 'required',
            'vendor_id' => 'required',
            'product_id' => 'required',
            'lifting_price' => 'required',
            'quantity' => 'required',
        ]);

        $admin_setting = AdminSetting::first();
        if (@$admin_setting->accounting == 1 && $request->payment_type == 'cash') {
            $request->validate([
                'coa_setup_id' => 'required',
            ]);
        }

        $vendor = Vendor::find($request->vendor_id);
        if (@$admin_setting->accounting == 1 && is_null($vendor->coa)) {
            return redirect()->back()->withErrors('Please Setup a vendors account!');
        }

        DB::transaction(function () use ($request, $id, $admin_setting) {

            $lifting = Lifting::findOrFail($id);
            $payment = VendorPayment::withTrashed()->where('lifting_id', $id)->first();
            AccountTransactionAuto::withTrashed()->where('voucher_no', $lifting->lifting_no)->where('voucher_type', 'Product Purchase')->forceDelete();
            if ($payment) {
                AccountTransactionAuto::withTrashed()->where('voucher_no', $payment->payment_no)->where('voucher_type', 'Vendor Payment')->forceDelete();
                $payment->forceDelete();
            }
            foreach ($lifting->products as $item) {
                LiftingReturnList::where('lifting_product_id', $item->id)->forceDelete();
            }
            LiftingProduct::where('lifting_id', $id)->delete();

            $lifting->update([
                'store_id' => $request->store_id,
                'vendor_id' => $request->vendor_id,
                'coa_setup_id' => $request->coa_setup_id,
                'voucher_no' => $request->voucher_no,
                'payment_type' => $request->payment_type,
                'lifting_date' => date('Y-m-d', strtotime($request->lifting_date)),
                'total_cost' => $request->total_cost ?? 0,
                'discount' => $request->discount ?? 0,
                'net_amount' => ($request->total_cost ?? 0) - ($request->discount ?? 0),
                'total_paid' => $request->net_payable,
                'updated_by' => Auth::user()->id,
            ]);

            $log_data = '';
            foreach ($request->product_id as $key => $product_id) {
                $product = Product::where('id', $product_id)->first();
                $discount = $request->trade_discount[$key];
             
                $total_paid = $request->amount[$key] - $discount;
               
                LiftingProduct::create([
                    'lifting_id' => $lifting->id,
                    'company_id' => Auth::user()->company_id ?? 1,
                    'store_id' => $request->store_id,
                    'vendor_id' => $request->vendor_id,
                    'product_id' => $product_id,
                    'total_amount' => $request->amount[$key],
                    'total_paid' => $total_paid,
                    'lifting_price' => $request->lifting_price[$key],
                    'net_amount' => $request->amount[$key] - $discount,
                    'expiry_date' => !is_null(@$request->expiry_date[$key]) ? date('Y-m-d', strtotime(@$request->expiry_date[$key])) : null,
                    'qty' => $request->quantity[$key],
                    'offer_qty' => $request->offer_qty[$key],
                    'discount' => $request->trade_discount[$key],
                    'do_ratio'  =>  $request->do_ratio[$key],
                    'delivery_amount' => 0,
                    'created_by' => Auth::user()->id,
                ]);
                $log_data .= ' ' . $product->name . ' ' . $request->quantity[$key] . ' ' . $product->attribute->name . ' ';
            }

            $vendor = Vendor::find($request->vendor_id);
            if ($vendor->coa && @$admin_setting->accounting == 1) {
                $expense_head = CoaSetup::where('head_type', 'E')->where('head_name', 'Product Purchase')->first();
                $headCode = collect([
                    '0' => $expense_head->head_code,
                    '1' => $vendor->coa->head_code
                ]);

                $debit_amount = collect([
                    '0' => $request->net_payable,
                    '1' => 0.00
                ]);

                $credit_amount = collect([
                    '0' => 0.00,
                    '1' => $request->net_payable,
                ]);

                $countHead = count($headCode);
                $postData = [];
                for ($i = 0; $i < $countHead; $i++) {
                    $coa = CoaSetup::where('company_id', Auth::user()->company_id ?? 1)->where('head_code', $headCode[$i])->first();
                    $postData[] = [
                        'company_id' => Auth::user()->company_id ?? 1,
                        'voucher_no' => $lifting->lifting_no,
                        'voucher_type' => "Product Purchase",
                        'voucher_date' => date('Y-m-d', strtotime($request->lifting_date)),
                        'coa_setup_id' => $coa->id,
                        'coa_head_code' => $headCode[$i],
                        'narration' => 'Product Purchase Against Purchase No - ' . $lifting->lifting_no,
                        'debit_amount' => $debit_amount[$i],
                        'credit_amount' => $credit_amount[$i],
                        'created_by' => Auth::user()->id,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()
                    ];
                }
                AccountTransactionAuto::insert($postData);
            }

            if ($request->payment_type == 'cash') {
                $first = date('Y-m-01');
                $last = new Carbon('last day of this month');
                $pay_data = VendorPayment::withoutGlobalScope(CompanyScope::class)->withTrashed()->select(['payment_no'])->whereDate('created_at', '>=', $first)->whereDate('created_at', '<=', $last)->orderBy('id', 'desc')->first();
                if ($pay_data) {
                    $trim = str_replace("STP", '', $pay_data->payment_no);
                    $dataPrefix = (int)$trim + 1;
                    $payment_no = "STP" . $dataPrefix;
                } else {
                    $payment_no = "STP" . date('y') . date('m') . '000001';
                }

                $vendor_payment = VendorPayment::create([
                    'company_id' => Auth::user()->company_id ?? 1,
                    'vendor_id' => $request->vendor_id,
                    'lifting_id' => $lifting->id,
                    'payment_no' => $payment_no,
                    'payment_date' => date('Y-m-d', strtotime($request->lifting_date)),
                    'payment_type' => $request->payment_type,
                    'type' => 'payment',
                    'amount' => $request->total_cost - $request->discount,
                    'remarks' => 'Cash Purchase',
                    'created_by' => Auth::user()->id,
                ]);

                VendorPaymentData::create([
                    'vendor_payment_id' => $vendor_payment->id,
                    'lifting_id' => $lifting->id,
                    'paid' => $request->total_cost - $request->discount,
                ]);

                if ($vendor->coa && @$admin_setting->accounting == 1) {
                    $cash_head = CoaSetup::findOrFail($request->coa_setup_id);
                    $headCode = collect([
                        '0' => $vendor->coa->head_code,
                        '1' => $cash_head->head_code,
                    ]);

                    $postData = [];
                    for ($i = 0; $i < $countHead; $i++) {
                        $coa = CoaSetup::where('company_id', Auth::user()->company_id ?? 1)->where('head_code', $headCode[$i])->first();
                        $postData[] = [
                            'company_id' => Auth::user()->company_id ?? 1,
                            'voucher_no' => $payment_no,
                            'voucher_type' => "Vendor Payment",
                            'voucher_date' => date('Y-m-d', strtotime($request->lifting_date)),
                            'coa_setup_id' => $coa->id,
                            'coa_head_code' => $headCode[$i],
                            'narration' => 'Payment Vendor Against PAYMENT NO - ' . $payment_no,
                            'debit_amount' => $debit_amount[$i],
                            'credit_amount' => $credit_amount[$i],
                            'created_by' => Auth::user()->id,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now()
                        ];
                    }
                    AccountTransactionAuto::insert($postData);
                }
            }

            $store = Store::findOrFail($request->store_id);
            AccessLog::create([
                'date_time' => Carbon::now(),
                'page' => 'Purchase',
                'action' => 'Update',
                'description' => 'Update purhcase against purchase no ' . $lifting->lifting_no . ' to ' . $store->name . ' products : ' . $log_data . ' on ' . $request->payment_type,
                'user_id' => Auth::user()->id,
            ]);
        });
        return redirect()->route('admin.lifting.index')->withSuccessMessage('Updated Successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {

            DB::transaction(function () use ($id) {

                $lifting = Lifting::find($id);

                if (!$lifting) {
                    throw new \Exception('Lifting not found.');
                }

                // Get all lifting receives
                $liftingReceives = LiftingReceive::where('lifting_id', $id)->get();

                // Delete Lifting Products
                LiftingProduct::where('lifting_id', $id)->delete();

                // Delete Receive Products & Receives
                foreach ($liftingReceives as $liftingReceive) {

                    LiftingReceiveProduct::where(
                        'lifting_receives_id',
                        $liftingReceive->id
                    )->delete();

                    $liftingReceive->delete();
                }

                // Finally delete Lifting
                $lifting->delete();
            });

            return response()->json([
                'status' => 'success',
                'message' => 'Lifting deleted successfully.'
            ]);

        } catch (Throwable $e) {

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete lifting.',
                'error' => $e->getMessage()
            ], 500);
        }
    }



    
}
