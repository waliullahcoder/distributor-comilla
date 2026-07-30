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
use App\Models\CoaSetup;
use App\Models\Collection;
use App\Models\CollectionData;
use App\Models\Company;
use App\Models\DeliveryList;
use App\Models\Product;
use App\Models\Sales;
use App\Models\SalesList;
use App\Models\SalesReturnList;
use App\Models\Scopes\CompanyScope;
use App\Models\Staff;
use App\Models\Store;
use App\Models\Vendor;
use App\Services\ActionButtons\ActionButtons;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Throwable;
use Yajra\DataTables\Facades\DataTables;

class RunningSalesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (request()->ajax()) {
            $model = Sales::with(['company', 'store', 'client', 'staff'])->where('product_type', 'Consumer')->where('sales_type', 'running')->latest('id');
            if (!is_null(request('invoice'))) {
                $model->where('invoice', request('invoice'));
            } else {
                $date = !is_null(request('sales_date')) ? date('Y-m-d', strtotime(request('sales_date'))) : date('Y-m-d');
                $model->where('date', $date);
            }
            $type = request('type');
            if (!empty($type) && $type == 'trash') {
                $model->onlyTrashed();
            }
            return DataTables::eloquent($model)
                ->addColumn('checkbox', function ($row) {
                    $collection_data = CollectionData::with('collection')->whereHas('collection')->where('sales_id', $row->id)->first();
                    $transaction = AccountTransaction::withTrashed()->where('voucher_no', $row->invoice)->where('voucher_type', 'Sales')->first();
                    $coll_transaction = AccountTransaction::withTrashed()->where('voucher_no', @$collection_data->collection->payment_no)->where('voucher_type', 'Collection')->first();
                    $sales_return = SalesReturnList::with('sales_list')->whereHas('sales_list', function ($query) use ($row) {
                        $query->where('sales_id', $row->id);
                    })->first();
                    $delivery = DeliveryList::where('sales_id', $row->id)->first();
                    if (is_null($collection_data) && is_null($transaction) && is_null($coll_transaction) && is_null($sales_return) && is_null($delivery)) {
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
                    $data = [
                        'id' => $row->id,
                        'edit' => !empty($type) && $type == 'trash' ? false : true,
                    ];
                    $addiotional_buttons = '';
                    $addiotional_buttons .= '<a class="btn btn-sm border-0 px-10px fs-15 text-white tt btn-print-1" href="' . Route('admin.running-sales.show', $row->id) . '" target="_blank"  data-bs-toggle="tooltip" data-bs-placement="top" title="Chalan"><i class="fal fa-print"></i></a>';
                    $addiotional_buttons .= '<a class="btn btn-sm border-0 px-10px fs-15 text-white tt btn-print-2" href="' . Route('admin.sales.invoice', $row->id) . '" target="_blank"  data-bs-toggle="tooltip" data-bs-placement="top" title="Invoice"><i class="fal fa-file-pdf"></i></a>';
                    $sales_return = SalesReturnList::with('sales_list')->whereHas('sales_list', function ($query) use ($row) {
                        $query->where('sales_id', $row->id);
                    })->first();
                    if (is_null($sales_return)) {
                        return ActionButtons::actions($data, $addiotional_buttons);
                    }
                    return '<div class="btn-group">' . $addiotional_buttons . '</div>';
                })
                ->rawColumns(['checkbox', 'actions'])
                ->make(true);
        }

