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
use App\Models\Product;
use App\Models\Sales;
use App\Models\SalesList;
use App\Models\Scopes\CompanyScope;
use App\Models\Store;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Throwable;
use Yajra\DataTables\Facades\DataTables;

class PosSalesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->ajax()) {
            $model = Sales::with(['company', 'store', 'client', 'staff'])->whereNotIn('sales_type', ['credit', 'cash'])->latest('id');
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
                    $checkbox = '<div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input ' . (!empty(request('type')) && request('type') == "trash" ? 'trash_multi_checkbox' : 'multi_checkbox') . '" id="' . $row->id . '" name="multi_checkbox[]" value="' . $row->id . '"><label for="' . $row->id . '" class="custom-control-label"></label></div>';
                    return $checkbox;
                })
                ->addColumn('date', function ($row) {
                    return date('d-m-Y', strtotime($row->date));
                })
                ->addColumn('actions', function ($row) {
                    $addiotional_buttons = '<a class="btn btn-sm border-0 px-10px fs-15 text-white tt btn-print-1" href="' . Route('admin.pos-sales.show', $row->id) . '" target="_blank"  data-bs-toggle="tooltip" data-bs-placement="top" title="Invoice"><i class="fal fa-print"></i></a>';
                    $transaction = AccountTransaction::withTrashed()->where('voucher_no', $row->invoice)->where('voucher_type', 'Sales')->first();
                    if (is_null($transaction)) {
                        $currentRouteName = request()->route()->getName();
                        $menu = AdminMenu::where('route', $currentRouteName)->first();
                        $delete = str_replace('index', 'destroy', $menu->route);
                        $menuAction = AdminMenuAction::where('route', $delete)->first();
                        if ($menuAction) {
                            $currentRoutePermission = Permission::findById($menuAction->permission_id);
                            if (!is_null($currentRoutePermission)) {
                                if (auth()->user()->can($currentRoutePermission->name) && is_null(request('type')) || auth()->user()->can($currentRoutePermission->name) && !is_null(request('type')) && request('type') == 'all') {
                                    $addiotional_buttons .= '<button type="button" class="btn btn-sm border-0 px-10px fs-15 btn-danger tt link-delete" data-url="' . Route($delete, $row->id) . '" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete"><i class="far fa-trash-alt"></i></button>';
                                } elseif (auth()->user()->can($currentRoutePermission->name)) {
                                    $addiotional_buttons .= '<button type="button" class="btn btn-sm border-0 px-10px fs-15 btn-success tt link-recovery" data-url="' . Route($delete, $row->id) . '" data-bs-toggle="tooltip" data-bs-placement="top" title="Recover"><i class="fad fa-recycle"></i></button>';
                                    $addiotional_buttons .= '<button type="button" class="btn btn-sm border-0 px-10px fs-15 btn-danger tt trash_delete" data-url="' . Route($delete, $row->id) . '" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete Permanently"><i class="far fa-trash-alt"></i></button>';
                                }
                            }
                        }
                    }
                    return '<div class="btn-group">' . $addiotional_buttons . '</div>';
                })
                ->rawColumns(['checkbox', 'actions'])
                ->make(true);
        }

        $title = "POS Sales";
        $params = '<input type="text" class="form-control date_picker px-2 py-1" id="sales_date" name="sales_date" style="width: 150px; min-height: auto;" value="' . date('d-m-Y') . '" placeholder="Sales Date">';
        return view('admin.pos_sales.index', compact('title', 'params'));
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

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        if ($request->ajax()) {
            $store_id = $request->store_id;
            $product = Product::where('code', $request->barcode)->first();
            if (is_null($product)) {
                return response()->json(['status' => 'error', 'data' => 'Product Not Found!']);
            }
            $product_id = $product->id;
            $stock = $this->stock($product_id, $store_id);
            if (is_array($request->product_id) && in_array($product_id, $request->product_id)) {
                $total_qty = $request->qty[$product_id] + 1;
                if ($stock > $total_qty) {
                    $client_price = ClientPrice::where('client_id', $request->client_id)->where('product_id', $product_id)->first();
                    $price = $client_price ? $client_price->client_price : $product->price->sale_price;
                    $amount = $total_qty * $price;
                    return response()->json(['status' => 'increment', 'product_id' => $product_id, 'total_qty' => $total_qty, 'amount' => $amount]);
                } else {
                    return response()->json(['status' => 'error', 'data' => 'Stock Insuficient!']);
                }
            }

            if ($stock > 0) {
                $client_price = ClientPrice::where('client_id', $request->client_id)->where('product_id', $product_id)->first();
                $price = $client_price ? $client_price->client_price : $product->price->sale_price;
                $unit = $product->attribute->name;
                return response()->json(['status' => 'success', 'product' => $product, 'unit' => $unit, 'price' => $price]);
            } else {
                return response()->json(['status' => 'error', 'data' => 'stock not available any more for this product!']);
            }
        }

        // if (@Auth::user()->staff->type != 'sales') {
        //     return redirect()->back()->withErrors('You are not a staff!');
        // }

        $title = 'Add New Sales';
        $clients = Client::where('status', 1)->orWhere('is_chain', 1)->orderBy('name', 'asc')->get();
        $stores = Store::where('status', 1)->get();
        $invoice = $this->getOrderNo();
        $cash_heads = CoaSetup::with('parent')->whereHas('parent', function ($query) {
            $query->where('head_name', 'Cash In Hand')->orWhere('head_name', 'Cash In Bank');
        })->get();
        $target_blank = true;
        return view('admin.pos_sales.create', compact('title', 'clients', 'invoice', 'stores', 'cash_heads', 'target_blank'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
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

        try {
            DB::transaction(function () use ($request, &$sales) {
                $total_amount = 0;
                foreach ($request->amount as $amount) {
                    $total_amount += $amount;
                }

                $invoice = $this->getOrderNo();
                $sales = Sales::create([
                    'company_id' => Auth::user()->company_id ?? 1,
                    'store_id' => $request->store_id,
                    'client_id' => $request->client_id,
                    'invoice' => $invoice,
                    'date' => date('Y-m-d'),
                    'sales_type' => 'POS',
                    'total_amount' => $total_amount,
                    'discount' => $request->discount,
                    'total_paid' => $request->net_payable,
                    'created_by' => Auth::user()->id,
                    'staff_id' => @Auth::user()->staff->id,
                ]);

                foreach ($request->product_id as $product_id) {
                    $stock = $this->stock($product_id, $request->store_id);
                    if ($request->qty[$product_id] > $stock) {
                        $product = Product::find($product_id);
                        throw new Exception('stock not available please decrease quantity for ' . $product->name);
                    } else {
                        $discount = ($request->discount / $request->total_amount) * $request->amount[$product_id];
                        SalesList::create([
                            'company_id' => Auth::user()->company_id ?? 1,
                            'sales_id' => $sales->id,
                            'store_id' => $request->store_id,
                            'client_id' => $request->client_id,
                            'product_id' => $product_id,
                            'rate' => $request->rate[$product_id],
                            'qty' => $request->qty[$product_id],
                            'amount' => $request->amount[$product_id],
                            'discount' => $discount,
                            'collection' => $request->amount[$product_id],
                        ]);
                    }
                }

                $client = Client::find($request->client_id);
                if ($client->coa) {
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
                            'voucher_no' => $invoice,
                            'voucher_type' => "Sales",
                            'voucher_date' => date('Y-m-d'),
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
                    'client_id' => $request->client_id,
                    'payment_no' => $payment_no,
                    'amount' => $total_amount - $request->discount,
                    'payment_date' => date('Y-m-d'),
                    'collection_type' => 'collection',
                    'payment_type' => 'Cash',
                    'remarks' => 'Paid on Sale',
                    'sales_id' => $sales->id,
                    'created_by' => Auth::user()->id,
                ]);

                CollectionData::create([
                    'collection_id' => $collection->id,
                    'sales_id' => $sales->id,
                    'amount' => $total_amount - $request->discount,
                ]);

                if ($client->coa) {
                    $cash_head = CoaSetup::findOrFail($request->coa_setup_id);
                    $headCode = collect([
                        '0' => $cash_head->head_code,
                        '1' => $client->coa->head_code
                    ]);

                    $postData = [];
                    for ($i = 0; $i < $countHead; $i++) {
                        $coa = CoaSetup::where('company_id', Auth::user()->company_id ?? 1)->where('head_code', $headCode[$i])->first();
                        $postData[] = [
                            'company_id' => Auth::user()->company_id ?? 1,
                            'voucher_no' => $payment_no,
                            'voucher_type' => "Collection",
                            'voucher_date' => date('Y-m-d'),
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

                $store = Store::find($request->store_id);
                $products_info = '';
                foreach ($request->product_id as $product_id) {
                    $product = Product::find($product_id);
                    $products_info .= ' ' . $product->name . ' Quanity : ' . $request->qty[$product_id] . ' ' . $product->attribute->name;
                }
                AccessLog::create([
                    'date_time' => Carbon::now(),
                    'page' => 'Sales',
                    'action' => 'Add',
                    'description' => 'Create a new sales with invoice no ' . $sales->invoice . ' to client ' . $client->name . ' from store ' . $store->name . ' sales amount is ' . $sales->total_amount . ' sales discount ' . $sales->discount . ' products ' . $products_info . ' on POS Sales',
                    'user_id' => Auth::user()->id,
                ]);
            });
            return $this->show($sales->id);
        } catch (Throwable $caught) {
            if ($caught) {
                return redirect()->back()->withErrors('Stock not available!');
            }
        }
        return redirect()->back()->withSuccessMessage('Created Successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        if (Auth::user()->company_id) {
            $company = Company::find(Auth::user()->company_id);
            $hotline = $company->fax;
            $logo = $company->logo;
            $title = $company->name;
            $informations = $company->address . '</br>' . $company->phone . ', ' . $company->email . ', ' . $company->website;
        } else {
            $hotline = '01xxxxx-xxxxx';
            $title = 'Company Name Goes Here.';
            $informations = 'Company address will goes here </br> Mobile: 0967XXXXXX, Email: youremail@gmail.com, www.website.com';
        }
        $data = Sales::findOrFail($id);
        $report_title = 'Invoice';
        return view('admin.pos_sales.invoice', compact('title', 'logo', 'informations', 'hotline', 'report_title', 'data'));
        $pdf = Pdf::loadView('admin.pos_sales.invoice', compact('title', 'logo', 'informations', 'hotline', 'report_title', 'data'));
        return $pdf->stream('invoice_' . date('d_m_Y_H_i_s') . '.pdf');
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