        $title = "Runnings Sales";
        $params = '';
        $currentRouteName = $request->route()->getName();
        $menu = AdminMenu::where('route', $currentRouteName)->first();
        $edit = str_replace('index', 'edit', $menu->route);
        $menuAction = AdminMenuAction::where('route', $edit)->first();
        $currentRoutePermission = Permission::findById($menuAction->permission_id);
        if (!is_null($currentRoutePermission)) {
            if (auth()->user()->can($currentRoutePermission->name)) {
                $params .= '<form class="d-inline-flex gap-2" method="get" target="_blank" action="' . Route('admin.running-sales.search-edit') . '"><input type="text" class="form-control px-2 py-1" id="invoice" name="invoice" style="width: 150px; min-height: auto;" placeholder="Invoice No."><button type="submit" class="btn btn-sm btn-warning tt"><i class="far fa-pencil-alt"></i></button></form>';
            }
        }
        $params .= '<input type="text" class="form-control date_picker px-2 py-1" id="sales_date" name="sales_date" style="width: 150px; min-height: auto;" value="' . date('d-m-Y') . '" placeholder="Sales Date">';
        return view('admin.running_sales.index', compact('title', 'params'));
    }

    public static function stock($product_id, $store_id)
    {
        $liftings = DB::table('view_liftings')->where('product_type', 'Consumer')->where('product_id', $product_id)->where('store_id', $store_id)->sum('qty');
        $lifting_returns = DB::table('view_lifting_returns')->where('product_type', 'Consumer')->where('product_id', $product_id)->where('store_id', $store_id)->sum('qty');
        $client_sales = DB::table('view_sales')->where('product_type', 'Consumer')->where('product_id', $product_id)->where('store_id', $store_id)->sum('qty');
        $sales_returns = DB::table('view_sales_returns')->where('product_type', 'Consumer')->where('product_id', $product_id)->where('store_id', $store_id)->sum('qty');
        $online_sales = DB::table('view_online_sales')->where('product_type', 'Consumer')->where('product_id', $product_id)->where('store_id', $store_id)->sum('qty');
        $transfers = DB::table('view_transfers')->where('product_type', 'Consumer')->where('product_id', $product_id)->where('host_id', $store_id)->sum('qty');
        $receives = DB::table('view_transfers')->where('product_type', 'Consumer')->where('product_id', $product_id)->where('destination_id', $store_id)->sum('qty');
        return $liftings + $sales_returns + $receives - $lifting_returns - $client_sales - $online_sales - $transfers;
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
            $products = $query->where('status', 1)->orderBy('name', 'asc')->get();
            return response()->json(['status' => 'success', 'products' => $products]);
        }

        if (request()->ajax() && request()->has('get_product')) {
            $product_id = request('product_id');
            $store_id = json_decode(Auth::user()->store_id)[0];
            $stock = $this->stock($product_id, $store_id);
            return response()->json(['status' => 'success', 'stock' => $stock]);
        }

        if (request()->ajax() && request()->has('get_stock')) {
            $store_id = json_decode(Auth::user()->store_id)[0];
            $product_id = request('product_id');

            $stock = $this->stock($product_id, $store_id);
            if (request('quantity') > $stock) {
                return response()->json(['status' => 'error', 'data' => 'stock not available please decrease quantity!']);
            } else {
                $product = Product::find(request('product_id'));
                $price = $product->price->sale_price;
                $amount = request('quantity') * $price;
                $unit = @$product->attribute->name;
                $vendor = @$product->vendor->name;
                return response()->json(['status' => 'success', 'product' => $product, 'unit' => $unit, 'quantity' => request('quantity'), 'stock' => $stock, 'price' => $price, 'amount' => $amount, 'vendor' => $vendor]);
            }
        }

        if (request()->ajax() && request()->has('check_client')) {
            $phone = request('phone');
            $client = Client::where('company_id', (Auth::user()->company_id ?? 1))->where('phone', $phone)->first();
            $client_name = @$client->name;
            return response()->json(['status' => 'success', 'client_id' => @$client->id, 'client_name' => $client_name]);
        }

        if (!@Auth::user()->staff) {
            return redirect()->back()->withErrors('You are not a staff!');
        }

        if (is_null(Auth::user()->store_id)) {
            return redirect()->back()->withErrors('Store not found!');
        }

        $products = Product::where('product_type', 'Consumer')->where('status', 1)->orderBy('name', 'asc')->get();
        $title = 'Add New Sales';
        $vendors = Vendor::where('status', 1)->orderBy('name', 'asc')->get();
        $stores = Store::where('status', 1)->get();
        $staffs = Staff::where('status', 1)->where('type', 'sales')->orderBy('name', 'asc')->get();
        $invoice = $this->getOrderNo();
        return view('admin.running_sales.create', compact('title', 'invoice', 'vendors', 'stores', 'staffs', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'invoice' => 'required',
            'date' => 'required',
            'product_id' => 'required',
            'qty' => 'required',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $total_amount = 0;
                foreach ($request->amount as $amount) {
                    $total_amount += $amount;
                }

                $client = Client::where('phone', $request->client_phone)->first();
                if (is_null($client)) {
                    $latest_client = Client::withoutGlobalScope(CompanyScope::class)->withTrashed()->orderBy('code', 'desc')->first();
                    if ($latest_client) {
                        $code = (int)$latest_client->code + 1;
                    } else {
                        $code = 1;
                    }
                    $client = Client::create([
                        'company_id' => Auth::user()->company_id ?? 1,
                        'code' => $code,
                        'name' => $request->client_name,
                        'phone' => $request->client_phone,
                        'area_id' => 15,
                        'territory_id' => 52,
                    ]);
                }

                $invoice = $this->getOrderNo();
                $store_id = json_decode(Auth::user()->store_id)[0];
                $sales = Sales::create([
                    'company_id' => Auth::user()->company_id ?? 1,
                    'store_id' => $store_id,
                    'client_id' => $client->id,
                    'invoice' => $invoice,
                    'date' => date('Y-m-d', strtotime($request->date)),
                    'sales_type' => 'running',
                    'total_amount' => $total_amount,
                    'discount' => $request->discount,
                    'total_paid' => $request->net_payable,
                    'created_by' => Auth::user()->id,
                    'staff_id' => @Auth::user()->staff->id,
                ]);

                foreach ($request->product_id as $key => $product_id) {
                    $stock = $this->stock($product_id, $store_id);
                    if ($request->qty[$key] > $stock) {
                        $product = Product::find($product_id);
                        throw new Exception('stock not available please decrease quantity for ' . $product->name);
                    } else {
                        $discount = ($request->discount / $request->total_amount) * $request->amount[$key];
                        SalesList::create([
                            'company_id' => Auth::user()->company_id ?? 1,
                            'sales_id' => $sales->id,
                            'store_id' => $store_id,
                            'client_id' => $client->id,
                            'product_id' => $product_id,
                            'rate' => $request->rate[$key],
                            'qty' => $request->qty[$key],
                            'amount' => $request->amount[$key],
                            'discount' => $discount,
                            'collection' => $request->amount[$key] - $discount,
                        ]);
                    }
                }

                $admin_setting = AdminSetting::first();
                if (@$admin_setting->accounting == 1) {
                    $income_head = CoaSetup::where('head_type', 'I')->where('head_name', 'Whole Sale')->first();
                    $headCode = collect([
                        '0' => 1010105,
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

                // Collection
                $first = date('Y-m-01');
                $last = new Carbon('last day of this month');
                $pay_data = Collection::withoutGlobalScope(CompanyScope::class)->withTrashed()->select(['payment_no'])->where('created_at', '>=', $first)->where('created_at', '<=', $last)->latest('id')->first();
                if ($pay_data) {
                    $trim = str_replace("STC", '', $pay_data->payment_no);
                    $dataPrefix = (int)$trim + 1;
                    $payment_no = "STC" . $dataPrefix;
                } else {
                    $payment_no = "STC" . date('y') . date('m') . '000001';
                }

                $collection = Collection::create([
                    'company_id' => Auth::user()->company_id ?? 1,
                    'client_id' => $client->id,
                    'payment_no' => $payment_no,
                    'amount' => $total_amount - $request->discount,
                    'payment_date' => date('Y-m-d', strtotime($request->date)),
                    'collection_type' => 'collection',
                    'payment_type' => 'cash',
                    'remarks' => 'Paid on Sale',
                    'sales_id' => $sales->id,
                    'created_by' => Auth::user()->id,
                ]);

                CollectionData::create([
                    'collection_id' => $collection->id,
                    'sales_id' => $sales->id,
                    'amount' => $total_amount - $request->discount,
                ]);

                if (@$admin_setting->accounting == 1) {
                    $cash_head = CoaSetup::where('head_name', 'Cash in Hand')->first();
                    $headCode = collect([
                        '0' => $cash_head->head_code,
                        '1' => 1010105
                    ]);

                    $postData = [];
                    for ($i = 0; $i < $countHead; $i++) {
                        $coa = CoaSetup::where('company_id', Auth::user()->company_id ?? 1)->where('head_code', $headCode[$i])->first();
                        $postData[] = [
                            'company_id' => Auth::user()->company_id ?? 1,
                            'voucher_no' => $payment_no,
                            'voucher_type' => "Collection",
                            'voucher_date' => date('Y-m-d', strtotime($request->date)),
                            'coa_setup_id' => $coa->id,
                            'coa_head_code' => $headCode[$i],
                            'narration' => 'Collection Against PAYMENT NO - ' . $payment_no,
                            'debit_amount' => $debit_amount[$i],
                            'credit_amount' => $credit_amount[$i],
                            'created_by' => Auth::user()->id,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now()
                        ];
                    }
                    AccountTransactionAuto::insert($postData);
                }

                $store = Store::find($store_id);
                $products_info = '';
                foreach ($request->product_id as $key => $product_id) {
                    $product = Product::find($product_id);
                    $products_info .= ' ' . $product->name . ' Quanity : ' . $request->qty[$key] . ' ' . @$product->attribute->name;
                }
                AccessLog::create([
                    'date_time' => Carbon::now(),
                    'page' => 'Running Sales',
                    'action' => 'Add',
                    'description' => 'Create a new sales with invoice no ' . $sales->invoice . ' to client ' . $client->name . ' from store ' . $store->name . ' sales amount is ' . $sales->total_amount . ' sales discount ' . $sales->discount . ' products ' . $products_info . ' on cash sale',
                    'user_id' => Auth::user()->id,
                ]);
            });

            return redirect()->route('admin.running-sales.index')->withSuccessMessage('Created Successfully!');
        } catch (Throwable $caught) {
            if ($caught) {
                return redirect()->back()->withErrors('Stock not available!');
            }
        }
        return redirect()->route('admin.running-sales.index')->withSuccessMessage('Created Successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
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
        $report_title = 'Chalan';
        // return view('admin.running_sales.chalan', compact('title', 'logo', 'informations', 'report_title', 'data'));
        $pdf = Pdf::loadView('admin.running_sales.chalan', compact('title', 'logo', 'informations', 'report_title', 'data'));
        // $pdf->setPaper('A4', 'landscape');
        return $pdf->stream('chalan_' . date('d_m_Y_H_i_s') . '.pdf');
    }

    /**
     * Display the specified resource.
     */
    public function invoice(string $id)
    {
        if (Auth::user()->company_id) {
            $company = Company::find(Auth::user()->company_id);
            $hotline = $company->fax;
            $logo = $company->logo;
            $title = $company->name;
            $informations = $company->address . '</br>' . $company->phone . ', ' . $company->email . ', ' . $company->website;
        } else {
            $logo = NULL;
            $hotline = '01xxxxx-xxxxx';
            $title = 'Company Name Goes Here.';
            $informations = 'Company address will goes here </br> Mobile: 0967XXXXXX, Email: youremail@gmail.com, www.website.com';
        }
        $data = Sales::findOrFail($id);

        $total_sale_amount = Sales::whereNotIn('id', [$id])->where('client_id', $data->client_id)->sum('total_amount');
        $total_discount_amount = Sales::whereNotIn('id', [$id])->where('client_id', $data->client_id)->sum('discount');
        $total_paid_amount = Collection::where('client_id', $data->client_id)->where('payment_no', '!=', $data->invoice)->where('collection_type', '!=', 'adjust')->sum('amount');
        $opening = $total_sale_amount - ($total_discount_amount + $total_paid_amount);

        $report_title = 'Invoice';
        // return view('admin.running_sales.invoice', compact('title', 'logo', 'informations', 'hotline', 'report_title', 'data', 'opening'));
        $pdf = Pdf::loadView('admin.running_sales.invoice', compact('title', 'logo', 'informations', 'hotline', 'report_title', 'data', 'opening'));
        // $pdf->setPaper('A4', 'landscape');
        return $pdf->stream('sales_invoice_' . date('d_m_Y_H_i_s') . '.pdf');
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
            $products = $query->where('status', 1)->orderBy('name', 'asc')->get();
            return response()->json(['status' => 'success', 'products' => $products]);
        }

        if (request()->ajax() && request()->has('get_product')) {
            $product_id = request('product_id');
            $store_id = json_decode(Auth::user()->store_id)[0];
            $sales = SalesList::where('sales_id', $id)->where('store_id', $store_id)->where('product_id', $product_id)->first();
            $stock = $this->stock($product_id, $store_id) + @$sales->qty;
            return response()->json(['status' => 'success', 'stock' => $stock]);
        }

        if (request()->ajax() && request()->has('get_stock')) {
            $store_id = json_decode(Auth::user()->store_id)[0];
            $product_id = request('product_id');

            $sales = SalesList::where('sales_id', $id)->where('store_id', $store_id)->where('product_id', $product_id)->first();
            $stock = $this->stock($product_id, $store_id) + @$sales->qty;
            if (request('quantity') > $stock) {
                return response()->json(['status' => 'error', 'data' => 'stock not available please decrease quantity!']);
            } else {
                $product = Product::find(request('product_id'));
                $price = $product->price->sale_price;
                $amount = request('quantity') * $price;
                $unit = @$product->attribute->name;
                $vendor = @$product->vendor->name;
                return response()->json(['status' => 'success', 'product' => $product, 'unit' => $unit, 'quantity' => request('quantity'), 'stock' => $stock, 'price' => $price, 'amount' => $amount, 'vendor' => $vendor]);
            }
        }

        if (request()->ajax() && request()->has('check_client')) {
            $phone = request('phone');
            $client = Client::where('company_id', (Auth::user()->company_id ?? 1))->where('phone', $phone)->first();
            $client_name = @$client->name;
            return response()->json(['status' => 'success', 'client_id' => @$client->id, 'client_name' => $client_name]);
        }

        if (!@Auth::user()->staff) {
            return redirect()->back()->withErrors('You are not a staff!');
        }

        if (is_null(Auth::user()->store_id)) {
            return redirect()->back()->withErrors('Store not found!');
        }

        $title = 'Update Sales';
        $data = Sales::findOrFail($id);
        $products = Product::where('product_type', 'Consumer')->where('status', 1)->whereNotIn('id', $data->list->pluck('product_id')->toArray())->orderBy('name', 'asc')->get();
        $link = Route('admin.running-sales.update', $id);
        return view('admin.running_sales.edit', compact('title', 'data', 'link', 'products'));
    }

    public function searchEdit(Request $request)
    {
        if (is_null($request->invoice)) {
            return redirect()->route('admin.running-sales.index');
        }

        $title = 'Update Sales';
        $data = Sales::where('product_type', 'Consumer')->where('invoice', $request->invoice)->orderBy('id', 'desc')->first();
        $products = Product::where('product_type', 'Consumer')->where('status', 1)->whereNotIn('id', $data->list->pluck('product_id')->toArray())->orderBy('name', 'asc')->get();
        $link = Route('admin.running-sales.update', $data->id);
        return view('admin.running_sales.edit', compact('title', 'data', 'link', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'invoice' => 'required',
            'date' => 'required',
            'product_id' => 'required',
            'qty' => 'required',
        ]);

        try {
            DB::transaction(function () use ($request, $id) {
                $sales = Sales::findOrFail($id);
                AccountTransactionAuto::withTrashed()->where('voucher_no', $sales->invoice)->where('voucher_type', 'Sales')->forceDelete();
                $collection = Collection::withTrashed()->where('sales_id', $id)->first();
                if ($collection) {
                    AccountTransactionAuto::withTrashed()->where('voucher_no', $collection->payment_no)->where('voucher_type', 'Collection')->forceDelete();
                    $collection->forceDelete();
                }
                SalesList::where('sales_id', $id)->delete();

                $total_amount = 0;
                foreach ($request->amount as $amount) {
                    $total_amount += $amount;
                }

                $client = Client::where('phone', $request->client_phone)->first();
                if (is_null($client)) {
                    $latest_client = Client::withoutGlobalScope(CompanyScope::class)->withTrashed()->orderBy('code', 'desc')->first();
                    if ($latest_client) {
                        $code = (int)$latest_client->code + 1;
                    } else {
                        $code = 1;
                    }
                    $client = Client::create([
                        'company_id' => Auth::user()->company_id ?? 1,
                        'code' => $code,
                        'name' => $request->client_name,
                        'phone' => $request->client_phone,
                        'area_id' => 15,
                        'territory_id' => 52,
                    ]);
                }

                $store_id = json_decode(Auth::user()->store_id)[0];
                $sales->update([
                    'store_id' => $store_id,
                    'client_id' => $client->id,
                    'date' => date('Y-m-d', strtotime($request->date)),
                    'total_amount' => $total_amount,
                    'discount' => $request->discount,
                    'total_paid' => $request->net_payable,
                    'updated_by' => Auth::user()->id,
                ]);

                foreach ($request->product_id as $key => $product_id) {
                    $old_sales = SalesList::where('sales_id', $id)->where('store_id', $store_id)->where('product_id', $product_id)->first();
                    $stock = $this->stock($product_id, $store_id) + @$old_sales->qty;
                    if ($request->qty[$key] > $stock) {
                        $product = Product::find($product_id);
                        throw new Exception('stock not available please decrease quantity for ' . $product->name);
                    } else {
                        $discount = ($request->discount / $request->total_amount) * $request->amount[$key];
                        SalesList::create([
                            'company_id' => Auth::user()->company_id ?? 1,
                            'sales_id' => $sales->id,
                            'store_id' => $store_id,
                            'client_id' => $client->id,
                            'product_id' => $product_id,
                            'rate' => $request->rate[$key],
                            'qty' => $request->qty[$key],
                            'amount' => $request->amount[$key],
                            'discount' => $discount,
                            'collection' => $request->amount[$key] - $discount,
                        ]);
                    }
                }

                $admin_setting = AdminSetting::first();
                if (@$admin_setting->accounting == 1) {
                    $income_head = CoaSetup::where('head_type', 'I')->where('head_name', 'Whole Sale')->first();
                    $headCode = collect([
                        '0' => 1010105,
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

                // Collection
                $first = date('Y-m-01');
                $last = new Carbon('last day of this month');
                $pay_data = Collection::withoutGlobalScope(CompanyScope::class)->withTrashed()->select(['payment_no'])->where('created_at', '>=', $first)->where('created_at', '<=', $last)->latest('id')->first();
                if ($pay_data) {
                    $trim = str_replace("STC", '', $pay_data->payment_no);
                    $dataPrefix = (int)$trim + 1;
                    $payment_no = "STC" . $dataPrefix;
                } else {
                    $payment_no = "STC" . date('y') . date('m') . '000001';
                }

                $collection = Collection::create([
                    'company_id' => Auth::user()->company_id ?? 1,
                    'client_id' => $client->id,
                    'payment_no' => $payment_no,
                    'amount' => $total_amount - $request->discount,
                    'payment_date' => date('Y-m-d', strtotime($request->date)),
                    'collection_type' => 'collection',
                    'payment_type' => 'cash',
                    'remarks' => 'Paid on Sale',
                    'sales_id' => $sales->id,
                    'created_by' => Auth::user()->id,
                ]);

                CollectionData::create([
                    'collection_id' => $collection->id,
                    'sales_id' => $sales->id,
                    'amount' => $total_amount - $request->discount,
                ]);

                if (@$admin_setting->accounting == 1) {
                    $cash_head = CoaSetup::where('head_name', 'Cash in Hand')->first();
                    $headCode = collect([
                        '0' => $cash_head->head_code,
                        '1' => 1010105
                    ]);

                    $postData = [];
                    for ($i = 0; $i < $countHead; $i++) {
                        $coa = CoaSetup::where('company_id', Auth::user()->company_id ?? 1)->where('head_code', $headCode[$i])->first();
                        $postData[] = [
                            'company_id' => Auth::user()->company_id ?? 1,
                            'voucher_no' => $payment_no,
                            'voucher_type' => "Collection",
                            'voucher_date' => date('Y-m-d', strtotime($request->date)),
                            'coa_setup_id' => $coa->id,
                            'coa_head_code' => $headCode[$i],
                            'narration' => 'Collection Against PAYMENT NO - ' . $payment_no,
                            'debit_amount' => $debit_amount[$i],
                            'credit_amount' => $credit_amount[$i],
                            'created_by' => Auth::user()->id,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now()
                        ];
                    }
                    AccountTransactionAuto::insert($postData);
                }

                $store = Store::find($store_id);
                $products_info = '';
                foreach ($request->product_id as $key => $product_id) {
                    $product = Product::find($product_id);
                    $products_info .= ' ' . $product->name . ' Quanity : ' . $request->qty[$key] . ' ' . @$product->attribute->name;
                }
                AccessLog::create([
                    'date_time' => Carbon::now(),
                    'page' => 'Running Sales',
                    'action' => 'Update',
                    'description' => 'Update sales against invoice no ' . $sales->invoice . ' to client ' . $client->name . ' from store ' . $store->name . ' sales amount is ' . $sales->total_amount . ' sales discount ' . $sales->discount . ' products ' . $products_info . ' on cash sale',
                    'user_id' => Auth::user()->id,
                ]);
            });

            return redirect()->route('admin.running-sales.index')->withSuccessMessage('Updated Successfully!');
        } catch (Throwable $caught) {
            if ($caught) {
                return redirect()->back()->withErrors('Stock not available!');
            }
        }
        return redirect()->route('admin.running-sales.index')->withSuccessMessage('Updated Successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Recovery Deleted Data
        if (request()->has('recovery') && request('recovery') == 'true') {
            $data = Sales::onlyTrashed()->findOrFail($id);
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
            $data = Sales::onlyTrashed()->findOrFail($id);
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
                    'description' => 'Sales delete invoice no ' . $data->invoice . !is_null($collection) ? ' Collection delete ' . $collection->payment_no  : '',
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
