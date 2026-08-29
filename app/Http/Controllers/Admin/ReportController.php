<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Area;
use App\Models\User;
use App\Models\Group;
use App\Models\Order;
use App\Models\Sales;
use App\Models\Staff;
use App\Models\Store;
use App\Models\Client;
use App\Models\Invest;
use App\Models\Region;
use App\Models\Vendor;
use App\Models\Wallet;
use App\Models\Company;
use App\Models\Product;
use App\Models\Category;
use App\Models\CoaSetup;
use App\Models\Investor;
use Carbon\CarbonPeriod;
use App\Models\AccessLog;
use App\Models\StoreArea;
use App\Models\Territory;
use App\Models\Collection;
use App\Models\ProductSku;
use App\Models\DeliveryMan;
use App\Models\DeliveryList;
use App\Models\OrderProduct;
use App\Models\SalesHistory;
use App\Models\TrialBalance;
use Illuminate\Http\Request;
use App\Models\VendorPayment;
use App\Models\ClientCategory;
use App\Models\LiftingProduct;
use App\Models\SalesReturnList;
use App\Models\TransferProduct;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\CollectionHistory;
use App\Models\LiftingReturnList;
use App\Models\AccountTransaction;
use App\Models\InvestorProfitList;
use Illuminate\Support\Facades\DB;
use App\Models\Scopes\CompanyScope;
use App\DataTables\CoaListDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\DataTables\AccessLogDataTable;
use App\DataTables\OrderListDataTable;
use App\DataTables\ClientListDataTable;
use App\DataTables\ProductListDataTable;
use App\DataTables\ProfitSheetDataTable;
use App\DataTables\TransferLogDataTable;
use App\DataTables\voucherListDataTable;
use App\DataTables\CustomerListDataTable;
use App\DataTables\SalesHistoryDataTable;
use App\DataTables\SalesSummaryDataTable;
use App\DataTables\OnRouteOrdersDataTable;
use App\DataTables\ReturnHistoryDataTable;
use App\DataTables\ReturnSummaryDataTable;
use App\DataTables\LiftingHistoryDataTable;
use App\Services\Statement\Vendor\Statement;
use App\DataTables\OrderlistSummaryDataTable;
use App\DataTables\CollectionHistoryDataTable;
use App\DataTables\DeliveryStatementDataTable;
use App\DataTables\OnlineSalesHistoryDataTable;
use App\DataTables\OnlineSalesSummaryDataTable;
use App\DataTables\AreaWiseSalesSummaryDataTable;
use App\DataTables\LiftingReturnHistoryDataTable;
use App\DataTables\LiftingReturnSummaryDataTable;
use App\DataTables\VendorPaymentHistoryDataTable;
use App\DataTables\VendorPaymentSummaryDataTable;
use App\DataTables\LiftingSummeryHistoryDataTable;
use App\DataTables\SalesTargetAchivementDataTable;
use App\DataTables\StoreWiseSalesSummaryDataTable;
use App\Services\Statement\Client\ClientStatement;
use App\DataTables\ProductWiseSalesSummaryDataTable;
use App\Services\Statement\Product\ProductStatement;
use App\Services\LiftingRealization\LiftingRealization;
use App\DataTables\DeliveryManDeliveryStatementDataTable;
use App\DataTables\ModeratorOrdersDataTable;
use App\DataTables\CollectionReportDataTable;
use App\DataTables\CourierReportDataTable;
use App\DataTables\DeliverymanOrdersDataTable;
use App\DataTables\ProductSalesSummaryDataTable;
use App\Models\AdditionalCost;
use App\Models\DeliveryAgent;
use App\Models\InvestorPayment;
use App\Models\InvestorSattlement;
use App\Models\ProfitDistributionList;

class ReportController extends Controller
{
    public function productList(Request $request, ProductListDataTable $dataTable)
    {
        if ($request->has('print')) {
            if (Auth::user()->company_id) {
                $company = Company::find(Auth::user()->company_id);
                $title = $company->name;
                $informations = $company->address . '</br>' . $company->phone . ', ' . $company->email . ', ' . $company->website;
            } else {
                $title = 'Company Name Goes Here.';
                $informations = 'Company address will goes here </br> Mobile: 0967XXXXXX, Email: youremail@gmail.com, www.website.com';
            }
            $query = Product::with(['vendor', 'category', 'attribute']);
            if (!is_null($request->vendor_id)) {
                $query->where('vendor_id', $request->vendor_id);
            }
            if (!is_null($request->category_id)) {
                $query->where('category_id', $request->category_id);
            }
            $data = $query->orderBy('name', 'asc')->get();

            $report_title = 'Product Price List';
            $pdf = Pdf::loadView('admin.reports.product_list.print', compact('title', 'informations', 'report_title', 'data'));
            return $pdf->stream('product_list_' . date('d_m_Y_H_i_s') . '.pdf');
        }

        $title = 'Product List Report';
        $categories = Category::root()->where('status', 1)->orderBy('name', 'asc')->get();
        $vendors = Vendor::where('status', 1)->orderBy('name', 'asc')->get();
        return $dataTable->render('admin.reports.product_list.index', compact('title', 'categories', 'vendors'));
    }

    public function liftingReport(Request $request, LiftingHistoryDataTable $historyDataTable, LiftingSummeryHistoryDataTable $historySummaryDataTable)
    {
        if ($request->ajax() && $request->has('getProducts')) {
            $query = Product::query();
            if (!is_null($request->product_type)) {
                $query->where('product_type', $request->product_type);
            }
            if (!is_null($request->vendor_id)) {
                $query->whereIn('vendor_id', $request->vendor_id);
            }
            if (!is_null($request->category_id)) {
                $query->whereIn('category_id', $request->category_id);
            }
            $products = $query->where('status', 1)->orderBy('name', 'asc')->get();
            return response()->json(['status' => 'success', 'products' => $products]);
        }

        $vendor_ids = $request->vendor_id;
        $date_range = explode('to', $request->date_range);
        $start_date = !is_null($request->date_range) ? date('Y-m-d', strtotime($date_range[0])) : date('Y-m-01');
        $end_date = !is_null($request->date_range) ? date('Y-m-d', strtotime($date_range[1])) : date('Y-m-t');
        $category_ids = $request->category_id;
        $store_id = $request->store_id;
        $product_ids = $request->product_id;
        $product_type = $request->product_type;
        $report_type = $request->report_type;

        if (!is_null($request->print)) {
            if (Auth::user()->company_id) {
                $company = Company::find(Auth::user()->company_id);
                $title = $company->name;
                $informations = $company->address . '</br>' . $company->phone . ', ' . $company->email . ', ' . $company->website;
            } else {
                $title = 'Company Name Goes Here.';
                $informations = 'Company address will goes here </br> Mobile: 0967XXXXXX, Email: youremail@gmail.com, www.website.com';
            }

            if (request('report_type') == 'summary') {
                $report_type = 'summary';
                $report_title = 'Lifting Summary Report <br> <span class="text-sm">' . date('d-m-Y', strtotime($start_date)) . ' To ' . date('d-m-Y', strtotime($end_date)) . '</span>';
                $query = LiftingProduct::with(['lifting', 'product', 'variant', 'vendor'])->select(
                    'product_id',
                    'vendor_id',
                    'variant_id',
                    DB::raw('SUM(qty) as total_lifting_qty'),
                    DB::raw('SUM(total_amount - discount) as total_lifting_price')
                );
                $query->where('product_type', $product_type ?? 'Consumer');
                if (!is_null($category_ids)) {
                    $query->whereHas('product', function ($squery) use ($category_ids) {
                        $squery->whereIn('category_id', $category_ids);
                    });
                }
                if (!is_null($product_ids)) {
                    $query->whereIn('product_id', $product_ids);
                }
                if (!is_null($store_id)) {
                    $query->where('store_id', $store_id);
                }
                $query->whereHas('lifting', function ($squery) use ($vendor_ids, $start_date, $end_date) {
                    if (!is_null($vendor_ids)) {
                        $squery->whereIn('vendor_id', $vendor_ids);
                    }
                    $squery->where('lifting_date', '>=', $start_date)->where('lifting_date', '<=', $end_date);
                });
                if ($request->product_type == 'Consumer' || is_null($request->product_type)) {
                    $query->groupBy('product_id');
                } else {
                    $query->groupBy('variant_id');
                }
                $data = $query->get();
                // return view('admin.reports.lifting_history.print', compact('title', 'informations', 'report_title', 'report_type', 'data'));
                $pdf = Pdf::loadView('admin.reports.lifting_history.print', compact('title', 'informations', 'report_title', 'report_type', 'data'));
                return $pdf->stream('lifting_summary_' . date('d_m_Y_H_i_s') . '.pdf');
            } else {
                $report_type = 'history';
                $report_title = 'Lifting History Report <br> <span class="text-sm">' . date('d-m-Y', strtotime($start_date)) . ' To ' . date('d-m-Y', strtotime($end_date)) . '</span>';

                $query = LiftingProduct::with(['lifting', 'product', 'variant', 'store', 'vendor']);
                $query->where('product_type', $product_type ?? 'Consumer');
                if (!is_null($request->vendor_id)) {
                    $query->whereHas('lifting', function ($squery) use ($vendor_ids) {
                        $squery->whereIn('vendor_id', $vendor_ids);
                    });
                }
                if (!is_null($request->category_id)) {
                    $query->whereHas('product', function ($squery) use ($category_ids) {
                        $squery->whereIn('category_id', $category_ids);
                    });
                }
                if (!is_null($request->store_id)) {
                    $query->where('store_id', $store_id);
                }
                if (!is_null($request->product_id)) {
                    $query->whereIn('product_id', $product_ids);
                }
                $query->whereHas('lifting', function ($squery) use ($start_date, $end_date) {
                    $squery->where('lifting_date', '>=', $start_date)->where('lifting_date', '<=', $end_date);
                });
                $data = $query->get();
                // return view('admin.reports.lifting_history.print', compact('title', 'informations', 'report_title', 'report_type', 'data'));
                $pdf = Pdf::loadView('admin.reports.lifting_history.print', compact('title', 'informations', 'report_title', 'report_type', 'data'));
                $pdf->setPaper('A4', 'landscape');
                return $pdf->stream('lifting_history_' . date('d_m_Y_h_i_s') . '.pdf');
            }
        }

        $query = Product::query();
        $query->where('product_type', $request->product_type ?? 'Consumer');
        if (!is_null($request->vendor_id)) {
            $query->whereIn('vendor_id', $request->vendor_id);
        }
        if (!is_null($request->category_id)) {
            $query->whereIn('category_id', $request->category_id);
        }
        $products = $query->orderBy('name', 'asc')->get();

        $filter_link = Route('admin.lifting-history.index');
        $categories = Category::root()->where('status', 1)->orderBy('name', 'asc')->get();
        $vendors = Vendor::where('status', 1)->orderBy('name', 'asc')->get();
        $stores = Store::where('status', 1)->get();
        if ($request->report_type == 'summary') {
            $title = 'Lifting Summary Report';
            return $historySummaryDataTable->render('admin.reports.lifting_history.index', compact('title', 'filter_link', 'report_type', 'categories', 'vendors', 'stores', 'vendor_ids', 'start_date', 'end_date', 'category_ids', 'store_id', 'product_ids', 'products'));
        } else {
            $title = 'Lifting History Report';
            return $historyDataTable->render('admin.reports.lifting_history.index', compact('title', 'filter_link', 'report_type', 'categories', 'vendors', 'stores', 'vendor_ids', 'start_date', 'end_date', 'category_ids', 'store_id', 'product_ids', 'products'));
        }
    }

    public function liftingReturnHistory(Request $request, LiftingReturnHistoryDataTable $historyDataTable, LiftingReturnSummaryDataTable $summaryDataTable)
    {
        if ($request->ajax() && $request->has('getProducts')) {
            $query = Product::query();
            if (!is_null($request->product_type)) {
                $query->where('product_type', $request->product_type);
            }
            if (!is_null($request->vendor_id)) {
                $query->whereIn('vendor_id', $request->vendor_id);
            }
            if (!is_null($request->category_id)) {
                $query->whereIn('category_id', $request->category_id);
            }
            $products = $query->where('status', 1)->orderBy('name', 'asc')->get();
            return response()->json(['status' => 'success', 'products' => $products]);
        }

        $vendor_ids = $request->vendor_id;
        $date_range = explode('to', $request->date_range);
        $start_date = !is_null($request->date_range) ? date('Y-m-d', strtotime($date_range[0])) : date('Y-m-01');
        $end_date = !is_null($request->date_range) ? date('Y-m-d', strtotime($date_range[1])) : date('Y-m-t');
        $category_ids = $request->category_id;
        $store_id = $request->store_id;
        $product_ids = $request->product_id;
        $product_type = $request->product_type;
        $report_type = $request->report_type;

        if (!is_null($request->print)) {
            if (Auth::user()->company_id) {
                $company = Company::find(Auth::user()->company_id);
                $title = $company->name;
                $informations = $company->address . '</br>' . $company->phone . ', ' . $company->email . ', ' . $company->website;
            } else {
                $title = 'Company Name Goes Here.';
                $informations = 'Company address will goes here </br> Mobile: 0967XXXXXX, Email: youremail@gmail.com, www.website.com';
            }

            if (request('report_type') == 'summary') {
                $report_type = 'summary';
                $report_title = 'Lifting Return Summary <br> <span class="text-sm">' . date('d-m-Y', strtotime($start_date)) . ' To ' . date('d-m-Y', strtotime($end_date)) . '</span>';

                $query = LiftingReturnList::with(['return', 'product', 'variant', 'lifting_product', 'vendor', 'store'])->select(
                    'lifting_return_lists.product_id',
                    'lifting_return_lists.variant_id',
                    'lifting_return_lists.vendor_id',
                    DB::raw('SUM(lifting_return_lists.qty) as total_qty'),
                    DB::raw('SUM(lifting_return_lists.lifting_price) as total_price')
                );
                $query->where('product_type', $product_type ?? 'Consumer');
                if (!is_null($request->category_id)) {
                    $query->whereHas('product', function ($squery) use ($category_ids) {
                        $squery->whereIn('category_id', $category_ids);
                    });
                }
                if (!is_null($request->vendor_id)) {
                    $query->whereIn('vendor_id', $vendor_ids);
                }
                if (!is_null($request->product_id)) {
                    $query->whereIn('product_id', $product_ids);
                }
                if (!is_null($request->store_id)) {
                    $query->where('store_id', $store_id);
                }
                if (!is_null($start_date) && !is_null($end_date)) {
                    $query->whereHas('return', function ($squery) use ($start_date, $end_date) {
                        $squery->where('date', '>=', $start_date)->where('date', '<=', $end_date);
                    });
                }
                if ($product_type == 'Consumer' || is_null($product_type)) {
                    $query->groupBy('product_id');
                } else {
                    $query->groupBy('variant_id');
                }
                $data = $query->get();

                // return view('admin.reports.lifting_return_history.print', compact('title', 'informations', 'report_title', 'report_type', 'data'));
                $pdf = Pdf::loadView('admin.reports.lifting_return_history.print', compact('title', 'informations', 'report_title', 'report_type', 'data'));
                return $pdf->stream('lifting_return_summary_' . date('d_m_Y_H_i_s') . '.pdf');
            } else {
                $report_type = 'history';
                $report_title = 'Lifting Return History <br> <span class="text-sm">' . date('d-m-Y', strtotime($start_date)) . ' To ' . date('d-m-Y', strtotime($end_date)) . '</span>';

                $query = LiftingReturnList::with(['return', 'product', 'variant', 'lifting_product', 'vendor', 'store']);
                $query->where('product_type', $product_type ?? 'Consumer');
                if (!is_null($request->vendor_id)) {
                    $query->whereHas('return', function ($squery) use ($vendor_ids) {
                        $squery->whereIn('vendor_id', $vendor_ids);
                    });
                }
                if (!is_null($request->category_id)) {
                    $query->whereHas('product', function ($squery) use ($category_ids) {
                        $squery->whereIn('category_id', $category_ids);
                    });
                }
                if (!is_null($store_id)) {
                    $query->where('store_id', $store_id);
                }
                if (!is_null($request->product_id)) {
                    $query->whereIn('product_id', $product_ids);
                }
                if (!is_null($start_date) && !is_null($end_date)) {
                    $query->whereHas('return', function ($squery) use ($start_date, $end_date) {
                        $squery->where('date', '>=', $start_date)->where('date', '<=', $end_date);
                    });
                }
                $data = $query->get();
                // return view('admin.reports.lifting_return_history.print', compact('title', 'informations', 'report_title', 'report_type', 'data'));
                $pdf = Pdf::loadView('admin.reports.lifting_return_history.print', compact('title', 'informations', 'report_title', 'report_type', 'data'));
                $pdf->setPaper('A4', 'landscape');
                return $pdf->stream('lifting_return_history' . date('d_m_Y_H_i_s') . '.pdf');
            }
        }

        $query = Product::query();
        $query->where('product_type', $request->product_type ?? 'Consumer');
        if (!is_null($request->vendor_id)) {
            $query->whereIn('vendor_id', $request->vendor_id);
        }
        if (!is_null($request->category_id)) {
            $query->whereIn('category_id', $request->category_id);
        }
        $products = $query->orderBy('name', 'asc')->get();

        $filter_link = Route('admin.lifting-return-history.index');
        $categories = Category::root()->where('status', 1)->orderBy('name', 'asc')->get();
        $vendors = Vendor::where('status', 1)->orderBy('name', 'asc')->get();
        $stores = Store::where('status', 1)->get();
        if ($request->report_type == 'summary') {
            $title = 'Lifting History Summary Report';
            return $summaryDataTable->render('admin.reports.lifting_return_history.index', compact('title', 'filter_link', 'report_type', 'categories', 'vendors', 'stores', 'vendor_ids', 'start_date', 'end_date', 'category_ids', 'store_id', 'product_ids', 'products'));
        } else {
            $title = 'Lifting History Report';
            return $historyDataTable->render('admin.reports.lifting_return_history.index', compact('title', 'filter_link', 'report_type', 'categories', 'vendors', 'stores', 'vendor_ids', 'start_date', 'end_date', 'category_ids', 'store_id', 'product_ids', 'products'));
        }
    }

    public function vendorPayment(Request $request, VendorPaymentHistoryDataTable $historyDataTable, VendorPaymentSummaryDataTable $summaryDataTable)
    {
        $report_type = $request->report_type;
        $vendor_id = $request->vendor_id;
        $date_range = explode('to', $request->date_range);
        $start_date = !is_null($request->date_range) ? date('Y-m-d', strtotime($date_range[0])) : date('Y-m-01');
        $end_date = !is_null($request->date_range) ? date('Y-m-d', strtotime($date_range[1])) : date('Y-m-t');
        if ($request->has('print')) {
            if (Auth::user()->company_id) {
                $company = Company::find(Auth::user()->company_id);
                $title = $company->name;
                $informations = $company->address . '</br>' . $company->phone . ', ' . $company->email . ', ' . $company->website;
            } else {
                $title = 'Company Name Goes Here.';
                $informations = 'Company address will goes here </br> Mobile: 0967XXXXXX, Email: youremail@gmail.com, www.website.com';
            }

            if ($report_type == 'history') {
                $report_title = 'Payment History Report <br> <span class="text-sm">' . date('d-m-Y', strtotime($start_date)) . ' To ' . date('d-m-Y', strtotime($end_date)) . '</span>';
                $query = VendorPayment::with(['vendor']);
                if (!is_null($request->vendor_id)) {
                    $query->whereIn('vendor_id', $vendor_id);
                }
                if (!is_null($start_date) && !is_null($end_date)) {
                    $query->where('payment_date', '>=', $start_date)->where('payment_date', '<=', $end_date);
                }
                $data = $query->orderBy('payment_date', 'desc')->get();
            } else {
                $report_title = 'Payment Summary Report <br> <span class="text-sm">' . date('d-m-Y', strtotime($start_date)) . ' To ' . date('d-m-Y', strtotime($end_date)) . '</span>';
                $query = VendorPayment::with(['vendor']);
                if (!is_null($request->vendor_id)) {
                    $query->whereIn('vendor_id', $vendor_id);
                }
                if (!is_null($start_date) && !is_null($end_date)) {
                    $query->where('payment_date', '>=', $start_date)->where('payment_date', '<=', $end_date);
                }
                $data = $query->select(
                    'vendor_payments.vendor_id',
                    DB::raw('SUM(vendor_payments.amount) as total_amount')
                )->groupBy('vendor_payments.vendor_id')->get();
            }
            // return view('admin.reports.vendor_payment.print', compact('title', 'informations', 'report_title', 'report_type', 'data'));
            $pdf = Pdf::loadView('admin.reports.vendor_payment.print', compact('title', 'informations', 'report_title', 'report_type', 'data'));
            return $pdf->stream('vendor_payment_history' . date('d_m_Y_H_i_s') . '.pdf');
        }

        $filter_link = Route('admin.vendor-payment-history.index');
        $vendors = Vendor::where('status', 1)->orderBy('name', 'asc')->get();
        if ($report_type == 'summary') {
            $title = 'Payment Summary';
            return $summaryDataTable->render('admin.reports.vendor_payment.index', compact('filter_link', 'title', 'vendors', 'report_type', 'vendor_id', 'start_date', 'end_date'));
        } else {
            $title = 'Payment History';
            return $historyDataTable->render('admin.reports.vendor_payment.index', compact('filter_link', 'title', 'vendors', 'report_type', 'vendor_id', 'start_date', 'end_date'));
        }
    }

    public function vendorStatement(Request $request)
    {
        $date_range = explode('to', $request->date_range);
        $start_date = isset($date_range[0]) ? date('Y-m-d', strtotime($date_range[0])) : NULL;
        $end_date = isset($date_range[1]) ? date('Y-m-d', strtotime($date_range[1])) : NULL;
        $vendor_id = $request->vendor_id;

        if ($request->has('print')) {
            if (Auth::user()->company_id) {
                $company = Company::find(Auth::user()->company_id);
                $title = $company->name;
                $informations = $company->address . '</br>' . $company->phone . ', ' . $company->email . ', ' . $company->website;
            } else {
                $title = 'Company Name Goes Here.';
                $informations = 'Company address will goes here </br> Mobile: 0967XXXXXX, Email: youremail@gmail.com, www.website.com';
            }

            $vendor = Vendor::find($vendor_id);
            $report_title = 'Vendor Statement (' . $vendor->name . ') <br> <span class="text-sm">' . date('d-m-Y', strtotime($start_date)) . ' To ' . date('d-m-Y', strtotime($end_date)) . '</span>';

            $previousBalance = Statement::previousBalance($vendor_id, $start_date);
            $statements = Statement::Statement($vendor_id, $start_date, $end_date, $previousBalance);
            $data = [
                'previousBalance' => $previousBalance,
                'statements' => $statements,
            ];

            // return view('admin.reports.vendor_statement.print', compact('title', 'informations', 'report_title', 'data', 'vendor'));
            $pdf = Pdf::loadView('admin.reports.vendor_statement.print', compact('title', 'informations', 'report_title', 'data', 'vendor'));
            return $pdf->stream('vendor_statement_' . date('d_m_Y_H_i_s') . '.pdf');
        }

        if ($request->has('filter')) {
            $previousBalance = Statement::previousBalance($vendor_id, $start_date);
            $statements = Statement::Statement($vendor_id, $start_date, $end_date, $previousBalance);
            $data = [
                'previousBalance' => $previousBalance,
                'statements' => $statements,
            ];
        } else {
            $data = [];
        }
        $title = 'Vendor Statement';
        $vendors = Vendor::where('status', 1)->orderBy('name', 'asc')->get();
        $filter_link = Route('admin.vendor-statement.index');
        return view('admin.reports.vendor_statement.index', compact('title', 'filter_link', 'vendors', 'data', 'vendor_id', 'start_date', 'end_date'));
    }

    public function clientList(Request $request, ClientListDataTable $dataTable)
    {
        if ($request->ajax() && $request->has('getArea')) {
            $areas = Area::where('region_id', $request->region_id)->where('status', 1)->orderBy('name', 'asc')->get();
            return response()->json(['status' => 'success', 'areas' => $areas]);
        }

       

        if ($request->has('print')) {
            if (Auth::user()->company_id) {
                $company = Company::find(Auth::user()->company_id);
                $title = $company->name;
                $informations = $company->address . '</br>' . $company->phone . ', ' . $company->email . ', ' . $company->website;
            } else {
                $title = 'Company Name Goes Here.';
                $informations = 'Company address will goes here </br> Mobile: 0967XXXXXX, Email: youremail@gmail.com, www.website.com';
            }
            $query = Client::with(['reference', 'client_category', 'area', 'territory']);
            $region_id = $request->region_id;
            $area_id = $request->area_id;
            $category_id = $request->category_id;
            $staff_id = $request->staff_id;
            $client_type = $request->client_type;
            $start_date = $request->start_date;
            if (!is_null($request->region_id)) {
                $query->whereHas('area', function ($squery) use ($region_id) {
                    $squery->where('region_id', $region_id);
                });
            }
            if (!is_null($request->area_id)) {
                $query->where('area_id', $area_id);
            }
            if (!is_null($request->territory_id)) {
                $query->where('territory_id', $territory_id);
            }
            if (!is_null($request->category_id)) {
                $query->where('client_category_id', $category_id);
            }
            if (!is_null($request->staff_id)) {
                $query->where('reference_by', $staff_id);
            }
            if (!is_null($request->client_type)) {
            }
            if (!is_null($request->start_date)) {
                $query->where('created_at', '>=', date('Y-m-d', strtotime($start_date)));
            }
            $data = $query->orderBy('name', 'asc')->get();

            $report_title = 'Client List';
            // return view('admin.reports.client_list.print', compact('title', 'informations', 'report_title', 'data'));
            $pdf = Pdf::loadView('admin.reports.client_list.print', compact('title', 'informations', 'report_title', 'data'));
            $pdf->setPaper('A4', 'landscape');
            return $pdf->stream('client_list_' . date('d_m_Y_H_i_s') . '.pdf');
        }

        $title = 'Client List';
        $regions = Region::where('status', 1)->orderBy('name', 'asc')->get();
        $categories = ClientCategory::where('status', 1)->orderBy('name', 'asc')->get();
        $staffs = Staff::where('status', 1)->orderBy('name', 'asc')->get();
        return $dataTable->render('admin.reports.client_list.index', compact('title', 'regions', 'categories', 'staffs'));
    }

    public function salesHistroy(Request $request, SalesHistoryDataTable $dataTable, SalesSummaryDataTable $summaryDatatable, ProductSalesSummaryDataTable $productSummaryDatatable)
    {
        if ($request->ajax() && $request->has('get_area')) {
            $areas = Area::where('status', 1)->where('region_id', $request->region_id)->orderBy('name', 'asc')->get();
            return response()->json(['status' => 'success', 'areas' => $areas]);
        }
        if ($request->ajax() && $request->has('get_territory')) {
            $territories = Territory::where('status', 1)->where('area_id', $request->area_id)->orderBy('name', 'asc')->get();
            $clients = Client::where('status', 1)->where('area_id', $request->area_id)->orderBy('name', 'asc')->get();
            return response()->json(['status' => 'success', 'territories' => $territories, 'clients' => $clients]);
        }
        if ($request->ajax() && $request->has('get_clients')) {
            $clients = Client::where('status', 1)->where('territory_id', $request->territory_id)->orderBy('name', 'asc')->get();
            return response()->json(['status' => 'success', 'clients' => $clients]);
        }
        if ($request->ajax() && $request->has('get_products')) {
            if (!is_null($request->category_id)) {
                $query = Product::query();
                $query->whereIn('category_id', $request->category_id);
                $products = $query->where('status', 1)->orderBy('name', 'asc')->get();
            } else {
                $products = Product::where('status', 1)->orderBy('name', 'asc')->get();
            }
            return response()->json(['status' => 'success', 'products' => $products]);
        }

        $client_id = $request->client_id;
        $region_id = $request->region_id;
        $area_id = $request->area_id;
        $territory_id = $request->territory_id;
        $date_range = explode('to', $request->date_range);
        $start_date = !is_null($request->date_range) ? date('Y-m-d', strtotime($date_range[0])) : date('Y-m-01');
        $end_date = !is_null($request->date_range) ? date('Y-m-d', strtotime($date_range[1])) : date('Y-m-t');
        $category_id = $request->category_id;
        $store_id = $request->store_id;
        $product_id =  $request->product_id;
        $staff_id =  $request->staff_id;
        $report_type = $request->report_type;
        $sales_type = $request->sales_type;

        if (!is_null($request->print)) {
            if (Auth::user()->company_id) {
                $company = Company::find(Auth::user()->company_id);
                $title = $company->name;
                $informations = $company->address . '</br>' . $company->phone . ', ' . $company->email . ', ' . $company->website;
            } else {
                $title = 'Company Name Goes Here.';
                $informations = 'Company address will goes here </br> Mobile: 0967XXXXXX, Email: youremail@gmail.com, www.website.com';
            }
            $query = SalesHistory::query();
            if (!is_null($sales_type) && $sales_type == 'sample') {
                $query->where('sales_type', $sales_type);
            } elseif (!is_null($sales_type) && $sales_type == 'POS') {
                $query->where('sales_type', 'POS');
            } else {
                $query->whereNotIn('sales_type', ['sample', 'POS']);
            }
            if (!is_null($request->region_id)) {
                $query->where('region_id', $region_id);
            }
            if (!is_null($request->area_id)) {
                $query->where('area_id', $area_id);
            }
            if (!is_null($request->territory_id)) {
                $query->where('territory_id', $territory_id);
            }
            if (!is_null($request->category_id)) {
                $query->whereIn('category_id', $category_id);
            }
            if (!is_null($request->product_id)) {
                $query->whereIn('product_id', $product_id);
            }
            if (!is_null($request->client_id)) {
                $query->whereIn('client_id', $client_id);
            }
            if (!is_null($request->store_id)) {
                $query->where('store_id', $store_id);
            }
            if (!is_null($staff_id)) {
                $query->whereIn('staff_id', $staff_id);
            }
            $query->where('date', '>=', $start_date)->where('date', '<=', $end_date);

            if ($request->report_type == 'history') {
                $report_title = 'Sales History Report <br> <span class="text-sm">' . date('d-m-Y', strtotime($start_date)) . ' To ' . date('d-m-Y', strtotime($end_date)) . '</span>';
                $data = $query->get();
                // return view('admin.reports.sales_history.print', compact('title', 'informations', 'report_title', 'report_type', 'data'));
                $pdf = Pdf::loadView('admin.reports.sales_history.print', compact('title', 'informations', 'report_title', 'report_type', 'data'));
                $pdf->setPaper('A4', 'landscape');
                return $pdf->stream('sales_history_' . date('d_m_Y_h_i_s') . '.pdf');
            } else {
                $report_title = 'Sales Summary Report <br> <span class="text-sm">' . date('d-m-Y', strtotime($start_date)) . ' To ' . date('d-m-Y', strtotime($end_date)) . '</span>';
                if ($request->report_type == 'product_summary') {
                    $query->select('*', DB::raw('SUM(qty) as total_qty'), DB::raw('SUM(amount) as total_amount'));
                    $query->groupBy('product_id');
                } elseif ($request->report_type == 'client_summary') {
                    $query->select('*', DB::raw('SUM(amount) as total_amount'));
                    $query->groupBy('client_id');
                }
                $data = $query->get();
                // return view('admin.reports.sales_history.print', compact('title', 'informations', 'report_title', 'report_type', 'data'));
                $pdf = Pdf::loadView('admin.reports.sales_history.print', compact('title', 'informations', 'report_title', 'report_type', 'data'));
                return $pdf->stream('sales_summary_' . date('d_m_Y_h_i_s') . '.pdf');
            }
        }

        $filter_link = Route('admin.sales-history.index');
        $stores = Store::where('status', 1)->get();
        $staffs = Staff::where('status', 1)->where('type', 'sales')->orderBy('name', 'asc')->get();
        $regions = Region::where('status', 1)->orderBy('name', 'asc')->get();
        $areas = Area::where('status', 1)->where('region_id', $region_id)->orderBy('name', 'asc')->get();
        $territories = Territory::where('status', 1)->where('area_id', $area_id)->orderBy('name', 'asc')->get();
        $query = Client::where('status', 1);
        if (!is_null($area_id)) {
            $query->where('area_id', $area_id);
        }
        if (!is_null($territory_id)) {
            $query->orWhere('territory_id', $territory_id);
        }
        $clients = $query->orderBy('name', 'asc')->get();
        $categories = Category::where('status', 1)->orderBy('name', 'asc')->get();
        if (is_array($category_id)) {
            $products = Product::where('status', 1)->whereIn('category_id', $category_id)->orderBy('name', 'asc')->get();
        } else {
            $products = Product::where('status', 1)->orderBy('name', 'asc')->get();
        }

        if ($report_type == 'product_summary') {
            $title = 'Sales Summary Report';
            return $productSummaryDatatable->render('admin.reports.sales_history.index', compact('title', 'filter_link', 'products', 'categories', 'clients', 'stores', 'staffs', 'regions', 'areas', 'territories', 'client_id', 'staff_id', 'date_range', 'start_date', 'end_date', 'category_id', 'store_id', 'product_id', 'region_id', 'area_id', 'territory_id', 'report_type'));
        } elseif ($report_type == 'client_summary') {
            $title = 'Sales Summary Report';
            return $summaryDatatable->render('admin.reports.sales_history.index', compact('title', 'filter_link', 'products', 'categories', 'clients', 'stores', 'staffs', 'regions', 'areas', 'territories', 'client_id', 'staff_id', 'date_range', 'start_date', 'end_date', 'category_id', 'store_id', 'product_id', 'region_id', 'area_id', 'territory_id', 'report_type'));
        } else {
            $title = 'Sales History Report';
            return $dataTable->render('admin.reports.sales_history.index', compact('title', 'filter_link', 'products', 'categories', 'clients', 'stores', 'staffs', 'regions', 'areas', 'territories', 'client_id', 'staff_id', 'date_range', 'start_date', 'end_date', 'category_id', 'store_id', 'product_id', 'region_id', 'area_id', 'territory_id', 'report_type'));
        }
    }

    public function collectionHistory(Request $request, CollectionHistoryDataTable $historyDataTable)
    {
        $report_type = $request->report_type;
        $collection_type = $request->collection_type;
        $staff_id = $request->staff_id;
        $client_id = $request->client_id;
        $date_range = explode('to', $request->date_range);
        $start_date = !is_null($request->date_range) ? date('Y-m-d', strtotime($date_range[0])) : date('Y-m-01');
        $end_date = !is_null($request->date_range) ? date('Y-m-d', strtotime($date_range[1])) : date('Y-m-t');

        if (!is_null($request->print)) {
            if (Auth::user()->company_id) {
                $company = Company::find(Auth::user()->company_id);
                $title = $company->name;
                $informations = $company->address . '</br>' . $company->phone . ', ' . $company->email . ', ' . $company->website;
            } else {
                $title = 'Company Name Goes Here.';
                $informations = 'Company address will goes here </br> Mobile: 0967XXXXXX, Email: youremail@gmail.com, www.website.com';
            }
            $query = CollectionHistory::query();

            if (!is_null($collection_type)) {
                $query->where('collection_type', $collection_type);
            }
            if (!is_null($client_id)) {
                $query->whereIn('client_id', $client_id);
            }
            if (!is_null($staff_id)) {
                $query->whereIn('staff_id', $staff_id);
            }
            $query->where('date', '>=', $start_date)->where('date', '<=', $end_date);

            if ($report_type == 'summary') {
                $report_title = 'Collection History Report <br> <span class="text-sm">' . date('d-m-Y', strtotime($start_date)) . ' To ' . date('d-m-Y', strtotime($end_date)) . '</span>';
                $data = $query->select('*', DB::raw('SUM(amount) as total_amount'))->groupBy('client_id')->get();
            } elseif ($report_type == 'history') {
                $report_title = 'Collection Summary Report <br> <span class="text-sm">' . date('d-m-Y', strtotime($start_date)) . ' To ' . date('d-m-Y', strtotime($end_date)) . '</span>';
                $data = $query->orderBy('date', 'desc')->get();
            }
            // return view('admin.reports.collection_history.print', compact('title', 'informations', 'report_title', 'report_type', 'collection_type', 'data'));
            $pdf = Pdf::loadView('admin.reports.collection_history.print', compact('title', 'informations', 'report_title', 'report_type', 'collection_type', 'data'));
            return $pdf->stream('collection_history_' . date('d_m_Y_h_i_s') . '.pdf');
        }

        $filter_link = Route('admin.collection-history.index');
        $staffs = User::where('is_staff', 1)->where('status', 1)->orderBy('name', 'asc')->get();
        $clients = Client::where('status', 1)->orderBy('name', 'asc')->get();
        $collection_type = $request->collection_type;
        if ($report_type == 'summary') {
            $title = 'Collection Summary';
        } else {
            $title = 'Collection History';
        }
        return $historyDataTable->render('admin.reports.collection_history.index', compact('filter_link', 'title', 'staffs', 'clients', 'report_type', 'client_id', 'staff_id', 'start_date', 'end_date', 'collection_type'));
    }

    public function returnHistory(Request $request, ReturnSummaryDataTable $summaryDataTable, ReturnHistoryDataTable $historyDataTable)
    {
        if ($request->ajax() && $request->has('get_area')) {
            $areas = Area::where('status', 1)->where('region_id', $request->region_id)->orderBy('name', 'asc')->get();
            return response()->json(['status' => 'success', 'areas' => $areas]);
        }
        if ($request->ajax() && $request->has('get_territory')) {
            $territories = Territory::where('status', 1)->where('area_id', $request->area_id)->orderBy('name', 'asc')->get();
            $clients = Client::where('status', 1)->where('area_id', $request->area_id)->orderBy('name', 'asc')->get();
            return response()->json(['status' => 'success', 'territories' => $territories, 'clients' => $clients]);
        }
        if ($request->ajax() && $request->has('get_clients')) {
            $clients = Client::where('status', 1)->where('territory_id', $request->territory_id)->orderBy('name', 'asc')->get();
            return response()->json(['status' => 'success', 'clients' => $clients]);
        }
        if ($request->ajax() && $request->has('get_products')) {
            if (!is_null($request->category_id)) {
                $query = Product::query();
                $query->whereIn('category_id', $request->category_id);
                $products = $query->where('status', 1)->orderBy('name', 'asc')->get();
            } else {
                $products = Product::where('status', 1)->orderBy('name', 'asc')->get();
            }
            return response()->json(['status' => 'success', 'products' => $products]);
        }

        if ($request->has('print')) {
            if (Auth::user()->company_id) {
                $company = Company::find(Auth::user()->company_id);
                $title = $company->name;
                $informations = $company->address . '</br>' . $company->phone . ', ' . $company->email . ', ' . $company->website;
            } else {
                $title = 'Company Name Goes Here.';
                $informations = 'Company address will goes here </br> Mobile: 0967XXXXXX, Email: youremail@gmail.com, www.website.com';
            }

            $report_type = $request->report_type;
            $region_id = $request->region_id;
            $area_id = $request->area_id;
            $territory_id = $request->territory_id;
            $category_id = explode(',', $request->category_id);
            $client_id = explode(',', $request->client_id);
            $product_id = explode(',', $request->product_id);
            $date_range = explode('to', $request->date_range);
            $start_date = isset($date_range[0]) ? date('Y-m-d', strtotime($date_range[0])) : date('Y-m-01');
            $end_date = isset($date_range[1]) ? date('Y-m-d', strtotime($date_range[1])) : date('Y-m-t');
            if ($report_type == 'history') {
                $report_title = 'Return History Report <br> <span class="text-sm">' . date('d-m-Y', strtotime($start_date)) . ' To ' . date('d-m-Y', strtotime($end_date)) . '</span>';
                $query = SalesReturnList::with(['return', 'client', 'client.area', 'product']);

                if (!is_null($request->region_id)) {
                    $query->whereHas('client.area', function ($squery) use ($region_id) {
                        $squery->where('region_id', $region_id);
                    });
                }
                if (!is_null($request->area_id)) {
                    $query->whereHas('client', function ($squery) use ($area_id) {
                        $squery->where('area_id', $area_id);
                    });
                }
                if (!is_null($request->territory_id)) {
                    $query->whereHas('client', function ($squery) use ($territory_id) {
                        $squery->where('territory_id', $territory_id);
                    });
                }
                if (!is_null($request->client_id)) {
                    $query->whereIn('client_id', $client_id);
                }
                if (!is_null($request->category_id)) {
                    $query->whereHas('product', function ($squery) use ($category_id) {
                        $squery->whereIn('category_id', $category_id);
                    });
                }
                if (!is_null($request->product_id)) {
                    $query->whereIn('product_id', $product_id);
                }
                if (!is_null($start_date) && !is_null($end_date)) {
                    $query->whereHas('return', function ($squery) use ($start_date, $end_date) {
                        $squery->where('date', '>=', $start_date)->where('date', '<=', $end_date);
                    });
                }
                $data = $query->select(['*', DB::raw('(amount - sales_discount) as amount')])->get();
            } else {
                $report_title = 'Return Summary Report <br> <span class="text-sm">' . date('d-m-Y', strtotime($start_date)) . ' To ' . date('d-m-Y', strtotime($end_date)) . '</span>';
                $query = SalesReturnList::with(['return', 'client', 'client.area', 'product']);

                if (!is_null($request->region_id)) {
                    $query->whereHas('client.area', function ($squery) use ($region_id) {
                        $squery->where('region_id', $region_id);
                    });
                }
                if (!is_null($request->area_id)) {
                    $query->whereHas('client', function ($squery) use ($area_id) {
                        $squery->where('area_id', $area_id);
                    });
                }
                if (!is_null($request->territory_id)) {
                    $query->whereHas('client', function ($squery) use ($territory_id) {
                        $squery->where('territory_id', $territory_id);
                    });
                }
                if (!is_null($request->client_id)) {
                    $query->whereIn('client_id', $client_id);
                }
                if (!is_null($request->category_id)) {
                    $query->whereHas('product', function ($squery) use ($category_id) {
                        $squery->whereIn('category_id', $category_id);
                    });
                }
                if (!is_null($request->product_id)) {
                    $query->whereIn('product_id', $product_id);
                }
                if (!is_null($start_date) && !is_null($end_date)) {
                    $query->whereHas('return', function ($squery) use ($start_date, $end_date) {
                        $squery->where('date', '>=', $start_date)->where('date', '<=', $end_date);
                    });
                }
                if ($report_type == 'product_summary') {
                    $query->select(
                        'product_id',
                        DB::raw('SUM(qty) as total_qty'),
                        DB::raw('SUM(amount-sales_discount) as total_amount')
                    );
                    $query->groupBy('product_id');
                    $data = $query->groupBy('product_id')->get();
                }
                if ($report_type == 'client_summary') {
                    $query->select(
                        'client_id',
                        DB::raw('SUM(qty) as total_qty'),
                        DB::raw('SUM(amount-sales_discount) as total_amount')
                    );
                    $query->groupBy('client_id');
                    $data = $query->groupBy('client_id')->get();
                }
            }
            // return view('admin.reports.return_history.print', compact('title', 'informations', 'report_title', 'report_type', 'data'));
            $pdf = Pdf::loadView('admin.reports.return_history.print', compact('title', 'informations', 'report_title', 'report_type', 'data'));
            return $pdf->stream('return_history' . date('d_m_Y_H_i_s') . '.pdf');
        }

        $filter_link = Route('admin.return-history.index');
        $report_type = $request->report_type;
        $client_id = $request->client_id;
        $region_id = $request->region_id;
        $area_id = $request->area_id;
        $territory_id = $request->territory_id;
        $category_id = $request->category_id;
        $product_id = $request->product_id;
        $date_range = explode('to', $request->date_range);
        $start_date = isset($date_range[0]) ? date('d-m-Y', strtotime($date_range[0])) : NULL;
        $end_date = isset($date_range[1]) ? date('d-m-Y', strtotime($date_range[1])) : NULL;

        $regions = Region::where('status', 1)->orderBy('name', 'asc')->get();
        $areas = Area::where('status', 1)->where('region_id', $region_id)->orderBy('name', 'asc')->get();
        $territories = Territory::where('status', 1)->where('area_id', $area_id)->orderBy('name', 'asc')->get();
        if (!is_null($area_id) || !is_null($territory_id)) {
            $clients = Client::where('status', 1)->where('area_id', $area_id)->orWhere('territory_id', $territory_id)->orderBy('name', 'asc')->get();
        } else {
            $clients = array();
        }
        $categories = Category::where('status', 1)->orderBy('name', 'asc')->get();
        if (!is_null($request->category_id)) {
            $products = Product::where('status', 1)->whereIn('category_id', $category_id)->orderBy('name', 'asc')->get();
        } else {
            $products = Product::where('status', 1)->orderBy('name', 'asc')->get();
        }

        if ($report_type == 'product_summary' || $report_type == 'client_summary') {
            $title = 'Return Summary';
            return $summaryDataTable->render('admin.reports.return_history.index', compact('filter_link', 'title', 'regions', 'areas', 'territories', 'clients', 'categories', 'products', 'report_type', 'client_id', 'region_id', 'area_id', 'territory_id', 'category_id', 'product_id', 'start_date', 'end_date'));
        } else {
            $title = 'Return History';
            return $historyDataTable->render('admin.reports.return_history.index', compact('filter_link', 'title', 'regions', 'areas', 'territories', 'clients', 'categories', 'products', 'report_type', 'client_id', 'region_id', 'area_id', 'territory_id', 'category_id', 'product_id', 'start_date', 'end_date'));
        }
    }

    public function clientDeliveryReport(Request $request)
    {
        $title = "Delivery Report";
        $filter_link = Route('admin.client.delivery_report.index');

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
            'admin.reports.delivery_report.index',
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

    public function clientStatement(Request $request)
    {
        $date_range = explode('to', $request->date_range);
        $start_date = isset($date_range[0]) ? date('Y-m-d', strtotime($date_range[0])) : NULL;
        $end_date = isset($date_range[1]) ? date('Y-m-d', strtotime($date_range[1])) : NULL;

        if ($request->client_id) {
            $client = Client::findOrFail($request->client_id);
            if (!is_null($client->chain_client_id)) {
                $client_id = Client::where('chain_client_id', $client->chain_client_id)->get(['id'])->pluck('id');
            } else {
                $client_id = [$request->client_id];
            }
        }

        if ($request->has('print')) {
            if (Auth::user()->company_id) {
                $company = Company::find(Auth::user()->company_id);
                $title = $company->name;
                $informations = $company->address . '</br>' . $company->phone . ', ' . $company->email . ', ' . $company->website;
            } else {
                $title = 'Company Name Goes Here.';
                $informations = 'Company address will goes here </br> Mobile: 0967XXXXXX, Email: youremail@gmail.com, www.website.com';
            }

            $header_title = 'Client Statement On ' . $start_date . ' To ' . $end_date;
            $previousBalance = ClientStatement::previousBalance($client_id, $start_date);
            $statements = ClientStatement::Statement($client_id, $start_date, $end_date, $previousBalance);
            $data = [
                'previousBalance' => $previousBalance,
                'statements' => $statements,
            ];
            $client = Client::find($request->client_id);
            // return view('admin.reports.client_statement.print', compact('title', 'informations', 'header_title', 'data', 'client'));
            $pdf = Pdf::loadView('admin.reports.client_statement.print', compact('title', 'informations', 'header_title', 'data', 'client'));
            return $pdf->stream('client_statement_' . date('d_m_Y_h_i_s') . '.pdf');
        }

        $data = [];
        if ($request->has('filter')) {
            $previousBalance = ClientStatement::previousBalance($client_id, $start_date);
            $statements = ClientStatement::Statement($client_id, $start_date, $end_date, $previousBalance);
            $data = [
                'previousBalance' => $previousBalance,
                'statements' => $statements,
            ];
        }
        $title = 'Client Statement';
        $clients = Client::where('status', 1)->orderBy('name', 'asc')->get();
        $client_id = $request->client_id;
        $filter_link = Route('admin.client-statement.index');
        return view('admin.reports.client_statement.index', compact('title', 'filter_link', 'clients', 'data', 'client_id', 'start_date', 'end_date'));
    }

    public function deliveryStatement(Request $request, DeliveryStatementDataTable $dataTable)
    {
        if ($request->has('print')) {
            if (Auth::user()->company_id) {
                $company = Company::find(Auth::user()->company_id);
                $title = $company->name;
                $informations = $company->address . '</br>' . $company->phone . ', ' . $company->email . ', ' . $company->website;
            } else {
                $title = 'Company Name Goes Here.';
                $informations = 'Company address will goes here </br> Mobile: 0967XXXXXX, Email: youremail@gmail.com, www.website.com';
            }
            $date_range = explode('to', $request->date_range);
            $start_date = isset($date_range[0]) ? date('Y-m-d', strtotime($date_range[0])) : date('Y-m-01');
            $end_date = isset($date_range[1]) ? date('Y-m-d', strtotime($date_range[1])) : date('Y-m-t');
            $client_id = $request->client_id;
            $client = Client::find($client_id);
            $header_title = 'Delivery Statement On ' . date('d-m-Y', strtotime($start_date)) . ' To ' . date('d-m-Y', strtotime($end_date));

            $query = DeliveryList::with(['sales', 'delivery']);
            if (!is_null($request->client_id)) {
                $query->where('client_id', $client_id);
            }
            if (!is_null($start_date) && !is_null($end_date)) {
                $query->whereHas('delivery', function ($squery) use ($start_date, $end_date) {
                    $squery->where('date', '>=', $start_date)->where('date', '<=', $end_date);
                });
            }
            $data = $query->latest('id')->get();
            // return view('admin.reports.delivery_statement.print', compact('title', 'informations', 'header_title', 'data', 'client'));
            $pdf = Pdf::loadView('admin.reports.delivery_statement.print', compact('title', 'informations', 'header_title', 'data', 'client'));
            return $pdf->stream('delivery_statement_' . date('d_m_Y_H_i_s') . '.pdf');
        }
        $title = 'Delivery Statement';
        $clients = Client::where('status', 1)->orderBy('name', 'asc')->get();
        return $dataTable->render('admin.reports.delivery_statement.index', compact('title', 'clients'));
    }

    public function transferLog(Request $request, TransferLogDataTable $dataTable)
    {
        if ($request->ajax() && $request->has('get_destination_store')) {
            $destination_stores = Store::where('status', 1)->where('id', '!=', $request->host_id)->get();
            return response()->json(['status' => 'success', 'destination_stores' => $destination_stores]);
        }

        if ($request->has('print')) {
            if (Auth::user()->company_id) {
                $company = Company::find(Auth::user()->company_id);
                $title = $company->name;
                $informations = $company->address . '</br>' . $company->phone . ', ' . $company->email . ', ' . $company->website;
            } else {
                $title = 'Company Name Goes Here.';
                $informations = 'Company address will goes here </br> Mobile: 0967XXXXXX, Email: youremail@gmail.com, www.website.com';
            }

            $host_id = $request->host_id;
            $destination_id = $request->destination_id;
            $date_range = explode('to', $request->date_range);
            $start_date = !is_null($request->date_range) ? date('Y-m-d', strtotime($date_range[0])) : date('Y-m-01');
            $end_date = !is_null($request->date_range) ? date('Y-m-d', strtotime($date_range[1])) : date('Y-m-t');
            $report_title = 'Transfer History Report <br> <span class="text-sm">' . date('d-m-Y', strtotime($start_date)) . ' To ' . date('d-m-Y', strtotime($end_date)) . '</span>';

            $query = TransferProduct::with(['transfer', 'product', 'variant']);
            $query->where('product_type', $request->product_type ?? 'Consumer');
            if (!is_null($start_date) && !is_null($end_date)) {
                $query->whereHas('transfer', function ($squery) use ($start_date, $end_date) {
                    $squery->where('date', '>=', $start_date)->where('date', '<=', $end_date);
                });
            }
            $query->whereHas('transfer', function ($squery) use ($host_id, $destination_id) {
                if (!is_null($host_id)) {
                    $squery->where('host_id', $host_id);
                }
                if (!is_null($destination_id)) {
                    $squery->where('destination_id', $destination_id);
                }
            });
            $data = $query->orderBy('id', 'desc')->get();
            // return view('admin.reports.transfer_log.print', compact('title', 'informations', 'report_title', 'data'));
            $pdf = Pdf::loadView('admin.reports.transfer_log.print', compact('title', 'informations', 'report_title', 'data'));
            return $pdf->stream('transfer_log_' . date('d_m_Y_H_i_s') . '.pdf');
        }

        $title = 'Transfer Log';
        $stores = Store::where('status', 1)->get();
        return $dataTable->render('admin.reports.transfer_log.index', compact('title', 'stores'));
    }

    public function stockStatus(Request $request)
    {
        if ($request->ajax()) {
            $query = Product::where('status', 1);
            if (!is_null($request->category_id)) {
                $query->whereIn('category_id', $request->category_id);
            }
            if (!is_null($request->product_type)) {
                $query->where('product_type', $request->product_type);
            }
            $products = $query->orderBy('name', 'asc')->get();
            return response()->json(['status' => 'success', 'products' => $products]);
        }

        $category_id = $request->category_id;
        $product_id = $request->product_id;
        $date_range = explode('to', $request->date_range);
        $start_date = !is_null($request->date_range) ? date('Y-m-d', strtotime($date_range[0])) : date('Y-m-01');
        $end_date = !is_null($request->date_range) ? date('Y-m-d', strtotime($date_range[1])) : date('Y-m-t');
        if ($request->store_id) {
            $store_id = $request->store_id;
            $selected_stores = Store::where('status', 1)->whereIn('id', $store_id)->get(['name']);
        } else {
            $store_id = Store::where('status', 1)->get(['id'])->pluck('id')->toArray();
            $selected_stores = [];
        }

        $data = [];
        if ($request->has('filter')) {
            // $query = DB::table('view_liftings')->whereNotNull('date');
            // $company_id = Auth::user()->company_id;
            // if ($company_id) {
            //     $query->where('company_id', $company_id);
            // }
            // if ($category_id) {
            //     $query->whereIn('category_id', $category_id);
            // }
            // if ($product_id) {
            //     $query->whereIn('product_id', $product_id);
            // }

            // if ($request->product_type == 'Consumer' || is_null($request->product_type)) {
            //     $searched_products = $query
            //     ->groupBy('product_id', 'name', 'code', 'attribute_name')
            //     ->orderBy('name')
            //     ->get([
            //         'product_id',
            //         'name',
            //         'code',
            //         'attribute_name'
            //     ]);
            //     $product_ids = $searched_products->pluck('product_id')->toArray();
            //     $liftings = DB::table('view_liftings')->where('product_type', $request->product_type ?? 'Consumer')->whereIn('store_id', $store_id)->whereIn('product_id', $product_ids)->get();
            //     $lifting_returns = DB::table('view_lifting_returns')->where('product_type', $request->product_type ?? 'Consumer')->whereIn('store_id', $store_id)->whereIn('product_id', $product_ids)->get();
            //     $sales = DB::table('view_sales')->where('product_type', $request->product_type ?? 'Consumer')->whereIn('store_id', $store_id)->whereIn('product_id', $product_ids)->get();
            //     $sales_returns = DB::table('view_sales_returns')->where('product_type', $request->product_type ?? 'Consumer')->whereIn('store_id', $store_id)->whereIn('product_id', $product_ids)->get();
            //     $online_sales = DB::table('view_online_sales')->where('product_type', $request->product_type ?? 'Consumer')->whereIn('status', ['On Route', 'Delivered', 'Collected'])->whereIn('store_id', $store_id)->whereIn('product_id', $product_ids)->get();
            //     $transfer_or_receives = DB::table('view_transfers')->where('product_type', $request->product_type ?? 'Consumer')->whereIn('product_id', $product_ids)->get();


            $query = DB::table('lifting_products')
                    ->leftJoin('products', 'products.id', '=', 'lifting_products.product_id')
                    ->leftJoin('product_prices', 'product_prices.product_id', '=', 'products.id')
                    ->leftJoin('attributes', 'attributes.id', '=', 'products.attribute_id')
                    ->leftJoin('categories', 'categories.id', '=', 'products.category_id')
                    ->leftJoin('product_skus', 'product_skus.id', '=', 'lifting_products.variant_id')
                    ->leftJoin('liftings', 'liftings.id', '=', 'lifting_products.lifting_id')
                    ->whereNotNull('liftings.lifting_date')
                    ->whereNull('liftings.deleted_at')
                    ->whereNull('products.deleted_at')
                    ->where('products.status', 1);

                $company_id = Auth::user()->company_id;

                if ($company_id) {
                    $query->where('liftings.company_id', $company_id);
                }

                if ($category_id) {
                    $query->whereIn('products.category_id', $category_id);
                }

                if ($product_id) {
                    $query->whereIn('lifting_products.product_id', $product_id);
                }

                if ($request->product_type == 'Consumer' || is_null($request->product_type)) {

                    $searched_products = $query
                        ->where('liftings.product_type', $request->product_type ?? 'Consumer')
                        ->groupBy(
                            'lifting_products.product_id',
                            'products.name',
                            'products.code',
                            'attributes.name'
                        )
                        ->orderBy('products.name')
                        ->get([
                            'lifting_products.product_id as product_id',
                            'products.name as name',
                            'products.code as code',
                            'attributes.name as attribute_name'
                        ]);

                    $product_ids = $searched_products->pluck('product_id')->toArray();

                    $liftings = DB::table('lifting_products')
                                ->leftJoin('products', 'products.id', '=', 'lifting_products.product_id')
                                ->leftJoin('product_prices', 'product_prices.product_id', '=', 'products.id')
                                ->leftJoin('attributes', 'attributes.id', '=', 'products.attribute_id')
                                ->leftJoin('categories', 'categories.id', '=', 'products.category_id')
                                ->leftJoin('product_skus', 'product_skus.id', '=', 'lifting_products.variant_id')
                                ->leftJoin('liftings', 'liftings.id', '=', 'lifting_products.lifting_id')
                                ->whereNull('liftings.deleted_at')
                                ->whereNull('products.deleted_at')
                                ->where('products.status', 1)
                                ->whereIn('liftings.store_id', $store_id)
                                ->whereIn('lifting_products.product_id', $product_ids)
                                ->get([
                                    'liftings.company_id as company_id',
                                    'liftings.store_id as store_id',
                                    'liftings.lifting_date as date',
                                    'liftings.product_type as product_type',

                                    'lifting_products.product_id as product_id',

                                    'products.name as name',
                                    'products.code as code',
                                    'products.shared_profit as shared_profit',

                                    'lifting_products.variant_id as variant_id',

                                    'attributes.name as attribute_name',

                                    'categories.id as category_id',
                                    'categories.name as category_name',

                                    'product_prices.sale_price as sale_price',
                                    'product_prices.online_price as online_price',

                                    'product_skus.id as sku_id',
                                    'product_skus.sku as sku',
                                    'product_skus.price as variant_price',

                                    'lifting_products.delivery as qty',

                                    DB::raw('lifting_products.total_amount - lifting_products.discount as amount')
                                ]);
            $lifting_returns = DB::table('lifting_return_lists')
                                ->leftJoin(
                                    'products',
                                    'products.id',
                                    '=',
                                    'lifting_return_lists.product_id'
                                )
                                ->leftJoin(
                                    'product_skus',
                                    'product_skus.id',
                                    '=',
                                    'lifting_return_lists.variant_id'
                                )
                                ->leftJoin(
                                    'lifting_returns',
                                    'lifting_returns.id',
                                    '=',
                                    'lifting_return_lists.lifting_return_id'
                                )
                                ->whereNull('lifting_returns.deleted_at')
                                ->whereNull('products.deleted_at')
                                ->where('products.status', 1)
                                ->where('lifting_returns.product_type', $request->product_type ?? 'Consumer')
                                ->whereIn('lifting_returns.store_id', $store_id)
                                ->whereIn('lifting_return_lists.product_id', $product_ids)
                                ->get([
                                    'lifting_returns.company_id as company_id',
                                    'lifting_returns.store_id as store_id',
                                    'lifting_returns.date as date',
                                    'lifting_returns.product_type as product_type',

                                    'lifting_return_lists.product_id as product_id',

                                    'product_skus.id as sku_id',
                                    'product_skus.sku as sku',

                                    'lifting_return_lists.qty as qty',

                                    DB::raw(
                                        'lifting_return_lists.amount - lifting_return_lists.lifting_discount as amount'
                                    )
                                ]);
                    $sales = DB::table('sales_lists')
                            ->leftJoin(
                                'products',
                                'products.id',
                                '=',
                                'sales_lists.product_id'
                            )
                            ->leftJoin(
                                'categories',
                                'categories.id',
                                '=',
                                'products.category_id'
                            )
                            ->leftJoin(
                                'product_skus',
                                'product_skus.id',
                                '=',
                                'sales_lists.variant_id'
                            )
                            ->leftJoin(
                                'sales',
                                'sales.id',
                                '=',
                                'sales_lists.sales_id'
                            )
                            ->whereNull('sales.deleted_at')
                            ->whereNull('products.deleted_at')
                            ->where('products.status', 1)
                            ->whereIn('sales.store_id', $store_id)
                            ->whereIn('sales_lists.product_id', $product_ids)
                            ->get([
                                'sales.company_id as company_id',
                                'sales.store_id as store_id',
                                'sales.date as date',
                                'sales.product_type as product_type',
                                'sales.sales_type as sales_type',

                                'sales_lists.product_id as product_id',

                                'products.name as name',

                                'categories.id as category_id',
                                'categories.name as category_name',

                                'product_skus.id as sku_id',
                                'product_skus.sku as sku',

                                'sales_lists.delivery as qty',

                                DB::raw(
                                    'sales_lists.amount - sales_lists.discount as amount'
                                ),

                                'sales_lists.returned_qty as returned_qty',
                                'sales_lists.returned_amount as returned_amount'
                            ]);
                            //dd($sales,$product_ids);
                $sales_returns = DB::table('sales_return_lists')
                                ->leftJoin(
                                    'products',
                                    'products.id',
                                    '=',
                                    'sales_return_lists.product_id'
                                )
                                ->leftJoin(
                                    'categories',
                                    'categories.id',
                                    '=',
                                    'products.category_id'
                                )
                                ->leftJoin(
                                    'product_skus',
                                    'product_skus.id',
                                    '=',
                                    'sales_return_lists.variant_id'
                                )
                                ->leftJoin(
                                    'sales_returns',
                                    'sales_returns.id',
                                    '=',
                                    'sales_return_lists.sales_return_id'
                                )
                                ->whereNull('sales_returns.deleted_at')
                                ->where('sales_returns.approve', 1)
                                ->where('sales_returns.reject', 0)
                                ->whereNull('products.deleted_at')
                                ->where('products.status', 1)
                                ->where(
                                    'sales_returns.product_type',
                                    $request->product_type ?? 'Consumer'
                                )
                                ->whereIn('sales_returns.store_id', $store_id)
                                ->whereIn('sales_return_lists.product_id', $product_ids)
                                ->get([
                                    'sales_returns.company_id as company_id',
                                    'sales_returns.store_id as store_id',
                                    'sales_returns.date as date',
                                    'sales_returns.product_type as product_type',

                                    'sales_return_lists.product_id as product_id',

                                    'products.name as name',

                                    'categories.id as category_id',
                                    'categories.name as category_name',

                                    'product_skus.id as sku_id',
                                    'product_skus.sku as sku',

                                    'sales_return_lists.qty as qty',

                                    DB::raw(
                                        'sales_return_lists.amount - sales_return_lists.sales_discount as amount'
                                    )
                                ]);

                $online_sales = DB::table('order_products')
                                ->leftJoin(
                                    'products',
                                    'products.id',
                                    '=',
                                    'order_products.product_id'
                                )
                                ->leftJoin(
                                    'orders',
                                    'orders.id',
                                    '=',
                                    'order_products.order_id'
                                )
                                ->whereNull('orders.deleted_at')
                                ->whereNull('products.deleted_at')
                                ->where('products.status', 1)
                                ->where('products.product_type', $request->product_type ?? 'Consumer')
                                ->whereIn('orders.status', [
                                    'On Route',
                                    'Delivered',
                                    'Collected'
                                ])
                                ->whereIn('orders.store_id', $store_id)
                                ->whereIn('order_products.product_id', $product_ids)
                                ->get([
                                    'order_products.order_id as order_id',
                                    'order_products.id as order_product_id',

                                    'products.company_id as company_id',
                                    'products.product_type as product_type',

                                    'order_products.product_id as product_id',
                                    'order_products.variant_id as sku_id',

                                    'products.category_id as category_id',
                                    'products.name as name',
                                    'products.code as code',

                                    'order_products.quantity as qty',
                                    'order_products.damaged_quantity as damaged_qty',

                                    DB::raw(
                                        'order_products.subtotal
                                        - order_products.return_amount
                                        - order_products.discount as amount'
                                    ),

                                    'orders.date as date',
                                    'orders.collected_at as collected_at',
                                    'orders.store_id as store_id',
                                    'orders.collected as collected',
                                    'orders.status as status',
                                    'orders.is_stock as is_stock'
                                ]);
                $transfer_or_receives = DB::table('transfer_products')
                                        ->leftJoin(
                                            'products',
                                            'products.id',
                                            '=',
                                            'transfer_products.product_id'
                                        )
                                        ->leftJoin(
                                            'categories',
                                            'categories.id',
                                            '=',
                                            'products.category_id'
                                        )
                                        ->leftJoin(
                                            'product_skus',
                                            'product_skus.id',
                                            '=',
                                            'transfer_products.variant_id'
                                        )
                                        ->leftJoin(
                                            'transfers',
                                            'transfers.id',
                                            '=',
                                            'transfer_products.transfer_id'
                                        )
                                        ->whereNull('transfers.deleted_at')
                                        ->where('transfers.reject', 0)
                                        ->where('transfer_products.is_back', 0)
                                        ->whereNull('products.deleted_at')
                                        ->where('products.status', 1)
                                        ->where('transfers.product_type', $request->product_type ?? 'Consumer')
                                        ->whereIn('transfer_products.product_id', $product_ids)
                                        ->get([
                                            'transfers.company_id as company_id',
                                            'transfers.host_id as host_id',
                                            'transfers.destination_id as destination_id',
                                            'transfers.date as date',
                                            'transfers.product_type as product_type',

                                            'transfer_products.product_id as product_id',

                                            'products.name as name',

                                            'categories.id as category_id',
                                            'categories.name as category_name',

                                            'product_skus.id as sku_id',
                                            'product_skus.sku as sku',

                                            'transfer_products.qty as qty'
                                        ]);
            
                }
            if ($request->product_type == 'Fashion') {
                $searched_products = $query->whereNotNull('sku_id')->groupBy('sku_id')->orderBy('name', 'asc')->get(['product_id', 'sku_id', 'sku', 'name']);
                $sku_ids = $searched_products->pluck('sku_id')->toArray();

                $liftings = DB::table('view_liftings')->where('product_type', $request->product_type)->whereIn('store_id', $store_id)->whereIn('sku_id', $sku_ids)->get();
                $lifting_returns = DB::table('view_lifting_returns')->where('product_type', $request->product_type)->whereIn('store_id', $store_id)->whereIn('sku_id', $sku_ids)->get();
                $sales = DB::table('view_sales')->where('product_type', $request->product_type)->whereIn('store_id', $store_id)->whereIn('sku_id', $sku_ids)->get();
                $sales_returns = DB::table('view_sales_returns')->where('product_type', $request->product_type)->whereIn('store_id', $store_id)->whereIn('sku_id', $sku_ids)->get();
                $online_sales = DB::table('view_online_sales')->where('product_type', $request->product_type)->whereIn('status', ['On Route', 'Delivered', 'Collected'])->whereIn('store_id', $store_id)->whereIn('sku_id', $sku_ids)->get();
                $transfer_or_receives = DB::table('view_transfers')->where('product_type', $request->product_type)->whereIn('sku_id', $sku_ids)->get();
            }

            $data = [
                'start_date' => $start_date,
                'end_date' => $end_date,
                'store_id' => $store_id,
                'searched_products' => $searched_products,
                'liftings' => $liftings,
                'lifting_returns' => $lifting_returns,
                'sales' => $sales,
                'sales_returns' => $sales_returns,
                'online_sales' => $online_sales,
                'transfer_or_receives' => $transfer_or_receives,
            ];
          //  dd($data);
        }

        if (!is_null($request->print)) {
            if (Auth::user()->company_id) {
                $company = Company::find(Auth::user()->company_id);
                $title = $company->name;
                $informations = $company->address . '</br>' . $company->phone . ', ' . $company->email . ', ' . $company->website;
            } else {
                $title = 'Company Name Goes Here.';
                $informations = 'Company address will goes here </br> Mobile: 0967XXXXXX, Email: youremail@gmail.com, www.website.com';
            }
            if (count($selected_stores) > 0) {
                $string = '';
                foreach ($selected_stores as $key => $store_item) {
                    $string .= $store_item->name;
                    if ($key > 0) {
                        $string .= ', ';
                    }
                }
                $report_title = 'Stock Status (' . $string . ') <br> <span class="text-sm">' . date('d-m-Y', strtotime($start_date)) . ' To ' . date('d-m-Y', strtotime($end_date)) . '</span>';
            } else {
                $report_title = 'Stock Status (ALL) <br> <span class="text-sm">' . date('d-m-Y', strtotime($start_date)) . ' To ' . date('d-m-Y', strtotime($end_date)) . '</span>';
            }
            // return view('admin.reports.stock_status.print', compact('title', 'informations', 'report_title', 'data'));
            $pdf = Pdf::loadView('admin.reports.stock_status.print', compact('title', 'informations', 'report_title', 'data'));
            $pdf->setPaper('A4', 'landscape');
            return $pdf->stream('stock_status_' . date('d_m_Y_H_i_s') . '.pdf');
        }

        $title = 'Stock Status';
        $categories = Category::where('status', 1)->orderBy('name', 'asc')->get();
        $query = Product::where('status', 1);
        if ($category_id) {
            $query->whereIn('category_id', $category_id);
        }
        if ($request->product_type) {
            $query->where('product_type', $request->product_type);
        } else {
            $query->where('product_type', 'Consumer');
        }
        $products = $query->orderBy('name', 'asc')->get();
        $stores = Store::where('status', 1)->get();
        $store_id = $request->store_id;
        $filter_link = Route('admin.stock-status.index');
        return view('admin.reports.stock_status.index', compact('title', 'filter_link', 'stores', 'categories', 'products', 'data', 'store_id', 'category_id', 'product_id', 'start_date', 'end_date'));
    }

    public function stockValuation(Request $request)
    {
        if ($request->ajax()) {
            if (!is_null($request->category_id)) {
                $products = Product::where('status', 1)->whereIn('category_id', $request->category_id)->orderBy('name', 'asc')->get();
            } else {
                $products = Product::where('status', 1)->orderBy('name', 'asc')->get();
            }
            return response()->json(['status' => 'success', 'products' => $products]);
        }

        $category_id = $request->category_id;
        $product_id = $request->product_id;

        if ($request->store_id) {
            $store_id = $request->store_id;
            $selected_stores = Store::where('status', 1)->whereIn('id', $store_id)->get(['name']);
        } else {
            $store_id = Store::withoutGlobalScope(CompanyScope::class)->where('status', 1)->get(['id'])->pluck('id')->toArray();
            $selected_stores = [];
        }

        $data = [];
        if ($request->has('filter')) {
            $query = DB::table('view_liftings')->whereNotNull('date');
            $company_id = Auth::user()->company_id;
            if ($company_id) {
                $query->where('company_id', $company_id);
            }
            if ($category_id) {
                $query->whereIn('category_id', $category_id);
            }
            if ($product_id) {
                $query->whereIn('product_id', $product_id);
            }
            $product_prices = $query->groupBy('product_id')->orderBy('name', 'asc')->get(['product_id', 'name', 'code', 'attribute_name', 'category_name', 'sale_price', 'online_price']);
            $product_ids = $product_prices->pluck('product_id')->toArray();

            if ($request->product_type == 'Consumer' || is_null($request->product_type)) {
                $searched_products = $query->groupBy('product_id')->orderBy('name', 'asc')->get(['product_id', 'name', 'code', 'attribute_name']);
                $product_ids = $searched_products->pluck('product_id')->toArray();

                $liftings = DB::table('view_liftings')->where('product_type', $request->product_type ?? 'Consumer')->whereIn('store_id', $store_id)->whereIn('product_id', $product_ids)->get();
                $lifting_returns = DB::table('view_lifting_returns')->where('product_type', $request->product_type ?? 'Consumer')->whereIn('store_id', $store_id)->whereIn('product_id', $product_ids)->get();
                $sales = DB::table('view_sales')->where('product_type', $request->product_type ?? 'Consumer')->whereIn('store_id', $store_id)->whereIn('product_id', $product_ids)->get();
                $sales_returns = DB::table('view_sales_returns')->where('product_type', $request->product_type ?? 'Consumer')->whereIn('store_id', $store_id)->whereIn('product_id', $product_ids)->get();
                $online_sales = DB::table('view_online_sales')->where('product_type', $request->product_type ?? 'Consumer')->whereIn('status', ['On Route', 'Delivered', 'Collected'])->whereIn('store_id', $store_id)->whereIn('product_id', $product_ids)->get();
                $transfer_or_receives = DB::table('view_transfers')->where('product_type', $request->product_type ?? 'Consumer')->whereIn('product_id', $product_ids)->get();
            }
            if ($request->product_type == 'Fashion') {
                $searched_products = $query->whereNotNull('sku_id')->groupBy('sku_id')->orderBy('name', 'asc')->get(['product_id', 'sku_id', 'sku', 'name']);
                $sku_ids = $searched_products->pluck('sku_id')->toArray();

                $liftings = DB::table('view_liftings')->where('product_type', $request->product_type)->whereIn('store_id', $store_id)->whereIn('sku_id', $sku_ids)->get();
                $lifting_returns = DB::table('view_lifting_returns')->where('product_type', $request->product_type)->whereIn('store_id', $store_id)->whereIn('sku_id', $sku_ids)->get();
                $sales = DB::table('view_sales')->where('product_type', $request->product_type)->whereIn('store_id', $store_id)->whereIn('sku_id', $sku_ids)->get();
                $sales_returns = DB::table('view_sales_returns')->where('product_type', $request->product_type)->whereIn('store_id', $store_id)->whereIn('sku_id', $sku_ids)->get();
                $online_sales = DB::table('view_online_sales')->where('product_type', $request->product_type)->whereIn('status', ['On Route', 'Delivered', 'Collected'])->whereIn('store_id', $store_id)->whereIn('sku_id', $sku_ids)->get();
                $transfer_or_receives = DB::table('view_transfers')->where('product_type', $request->product_type)->whereIn('sku_id', $sku_ids)->get();
            }

            $data = [
                'product_prices' => $product_prices,
                'store_id' => $store_id,
                'liftings' => $liftings,
                'lifting_returns' => $lifting_returns,
                'sales' => $sales,
                'sales_returns' => $sales_returns,
                'online_sales' => $online_sales,
                'transfer_or_receives' => $transfer_or_receives,
            ];
        }

        if (!is_null($request->print)) {
            if (Auth::user()->company_id) {
                $company = Company::find(Auth::user()->company_id);
                $title = $company->name;
                $informations = $company->address . '</br>' . $company->phone . ', ' . $company->email . ', ' . $company->website;
            } else {
                $title = 'Company Name Goes Here.';
                $informations = 'Company address will goes here </br> Mobile: 0967XXXXXX, Email: youremail@gmail.com, www.website.com';
            }
            if (count($selected_stores) > 0) {
                $string = '';
                foreach ($selected_stores as $key => $store_item) {
                    $string .= $store_item->name;
                    if ($key > 0) {
                        $string .= ', ';
                    }
                }
                $report_title = 'Stock Valuation (' . $string . ')';
            } else {
                $report_title = 'Stock Status (ALL)';
            }
            // return view('admin.reports.stock_valuation.print', compact('title', 'informations', 'report_title', 'data'));
            $pdf = Pdf::loadView('admin.reports.stock_valuation.print', compact('title', 'informations', 'report_title', 'data'));
            $pdf->setPaper('A4', 'landscape');
            return $pdf->stream('stock_valuation_' . date('d_m_Y_H_i_s') . '.pdf');
        }

        $title = 'Stock Valuation';
        $store_id = $request->store_id;
        $categories = Category::where('status', 1)->orderBy('name', 'asc')->get();
        if ($category_id) {
            $products = Product::where('status', 1)->whereIn('category_id', $category_id)->orderBy('name', 'asc')->get();
        } else {
            $products = Product::where('status', 1)->orderBy('name', 'asc')->get();
        }
        $stores = Store::where('status', 1)->get();
        $filter_link = Route('admin.stock-valuation.index');
        return view('admin.reports.stock_valuation.index', compact('title', 'filter_link', 'stores', 'categories', 'products', 'data', 'store_id', 'category_id', 'product_id'));
    }

    public function salesContribution(Request $request)
    {
        $criteria = $request->criteria;
        $date_range = explode('to', $request->date_range);
        $start_date = !is_null($request->date_range) ? date('Y-m-d', strtotime($date_range[0])) : date('Y-m-01');
        $end_date = !is_null($request->date_range) ? date('Y-m-d', strtotime($date_range[1])) : date('Y-m-t');
        $data = [];

        if ($request->has('filter')) {
            if ($request->criteria == "client") {
                $company_id = Auth::user()->company_id;
                $query = DB::table('view_client_sales')->whereNotNull('date')->where('date', '>=', $start_date)->where('date', '<=', $end_date)->orderBy('client_name', 'asc');
                if ($company_id) {
                    $query->where('company_id', $company_id);
                }
                $client_sales = $query->select('*', DB::raw('SUM(amount) as amount'))->groupBy('client_id')->get();
                $client_ids = $client_sales->pluck('client_id')->toArray();
                $client_returns = DB::table('view_client_returns')->whereIn('client_id', $client_ids)->whereNotNull('date')->where('date', '>=', $start_date)->where('date', '<=', $end_date)->select('client_id', DB::raw('SUM(amount) as amount'))->groupBy('client_id')->get();

                $company_id = Auth::user()->company_id;
                $sales = DB::table('view_product_sales')->whereNotNull('date')->where('date', '>=', $start_date)->where('date', '<=', $end_date);
                if ($company_id) {
                    $sales->where('company_id', $company_id);
                }
                $sales = $sales->get();

                $sales_returns = DB::table('view_product_returns')->whereNotNull('date')->where('date', '>=', $start_date)->where('date', '<=', $end_date);
                if ($company_id) {
                    $sales_returns->where('company_id', $company_id);
                }
                $sales_returns = $sales_returns->get();

                $online_sales = DB::table('view_online_sales')->whereNotNull('store_id')->where('date', '>=', $start_date)->where('date', '<=', $end_date);
                if ($company_id) {
                    $online_sales->where('company_id', $company_id);
                }
                $online_sales = $online_sales->get();
                $data = [
                    'client_sales' => $client_sales,
                    'client_returns' => $client_returns,
                    'sales' => $sales,
                    'sales_returns' => $sales_returns,
                    'online_sales' => $online_sales,
                ];
            }

            if ($request->criteria == "product") {
                $company_id = Auth::user()->company_id;
                $sales = DB::table('view_product_sales')->whereNotNull('date')->where('date', '>=', $start_date)->where('date', '<=', $end_date);
                if ($company_id) {
                    $sales->where('company_id', $company_id);
                }
                $sales = $sales->select('*', DB::raw('SUM(qty) as qty'), DB::raw('SUM(amount) as amount'))->groupBy('product_id')->get();

                $sales_returns = DB::table('view_product_returns')->whereNotNull('date')->where('date', '>=', $start_date)->where('date', '<=', $end_date);
                if ($company_id) {
                    $sales_returns->where('company_id', $company_id);
                }
                $sales_returns = $sales_returns->get();

                $online_sales = DB::table('view_online_sales')->whereNotNull('store_id')->where('date', '>=', $start_date)->where('date', '<=', $end_date);
                if ($company_id) {
                    $online_sales->where('company_id', $company_id);
                }
                $online_sales = $online_sales->select('*', DB::raw('SUM(amount) as amount', DB::raw('SUM(qty) as qty')))->groupBy('product_id')->get();

                $data = [
                    'sales' => $sales,
                    'sales_returns' => $sales_returns,
                    'online_sales' => $online_sales,
                ];
            }

            if ($request->criteria == "category") {
                $company_id = Auth::user()->company_id;
                $sales = DB::table('view_product_sales')->whereNotNull('date')->where('date', '>=', $start_date)->where('date', '<=', $end_date);
                if ($company_id) {
                    $sales->where('company_id', $company_id);
                }
                $category_ids = $sales->groupBy('category_id')->pluck('category_id')->toArray();
                $sales = $sales->select('*', DB::raw('SUM(qty) as qty'), DB::raw('SUM(amount) as amount'))->get();

                $sales_returns = DB::table('view_product_returns')->whereNotNull('date')->where('date', '>=', $start_date)->where('date', '<=', $end_date);
                if ($company_id) {
                    $sales_returns->where('company_id', $company_id);
                }
                $sales_returns = $sales_returns->get();

                $online_sales = DB::table('view_online_sales')->whereNotNull('store_id')->where('date', '>=', $start_date)->where('date', '<=', $end_date);
                if ($company_id) {
                    $online_sales->where('company_id', $company_id);
                }
                $category_ids2 = $online_sales->groupBy('category_id')->pluck('category_id')->toArray();
                $online_sales = $online_sales->select('*', DB::raw('SUM(qty) as qty'), DB::raw('SUM(amount) as amount'))->get();

                $data = [
                    'sales' => $sales,
                    'sales_returns' => $sales_returns,
                    'online_sales' => $online_sales,
                ];
            }

            if ($request->criteria == "employee") {
                $company_id = Auth::user()->company_id;
                $sales = DB::table('view_product_sales')->whereNotNull('staff_id')->where('date', '>=', $start_date)->where('date', '<=', $end_date);
                if ($company_id) {
                    $sales->where('company_id', $company_id);
                }
                $sales = $sales->groupBy('staff_id')->select('*', DB::raw('SUM(amount) as amount'))->get();

                $online_sales = DB::table('view_online_sales')->whereNotNull('store_id')->where('date', '>=', $start_date)->where('date', '<=', $end_date);
                if ($company_id) {
                    $online_sales->where('company_id', $company_id);
                }
                $online_sales = $online_sales->sum('amount');

                $total_sales = $sales->sum('amount') +  $online_sales;
                $data = [
                    'sales' => $sales,
                    'online_sales' => $online_sales,
                    'total_sales' => $total_sales,
                ];
            }

            if ($request->criteria == "client_type") {
                $company_id = Auth::user()->company_id;
                $query = DB::table('view_client_sales')->whereNotNull('date')->whereNotNull('client_category_id')->where('date', '>=', $start_date)->where('date', '<=', $end_date)->orderBy('client_category_name', 'asc');
                if ($company_id) {
                    $query->where('company_id', $company_id);
                }
                $cateogry_sales = $query->select('*', DB::raw('SUM(amount) as amount'))->groupBy('client_category_id')->get();
                $client_category_ids = $cateogry_sales->pluck('client_category_id')->toArray();
                $category_returns = DB::table('view_client_returns')->whereIn('client_category_id', $client_category_ids)->whereNotNull('date')->where('date', '>=', $start_date)->where('date', '<=', $end_date)->select('client_category_id', DB::raw('SUM(amount) as amount'))->groupBy('client_id')->get();

                $sales = DB::table('view_product_sales')->whereNotNull('date')->where('date', '>=', $start_date)->where('date', '<=', $end_date);
                if ($company_id) {
                    $sales->where('company_id', $company_id);
                }
                $sales = $sales->get();

                $sales_returns = DB::table('view_product_returns')->whereNotNull('date')->where('date', '>=', $start_date)->where('date', '<=', $end_date);
                if ($company_id) {
                    $sales_returns->where('company_id', $company_id);
                }
                $sales_returns = $sales_returns->get();

                $online_sales = DB::table('view_online_sales')->whereNotNull('store_id')->where('date', '>=', $start_date)->where('date', '<=', $end_date);
                if ($company_id) {
                    $online_sales->where('company_id', $company_id);
                }
                $online_sales = $online_sales->get();
                $data = [
                    'cateogry_sales' => $cateogry_sales,
                    'category_returns' => $category_returns,
                    'sales' => $sales,
                    'sales_returns' => $sales_returns,
                    'online_sales' => $online_sales,
                ];
            }

            if ($request->criteria == "region") {
                $company_id = Auth::user()->company_id;
                $query = DB::table('view_client_sales')->whereNotNull('date')->where('date', '>=', $start_date)->where('date', '<=', $end_date)->orderBy('region_name', 'asc');
                if ($company_id) {
                    $query->where('company_id', $company_id);
                }
                $client_sales = $query->select('*', DB::raw('SUM(amount) as amount'))->groupBy('region_id')->get();
                $client_ids = $client_sales->pluck('client_id')->toArray();
                $client_returns = DB::table('view_client_returns')->whereIn('client_id', $client_ids)->whereNotNull('date')->where('date', '>=', $start_date)->where('date', '<=', $end_date)->select('client_id', DB::raw('SUM(amount) as amount'))->groupBy('client_id')->get();

                $company_id = Auth::user()->company_id;
                $sales = DB::table('view_product_sales')->whereNotNull('date')->where('date', '>=', $start_date)->where('date', '<=', $end_date);
                if ($company_id) {
                    $sales->where('company_id', $company_id);
                }
                $sales = $sales->get();

                $sales_returns = DB::table('view_product_returns')->whereNotNull('date')->where('date', '>=', $start_date)->where('date', '<=', $end_date);
                if ($company_id) {
                    $sales_returns->where('company_id', $company_id);
                }
                $sales_returns = $sales_returns->get();

                $online_sales = DB::table('view_online_sales')->whereNotNull('store_id')->where('date', '>=', $start_date)->where('date', '<=', $end_date);
                if ($company_id) {
                    $online_sales->where('company_id', $company_id);
                }
                $online_sales = $online_sales->get();
                $data = [
                    'client_sales' => $client_sales,
                    'client_returns' => $client_returns,
                    'sales' => $sales,
                    'sales_returns' => $sales_returns,
                    'online_sales' => $online_sales,
                ];
            }

            if ($request->criteria == "area") {
                $company_id = Auth::user()->company_id;
                $query = DB::table('view_client_sales')->whereNotNull('date')->where('date', '>=', $start_date)->where('date', '<=', $end_date)->orderBy('area_name', 'asc');
                if ($company_id) {
                    $query->where('company_id', $company_id);
                }
                $client_sales = $query->select('*', DB::raw('SUM(amount) as amount'))->groupBy('region_id')->get();
                $client_ids = $client_sales->pluck('client_id')->toArray();
                $client_returns = DB::table('view_client_returns')->whereIn('client_id', $client_ids)->whereNotNull('date')->where('date', '>=', $start_date)->where('date', '<=', $end_date)->select('client_id', DB::raw('SUM(amount) as amount'))->groupBy('client_id')->get();

                $company_id = Auth::user()->company_id;
                $sales = DB::table('view_product_sales')->whereNotNull('date')->where('date', '>=', $start_date)->where('date', '<=', $end_date);
                if ($company_id) {
                    $sales->where('company_id', $company_id);
                }
                $sales = $sales->get();

                $sales_returns = DB::table('view_product_returns')->whereNotNull('date')->where('date', '>=', $start_date)->where('date', '<=', $end_date);
                if ($company_id) {
                    $sales_returns->where('company_id', $company_id);
                }
                $sales_returns = $sales_returns->get();

                $online_sales = DB::table('view_online_sales')->whereNotNull('store_id')->where('date', '>=', $start_date)->where('date', '<=', $end_date);
                if ($company_id) {
                    $online_sales->where('company_id', $company_id);
                }
                $online_sales = $online_sales->get();
                $data = [
                    'client_sales' => $client_sales,
                    'client_returns' => $client_returns,
                    'sales' => $sales,
                    'sales_returns' => $sales_returns,
                    'online_sales' => $online_sales,
                ];
            }

            if ($request->criteria == "territory") {
                $company_id = Auth::user()->company_id;
                $query = DB::table('view_client_sales')->whereNotNull('date')->where('date', '>=', $start_date)->where('date', '<=', $end_date)->orderBy('territory_name', 'asc');
                if ($company_id) {
                    $query->where('company_id', $company_id);
                }
                $client_sales = $query->select('*', DB::raw('SUM(amount) as amount'))->groupBy('region_id')->get();
                $client_ids = $client_sales->pluck('client_id')->toArray();
                $client_returns = DB::table('view_client_returns')->whereIn('client_id', $client_ids)->whereNotNull('date')->where('date', '>=', $start_date)->where('date', '<=', $end_date)->select('client_id', DB::raw('SUM(amount) as amount'))->groupBy('client_id')->get();

                $company_id = Auth::user()->company_id;
                $sales = DB::table('view_product_sales')->whereNotNull('date')->where('date', '>=', $start_date)->where('date', '<=', $end_date);
                if ($company_id) {
                    $sales->where('company_id', $company_id);
                }
                $sales = $sales->get();

                $sales_returns = DB::table('view_product_returns')->whereNotNull('date')->where('date', '>=', $start_date)->where('date', '<=', $end_date);
                if ($company_id) {
                    $sales_returns->where('company_id', $company_id);
                }
                $sales_returns = $sales_returns->get();

                $online_sales = DB::table('view_online_sales')->whereNotNull('store_id')->where('date', '>=', $start_date)->where('date', '<=', $end_date);
                if ($company_id) {
                    $online_sales->where('company_id', $company_id);
                }
                $online_sales = $online_sales->get();
                $data = [
                    'client_sales' => $client_sales,
                    'client_returns' => $client_returns,
                    'sales' => $sales,
                    'sales_returns' => $sales_returns,
                    'online_sales' => $online_sales,
                ];
            }
        }

        if (!is_null($request->print)) {
            if (Auth::user()->company_id) {
                $company = Company::find(Auth::user()->company_id);
                $title = $company->name;
                $informations = $company->address . '</br>' . $company->phone . ', ' . $company->email . ', ' . $company->website;
            } else {
                $title = 'Company Name Goes Here.';
                $informations = 'Company address will goes here </br> Mobile: 0967XXXXXX, Email: youremail@gmail.com, www.website.com';
            }
            $report_title = 'Sales Contribution Report <br> <span class="text-sm">' . date('d-m-Y', strtotime($start_date)) . ' To ' . date('d-m-Y', strtotime($end_date)) . '</span>';
            // return view('admin.reports.sales_contribution.print', compact('title', 'informations', 'report_title', 'criteria', 'data'));
            $pdf = Pdf::loadView('admin.reports.sales_contribution.print', compact('title', 'informations', 'report_title', 'criteria', 'data'));
            // $pdf->setPaper('A4', 'landscape');
            return $pdf->stream('sales_contribution_' . date('d_m_Y_H_i_s') . '.pdf');
        }

        $title = 'Sales Contribution';
        $filter_link = Route('admin.sales-contribution.index');
        return view('admin.reports.sales_contribution.index', compact('title', 'filter_link', 'criteria', 'start_date', 'end_date', 'data'));
    }

    public function salesRealization(Request $request)
    {
        if ($request->ajax() && $request->has('get_area')) {
            $region_id = $request->region_id;
            $client_type = $request->client_type;
            $areas = Area::where('region_id', $region_id)->where('status', 1)->orderBy('name', 'asc')->get();
            $query = Client::with(['area'])->where('status', 1);
            if (!is_null($region_id)) {
                $query->whereHas('area', function ($squery) use ($region_id) {
                    $squery->where('region_id', $region_id);
                });
            }
            if (!is_null($request->client_type)) {
                $query->where('client_category_id', $client_type);
            }
            $clients = $query->orderBy('name', 'asc')->get();
            return response()->json(['status' => 'success', 'areas' => $areas, 'clients' => $clients]);
        }
        if ($request->ajax() && $request->has('get_territory')) {
            $area_id = $request->area_id;
            $client_type = $request->client_type;
            $territories = Territory::where('area_id', $area_id)->where('status', 1)->orderBy('name', 'asc')->get();
            $query = Client::with(['area'])->where('status', 1);
            if (!is_null($request->area_id)) {
                $query->where('area_id', $area_id);
            }
            if (!is_null($request->client_type)) {
                $query->where('client_category_id', $client_type);
            }
            $clients = $query->orderBy('name', 'asc')->get();
            return response()->json(['status' => 'success', 'territories' => $territories, 'clients' => $clients]);
        }
        if ($request->ajax() && $request->has('get_clients')) {
            $territory_id = $request->territory_id;
            $client_type = $request->client_type;
            $query = Client::with(['area'])->where('status', 1);
            if (!is_null($request->territory_id)) {
                $query->where('territory_id', $territory_id);
            }
            if (!is_null($request->client_category_id)) {
                $query->where('client_category_id', $client_type);
            }
            $clients = $query->orderBy('name', 'asc')->get();
            return response()->json(['status' => 'success', 'clients' => $clients]);
        }

        $month = !is_null($request->month) ? $request->month : date('m');
        $year = !is_null($request->year) ? $request->year : date('Y');
        $region_id = $request->region_id;
        $area_id = $request->area_id;
        $territory_id = $request->territory_id;
        $client_category_id = $request->client_type;
        $client_id = $request->client_id;

        $query = DB::table('view_client_sales');
        $company_id = Auth::user()->company_id;
        if ($company_id) {
            $query->where('company_id', $company_id);
        }
        if (!is_null($client_category_id)) {
            $query->where('client_category_id', $client_category_id);
        }
        if (!is_null($region_id)) {
            $query->where('region_id', $region_id);
        }
        if (!is_null($area_id)) {
            $query->where('area_id', $area_id);
        }
        if (!is_null($territory_id)) {
            $query->where('territory_id', $territory_id);
        }
        $all_clients = $query->select('client_id as id', 'client_name as name')->groupBy('client_id')->orderBy('client_name', 'asc')->get();
        if ($client_id) {
            $query->whereIn('client_id', $client_id);
        }
        $clients = $query->select('client_id', 'client_name')->whereNotNull('amount')->groupBy('client_id')->orderBy('client_name', 'asc')->get();

        $data = [];
        if ($request->has('filter')) {
            $client_ids = $clients->pluck('client_id')->toArray();
            $client_sales = DB::table('view_client_realization_sales')->whereIn('client_id', $client_ids)->get();
            $client_returns = DB::table('view_client_returns')->whereIn('client_id', $client_ids)->get();
            $client_collections = DB::table('view_client_collections')->whereIn('client_id', $client_ids)->get();
            $data = [
                'clients' => $clients,
                'client_sales' => $client_sales,
                'client_returns' => $client_returns,
                'client_collections' => $client_collections,
                'first_of_month' => Carbon::create($year, $month)->firstOfMonth()->format('Y-m-d'),
                'last_of_month' => Carbon::create($year, $month)->lastOfMonth()->format('Y-m-d'),
                'first_of_year' => Carbon::create($year)->firstOfYear()->format('Y-m-d'),
                'last_of_year' => Carbon::create($year)->lastofYear()->format('Y-m-d'),
                'previous_year' => Carbon::create($year)->firstOfYear()->format('Y-m-d')
            ];
        }

        if (!is_null($request->print)) {
            if (Auth::user()->company_id) {
                $company = Company::find(Auth::user()->company_id);
                $title = $company->name;
                $informations = $company->address . '</br>' . $company->phone . ', ' . $company->email . ', ' . $company->website;
            } else {
                $title = 'Company Name Goes Here.';
                $informations = 'Company address will goes here </br> Mobile: 0967XXXXXX, Email: youremail@gmail.com, www.website.com';
            }

            $report_title = 'Sales Realization For The Month Of ' . date('F', strtotime($month))  . ' - ' . $year;
            // return view('admin.reports.sales_realization.print', compact('title', 'informations', 'report_title', 'month', 'year', 'data'));
            $pdf = Pdf::loadView('admin.reports.sales_realization.print', compact('title', 'informations', 'report_title', 'month', 'year', 'data'));
            $pdf->setPaper('A4', 'landscape');
            return $pdf->stream('sales_realization_' . date('d_m_Y_H_i_s') . '.pdf');
        }

        $title = 'Sales Realization';
        $filter_link = Route('admin.sales-realization.index');
        $regions = Region::where('status', 1)->orderBy('name', 'asc')->get();
        if ($region_id) {
            $areas = Area::where('status', 1)->where('region_id', $region_id)->orderBy('name', 'asc')->get();
        } else {
            $areas = [];
        }
        if ($area_id) {
            $territories = Territory::where('status', 1)->where('area_id', $area_id)->orderBy('name', 'asc')->get();
        } else {
            $territories = [];
        }
        $categories = ClientCategory::where('status', 1)->orderBy('name', 'asc')->get();
        return view('admin.reports.sales_realization.index', compact('title', 'filter_link', 'regions', 'areas', 'territories', 'categories', 'all_clients', 'client_id', 'region_id', 'area_id', 'territory_id', 'client_category_id', 'month', 'year', 'data'));
    }

    public function salesAgeing(Request $request)
    {
        if ($request->ajax() && $request->has('get_area')) {
            $areas = Area::where('region_id', $request->region_id)->where('status', 1)->orderBy('name', 'asc')->get();
            return response()->json(['status' => 'success', 'areas' => $areas]);
        }
        if ($request->ajax() && $request->has('get_territory')) {
            $territories = Territory::where('area_id', $request->area_id)->where('status', 1)->orderBy('name', 'asc')->get();
            return response()->json(['status' => 'success', 'territories' => $territories]);
        }

        $region_id = $request->region_id;
        $area_id = $request->area_id;
        $territory_id = $request->territory_id;

        $data = [];
        if ($request->has('filter')) {
            $one_start_date = Carbon::now()->subDays(0)->format('Y-m-d');
            $one_end_date = Carbon::now()->subDays(30)->format('Y-m-d');
            $two_start_date = Carbon::now()->subDays(31)->format('Y-m-d');
            $two_end_date = Carbon::now()->subDays(60)->format('Y-m-d');
            $three_start_date = Carbon::now()->subDays(61)->format('Y-m-d');
            $three_end_date = Carbon::now()->subDays(90)->format('Y-m-d');
            $over_three_start_date = Carbon::now()->subDays(91)->format('Y-m-d');

            $query = DB::table('view_client_sales');
            $company_id = Auth::user()->company_id;
            if ($company_id) {
                $query->where('company_id', $company_id);
            }
            if (!is_null($region_id)) {
                $query->where('region_id', $region_id);
            }
            if (!is_null($area_id)) {
                $query->where('area_id', $area_id);
            }
            if (!is_null($territory_id)) {
                $query->where('territory_id', $territory_id);
            }
            $clients = $query->select('client_id', 'client_name')->whereNotNull('amount')->groupBy('client_id')->orderBy('client_name', 'asc')->get();
            $client_ids = $clients->pluck('client_id')->toArray();
            $client_sales = DB::table('view_client_sales')->whereIn('client_id', $client_ids)->get();
            $data = [
                'clients' => $clients,
                'client_sales' => $client_sales,
                'one_start_date' => $one_start_date,
                'one_end_date' => $one_end_date,
                'two_start_date' => $two_start_date,
                'two_end_date' => $two_end_date,
                'three_start_date' => $three_start_date,
                'three_end_date' => $three_end_date,
                'over_three_start_date' => $over_three_start_date
            ];
        }

        if (!is_null($request->print)) {
            if (Auth::user()->company_id) {
                $company = Company::find(Auth::user()->company_id);
                $title = $company->name;
                $informations = $company->address . '</br>' . $company->phone . ', ' . $company->email . ', ' . $company->website;
            } else {
                $title = 'Company Name Goes Here.';
                $informations = 'Company address will goes here </br> Mobile: 0967XXXXXX, Email: youremail@gmail.com, www.website.com';
            }

            $report_title = 'Ageing Report';
            // return view('admin.reports.sales_ageing.print', compact('title', 'informations', 'report_title', 'data'));
            $pdf = Pdf::loadView('admin.reports.sales_ageing.print', compact('title', 'informations', 'report_title', 'data'));
            return $pdf->stream('sales_ageing_' . date('d_m_Y_h_i_s') . '.pdf');
        }

        $title = 'Ageing Report';
        $filter_link = Route('admin.sales-ageing.index');
        $regions = Region::where('status', 1)->orderBy('name', 'asc')->get();
        if ($region_id) {
            $areas = Area::where('status', 1)->where('region_id', $region_id)->orderBy('name', 'asc')->get();
        } else {
            $areas = [];
        }
        if ($area_id) {
            $territories = Territory::where('status', 1)->where('area_id', $area_id)->orderBy('name', 'asc')->get();
        } else {
            $territories = [];
        }
        return view('admin.reports.sales_ageing.index', compact('title', 'filter_link', 'regions', 'areas', 'territories', 'region_id', 'area_id', 'territory_id', 'data'));
    }

    public function liftingRealization(Request $request)
    {
        $vendor_id = $request->vendor_id;
        $month = !is_null(request('month')) ? request('month') : date('m');
        $year = !is_null(request('year')) ? request('year') : date('Y');
        $data = [];
        if ($request->has('filter')) {
            $query = Vendor::where('status', 1);
            if (!is_null($vendor_id)) {
                $query->whereIn('id', $vendor_id);
            }
            $vendors = $query->orderBy('name', 'asc')->get();
            foreach ($vendors as $vendor) {
                $realization = new LiftingRealization($vendor->id, $year, $month);
                $payable = $realization->getPreviousBalance() + $realization->getYearlyInfo()['balance'];
                if ($payable > 0) {
                    $data[] = [
                        'vendor' => $vendor,
                        'previousBalance' => $realization->getPreviousBalance(),
                        'year_liftings' => $realization->getYearlyInfo()['lifting'],
                        'year_payments' => $realization->getYearlyInfo()['payment'],
                        'year_return' => $realization->getYearlyInfo()['return'],
                        'year_balance' => $realization->getYearlyInfo()['balance'],
                        'month_liftings' => $realization->getMonthlyInfo()['lifting'],
                        'month_payments' => $realization->getMonthlyInfo()['payment'],
                        'month_return' => $realization->getMonthlyInfo()['return'],
                        'month_balance' => $realization->getMonthlyInfo()['balance'],
                        'payable' => $payable,
                    ];
                }
            }
        }

        if (!is_null($request->print)) {
            if (Auth::user()->company_id) {
                $company = Company::find(Auth::user()->company_id);
                $title = $company->name;
                $informations = $company->address . '</br>' . $company->phone . ', ' . $company->email . ', ' . $company->website;
            } else {
                $title = 'Company Name Goes Here.';
                $informations = 'Company address will goes here </br> Mobile: 0967XXXXXX, Email: youremail@gmail.com, www.website.com';
            }

            $report_title = 'Lifting Payment Summary Till ' . date('F', strtotime($month))  . ' - ' . $year;
            // return view('admin.reports.lifting_realization.print', compact('title', 'informations', 'report_title', 'month', 'year', 'data'));
            $pdf = Pdf::loadView('admin.reports.lifting_realization.print', compact('title', 'informations', 'report_title', 'month', 'year', 'data'));
            $pdf->setPaper('A4', 'landscape');
            return $pdf->stream('lifting_realization_' . date('d_m_Y_H_i_s') . '.pdf');
        }

        $title = 'Lifting Realization';
        $filter_link = Route('admin.lifting-realization.index');
        $vendors = Vendor::where('status', 1)->orderBy('name', 'asc')->get();
        return view('admin.reports.lifting_realization.index', compact('title', 'filter_link', 'vendors', 'vendor_id', 'year', 'month', 'data'));
    }

    public function clientOutstanding(Request $request)
    {
        if ($request->ajax() && $request->has('get_location')) {
            if ($request->distribution == 'region') {
                $locations = Region::where('status', 1)->orderBy('name', 'asc')->get();
                return response()->json(['status' => 'success', 'locations' => $locations]);
            }
            if ($request->distribution == 'area') {
                $locations = Area::where('status', 1)->orderBy('name', 'asc')->get();
                return response()->json(['status' => 'success', 'locations' => $locations]);
            }
            if ($request->distribution == 'territory') {
                $locations = Territory::where('status', 1)->orderBy('name', 'asc')->get();
                return response()->json(['status' => 'success', 'locations' => $locations]);
            }
        }

        $staff_id = $request->staff_id;
        $distribution = $request->distribution;
        $location_id = $request->location_id;
        $date_range = explode('to', $request->date_range);
        $start_date = !is_null($request->date_range) ? date('Y-m-d', strtotime($date_range[0])) : date('Y-m-01');
        $end_date = !is_null($request->date_range) ? date('Y-m-d', strtotime($date_range[1])) : date('Y-m-t');

        $data = [];
        if ($request->has('filter')) {
            $company_id = Auth::user()->company_id;
            $query = DB::table('view_client_sales');
            if ($company_id) {
                $query->where('company_id', $company_id);
            }
            if (!is_null($staff_id)) {
                $query->where('staff_id', $staff_id);
            }
            if ($distribution == 'region' && !is_null($location_id)) {
                $query->where('region_id', $location_id);
            }
            if ($distribution == 'area' && !is_null($location_id)) {
                $query->where('area_id', $location_id);
            }
            if ($distribution == 'territory' && !is_null($location_id)) {
                $query->where('territory_id', $location_id);
            }
            $clients = $query->whereNotNull('date')->groupBy('client_id')->orderBy('client_name', 'asc')->get();
            $client_ids = $clients->pluck('client_id')->toArray();

            $query = DB::table('view_client_realization_sales');
            if ($company_id) {
                $query->where('company_id', $company_id);
            }
            if (!is_null($staff_id)) {
                $query->where('staff_id', $staff_id);
            }
            if ($distribution == 'region' && !is_null($location_id)) {
                $query->where('region_id', $location_id);
            }
            if ($distribution == 'area' && !is_null($location_id)) {
                $query->where('area_id', $location_id);
            }
            if ($distribution == 'territory' && !is_null($location_id)) {
                $query->where('territory_id', $location_id);
            }
            $total_sales = $query->whereNotNull('date')->orderBy('client_name', 'asc')->get();

            // ====== ************************************** ====== //
            $total_returns = DB::table('view_client_returns')->whereIn('client_id', $client_ids)->get();
            $total_collections = DB::table('view_client_collections')->whereIn('client_id', $client_ids)->get();
            // ====== ************************************** ====== //

            $data = [
                'clients' => $clients,
                'opening_sales' => $total_sales->where('date', '<', $start_date),
                'opening_returns' => $total_returns->where('date', '<', $start_date),
                'opening_collections' => $total_collections->where('payment_date', '<', $start_date),
                'sales' => $total_sales->where('date', '>=', $start_date)->where('date', '<=', $end_date),
                'returns' => $total_returns->where('date', '>=', $start_date)->where('date', '<=', $end_date),
                'collections' => $total_collections->where('payment_date', '>=', $start_date)->where('payment_date', '<=', $end_date),
            ];
        }

        if (!is_null($request->print)) {
            if (Auth::user()->company_id) {
                $company = Company::find(Auth::user()->company_id);
                $title = $company->name;
                $informations = $company->address . '</br>' . $company->phone . ', ' . $company->email . ', ' . $company->website;
            } else {
                $title = 'Company Name Goes Here.';
                $informations = 'Company address will goes here </br> Mobile: 0967XXXXXX, Email: youremail@gmail.com, www.website.com';
            }

            $report_title = 'Client Outstanding Ratio';
            // return view('admin.reports.client_outstanding.print', compact('title', 'informations', 'report_title', 'start_date', 'end_date', 'data'));
            $pdf = Pdf::loadView('admin.reports.client_outstanding.print', compact('title', 'informations', 'report_title', 'start_date', 'end_date', 'data'));
            $pdf->setPaper('A4', 'landscape');
            return $pdf->stream('client_outstanding_' . date('d_m_Y_h_i_s') . '.pdf');
        }

        $title = 'Client Outstanding';
        $filter_link = Route('admin.client-outstanding.index');
        $staffs = Staff::where('status', 1)->where('type', 'sales')->orderBy('name', 'asc')->get();
        $distributions = ['region' => 'Region', 'area' => 'Area', 'territory' => 'Territory'];
        $locations = [];
        if (!is_null($distribution) && $distribution == 'region') {
            $locations = Region::where('status', 1)->orderBy('name', 'asc')->get();
        } elseif (!is_null($distribution) && $distribution == 'area') {
            $locations = Area::where('status', 1)->orderBy('name', 'asc')->get();
        } elseif (!is_null($distribution) && $distribution == 'territory') {
            $locations = Territory::where('status', 1)->orderBy('name', 'asc')->get();
        }

        return view('admin.reports.client_outstanding.index', compact('title', 'filter_link', 'staffs', 'distributions', 'locations', 'staff_id', 'distribution', 'location_id', 'start_date', 'end_date', 'data'));
    }

    public function SalesTargetAchievement(Request $request, SalesTargetAchivementDataTable $dataTable)
    {
        if ($request->has('print')) {
            if (Auth::user()->company_id) {
                $company = Company::find(Auth::user()->company_id);
                $title = $company->name;
                $informations = $company->address . '</br>' . $company->phone . ', ' . $company->email . ', ' . $company->website;
            } else {
                $title = 'Company Name Goes Here.';
                $informations = 'Company address will goes here </br> Mobile: 0967XXXXXX, Email: youremail@gmail.com, www.website.com';
            }

            $query = Group::with(['leader', 'members', 'targets']);
            $group_id = $request->group_id;
            if (!is_null($group_id)) {
                $query->whereIn('id', $group_id);
            }
            $month = $request->month;
            $year = $request->year;
            $query->whereHas('targets', function ($squery) use ($month, $year) {
                $squery->where('month', $month)->where('year', $year);
            });
            $data = $query->orderBy('name', 'asc')->get();

            $report_title = 'Sales Target & Achievement';
            // return view('admin.reports.sales_target_achievement.print', compact('title', 'informations', 'report_title', 'month', 'year', 'data'));
            $pdf = Pdf::loadView('admin.reports.sales_target_achievement.print', compact('title', 'informations', 'report_title', 'month', 'year', 'data'));
            return $pdf->stream('sales_target_achievement_' . date('d_m_Y_H_i_s') . '.pdf');
        }

        $title = 'Sales Target & Achievement';
        $groups = Group::orderBy('name')->get();
        return $dataTable->render('admin.reports.sales_target_achievement.index', compact('title', 'groups'));
    }

    public function productWiseProfit(Request $request)
    {
        if ($request->ajax() && $request->has('get_products')) {
            if (!is_null($request->category_id)) {
                $products = Product::whereIn('category_id', $request->category_id)->where('status', 1)->orderBy('name', 'asc')->get();
            } else {
                $products = Product::where('status', 1)->orderBy('name', 'asc')->get();
            }
            return response()->json(['status' => 'success', 'products' => $products]);
        }

        $category_id = $request->category_id;
        $product_id = $request->product_id;
        $date_range = explode('to', $request->date_range);
        $start_date = !is_null($request->date_range) ? date('Y-m-d', strtotime($date_range[0])) : date('Y-m-01');
        $end_date = !is_null($request->date_range) ? date('Y-m-d', strtotime($date_range[1])) : date('Y-m-t');

        $data = [];
        if ($request->has('filter')) {
            $query = DB::table('view_liftings')->whereNotNull('date')
                ->where('date', '>=', $start_date)
                ->where('date', '<=', $end_date);
            $company_id = Auth::user()->company_id;
            if ($company_id) {
                $query->where('company_id', $company_id);
            }
            if ($category_id) {
                $query->whereIn('category_id', $category_id);
            }
            if ($product_id) {
                $query->whereIn('product_id', $product_id);
            }
            $searched_products = $query->groupBy('product_id')->orderBy('name', 'asc')->get(['product_id', 'name', 'code', 'attribute_name', 'category_name']);
            $product_ids = $searched_products->pluck('product_id')->toArray();

            $liftings = DB::table('view_liftings')->whereNotNull('date')->whereIn('product_id', $product_ids)->orderBy('name', 'asc')->get();
            $lifting_returns = DB::table('view_lifting_returns')->whereNotNull('date')->whereIn('product_id', $product_ids)->get();
            $sales = DB::table('view_sales')->whereNotNull('date')->whereIn('product_id', $product_ids)->get();
            $online_sales = DB::table('view_online_sales')->whereNotNull('store_id')->where('status', 'Collected')->whereIn('product_id', $product_ids)->get();
            $sales_returns = DB::table('view_sales_returns')->whereNotNull('date')->whereIn('product_id', $product_ids)->get();

            $data = [
                'start_date' => $start_date,
                'end_date' => $end_date,
                'searched_products' => $searched_products,
                'liftings' => $liftings,
                'lifting_returns' => $lifting_returns,
                'sales' => $sales,
                'sales_returns' => $sales_returns,
                'online_sales' => $online_sales,
            ];
        }

        if (!is_null($request->print)) {
            if (Auth::user()->company_id) {
                $company = Company::find(Auth::user()->company_id);
                $title = $company->name;
                $informations = $company->address . '</br>' . $company->phone . ', ' . $company->email . ', ' . $company->website;
            } else {
                $title = 'Company Name Goes Here.';
                $informations = 'Company address will goes here </br> Mobile: 0967XXXXXX, Email: youremail@gmail.com, www.website.com';
            }

            $report_title = 'Product Wise Profit';
            // return view('admin.reports.product_wise_profit.print', compact('title', 'informations', 'report_title', 'data'));
            $pdf = Pdf::loadView('admin.reports.product_wise_profit.print', compact('title', 'informations', 'report_title', 'data'));
            $pdf->setPaper('A4', 'landscape');
            return $pdf->stream('product_wise_profit_' . date('d_m_Y_h_i_s') . '.pdf');
        }

        $title = 'Product Wise Profit';
        $filter_link = Route('admin.product-wise-profit.index');
        $categories = Category::where('status', 1)->orderBy('name', 'asc')->get();
        $products = Product::where('status', 1)->orderBy('name', 'asc')->get();
        return view('admin.reports.product_wise_profit.index', compact('title', 'filter_link', 'categories', 'products', 'category_id', 'product_id', 'data', 'start_date', 'end_date'));
    }

    public function accessLog(Request $request, AccessLogDataTable $dataTable)
    {
        if ($request->has('print')) {
            if (Auth::user()->company_id) {
                $company = Company::find(Auth::user()->company_id);
                $title = $company->name;
                $informations = $company->address . '</br>' . $company->phone . ', ' . $company->email . ', ' . $company->website;
            } else {
                $title = 'Company Name Goes Here.';
                $informations = 'Company address will goes here </br> Mobile: 0967XXXXXX, Email: youremail@gmail.com, www.website.com';
            }

            $query = AccessLog::with(['user']);
            $user_id = $request->user_id;
            $date_range = explode('to', $request->date_range);
            $start_date = isset($date_range[0]) ? date('Y-m-d', strtotime($date_range[0])) : NULL;
            $end_date = isset($date_range[1]) ? date('Y-m-d', strtotime($date_range[1])) : NULL;
            if (!is_null($user_id)) {
                $query->where('user_id', '>=', $user_id);
                $user = User::find($user_id)->name;
            } else {
                $user = 'All';
            }
            if (!is_null($start_date) && !is_null($end_date)) {
                $query->where('date_time', '>=', $start_date)->where('date_time', '<=', $end_date);
            }
            $data = $query->orderBy('date_time', 'desc')->get();

            $report_title = 'Access Log';
            // return view('admin.reports.access_log.print', compact('title', 'informations', 'report_title', 'data', 'start_date', 'end_date', 'user'));
            $pdf = Pdf::loadView('admin.reports.access_log.print', compact('title', 'informations', 'report_title', 'data', 'start_date', 'end_date', 'user'));
            $pdf->setPaper('A4', 'landscape');
            return $pdf->stream('access_log_' . date('d_m_Y_H_i_s') . '.pdf');
        }

        $title = 'Access Log';
        $query = User::where('status', 1);
        $company_id = Auth::user()->company_id;
        if ($company_id) {
            $query->where('company_id', $company_id);
        }
        $users = $query->orderBy('name', 'asc')->get();
        return $dataTable->render('admin.reports.access_log.index', compact('title', 'users'));
    }

    public function productStatement(Request $request)
    {
        if ($request->ajax() && $request->has('get_variants')) {
            $variants = ProductSku::where('product_id', $request->product_id)->get();
            return response()->json(['status' => 'success', 'variants' => $variants]);
        }

        if ($request->ajax()) {
            $products = Product::where('status', 1)->where('product_type', $request->product_type ?? 'Consumer')->orderBy('name', 'asc')->get();
            return response()->json(['status' => 'success', 'products' => $products]);
        }

        $product_id = $request->product_id;
        $variant_id = $request->variant_id;
        $date_range = explode('to', $request->date_range);
        $start_date = !is_null($request->date_range) ? date('Y-m-d', strtotime($date_range[0])) : date('Y-m-01');
        $end_date = !is_null($request->date_range) ? date('Y-m-d', strtotime($date_range[1])) : date('Y-m-t');

        if ($request->store_id) {
            $store_id = [$request->store_id];
        } else {
            $store_id = Store::where('status', 1)->get(['id'])->pluck('id')->toArray();
        }

        $data = [
            'opening' => 0,
            'statements' => [],
        ];

        if ($request->has('filter')) {
            if ($request->product_type == 'Consumer' || is_null($request->product_type)) {
                $product = Product::findOrFail($product_id);
                $previousBalance = ProductStatement::previousBalance($start_date, $product_id, $store_id, $request->product_type ?? 'Consumer');
                $statements = ProductStatement::Statement($start_date, $end_date, $product_id, $store_id, $previousBalance, $request->product_type ?? 'Consumer');
                $data = [
                    'product' => $product,
                    'opening' => $previousBalance,
                    'statements' => $statements,
                ];
            } else {
                $variant = ProductSku::with('product')->findOrFail($variant_id);
                $previousBalance = ProductStatement::previousBalance($start_date, $variant_id, $store_id, $request->product_type ?? 'Consumer');
                $statements = ProductStatement::Statement($start_date, $end_date, $variant_id, $store_id, $previousBalance, $request->product_type ?? 'Consumer');
                $data = [
                    'variant' => $variant,
                    'opening' => $previousBalance,
                    'statements' => $statements,
                ];
            }
        }

        if (!is_null($request->print)) {
            if (Auth::user()->company_id) {
                $company = Company::find(Auth::user()->company_id);
                $title = $company->name;
                $informations = $company->address . '</br>' . $company->phone . ', ' . $company->email . ', ' . $company->website;
            } else {
                $title = 'Company Name Goes Here.';
                $informations = 'Company address will goes here </br> Mobile: 0967XXXXXX, Email: youremail@gmail.com, www.website.com';
            }
            $report_title = 'Product Statement Report <br> <span class="text-sm">' . date('d-m-Y', strtotime($start_date)) . ' To ' . date('d-m-Y', strtotime($end_date)) . '</span>';
            // return view('admin.reports.product_statement.print', compact('title', 'informations', 'report_title', 'data'));
            $pdf = Pdf::loadView('admin.reports.product_statement.print', compact('title', 'informations', 'report_title', 'data'));
            return $pdf->stream('product_statement_' . date('d_m_Y_H_i_s') . '.pdf');
        }

        $title = 'Product Statement';
        $products = Product::where('status', 1)->where('product_type', $request->product_type ?? 'Consumer')->orderBy('name', 'asc')->get();
        $stores = Store::where('status', 1)->get();
        $store_id = $request->store_id;
        $filter_link = Route('admin.product-statement.index');
        return view('admin.reports.product_statement.index', compact('title', 'filter_link', 'stores', 'products', 'data', 'store_id', 'product_id', 'start_date', 'end_date'));
    }

    public function salesManFlowChart(Request $request)
    {
        if (!is_null($request->date_range)) {
            $date_range = explode('to', $request->date_range);
        }
        $start_date = isset($date_range[0]) ? date('Y-m-d', strtotime($date_range[0])) : date('Y-m-01');
        $end_date = isset($date_range[1]) ? date('Y-m-d', strtotime($date_range[1])) : date('Y-m-t');

        $sales = 0;
        $collections = 0;
        $staff_id = $request->staff_id;

        // Dealer Chart
        $total_sales_collections = [];
        if ($staff_id) {
            $dateRange = CarbonPeriod::create($start_date, $end_date);
            foreach ($dateRange as $date) {
                $date = $date->format('Y-m-d');
                $total_sales = Sales::where('staff_id', $staff_id)->where('date', $date)->sum(DB::raw('total_amount - discount'));
                $total_collection = Collection::where('staff_id', $staff_id)->where('payment_date', $date)->where('collection_type', '!=', 'adjust')->sum('amount');

                $info = [
                    'total_sales' => number_format($total_sales, 2, '.', ''),
                    'total_collection' => number_format($total_collection, 2, '.', ''),
                    'date' => date('d-m', strtotime($date)),
                ];

                $total_sales_collections[] = $info;
                $sales += $total_sales;
                $collections += $total_collection;
            }
        }

        $title = 'Sales Man Performance Flow';
        $staffs = Staff::where('status', 1)->where('type', 'sales')->orderBy('name', 'asc')->get();
        $filter_link = Route('admin.salesman-flowchart.index');
        return view('admin.reports.salesman_flowchart.index', compact('title', 'filter_link', 'staffs', 'staff_id', 'start_date', 'end_date', 'sales', 'collections', 'total_sales_collections'));
    }

    public function clientSalesFlow(Request $request)
    {
        if (!is_null($request->date_range)) {
            $date_range = explode('to', $request->date_range);
        }
        $start_date = isset($date_range[0]) ? date('Y-m-d', strtotime($date_range[0])) : date('Y-m-01');
        $end_date = isset($date_range[1]) ? date('Y-m-d', strtotime($date_range[1])) : date('Y-m-t');

        $sales = 0;
        $collections = 0;
        $client_id = $request->client_id;

        // Dealer Chart
        $total_sales_collections = [];
        if ($client_id) {
            $dateRange = CarbonPeriod::create($start_date, $end_date);
            foreach ($dateRange as $date) {
                $date = $date->format('Y-m-d');
                $total_sales = Sales::where('client_id', $client_id)->where('date', $date)->sum(DB::raw('total_amount - discount'));
                $total_collection = Collection::where('client_id', $client_id)->where('payment_date', $date)->where('collection_type', '!=', 'adjust')->sum('amount');

                $info = [
                    'total_sales' => number_format($total_sales, 2, '.', ''),
                    'total_collection' => number_format($total_collection, 2, '.', ''),
                    'date' => date('d-m', strtotime($date)),
                ];

                $total_sales_collections[] = $info;
                $sales += $total_sales;
                $collections += $total_collection;
            }
        }

        $title = 'Client Sales Flow';
        $clients = Client::where('status', 1)->orderBy('name', 'asc')->get();
        $filter_link = Route('admin.client-sales-flow.index');
        return view('admin.reports.client_sales_flow.index', compact('title', 'filter_link', 'clients', 'client_id', 'start_date', 'end_date', 'sales', 'collections', 'total_sales_collections'));
    }

    public function onlineSalesHistory(Request $request, OnlineSalesHistoryDataTable $dataTable, OnlineSalesSummaryDataTable $summaryDatatable)
    {
        if ($request->ajax() && $request->has('get_products')) {
            if (!is_null($request->category_id)) {
                $query = Product::query();
                $query->whereIn('category_id', $request->category_id);
                $products = $query->where('status', 1)->orderBy('name', 'asc')->get();
            } else {
                $products = Product::where('status', 1)->orderBy('name', 'asc')->get();
            }
            return response()->json(['status' => 'success', 'products' => $products]);
        }

        $date_range = explode('to', $request->date_range);
        $start_date = !is_null($request->date_range) ? date('Y-m-d', strtotime($date_range[0])) : date('Y-m-01');
        $end_date = !is_null($request->date_range) ? date('Y-m-d', strtotime($date_range[1])) : date('Y-m-t');
        $product_id = $request->product_id;
        $report_type = $request->report_type;
        $status = $request->status;
        $area_id = $request->area_id;
        $created_by = $request->created_by;
        $store_id = $request->store_id;

        if (!is_null($request->print)) {
            if (Auth::user()->company_id) {
                $company = Company::find(Auth::user()->company_id);
                $title = $company->name;
                $informations = $company->address . '</br>' . $company->phone . ', ' . $company->email . ', ' . $company->website;
            } else {
                $title = 'Company Name Goes Here.';
                $informations = 'Company address will goes here </br> Mobile: 0967XXXXXX, Email: youremail@gmail.com, www.website.com';
            }

            if ($request->report_type == 'history') {
                $report_title = 'Online Sales History On ' . date('d-m-Y', strtotime($start_date)) . ' To ' . date('d-m-Y', strtotime($end_date));

                $query = OrderProduct::with(['order', 'product']);
                $query->whereHas('order', function ($q) use ($status, $area_id, $created_by, $start_date, $end_date, $store_id) {
                    if (is_array(Auth::user()->stores) && count(Auth::user()->stores)) {
                        $q->whereIn('store_id', Auth::user()->stores);
                    }
                    if (!is_null($store_id)) {
                        $q->where('store_id', $store_id);
                    }
                    if (is_array($status) && count($status) > 0) {
                        $q->whereIn('status', $status);
                    }
                    if (is_array($area_id) && count($area_id) > 0) {
                        $q->whereIn('area_id', $area_id);
                    }
                    if (Auth::user()->hasRole('Moderator')) {
                        $q->where('created_by', Auth::user()->id);
                    } else {
                        if (is_array($created_by) && count($created_by) > 0) {
                            $q->whereIn('created_by', $created_by);
                        }
                    }
                    $q->where('date', '>=', $start_date)->where('date', '<=', $end_date);
                });
                if (!is_null($request->product_id)) {
                    $query->whereIn('product_id', $product_id);
                }
                $data = $query->orderBy('id', 'desc')->get();
                // return view('admin.reports.online_sales_history.print', compact('title', 'informations', 'report_title', 'report_type', 'data'));
                $pdf = Pdf::loadView('admin.reports.online_sales_history.print', compact('title', 'informations', 'report_title', 'report_type', 'data'));
                $pdf->setPaper('A4', 'landscape');
                return $pdf->stream('online_sales_history_' . date('d_m_Y_H_i_s') . '.pdf');
            } else {
                $report_title = 'Online Sales Summary On ' . date('d-m-Y', strtotime($start_date)) . ' To ' . date('d-m-Y', strtotime($end_date));

                $query = OrderProduct::with(['order', 'product']);
                $query->whereHas('order', function ($q) use ($status, $area_id, $created_by, $start_date, $end_date, $store_id) {
                    if (!is_null($store_id)) {
                        $q->where('store_id', $store_id);
                    }
                    if (is_array($status) && count($status) > 0) {
                        $q->whereIn('status', $status);
                    }
                    if (is_array($area_id) && count($area_id) > 0) {
                        $q->whereIn('area_id', $area_id);
                    }
                    if (Auth::user()->hasRole('Moderator')) {
                        $q->where('created_by', Auth::user()->id);
                    } else {
                        if (is_array($created_by) && count($created_by) > 0) {
                            $q->whereIn('created_by', $created_by);
                        }
                    }
                    $q->where('date', '>=', $start_date)->where('date', '<=', $end_date);
                });
                if (!is_null($request->product_id)) {
                    $query->whereIn('product_id', $product_id);
                }
                $data = $query->groupBy('product_id')->select(['product_id', DB::raw('SUM(subtotal) as total_amount'), DB::raw('SUM(order_products.quantity) as total_qty')])->get();
                // return view('admin.reports.online_sales_history.print', compact('title', 'informations', 'report_title', 'report_type', 'data'));
                $pdf = Pdf::loadView('admin.reports.online_sales_history.print', compact('title', 'informations', 'report_title', 'report_type', 'data'));
                return $pdf->stream('online_sales_summary_' . date('d_m_Y_H_i_s') . '.pdf');
            }
        }

        $filter_link = Route('admin.online-sales-history.index');
        $areas = Area::where('status', 1)->orderBy('name', 'asc')->get();
        $products = Product::where('status', 1)->orderBy('name', 'asc')->get();
        $stores = Store::where('status', 1)->orderBy('name', 'asc')->get();

        $report_type = $request->report_type;
        if ($report_type == 'product_summary') {
            $title = 'Online Sales Summary Report';
            return $summaryDatatable->render('admin.reports.online_sales_history.index', compact('title', 'filter_link', 'products', 'areas', 'stores', 'start_date', 'end_date', 'product_id', 'report_type'));
        } else {
            $title = 'Online Sales History Report';
            return $dataTable->render('admin.reports.online_sales_history.index', compact('title', 'filter_link', 'products', 'areas', 'stores', 'start_date', 'end_date', 'product_id', 'report_type'));
        }
    }

    public function coaList(Request $request, CoaListDataTable $dataTable)
    {
        if ($request->ajax() && $request->has('getHeads')) {
            $value = $request->head_type;
            if ($value == 'gl') {
                $heads = CoaSetup::where('general', 1)->orderBy('head_name', 'asc')->get();
            } else {
                $heads = CoaSetup::whereNull('parent_id')->orderBy('head_name', 'asc')->get();
            }
            return response()->json(['status' => 'success', 'heads' => $heads]);
        }

        if (!is_null($request->print)) {
            if (Auth::user()->company_id) {
                $company = Company::find(Auth::user()->company_id);
                $title = $company->name;
                $informations = $company->address . '</br>' . $company->phone . ', ' . $company->email . ', ' . $company->website;
            } else {
                $title = 'Company Name Goes Here.';
                $informations = 'Company address will goes here </br> Mobile: 0967XXXXXX, Email: youremail@gmail.com, www.website.com';
            }
            $query = CoaSetup::query();
            $parent_head = $request->parent_head;
            if ($parent_head) {
                $query->where('head_code', 'LIKE', $parent_head . '%')->where('transaction', 1);
            }
            $data = $query->orderBy('head_name', 'asc')->get();

            $report_title = 'Chart of Accounts';
            // return view('admin.reports.coa_list.print', compact('title', 'informations', 'report_title', 'data'));
            $pdf = Pdf::loadView('admin.reports.coa_list.print', compact('title', 'informations', 'report_title', 'data'));
            $pdf->setPaper('A4');
            return $pdf->stream('coa_list_' . date('d_m_Y_h_i_s') . '.pdf');
        }

        $title = 'Chart of Accounts';
        $filter_link = Route('admin.coa-list.index');
        return $dataTable->render('admin.reports.coa_list.index', compact('title', 'filter_link'));
    }

    public function voucherList(Request $request, voucherListDataTable $dataTable)
    {
        if ($request->ajax() && $request->has('getHeads')) {
            $value = $request->head_type;
            if ($value == 'gl') {
                $heads = CoaSetup::where('general', 1)->orderBy('head_name', 'asc')->get();
            } else {
                $heads = CoaSetup::whereNull('parent_id')->orderBy('head_name', 'asc')->get();
            }
            return response()->json(['status' => 'success', 'heads' => $heads]);
        }

        if (!is_null($request->print)) {
            if (Auth::user()->company_id) {
                $company = Company::find(Auth::user()->company_id);
                $title = $company->name;
                $informations = $company->address . '</br>' . $company->phone . ', ' . $company->email . ', ' . $company->website;
            } else {
                $title = 'Company Name Goes Here.';
                $informations = 'Company address will goes here </br> Mobile: 0967XXXXXX, Email: youremail@gmail.com, www.website.com';
            }
            $query = AccountTransaction::query();
            $voucher_type = $request->voucher_type;
            $date_range = explode('to', $request->date_range);
            $start_date = !is_null($request->date_range) ? date('Y-m-d', strtotime($date_range[0])) : date('Y-m-01');
            $end_date = !is_null($request->date_range) ? date('Y-m-d', strtotime($date_range[1])) : date('Y-m-t');
            if (!is_null($voucher_type)) {
                $query->where('voucher_type', $voucher_type);
            }
            if (!is_null($start_date) && !is_null($end_date)) {
                $query->where('voucher_date', '>=', $start_date)->where('voucher_date', '<=', $end_date);
            }

            $data = $query->select('*', DB::raw('SUM(debit_amount) as amount'))->orderBY('id', 'desc')
                ->orderBY('voucher_date', 'desc')
                ->groupBy('voucher_no')->get();

            $report_title = 'Voucher List';
            // return view('admin.reports.voucher_list.print', compact('title', 'informations', 'report_title', 'data'));
            $pdf = Pdf::loadView('admin.reports.voucher_list.print', compact('title', 'informations', 'report_title', 'data'));
            $pdf->setPaper('A4', 'landscape');
            return $pdf->stream('voucher_list_' . date('d_m_Y_h_i_s') . '.pdf');
        }

        $title = 'Voucher List';
        $filter_link = Route('admin.voucher-list.index');
        $voucher_types = AccountTransaction::groupBy('voucher_type')->orderBy('voucher_type', 'asc')->get();
        return $dataTable->render('admin.reports.voucher_list.index', compact('title', 'filter_link', 'voucher_types'));
    }

    public function cashBook(Request $request)
    {
        $previousBalance = 0;
        $data = array();
        $coa_setup_id = $request->coa_setup_id;
        $date_range = explode('to', $request->date_range);
        $start_date = !is_null($request->date_range) ? date('Y-m-d', strtotime($date_range[0])) : date('Y-m-01');
        $end_date = !is_null($request->date_range) ? date('Y-m-d', strtotime($date_range[1])) : date('Y-m-t');
        if ($request->has('filter')) {
            if (!is_null($request->coa_setup_id)) {
                $previousBalance = AccountTransaction::where('coa_setup_id', $request->coa_setup_id)->where('voucher_date', '<', $start_date)->sum(DB::raw('debit_amount - credit_amount'));
                $data = AccountTransaction::where('coa_setup_id', $request->coa_setup_id)->where('voucher_date', '>=', $start_date)->where('voucher_date', '<=', $end_date)->orderBy('voucher_date', 'asc')->get();
            }
        }

        if (!is_null($request->print)) {
            if (Auth::user()->company_id) {
                $company = Company::find(Auth::user()->company_id);
                $title = $company->name;
                $informations = $company->address . '</br>' . $company->phone . ', ' . $company->email . ', ' . $company->website;
            } else {
                $title = 'Company Name Goes Here.';
                $informations = 'Company address will goes here </br> Mobile: 0967XXXXXX, Email: youremail@gmail.com, www.website.com';
            }

            $report_title = 'Cash book Report <br> <span class="text-sm">' . date('d-m-Y', strtotime($start_date)) . ' To ' . date('d-m-Y', strtotime($end_date)) . '</span>';
            // return view('admin.reports.cash_book.print', compact('title', 'informations', 'report_title', 'previousBalance', 'data'));
            $pdf = Pdf::loadView('admin.reports.cash_book.print', compact('title', 'informations', 'report_title', 'previousBalance', 'data'));
            $pdf->setPaper('A4', 'landscape');
            return $pdf->stream('cash_book_' . date('d_m_Y_h_i_s') . '.pdf');
        }

        $title = 'Cash Book';
        $filter_link = Route('admin.cash-book.index');
        $coas = CoaSetup::where('transaction', 1)->where('head_code', 'LIKE', '10102%')->orderBy('head_name', 'asc')->get();
        return view('admin.reports.cash_book.index', compact('title', 'filter_link', 'coas', 'coa_setup_id', 'start_date', 'end_date', 'previousBalance', 'data'));
    }

    public function bankBook(Request $request)
    {
        $previousBalance = 0;
        $data = array();
        $coa_setup_id = $request->coa_setup_id;
        $date_range = explode('to', $request->date_range);
        $start_date = !is_null($request->date_range) ? date('Y-m-d', strtotime($date_range[0])) : date('Y-m-01');
        $end_date = !is_null($request->date_range) ? date('Y-m-d', strtotime($date_range[1])) : date('Y-m-t');
        if ($request->has('filter')) {
            if (!is_null($request->coa_setup_id)) {
                $previousBalance = AccountTransaction::where('coa_setup_id', $request->coa_setup_id)->where('voucher_date', '<', $start_date)->sum(DB::raw('debit_amount - credit_amount'));
                $data = AccountTransaction::where('coa_setup_id', $request->coa_setup_id)->where('voucher_date', '>=', $start_date)->where('voucher_date', '<=', $end_date)->orderBy('voucher_date', 'asc')->get();
            }
        }

        if (!is_null($request->print)) {
            if (Auth::user()->company_id) {
                $company = Company::find(Auth::user()->company_id);
                $title = $company->name;
                $informations = $company->address . '</br>' . $company->phone . ', ' . $company->email . ', ' . $company->website;
            } else {
                $title = 'Company Name Goes Here.';
                $informations = 'Company address will goes here </br> Mobile: 0967XXXXXX, Email: youremail@gmail.com, www.website.com';
            }

            $report_title = 'Bank book Report <br> <span class="text-sm">' . date('d-m-Y', strtotime($start_date)) . ' To ' . date('d-m-Y', strtotime($end_date)) . '</span>';
            // return view('admin.reports.bank_book.print', compact('title', 'informations', 'report_title', 'previousBalance', 'data'));
            $pdf = Pdf::loadView('admin.reports.bank_book.print', compact('title', 'informations', 'report_title', 'previousBalance', 'data'));
            $pdf->setPaper('A4', 'landscape');
            return $pdf->stream('bank_book_' . date('d_m_Y_h_i_s') . '.pdf');
        }

        $title = 'Bank Book';
        $filter_link = Route('admin.bank-book.index');
        $coas = CoaSetup::where('transaction', 1)->where('head_code', 'LIKE', '10103%')->orderBy('head_name', 'asc')->get();
        return view('admin.reports.bank_book.index', compact('title', 'filter_link', 'coas', 'coa_setup_id', 'start_date', 'end_date', 'previousBalance', 'data'));
    }

    public function transactionLedger(Request $request)
    {
        $previousBalance = 0;
        $data = array();
        $coa_setup_id = $request->coa_setup_id;
        $date_range = explode('to', $request->date_range);
        $start_date = !is_null($request->date_range) ? date('Y-m-d', strtotime($date_range[0])) : date('Y-m-01');
        $end_date = !is_null($request->date_range) ? date('Y-m-d', strtotime($date_range[1])) : date('Y-m-t');
        if ($request->has('filter')) {
            if (!is_null($request->coa_setup_id)) {
                $previousBalance = AccountTransaction::where('coa_setup_id', $request->coa_setup_id)->where('voucher_date', '<', $start_date)->sum(DB::raw('debit_amount - credit_amount'));
                $data = AccountTransaction::where('coa_setup_id', $request->coa_setup_id)->where('voucher_date', '>=', $start_date)->where('voucher_date', '<=', $end_date)->orderBy('voucher_date', 'asc')->get();
            }
        }

        if (!is_null($request->print)) {
            if (Auth::user()->company_id) {
                $company = Company::find(Auth::user()->company_id);
                $title = $company->name;
                $informations = $company->address . '</br>' . $company->phone . ', ' . $company->email . ', ' . $company->website;
            } else {
                $title = 'Company Name Goes Here.';
                $informations = 'Company address will goes here </br> Mobile: 0967XXXXXX, Email: youremail@gmail.com, www.website.com';
            }

            $report_title = 'Transaction Ledger Report <br> <span class="text-sm">' . date('d-m-Y', strtotime($start_date)) . ' To ' . date('d-m-Y', strtotime($end_date)) . '</span>';
            // return view('admin.reports.transaction_ledger.print', compact('title', 'informations', 'report_title', 'previousBalance', 'data'));
            $pdf = Pdf::loadView('admin.reports.transaction_ledger.print', compact('title', 'informations', 'report_title', 'previousBalance', 'data'));
            $pdf->setPaper('A4', 'landscape');
            return $pdf->stream('transaction_ledger_' . date('d_m_Y_h_i_s') . '.pdf');
        }

        $title = 'Transaction Ledger';
        $filter_link = Route('admin.transaction-ledger.index');
        $coas = CoaSetup::where('transaction', 1)->orderBy('head_name', 'asc')->get();
        return view('admin.reports.transaction_ledger.index', compact('title', 'filter_link', 'coas', 'coa_setup_id', 'start_date', 'end_date', 'previousBalance', 'data'));
    }

    public function cashFlowStatement(Request $request)
    {
        $data = array();
        $date_range = explode('to', $request->date_range);
        $start_date = !is_null($request->date_range) ? date('Y-m-d', strtotime($date_range[0])) : date('Y-m-01');
        $end_date = !is_null($request->date_range) ? date('Y-m-d', strtotime($date_range[1])) : date('Y-m-t');
        $generalLedgerHeadCash = 10102;
        $generalLedgerHeadBank = 10103;

        if ($request->has('filter')) {
            $data = AccountTransaction::where('voucher_date', '>=', $start_date)->where('voucher_date', '<=', $end_date)->groupBy('voucher_date')->orderBy('voucher_date', 'desc')->get('voucher_date');
        }

        if (!is_null($request->print)) {
            if (Auth::user()->company_id) {
                $company = Company::find(Auth::user()->company_id);
                $title = $company->name;
                $informations = $company->address . '</br>' . $company->phone . ', ' . $company->email . ', ' . $company->website;
            } else {
                $title = 'Company Name Goes Here.';
                $informations = 'Company address will goes here </br> Mobile: 0967XXXXXX, Email: youremail@gmail.com, www.website.com';
            }

            $report_title = 'Cash Flow Statement <br> <span class="text-sm">' . date('d-m-Y', strtotime($start_date)) . ' To ' . date('d-m-Y', strtotime($end_date)) . '</span>';
            // return view('admin.reports.cash_flow_statement.print', compact('title', 'informations', 'report_title', 'data', 'generalLedgerHeadCash', 'generalLedgerHeadBank'));
            $pdf = Pdf::loadView('admin.reports.cash_flow_statement.print', compact('title', 'informations', 'report_title', 'data', 'generalLedgerHeadCash', 'generalLedgerHeadBank'));
            $pdf->setPaper('A4', 'landscape');
            return $pdf->stream('cash_flow_statement_' . date('d_m_Y_h_i_s') . '.pdf');
        }

        $title = 'Cash Flow Statement';
        $filter_link = Route('admin.cash-flow-statement.index');
        return view('admin.reports.cash_flow_statement.index', compact('title', 'filter_link', 'start_date', 'end_date', 'data', 'generalLedgerHeadCash', 'generalLedgerHeadBank'));
    }

    public function generalLedger(Request $request)
    {
        $previousBalance = 0;
        $data = array();
        $coa_head_code = $request->coa_head_code;
        $date_range = explode('to', $request->date_range);
        $start_date = !is_null($request->date_range) ? date('Y-m-d', strtotime($date_range[0])) : date('Y-m-01');
        $end_date = !is_null($request->date_range) ? date('Y-m-d', strtotime($date_range[1])) : date('Y-m-t');
        if ($request->has('filter')) {
            if (!is_null($request->coa_head_code)) {
                $previousBalance = AccountTransaction::where('coa_head_code', 'LIKE', $request->coa_head_code . '%')->where('voucher_date', '<', $start_date)->sum(DB::raw('debit_amount - credit_amount'));
                $data = AccountTransaction::where('coa_head_code', 'LIKE', $request->coa_head_code . '%')->where('voucher_date', '>=', $start_date)->where('voucher_date', '<=', $end_date)->orderBy('voucher_date', 'desc')->get();
            }
        }

        if (!is_null($request->print)) {
            if (Auth::user()->company_id) {
                $company = Company::find(Auth::user()->company_id);
                $title = $company->name;
                $informations = $company->address . '</br>' . $company->phone . ', ' . $company->email . ', ' . $company->website;
            } else {
                $title = 'Company Name Goes Here.';
                $informations = 'Company address will goes here </br> Mobile: 0967XXXXXX, Email: youremail@gmail.com, www.website.com';
            }

            $report_title = 'General Ledger Report <br> <span class="text-sm">' . date('d-m-Y', strtotime($start_date)) . ' To ' . date('d-m-Y', strtotime($end_date)) . '</span>';
            // return view('admin.reports.general_ledger.print', compact('title', 'informations', 'report_title', 'previousBalance', 'data'));
            $pdf = Pdf::loadView('admin.reports.general_ledger.print', compact('title', 'informations', 'report_title', 'previousBalance', 'data'));
            $pdf->setPaper('A4', 'landscape');
            return $pdf->stream('general_ledger_' . date('d_m_Y_h_i_s') . '.pdf');
        }

        $title = 'General Ledger';
        $filter_link = Route('admin.general-ledger.index');
        $coas = CoaSetup::where('general', 1)->orderBy('head_name', 'asc')->get();
        return view('admin.reports.general_ledger.index', compact('title', 'filter_link', 'coas', 'coa_head_code', 'start_date', 'end_date', 'previousBalance', 'data'));
    }

    public function incomeStatement(Request $request)
    {
        $date_range = explode('to', $request->date_range);
        $start_date = !is_null($request->date_range) ? date('Y-m-d', strtotime($date_range[0])) : date('Y-m-01');
        $end_date = !is_null($request->date_range) ? date('Y-m-d', strtotime($date_range[1])) : date('Y-m-t');

        $opening_balance = 0;
        $closing_balance = 0;
        $incomes = array();
        $expenses = array();
        if ($request->has('filter')) {
            $query = DB::table('view_liftings');
            $company_id = Auth::user()->company_id;
            if ($company_id) {
                $query->where('company_id', $company_id);
            }
            $store_id = Store::where('status', 1)->get(['id'])->pluck('id')->toArray();
            $product_prices = $query->whereIn('store_id', $store_id)->whereNotNull('date')->groupBy('product_id')->orderBy('name', 'asc')->get(['product_id', 'name', 'code', 'attribute_name', 'category_name', 'sale_price']);
            $product_ids = $product_prices->pluck('product_id')->toArray();

            $liftings = DB::table('view_liftings')->whereIn('store_id', $store_id)->whereNotNull('date')->whereIn('product_id', $product_ids)->orderBy('name', 'asc')->get();
            $lifting_returns = DB::table('view_lifting_returns')->whereNotNull('date')->whereIn('product_id', $product_ids)->whereIn('store_id', $store_id)->get();
            $sales = DB::table('view_sales')->whereNotNull('date')->whereIn('product_id', $product_ids)->whereIn('store_id', $store_id)->get();
            $sales_returns = DB::table('view_sales_returns')->whereNotNull('date')->whereIn('product_id', $product_ids)->whereIn('store_id', $store_id)->get();
            $online_sales = DB::table('view_online_sales')->where('product_type', $request->product_type ?? 'Consumer')->whereIn('status', ['On Route', 'Delivered', 'Collected'])->whereIn('store_id', $store_id)->whereIn('product_id', $product_ids)->get();

            $data = [
                'product_prices' => $product_prices,
                'store_id' => $store_id,
                'liftings' => $liftings,
                'lifting_returns' => $lifting_returns,
                'sales' => $sales,
                'sales_returns' => $sales_returns,
                'online_sales' => $online_sales,
            ];

            foreach ($data['product_prices'] as $key => $row) {
                $liftings = $data['liftings']->where('product_id', $row->product_id)->sum('qty');
                $lifting_amount = $data['liftings']->where('product_id', $row->product_id)->sum('amount');
                $avarage_lifting_price = $lifting_amount / $liftings;

                $opening_liftings = $data['liftings']->where('product_id', $row->product_id)->where('date', '<', $start_date)->sum('qty');
                $opening_lifting_returns = $data['lifting_returns']->where('product_id', $row->product_id)->where('date', '<', $start_date)->sum('qty');
                $opening_sales = $data['sales']->where('product_id', $row->product_id)->where('date', '<', $start_date)->sum('qty');
                $opening_sales_returns = $data['sales_returns']->where('product_id', $row->product_id)->where('date', '<', $start_date)->sum('qty');
                $opening_online_sales = $data['online_sales']->where('product_id', $row->product_id)->where('date', '<', $start_date)->sum('qty');
                $opening = $opening_liftings + $opening_sales_returns - $opening_lifting_returns - $opening_sales - $opening_online_sales;

                $closing_liftings = $data['liftings']->where('product_id', $row->product_id)->where('date', '<=', $end_date)->sum('qty');
                $closing_lifting_returns = $data['lifting_returns']->where('product_id', $row->product_id)->where('date', '<=', $end_date)->sum('qty');
                $closing_sales = $data['sales']->where('product_id', $row->product_id)->where('date', '<=', $end_date)->sum('qty');
                $closing_sales_returns = $data['sales_returns']->where('product_id', $row->product_id)->where('date', '<=', $end_date)->sum('qty');
                $closing_online_sales = $data['online_sales']->where('product_id', $row->product_id)->where('date', '<=', $end_date)->sum('qty');
                $balance = $closing_liftings + $closing_sales_returns - $closing_lifting_returns - $closing_sales - $closing_online_sales;

                $opening_balance += $opening * $avarage_lifting_price;
                $closing_balance += $balance * $avarage_lifting_price;
            }

            $incomes = AccountTransaction::with('coa')
                ->where('voucher_date', '>=', $start_date)
                ->where('voucher_date', '<=', $end_date)
                ->whereHas('coa', function ($query) {
                    $query->where('head_type', 'I');
                })
                ->groupBy('coa_setup_id')
                ->select('coa_head_code', 'coa_setup_id', DB::raw('SUM(debit_amount) as debit_amount'), DB::raw('SUM(credit_amount) as credit_amount'))
                ->get();

            $expenses = AccountTransaction::with('coa')->select('*', DB::raw('SUM(debit_amount - credit_amount) as amount'))
                ->where('voucher_date', '>=', $start_date)
                ->where('voucher_date', '<=', $end_date)
                ->whereHas('coa', function ($query) {
                    $query->where('head_type', 'E')->where('transaction', 1);
                })
                ->groupBy('coa_setup_id')
                ->get();
        }

        if (!is_null($request->print)) {
            if (Auth::user()->company_id) {
                $company = Company::find(Auth::user()->company_id);
                $title = $company->name;
                $informations = $company->address . '</br>' . $company->phone . ', ' . $company->email . ', ' . $company->website;
            } else {
                $title = 'Company Name Goes Here.';
                $informations = 'Company address will goes here </br> Mobile: 0967XXXXXX, Email: youremail@gmail.com, www.website.com';
            }

            $report_title = 'Income Statement Report <br> <span class="text-sm">' . date('d-m-Y', strtotime($start_date)) . ' To ' . date('d-m-Y', strtotime($end_date)) . '</span>';
            // return view('admin.reports.income_statement.print', compact('title', 'informations', 'report_title', 'opening_balance', 'closing_balance', 'incomes', 'expenses'));
            $pdf = Pdf::loadView('admin.reports.income_statement.print', compact('title', 'informations', 'report_title', 'opening_balance', 'closing_balance', 'incomes', 'expenses'));
            $pdf->setPaper('A4', 'landscape');
            return $pdf->stream('income_statement_' . date('d_m_Y_h_i_s') . '.pdf');
        }

        $title = 'Income Statement';
        $filter_link = Route('admin.income-statement.index');
        $print_buttons = '<button type="button" class="btn btn-sm btn-primary text-uppercase getPdf">Print</button>';
        return view('admin.reports.income_statement.index', compact('title', 'filter_link', 'start_date', 'end_date', 'opening_balance', 'closing_balance', 'incomes', 'expenses', 'print_buttons'));
    }

    public function trialBalance(Request $request)
    {
        $coaLists = array();
        $coaLists1 = array();
        $date_range = explode('to', $request->date_range);
        $start_date = !is_null($request->date_range) ? date('Y-m-d', strtotime($date_range[0])) : date('Y-m-01');
        $end_date = !is_null($request->date_range) ? date('Y-m-d', strtotime($date_range[1])) : date('Y-m-t');
        if ($request->has('filter')) {
            $coaLists = TrialBalance::with('coa_setup')->where('voucher_date', '>=', $start_date)->where('voucher_date', '<=', $end_date)->where('transaction', 1)->where('general', 1)->groupBy('coa_setup_id')->select('*', DB::raw('SUM(debit_amount) as debit_amount'), DB::raw('SUM(credit_amount) as credit_amount'))->get();
            $coaLists1 = TrialBalance::with('parent_head')->where('voucher_date', '>=', $start_date)->where('voucher_date', '<=', $end_date)->where('transaction', 1)->where('general', 0)->groupBy('parent_id')->select('*', DB::raw('SUM(debit_amount) as debit_amount'), DB::raw('SUM(credit_amount) as credit_amount'))->get();
        }

        if (!is_null($request->print)) {
            if (Auth::user()->company_id) {
                $company = Company::find(Auth::user()->company_id);
                $title = $company->name;
                $informations = $company->address . '</br>' . $company->phone . ', ' . $company->email . ', ' . $company->website;
            } else {
                $title = 'Company Name Goes Here.';
                $informations = 'Company address will goes here </br> Mobile: 0967XXXXXX, Email: youremail@gmail.com, www.website.com';
            }
            $report_title = 'Trial Balance <br> <span class="text-sm">' . date('d-m-Y', strtotime($start_date)) . ' To ' . date('d-m-Y', strtotime($end_date)) . '</span>';
            $pdf = Pdf::loadView('admin.reports.trial_balance.print', compact('title', 'informations', 'report_title', 'coaLists', 'coaLists1'));
            return $pdf->stream('trial_balance_' . date('d_m_Y_h_i_s') . '.pdf');
        }

        $title = 'Trial Balance';
        $filter_link = Route('admin.trial-balance.index');
        return view('admin.reports.trial_balance.index', compact('title', 'filter_link', 'start_date', 'end_date', 'coaLists', 'coaLists1'));
    }

    public function balanceSheet(Request $request)
    {
        $closing_stock = 0;
        $query = DB::table('view_liftings');
        $company_id = Auth::user()->company_id;
        if ($company_id) {
            $query->where('company_id', $company_id);
        }
        $store_id = Store::where('status', 1)->get(['id'])->pluck('id')->toArray();
        $product_prices = $query->whereIn('store_id', $store_id)->whereNotNull('date')->groupBy('product_id')->orderBy('name', 'asc')->get(['product_id', 'name', 'code', 'attribute_name', 'category_name', 'sale_price']);
        $product_ids = $product_prices->pluck('product_id')->toArray();

        $liftings = DB::table('view_liftings')->whereIn('store_id', $store_id)->whereNotNull('date')->whereIn('product_id', $product_ids)->orderBy('name', 'asc')->get();
        $lifting_returns = DB::table('view_lifting_returns')->whereNotNull('date')->whereIn('product_id', $product_ids)->whereIn('store_id', $store_id)->get();
        $sales = DB::table('view_sales')->whereNotNull('date')->whereIn('product_id', $product_ids)->whereIn('store_id', $store_id)->get();
        $sales_returns = DB::table('view_sales_returns')->whereNotNull('date')->whereIn('product_id', $product_ids)->whereIn('store_id', $store_id)->get();
        $online_sales = DB::table('view_online_sales')->where('product_type', $request->product_type ?? 'Consumer')->whereIn('status', ['On Route', 'Delivered', 'Collected'])->whereIn('store_id', $store_id)->whereIn('product_id', $product_ids)->get();

        $data = [
            'product_prices' => $product_prices,
            'store_id' => $store_id,
            'liftings' => $liftings,
            'lifting_returns' => $lifting_returns,
            'sales' => $sales,
            'sales_returns' => $sales_returns,
            'online_sales' => $online_sales,
        ];

        foreach ($data['product_prices'] as $key => $row) {
            $liftings = $data['liftings']->where('product_id', $row->product_id)->sum('qty');
            $lifting_amount = $data['liftings']->where('product_id', $row->product_id)->sum('amount');
            $avarage_lifting_price = $lifting_amount / $liftings;

            $closing_liftings = $data['liftings']->where('product_id', $row->product_id)->sum('qty');
            $closing_lifting_returns = $data['lifting_returns']->where('product_id', $row->product_id)->sum('qty');
            $closing_sales = $data['sales']->where('product_id', $row->product_id)->sum('qty');
            $closing_sales_returns = $data['sales_returns']->where('product_id', $row->product_id)->sum('qty');
            $closing_online_sales = $data['online_sales']->where('product_id', $row->product_id)->sum('qty');
            $balance = $closing_liftings + $closing_sales_returns - $closing_lifting_returns - $closing_sales - $closing_online_sales;
            $closing_stock += $balance * $avarage_lifting_price;
        }
        $assets = $this->assets('Assets');
        $liabilities = $this->assets('Liabilities');
        $currentPFL = $this->profitLoss($closing_stock);
        $data = [
            'assets' => $assets,
            'liabilities' => $liabilities,
            'currentPFL' => $currentPFL,
            'closing_stock' => $closing_stock,
        ];

        if (!is_null($request->print)) {
            if (Auth::user()->company_id) {
                $company = Company::find(Auth::user()->company_id);
                $title = $company->name;
                $informations = $company->address . '</br>' . $company->phone . ', ' . $company->email . ', ' . $company->website;
            } else {
                $title = 'Company Name Goes Here.';
                $informations = 'Company address will goes here </br> Mobile: 0967XXXXXX, Email: youremail@gmail.com, www.website.com';
            }

            $report_title = 'Balance Sheet';
            // return view('admin.reports.balance_sheet.print', compact('title', 'informations', 'report_title', 'data'));
            $pdf = Pdf::loadView('admin.reports.balance_sheet.print', compact('title', 'informations', 'report_title', 'data'));
            $pdf->setPaper('A4', 'landscape');
            return $pdf->stream('balance_sheet_' . date('d_m_Y_h_i_s') . '.pdf');
        }

        $title = 'Balance Sheet';
        $filter_link = Route('admin.balance-sheet.index');
        $coas = CoaSetup::where('general', 1)->orderBy('head_name', 'asc')->get();
        return view('admin.reports.balance_sheet.index', compact('title', 'filter_link', 'coas', 'data'));
    }

    public function headDetails(Request $request)
    {
        if ($request->has('income_statement')) {
            $title = 'Head Transactions';
            $data = AccountTransaction::with('coa')
                ->where('voucher_date', '>=', $request->start_date)
                ->where('voucher_date', '<=', $request->end_date)
                ->where('coa_setup_id', $request->coa_setup_id)
                ->get();

            if ($request->has('details_print')) {
                if (Auth::user()->company_id) {
                    $company = Company::find(Auth::user()->company_id);
                    $title = $company->name;
                    $informations = $company->address . '</br>' . $company->phone . ', ' . $company->email . ', ' . $company->website;
                } else {
                    $title = 'Company Name Goes Here.';
                    $informations = 'Company address will goes here </br> Mobile: 0967XXXXXX, Email: youremail@gmail.com, www.website.com';
                }

                $report_title = 'Head Transactions Details <br> <span class="text-sm">' . date('d-m-Y', strtotime($request->start_date)) . ' To ' . date('d-m-Y', strtotime($request->end_date)) . '</span>';
                // return view('admin.reports.income_statement.details_print', compact('title', 'informations', 'report_title', 'data'));
                $pdf = Pdf::loadView('admin.reports.income_statement.details_print', compact('title', 'informations', 'report_title', 'data'));
                // $pdf->setPaper('A4', 'landscape');
                return $pdf->stream('transaction_details' . date('d_m_Y_h_i_s') . '.pdf');
            }
            return view('admin.reports.income_statement.details', compact('title', 'data'));
        }

        if ($request->has('balance_sheet')) {
            $title = 'Head Transactions';
            $data = AccountTransaction::with('coa')
                ->where('coa_setup_id', $request->coa_setup_id)
                ->orderBy('voucher_date', 'asc')
                ->orderBy('id', 'asc')
                ->get();

            if ($request->has('details_print')) {
                if (Auth::user()->company_id) {
                    $company = Company::find(Auth::user()->company_id);
                    $title = $company->name;
                    $informations = $company->address . '</br>' . $company->phone . ', ' . $company->email . ', ' . $company->website;
                } else {
                    $title = 'Company Name Goes Here.';
                    $informations = 'Company address will goes here </br> Mobile: 0967XXXXXX, Email: youremail@gmail.com, www.website.com';
                }

                $report_title = 'Head Transactions Details <br> <span class="text-sm">' . date('d-m-Y', strtotime($request->start_date)) . ' To ' . date('d-m-Y', strtotime($request->end_date)) . '</span>';
                // return view('admin.reports.income_statement.details_print', compact('title', 'informations', 'report_title', 'data'));
                $pdf = Pdf::loadView('admin.reports.income_statement.details_print', compact('title', 'informations', 'report_title', 'data'));
                // $pdf->setPaper('A4', 'landscape');
                return $pdf->stream('transaction_details' . date('d_m_Y_h_i_s') . '.pdf');
            }
            return view('admin.reports.income_statement.details', compact('title', 'data'));
        }

        $title = 'Head Transactions';
        $coa_ids = CoaSetup::where('parent_id', $request->id)->pluck('id')->toArray();
        $child_coa_ids = CoaSetup::whereIn('parent_id', $coa_ids)->pluck('id')->toArray();
        $coa_ids = array_merge($coa_ids, $child_coa_ids);
        $data = AccountTransaction::with('coa')->select('*', DB::raw('SUM(debit_amount) as debit_amount'), DB::raw('SUM(credit_amount) as credit_amount'))
            ->whereIn('coa_setup_id', $coa_ids)
            ->groupBy('coa_setup_id')
            ->get();

        if ($request->has('details_print')) {
            if (Auth::user()->company_id) {
                $company = Company::find(Auth::user()->company_id);
                $title = $company->name;
                $informations = $company->address . '</br>' . $company->phone . ', ' . $company->email . ', ' . $company->website;
            } else {
                $title = 'Company Name Goes Here.';
                $informations = 'Company address will goes here </br> Mobile: 0967XXXXXX, Email: youremail@gmail.com, www.website.com';
            }

            $report_title = 'Head Transactions Details';
            // return view('admin.reports.balance_sheet.details_print', compact('title', 'informations', 'report_title', 'data'));
            $pdf = Pdf::loadView('admin.reports.balance_sheet.details_print', compact('title', 'informations', 'report_title', 'data'));
            // $pdf->setPaper('A4', 'landscape');
            return $pdf->stream('transaction_details' . date('d_m_Y_h_i_s') . '.pdf');
        }
        return view('admin.reports.balance_sheet.details', compact('title', 'data'));
    }

    public function viewVoucher(string $id)
    {
        $title = "View Voucher";
        $transaction = AccountTransaction::findOrFail($id);
        $transactions = AccountTransaction::where('voucher_no', $transaction->voucher_no)
            ->where('voucher_type', $transaction->voucher_type)->get();
        return view('admin.reports.income_statement.view')->with(compact('title', 'transaction', 'transactions'));
    }

    public function getAmount($headCode)
    {
        $balance = 0;
        $headReports = TrialBalance::select('*')
            ->where('coa_head_code', 'LIKE', $headCode . '%')
            ->get();

        foreach ($headReports as $headReport) {
            if ($headReport->head_type == 'I' || $headReport->head_type == 'L') {
                $balance += $headReport->credit_amount - $headReport->debit_amount;
            } else {
                $balance += $headReport->debit_amount - $headReport->credit_amount;
            }
        }
        return $balance;
    }

    public function assets($parent_head)
    {
        $parents = CoaSetup::with('parent')->whereHas('parent', function ($query) use ($parent_head) {
            $query->where('head_name', $parent_head);
        })->orderBy('head_name', 'asc')->get();

        $info = [];
        foreach ($parents as $parent) {
            $childs = CoaSetup::select('head_name', 'head_code', 'id')
                ->where('parent_id', $parent->id)
                ->get();
            $childInfo = [];
            foreach ($childs as $child) {
                $childInfo[] = [
                    'id' => $child->id,
                    'headCode' => $child->head_code,
                    'headName' => $parent->head_name,
                    'name' => $child->head_name,
                    'amount' => $this->getAmount($child->head_code)
                ];
            }

            $info[] = [
                'id' => $parent->id,
                'headCode' => $parent->head_code,
                'head' => $parent->head_name,
                'childs' => $childInfo
            ];
        }
        return $info;
    }

    private function profitLoss($closingStockValue)
    {
        $totalIncome = trialBalance::where('head_type', 'I')->sum(DB::raw('credit_amount - debit_amount'));
        $totalExpanse = trialBalance::where('head_type', 'E')->sum(DB::raw('debit_amount - credit_amount'));
        $netIncome = $totalIncome + $closingStockValue;
        return $profitLoss = $netIncome - $totalExpanse;
    }

    public function generateBarcode(Request $request)
    {
        if ($request->isMethod('GET')) {
            $title = 'Generate Barcode';
            $products = Product::where('status', 1)->orderBy('name', 'asc')->get();
            $target_blank = true;
            return view('admin.generate_barcode.create', compact('title', 'products', 'target_blank'));
        }

        ini_set('max_execution_time', '500');
        ini_set("pcre.backtrack_limit", "5000000");
        $quantity = $request->quantity;
        $product_name = $request->product_name;
        $product_code = $request->product_code;

        // return view('admin.generate_barcode.print', compact('quantity', 'product_name', 'product_code'));
        $pdf = PDF::loadView('admin.generate_barcode.print', compact('quantity', 'product_name', 'product_code'));
        return $pdf->stream('barcodes.pdf');
    }

    public function profitLossReport(Request $request)
    {
        $title = 'Invoicewise Profit Loss';
        $filter_link = Route('admin.profit-loss.index');
        $date_range = explode('to', $request->date_range);
        $start_date = !is_null($request->date_range) ? date('Y-m-d', strtotime($date_range[0])) : date('Y-m-01');
        $end_date = !is_null($request->date_range) ? date('Y-m-d', strtotime($date_range[1])) : date('Y-m-t');

        $data = [];
        if ($request->has('filter')) {
            $dates = CarbonPeriod::create($start_date, $end_date)->toArray();

            foreach ($dates as $date) {
                $lineData = [
                    'date' => $date->format('Y-m-d'),
                ];
                $sales = Sales::with(['client', 'list'])->where('date', $date->format('Y-m-d'))->get();
                $summary_sales = 0;
                $summary_discount = 0;
                $summary_payable = 0;
                $summary_cost = 0;
                $summary_gross_profit = 0;

                foreach ($sales as $key => $item) {
                    $total_cost = 0;
                    foreach ($item->list as $product) {
                        $lifting = LiftingProduct::withoutGlobalScope(CompanyScope::class)->leftJoin('liftings', 'liftings.id', '=', 'lifting_products.lifting_id')
                            ->where('product_id', $product->product_id)
                            ->where('liftings.lifting_date', '<=', $date->format('Y-m-d'))
                            ->orderBy('liftings.lifting_date', 'desc')
                            ->first();
                        $total_cost += @$lifting->lifting_price * $product->qty;
                    }
                    $payable = $item->list->sum('amount') - $item->list->sum('discount');
                    $gross_profit = $payable - $total_cost;
                    $percentage = number_format(($gross_profit / $payable) * 100, 2, '.', '');

                    $ld = $lineData;
                    $ld['issue_no'] = ($key == 0 ? '<div class="mb-1" style="font-weight: bold; font-size: 12px;">' . $date->format('Y-m-d') . '</div>' . $item->invoice  : $item->invoice);
                    $ld['customer_name'] = @$item->client->name;
                    $ld['total_amount'] = $item->list->sum('amount');
                    $ld['discount'] = $item->list->sum('discount');
                    $ld['net_payable'] = $payable;
                    $ld['total_cost'] = $total_cost;
                    $ld['gross_profit'] = $gross_profit;
                    $ld['profit'] = $percentage;
                    array_push($data, $ld);

                    $summary_sales += $item->list->sum('amount');
                    $summary_discount += $item->list->sum('discount');
                    $summary_payable += $item->list->sum('amount') - $item->list->sum('discount');
                    $summary_cost += $total_cost;
                    $summary_gross_profit += $gross_profit;
                }
                $online_sales = Order::with(['products'])->where('date', $date->format('Y-m-d'))->whereIn('status', ['Delivered', 'Collected'])->get();
                $summary_sales = 0;
                $summary_discount = 0;
                $summary_payable = 0;
                $summary_cost = 0;
                $summary_gross_profit = 0;

                foreach ($online_sales as $key => $item) {
                    $total_cost = 0;
                    foreach ($item->products as $product) {
                        $lifting = LiftingProduct::withoutGlobalScope(CompanyScope::class)->leftJoin('liftings', 'liftings.id', '=', 'lifting_products.lifting_id')
                            ->where('product_id', $product->product_id)
                            ->where('liftings.lifting_date', '<=', $date->format('Y-m-d'))
                            ->orderBy('liftings.lifting_date', 'desc')
                            ->first();
                        $total_cost += @$lifting->lifting_price * $product->quantity;
                    }
                    $payable = $item->products->sum('subtotal') - $item->products->sum('discount');
                    $gross_profit = $payable - $total_cost;
                    $percentage = number_format(($gross_profit / $payable) * 100, 2, '.', '');

                    $ld = $lineData;
                    $ld['issue_no'] = ($key == 0 ? '<div class="mb-1" style="font-weight: bold; font-size: 12px;">' . $date->format('Y-m-d') . '</div>' . $item->invoice  : $item->invoice);
                    $ld['customer_name'] = @$item->user_name;
                    $ld['total_amount'] = $item->products->sum('subtotal');
                    $ld['discount'] = $item->products->sum('discount');
                    $ld['net_payable'] = $payable;
                    $ld['total_cost'] = $total_cost;
                    $ld['gross_profit'] = $gross_profit;
                    $ld['profit'] = $percentage;
                    array_push($data, $ld);

                    $summary_sales += $item->products->sum('subtotal');
                    $summary_discount += $item->products->sum('discount');
                    $summary_payable += $item->products->sum('subtotal') - $item->products->sum('discount');
                    $summary_cost += $total_cost;
                    $summary_gross_profit += $gross_profit;
                }
                if ($summary_sales > 0) {
                    $percentage = number_format(($summary_gross_profit / $summary_payable) * 100, 2, '.', '');
                    $ld = NULL;
                    $ld['issue_no'] = NULL;
                    $ld['customer_name'] = NULL;
                    $ld['total_amount'] = $summary_sales;
                    $ld['discount'] = $summary_discount;
                    $ld['net_payable'] = $summary_payable;
                    $ld['total_cost'] = $summary_cost;
                    $ld['gross_profit'] = $summary_gross_profit;
                    $ld['profit'] = $percentage;
                    array_push($data, $ld);
                }
            }
        }

        if (!is_null($request->print)) {
            if (Auth::user()->company_id) {
                $company = Company::find(Auth::user()->company_id);
                $title = $company->name;
                $informations = $company->address . '</br>' . $company->phone . ', ' . $company->email . ', ' . $company->website;
            } else {
                $title = 'Company Name Goes Here.';
                $informations = 'Company address will goes here </br> Mobile: 0967XXXXXX, Email: youremail@gmail.com, www.website.com';
            }
            $report_title = 'Invoicewise Profit Loss <br> <span class="text-sm">' . date('d-m-Y', strtotime($start_date)) . ' To ' . date('d-m-Y', strtotime($end_date)) . '</span>';
            // return view('admin.reports.invoicewise_profit_loss.print', compact('title', 'informations', 'report_title', 'data'));
            $pdf = Pdf::loadView('admin.reports.invoicewise_profit_loss.print', compact('title', 'informations', 'report_title', 'data'));
            $pdf->setPaper('A4', 'landscape');
            return $pdf->stream('invoicewise_profit_loss_' . date('d_m_Y_h_i_s') . '.pdf');
        }

        return view('admin.reports.invoicewise_profit_loss.index', compact('title', 'filter_link', 'start_date', 'end_date', 'data'));
    }

    public function profitSheet(Request $request, ProfitSheetDataTable $dataTable)
    {
        if (!is_null($request->print)) {
            if (Auth::user()->company_id) {
                $company = Company::find(Auth::user()->company_id);
                $title = $company->name;
                $informations = $company->address . '</br>' . $company->phone . ', ' . $company->email . ', ' . $company->website;
            } else {
                $title = 'Company Name Goes Here.';
                $informations = 'Company address will goes here </br> Mobile: 0967XXXXXX, Email: youremail@gmail.com, www.website.com';
            }

            $report_title = 'Profit Sheet';
            $year = request('year');
            $month = request('month');
            $investor_id = request('investor_id');

            $query = InvestorProfitList::with(['parent', 'investor', 'product'])->whereHas('parent', function ($q) use ($month, $year) {
                $q->where('month', $month);
                $q->where('year', $year);
            });

            if (!empty($investor_id)) {
                $query->where('investor_id', $investor_id);
            }
            $data = $query->get();

            $pdf = Pdf::loadView('admin.reports.profit_sheet.print', compact('title', 'informations', 'report_title', 'data'));
            $pdf->setPaper('A4', 'landscape');
            return $pdf->stream('profit_sheet_' . date('d_m_Y_H_i_s') . '.pdf');
        }

        $title = 'Profit Sheet';
        $filter_link = Route('admin.profit-sheet.index');
        $current_time = Carbon::now();
        $investors = Investor::where('status', 1)->get();
        return $dataTable->render('admin.reports.profit_sheet.index', compact('title', 'filter_link', 'current_time', 'investors'));
    }

    public function investorStatement(Request $request)
    {
        $previous_balance = 0;
        $statements = [];
        $date_range = explode('to', $request->date_range);
        $start_date = !is_null($request->date_range) ? date('Y-m-d', strtotime($date_range[0])) : date('Y-m-01');
        $end_date = !is_null($request->date_range) ? date('Y-m-d', strtotime($date_range[1])) : date('Y-m-t');

        if ($request->has('filter')) {
            $investor = Investor::findOrFail($request->investor_id);
            $invest = Invest::where('date', '<', $start_date)->where('investor_id', $request->investor_id)->sum('amount');
            $investSattlement = InvestorSattlement::where('date', '<', $start_date)->where('investor_id', $request->investor_id)->sum('amount');
            $previous_balance = $invest - $investSattlement + ProfitDistributionList::whereHas('distribution', function ($query) use ($start_date) {
                $query->where('date', '<', $start_date);
            })->where('investor_id', $request->investor_id)->where('paid', 0)->sum('profit_amount');
            $dateRange = CarbonPeriod::create($start_date, $end_date);
            $balance = $previous_balance;
            foreach ($dateRange as $date) {
                $d = $date->format('Y-m-d');

                $invests = Invest::where('date', $d)->where('investor_id', $request->investor_id)->get();
                foreach ($invests as $invest) {
                    $balance += $invest->amount;
                    $row = [
                        'date' => $date->format('d-m-Y'),
                        'remarks' => 'Invest against Invest No ' . $invest->invest_no,
                        'amount_in' => $invest->amount,
                        'amount_out' => 0.00,
                        'balance' => $balance,
                    ];
                    array_push($statements, $row);
                }

                $profits = ProfitDistributionList::whereHas('distribution', function ($query) use ($d) {
                    $query->where('date', $d);
                })->where('investor_id', $request->investor_id)->get();

                foreach ($profits as $profit) {
                    $balance += $profit->profit_amount;
                    $row = [
                        'date' => $date->format('d-m-Y'),
                        'remarks' => 'Profit for Month ' . $profit->month,
                        'amount_in' => $profit->profit_amount,
                        'amount_out' => 0.00,
                        'balance' => $balance,
                    ];
                    array_push($statements, $row);
                }

                $payments = InvestorPayment::where('date',  $d)->where('investor_id', $request->investor_id)->get();
                foreach ($payments as $payment) {
                    $balance -= $payment->amount;
                    $row = [
                        'date' => $date->format('d-m-Y'),
                        'remarks' => 'Payment against payment no ' . $payment->payment_no,
                        'amount_in' => 0.00,
                        'amount_out' => $payment->amount,
                        'balance' => $balance,
                    ];
                    array_push($statements, $row);
                }

                $investSattlement = InvestorSattlement::where('date', $d)->where('investor_id', $request->investor_id)->get();
                foreach ($investSattlement as $sattlement) {
                    $balance -= $sattlement->amount;
                    $row = [
                        'date' => $date->format('d-m-Y'),
                        'remarks' => 'Invest Sattlement against Sattlement No ' . $sattlement->serial_no,
                        'amount_in' => $sattlement->amount,
                        'amount_out' => 0.00,
                        'balance' => $balance,
                    ];
                    array_push($statements, $row);
                }
            }
        }

        if (!is_null($request->print)) {
            if (Auth::user()->company_id) {
                $company = Company::find(Auth::user()->company_id);
                $title = $company->name;
                $informations = $company->address . '</br>' . $company->phone . ', ' . $company->email . ', ' . $company->website;
            } else {
                $title = 'Company Name Goes Here.';
                $informations = 'Company address will goes here </br> Mobile: 0967XXXXXX, Email: youremail@gmail.com, www.website.com';
            }

            $report_title = 'Investment Register';
            $pdf = Pdf::loadView('admin.reports.investor_statement.print', compact('title', 'informations', 'report_title', 'previous_balance', 'statements'));
            return $pdf->stream('investor_statement_' . date('d_m_Y_H_i_s') . '.pdf');
        }

        $title = 'Investment Register';
        $filter_link = Route('admin.investor-statement.index');
        $investors = Investor::where('status', 1)->get();
        return view('admin.reports.investor_statement.index', compact('title', 'filter_link', 'start_date', 'end_date', 'investors', 'previous_balance', 'statements'));
    }

    public function profitDueList(Request $request)
    {
        $data = [];
        $invests = Invest::groupBy('investor_id')->orderBy('date', 'asc')->get();
        foreach ($invests as $invest) {
            $this_invest = Invest::where('investor_id', $invest->investor_id)->get();
            $total_profit = Wallet::where('investor_id', $invest->investor_id)->where('approved', 1)->where('type', 'Profit')->sum('amount_in');
            $withdraw_profit = Wallet::where('investor_id', $invest->investor_id)->where('approved', 1)->where('type', 'Payment')->sum('amount_out');
            $dates = '';
            foreach ($this_invest as $key => $date) {
                $dates .= ($key > 0 ? ', ' : '') . date('d-m-Y', strtotime($date->date));
            }
            $item = [
                'investor_name' => @$invest->investor->name,
                'dates' => $dates,
                'total_invest' => $this_invest->sum('amount'),
                'withdraw_invest' => $this_invest->where('sattled', 1)->sum('amount'),
                'total_profit' => $total_profit,
                'withdraw_profit' => $withdraw_profit,
                'due' => $this_invest->where('sattled', 0)->sum('amount') + $total_profit - $withdraw_profit,
            ];
            array_push($data, $item);
        }

        if (!is_null($request->print)) {
            if (Auth::user()->company_id) {
                $company = Company::find(Auth::user()->company_id);
                $title = $company->name;
                $informations = $company->address . '</br>' . $company->phone . ', ' . $company->email . ', ' . $company->website;
            } else {
                $title = 'Company Name Goes Here.';
                $informations = 'Company address will goes here </br> Mobile: 0967XXXXXX, Email: youremail@gmail.com, www.website.com';
            }

            $report_title = 'Profit Due List';
            $pdf = Pdf::loadView('admin.reports.profit_due_list.print', compact('title', 'informations', 'report_title', 'data'));
            $pdf->setPaper('A4', 'landscape');
            return $pdf->stream('profit_due_list_' . date('d_m_Y_H_i_s') . '.pdf');
        }

        $title = 'Profit Due List';
        $filter_link = Route('admin.profit-due-list.index');
        return view('admin.reports.profit_due_list.index', compact('title', 'filter_link', 'data'));
    }

    public function orderList(Request $request, OrderListDataTable $historyDataTable, OrderlistSummaryDataTable $summaryDataTable)
    {
        $date_range = explode('to', $request->date_range);
        $start_date = !is_null($request->date_range) ? date('Y-m-d', strtotime($date_range[0])) : date('Y-m-01');
        $end_date = !is_null($request->date_range) ? date('Y-m-d', strtotime($date_range[1])) : date('Y-m-t');

        if (!is_null($request->print)) {
            if (Auth::user()->company_id) {
                $company = Company::find(Auth::user()->company_id);
                $title = $company->name;
                $informations = $company->address . '</br>' . $company->phone . ', ' . $company->email . ', ' . $company->website;
            } else {
                $title = 'Company Name Goes Here.';
                $informations = 'Company address will goes here </br> Mobile: 0967XXXXXX, Email: youremail@gmail.com, www.website.com';
            }

            if ($request->report_type == 'summary') {
                $query = OrderProduct::with(['order', 'product', 'product.attribute']);
                $query->whereHas('order', function ($q) use ($start_date, $end_date) {
                    $q->where('date', '>=', $start_date)->where('date', '<=', $end_date);

                    if (request('area_id')) {
                        $q->where('area_id', request('area_id'));
                    }
                    if (request('created_by')) {
                        $q->where('created_by', request('created_by'));
                    }
                    if (request('status')) {
                        $q->where('status', request('status'));
                    }
                });
                $data = $query->groupBy('product_id')->select('*', DB::raw('SUM(quantity) as total_quantity'), DB::raw('SUM(subtotal - return_amount) as total_subtotal'))->get();
            } else {
                $query = Order::with(['products']);
                $query->where('date', '>=', $start_date)->where('date', '<=', $end_date);
                if (request('area_id')) {
                    $query->where('area_id', request('area_id'));
                }
                if (request('created_by')) {
                    $query->where('created_by', request('created_by'));
                }
                if (request('status')) {
                    $query->where('status', request('status'));
                }
                $data = $query->get();
            }

            if (!is_null($request->chalan)) {
                $report_title = 'Order List Report';
            } else {
                $report_title = 'Order Report';
            }

            $pdf = Pdf::loadView('admin.reports.order_list.print', compact('title', 'informations', 'report_title', 'data'));
            $pdf->setPaper('A4', 'landscape');
            return $pdf->stream('order_list_' . date('d_m_Y_H_i_s') . '.pdf');
        }

        $areas = Area::where('status', 1)->orderBy('name', 'asc')->get();
        $query = User::where('role', 1)->whereHas('roles', function ($q) {
            $q->where('name', 'Moderator');
        });
        if (Auth::user()->hasRole('Moderator')) {
            $query->where('id', Auth::user()->id);
        }
        $staffs = $query->orderBy('name', 'asc')->get();
        $stores = Store::where('status', 1)->orderBy('name', 'asc')->get();
        $filter_link = Route('admin.order-list.index');

        if ($request->report_type == 'summary') {
            $title = 'Order List Summary Report';
            return $summaryDataTable->render('admin.reports.order_list.index', compact('title', 'filter_link', 'areas', 'staffs', 'stores', 'start_date', 'end_date'));
        } else {
            $title = 'Order List History Report';
            return $historyDataTable->render('admin.reports.order_list.index', compact('title', 'filter_link', 'areas', 'staffs', 'stores', 'start_date', 'end_date'));
        }
    }

    public static function stock($product_id, $store_id, $product_type = 'Consumer')
    {
        if ($product_type == 'Consumer') {
            $liftings = DB::table('view_liftings')->where('product_type', $product_type ?? 'Consumer')->whereIn('store_id', $store_id)->where('product_id', $product_id)->sum('qty');
            $lifting_returns = DB::table('view_lifting_returns')->where('product_type', $product_type ?? 'Consumer')->whereIn('store_id', $store_id)->where('product_id', $product_id)->sum('qty');
            $sales = DB::table('view_sales')->where('product_type', $product_type ?? 'Consumer')->whereIn('store_id', $store_id)->where('product_id', $product_id)->sum('qty');
            $sales_returns = DB::table('view_sales_returns')->where('product_type', $product_type ?? 'Consumer')->whereIn('store_id', $store_id)->where('product_id', $product_id)->sum('qty');
            $online_sales = DB::table('view_online_sales')->where('product_type', $product_type ?? 'Consumer')->whereIn('status', ['On Route', 'Delivered', 'Collected'])->whereIn('store_id', $store_id)->where('product_id', $product_id)->sum('qty');
            $transfers = DB::table('view_transfers')->where('product_type', $product_type)->where('product_id', $product_id)->whereIn('host_id', $store_id)->sum('qty');
            $receives = DB::table('view_transfers')->where('product_type', $product_type)->where('product_id', $product_id)->whereIn('destination_id', $store_id)->sum('qty');
        }
        if ($product_type == 'Fashion') {
            $liftings = DB::table('view_liftings')->where('product_type', $product_type)->whereIn('store_id', $store_id)->where('sku_id', $product_id)->sum('qty');
            $lifting_returns = DB::table('view_lifting_returns')->where('product_type', $product_type)->whereIn('store_id', $store_id)->where('sku_id', $product_id)->sum('qty');
            $sales = DB::table('view_sales')->where('product_type', $product_type)->whereIn('store_id', $store_id)->where('sku_id', $product_id)->sum('qty');
            $sales_returns = DB::table('view_sales_returns')->where('product_type', $product_type)->whereIn('store_id', $store_id)->where('sku_id', $product_id)->sum('qty');
            $online_sales = DB::table('view_online_sales')->where('product_type', $product_type)->whereIn('status', ['On Route', 'Delivered', 'Collected'])->whereIn('store_id', $store_id)->where('sku_id', $product_id)->sum('qty');
            $transfers = DB::table('view_transfers')->where('product_type', $product_type)->where('sku_id', $product_id)->whereIn('host_id', $store_id)->sum('qty');
            $receives = DB::table('view_transfers')->where('product_type', $product_type)->where('sku_id', $product_id)->whereIn('destination_id', $store_id)->sum('qty');
        }

        return $liftings + $sales_returns + $receives - $lifting_returns - $sales - $online_sales - $transfers;
    }

    public function requiredProduct(Request $request)
    {
        $data = [];
        if (!is_null($request->store_id)) {
            $store_id = [$request->store_id];
        } else {
            $store_id = Auth::user()->stores ?? Store::where('status', 1)->pluck('id')->toArray();
        }

        $products = OrderProduct::with('product')->whereHas('order', function ($query) use ($request, $store_id) {
            $query->whereIn('status', ['Pending', 'Forward']);
            if ($request->store_id) {
                $query->whereIn('store_id', $store_id);
            }
        })->groupBy('product_id')->select('product_id', DB::raw('SUM(quantity) as total_quantity'))->get();

        foreach ($products as $single) {
            $stock = $this->stock($single->product_id, $store_id);
            $item = [
                'product' => $single->product,
                'stock_qty' => $stock,
                'demand_qty' => $single->total_quantity,
            ];
            array_push($data, $item);
        }

        if (!is_null($request->print)) {
            if (Auth::user()->company_id) {
                $company = Company::find(Auth::user()->company_id);
                $title = $company->name;
                $informations = $company->address . '</br>' . $company->phone . ', ' . $company->email . ', ' . $company->website;
            } else {
                $title = 'Company Name Goes Here.';
                $informations = 'Company address will goes here </br> Mobile: 0967XXXXXX, Email: youremail@gmail.com, www.website.com';
            }

            $report_title = 'Required Product';
            $pdf = Pdf::loadView('admin.reports.required_product.print', compact('title', 'informations', 'report_title', 'data'));
            return $pdf->stream('required_product_list_' . date('d_m_Y_H_i_s') . '.pdf');
        }
        $title = 'Required Product';
        $filter_link = Route('admin.required-product.index');
        $stores = Store::where('status', 1)->orderBy('name', 'asc')->get();
        return view('admin.reports.required_product.index', compact('title', 'filter_link', 'stores', 'data'));
    }

    public function areawiseOrders(Request $request)
    {
        $data = [];
        $query = Area::with('orders')->whereHas('orders', function ($query) {
            $query->whereIn('status', ['Pending', 'Forward', 'Processing']);
        });
        if ($request->store_id) {
            $area_ids = StoreArea::where('store_id', $request->store_id)->pluck('area_id')->toArray();
            $query->whereIn('id', $area_ids);
        }
        $data = $query->where('status', 1)->orderBy('name', 'asc')->get();

        if (!is_null($request->print)) {
            if (Auth::user()->company_id) {
                $company = Company::find(Auth::user()->company_id);
                $title = $company->name;
                $informations = $company->address . '</br>' . $company->phone . ', ' . $company->email . ', ' . $company->website;
            } else {
                $title = 'Company Name Goes Here.';
                $informations = 'Company address will goes here </br> Mobile: 0967XXXXXX, Email: youremail@gmail.com, www.website.com';
            }

            $report_title = 'Areawise Order Report';
            $pdf = Pdf::loadView('admin.reports.areawise_orders.print', compact('title', 'informations', 'report_title', 'data'));
            return $pdf->stream('areawise_orders_report' . date('d_m_Y_H_i_s') . '.pdf');
        }
        $title = 'Areawise Order Report';
        $filter_link = Route('admin.areawise-orders.index');
        $stores = Store::where('status', 1)->orderBy('name', 'asc')->get();
        return view('admin.reports.areawise_orders.index', compact('title', 'filter_link', 'data', 'stores'));
    }

    public function areawiseOrder(Request $request)
    {
        $data = Area::findOrFail($request->area_id);

        if (!is_null($request->print)) {
            if (Auth::user()->company_id) {
                $company = Company::find(Auth::user()->company_id);
                $title = $company->name;
                $informations = $company->address . '</br>' . $company->phone . ', ' . $company->email . ', ' . $company->website;
            } else {
                $title = 'Company Name Goes Here.';
                $informations = 'Company address will goes here </br> Mobile: 0967XXXXXX, Email: youremail@gmail.com, www.website.com';
            }

            $report_title = $data->name;
            $pdf = Pdf::loadView('admin.reports.areawise_orders.single_area_print', compact('title', 'informations', 'report_title', 'data'));
            $pdf->setPaper('A4', 'landscape');
            return $pdf->stream('areawise_order_' . date('d_m_Y_H_i_s') . '.pdf');
        }

        $title = $data->name;
        $filter_link = Route('admin.areawise-order.index');
        return view('admin.reports.areawise_orders.single_area', compact('title', 'filter_link', 'data'));
    }

    public function productWiseSalesSummary(Request $request, ProductWiseSalesSummaryDataTable $dataTable)
    {
        if (!is_null($request->print)) {
            if (Auth::user()->company_id) {
                $company = Company::find(Auth::user()->company_id);
                $title = $company->name;
                $informations = $company->address . '</br>' . $company->phone . ', ' . $company->email . ', ' . $company->website;
            } else {
                $title = 'Company Name Goes Here.';
                $informations = 'Company address will goes here </br> Mobile: 0967XXXXXX, Email: youremail@gmail.com, www.website.com';
            }

            $report_title = 'Product Wise Sales Summary <br> <span class="text-sm">' . date('F', strtotime('01-' . $request->month . '-' . $request->year)) . ' ' . date('Y', strtotime($request->year)) . '</span>';

            $query = Product::where('status', 1);
            if (request('category_id')) {
                $query->whereIn('category_id', request('category_id'));
            }
            $data = $query->get();

            $pdf = Pdf::loadView('admin.reports.product_wise_sales_summary.print', compact('title', 'informations', 'report_title', 'data'));
            $pdf->setPaper('a1', 'potrait');
            return $pdf->stream('product_wise_sales_summary_' . date('d_m_Y_H_i_s') . '.pdf');
        }

        $filter_link = Route('admin.product-wise-sales-summary.index');
        $title = 'Product Wise Sales Summary';
        $categories = Category::where('status', 1)->orderBy('name', 'asc')->get();
        return $dataTable->render('admin.reports.product_wise_sales_summary.index', compact('title', 'filter_link', 'categories'));
    }

    public function areaWiseSalesStatement(Request $request, AreaWiseSalesSummaryDataTable $dataTable)
    {
        if ($request->ajax() && $request->has('get_area')) {
            $query = Area::where('status', 1);
            if ($request->store_id) {
                $area_ids = StoreArea::where('store_id', $request->store_id)->pluck('area_id')->toArray();
                $query->whereIn('id', $area_ids);
            }
            $areas = $query->orderBy('name', 'asc')->get();
            return response()->json(['status' => 'success', 'areas' => $areas]);
        }

        if (!is_null($request->view_orders)) {
            $query = Order::whereIn('status', ['Delivered', 'Collected']);
            if ($request->start_date & $request->end_date) {
                $query->where('date', '>=', date('Y-m-d', strtotime($request->start_date)))->where('date', '<=', date('Y-m-d', strtotime($request->end_date)));
            } elseif ($request->date) {
                $query->where('date', date('Y-m-d', strtotime($request->date)));
            }
            if ($request->area_id) {
                $area = Area::find($request->area_id);
                $query->where('area_id', $request->area_id);
            }
            $data = $query->get();

            if (!is_null($request->print)) {
                if (Auth::user()->company_id) {
                    $company = Company::find(Auth::user()->company_id);
                    $title = $company->name;
                    $informations = $company->address . '</br>' . $company->phone . ', ' . $company->email . ', ' . $company->website;
                } else {
                    $title = 'Company Name Goes Here.';
                    $informations = 'Company address will goes here </br> Mobile: 0967XXXXXX, Email: youremail@gmail.com, www.website.com';
                }

                if ($request->start_date & $request->end_date) {
                    $report_title = @$area->name . '<br> <span class="text-sm">' . date('d-m-Y', strtotime($request->start_date)) . ' to ' . date('d-m-Y', strtotime($request->end_date)) . '</span>';
                } elseif ($request->date) {
                    $report_title = @$area->name . '<br> <span class="text-sm">' . date('d-m-Y', strtotime($request->date)) . '</span>';
                }

                $pdf = Pdf::loadView('admin.reports.area_wise_sales_summary.single_area_print', compact('title', 'informations', 'report_title', 'data'));
                $pdf->setPaper('A4', 'landscape');
                return $pdf->stream('area_wise_orders_' . date('d_m_Y_H_i_s') . '.pdf');
            }

            $filter_link = Route('admin.area-wise-sales-statement.index');
            $title = @$area->name;
            return view('admin.reports.area_wise_sales_summary.single_area', compact('data', 'filter_link', 'title'));
        }

        if (!is_null($request->print)) {
            if (Auth::user()->company_id) {
                $company = Company::find(Auth::user()->company_id);
                $title = $company->name;
                $informations = $company->address . '</br>' . $company->phone . ', ' . $company->email . ', ' . $company->website;
            } else {
                $title = 'Company Name Goes Here.';
                $informations = 'Company address will goes here </br> Mobile: 0967XXXXXX, Email: youremail@gmail.com, www.website.com';
            }

            $report_title = 'Area Wise Sales Statement <br> <span class="text-sm">' . date('F', strtotime('01-' . $request->month . '-' . $request->year)) . ' ' . date('Y', strtotime($request->year)) . '</span>';

            $query = Area::where('status', 1);
            if ($request->store_id) {
                $area_ids = StoreArea::where('store_id', $request->store_id)->pluck('area_id')->toArray();
                $query->whereIn('id', $area_ids);
            }
            if ($request->area_id) {
                $query->whereIn('area_id', $request->area_id);
            }
            $data = $query->get();

            $pdf = Pdf::loadView('admin.reports.area_wise_sales_summary.print', compact('title', 'informations', 'report_title', 'data'));
            $pdf->setPaper('A2', 'potrait');
            return $pdf->stream('area_wise_sales_summary_' . date('d_m_Y_H_i_s') . '.pdf');
        }

        $filter_link = Route('admin.area-wise-sales-statement.index');
        $title = 'Area Wise Sales Statement';
        $stores = Store::where('status', 1)->orderBy('name', 'asc')->get();
        $query = Area::where('status', 1);
        if ($request->store_id) {
            $area_ids = StoreArea::where('store_id', $request->store_id)->pluck('area_id')->toArray();
            $query->whereIn('id', $area_ids);
        }
        $areas = $query->orderBy('name', 'asc')->get();
        return $dataTable->render('admin.reports.area_wise_sales_summary.index', compact('title', 'filter_link', 'stores', 'areas'));
    }

    public function storeWiseSalesStatement(Request $request, StoreWiseSalesSummaryDataTable $dataTable)
    {
        if (!is_null($request->view_orders)) {
            $query = Order::whereIn('status', ['Delivered', 'Collected']);
            if ($request->start_date & $request->end_date) {
                $query->where('date', '>=', date('Y-m-d', strtotime($request->start_date)))->where('date', '<=', date('Y-m-d', strtotime($request->end_date)));
            } elseif ($request->date) {
                $query->where('date', date('Y-m-d', strtotime($request->date)));
            }
            if ($request->store_id) {
                $store = Store::find($request->store_id);
                $query->where('store_id', $request->store_id);
            }
            $data = $query->get();

            if (!is_null($request->print)) {
                if (Auth::user()->company_id) {
                    $company = Company::find(Auth::user()->company_id);
                    $title = $company->name;
                    $informations = $company->address . '</br>' . $company->phone . ', ' . $company->email . ', ' . $company->website;
                } else {
                    $title = 'Company Name Goes Here.';
                    $informations = 'Company address will goes here </br> Mobile: 0967XXXXXX, Email: youremail@gmail.com, www.website.com';
                }

                if ($request->start_date & $request->end_date) {
                    $report_title = @$store->name . '<br> <span class="text-sm">' . date('d-m-Y', strtotime($request->start_date)) . ' to ' . date('d-m-Y', strtotime($request->end_date)) . '</span>';
                } elseif ($request->date) {
                    $report_title = @$store->name . '<br> <span class="text-sm">' . date('d-m-Y', strtotime($request->date)) . '</span>';
                }

                $pdf = Pdf::loadView('admin.reports.store_wise_sales_summary.single_store_print', compact('title', 'informations', 'report_title', 'data'));
                $pdf->setPaper('A4', 'landscape');
                return $pdf->stream('store_wise_orders_' . date('d_m_Y_H_i_s') . '.pdf');
            }

            $filter_link = Route('admin.store-wise-sales-statement.index');
            $title = @$store->name;
            return view('admin.reports.store_wise_sales_summary.single_store', compact('data', 'filter_link', 'title'));
        }

        if (!is_null($request->print)) {
            if (Auth::user()->company_id) {
                $company = Company::find(Auth::user()->company_id);
                $title = $company->name;
                $informations = $company->address . '</br>' . $company->phone . ', ' . $company->email . ', ' . $company->website;
            } else {
                $title = 'Company Name Goes Here.';
                $informations = 'Company address will goes here </br> Mobile: 0967XXXXXX, Email: youremail@gmail.com, www.website.com';
            }

            $report_title = 'Store Wise Sales Statement <br> <span class="text-sm">' . date('F', strtotime('01-' . $request->month . '-' . $request->year)) . ' ' . date('Y', strtotime($request->year)) . '</span>';

            $query = Store::where('status', 1);
            if ($request->store_id) {
                $query->whereIn('store_id', $request->store_id);
            }
            $data = $query->get();

            $pdf = Pdf::loadView('admin.reports.store_wise_sales_summary.print', compact('title', 'informations', 'report_title', 'data'));
            $pdf->setPaper('A2', 'potrait');
            return $pdf->stream('store_wise_sales_summary_' . date('d_m_Y_H_i_s') . '.pdf');
        }

        $filter_link = Route('admin.store-wise-sales-statement.index');
        $title = 'Store Wise Sales Statement';
        $stores = Store::where('status', 1)->orderBy('name', 'asc')->get();
        return $dataTable->render('admin.reports.store_wise_sales_summary.index', compact('title', 'filter_link', 'stores'));
    }

    public function monthlyStatement(Request $request)
    {
        if (!is_null($request->view_orders)) {
            $query = Order::query();
            if ($request->start_date & $request->end_date) {
                $query->where('date', '>=', date('Y-m-d', strtotime($request->start_date)))->where('date', '<=', date('Y-m-d', strtotime($request->end_date)));
            } elseif ($request->date) {
                $query->where('date', date('Y-m-d', strtotime($request->date)));
            }
            if ($request->status) {
                $query->where('status', $request->status);
            }
            $data = $query->get();

            if (!is_null($request->print)) {
                if (Auth::user()->company_id) {
                    $company = Company::find(Auth::user()->company_id);
                    $title = $company->name;
                    $informations = $company->address . '</br>' . $company->phone . ', ' . $company->email . ', ' . $company->website;
                } else {
                    $title = 'Company Name Goes Here.';
                    $informations = 'Company address will goes here </br> Mobile: 0967XXXXXX, Email: youremail@gmail.com, www.website.com';
                }

                if ($request->start_date & $request->end_date) {
                    $report_title = 'Monthly Statement <br> <span class="text-sm">' . $request->status . ' '  . date('d-m-Y', strtotime($request->start_date)) . ' to ' . date('d-m-Y', strtotime($request->end_date)) . '</span>';
                } elseif ($request->date) {
                    $report_title = 'Monthly Statement <br> <span class="text-sm">' . $request->status . ' '  . date('d-m-Y', strtotime($request->date)) . '</span>';
                }

                $pdf = Pdf::loadView('admin.reports.monthly_statement.single_status_print', compact('title', 'informations', 'report_title', 'data'));
                $pdf->setPaper('A4', 'landscape');
                return $pdf->stream('date_wise_orders_' . date('d_m_Y_H_i_s') . '.pdf');
            }

            $filter_link = Route('admin.monthly-statement.index');
            $title = 'Monthly Statement ' . $request->status . ' - ' . date('d-m-Y', strtotime($request->date));
            return view('admin.reports.monthly_statement.single_status', compact('data', 'filter_link', 'title'));
        }

        if (!is_null($request->print)) {
            if (Auth::user()->company_id) {
                $company = Company::find(Auth::user()->company_id);
                $title = $company->name;
                $informations = $company->address . '</br>' . $company->phone . ', ' . $company->email . ', ' . $company->website;
            } else {
                $title = 'Company Name Goes Here.';
                $informations = 'Company address will goes here </br> Mobile: 0967XXXXXX, Email: youremail@gmail.com, www.website.com';
            }

            $report_title = 'Monthly Statement <br> <span class="text-sm">' . date('F', strtotime('01-' . $request->month . '-' . $request->year)) . ' ' . date('Y', strtotime($request->year)) . '</span>';

            $pdf = Pdf::loadView('admin.reports.monthly_statement.print', compact('title', 'informations', 'report_title'));
            $pdf->setPaper('A3', 'potrait');
            return $pdf->stream('monthly_statement_' . date('d_m_Y_H_i_s') . '.pdf');
        }

        $title = 'Monthly Statement';
        $filter_link = Route('admin.monthly-statement.index');
        $stores = Store::where('status', 1)->orderBy('name', 'asc')->get();
        return view('admin.reports.monthly_statement.index', compact('filter_link', 'title', 'stores'));
    }

    public function storewiseOrders(Request $request)
    {
        $data = [];
        $query = Store::with('orders')->whereHas('orders', function ($query) {
            $query->whereIn('status', ['Pending', 'Forward', 'Processing', 'On Route', 'Delivered']);
        });
        if ($request->store_id) {
            $query->where('id', $request->store_id);
        }
        $data = $query->where('status', 1)->orderBy('name', 'asc')->get();

        if (!is_null($request->print)) {
            if (Auth::user()->company_id) {
                $company = Company::find(Auth::user()->company_id);
                $title = $company->name;
                $informations = $company->address . '</br>' . $company->phone . ', ' . $company->email . ', ' . $company->website;
            } else {
                $title = 'Company Name Goes Here.';
                $informations = 'Company address will goes here </br> Mobile: 0967XXXXXX, Email: youremail@gmail.com, www.website.com';
            }

            $report_title = 'Storewise Order Report';
            $pdf = Pdf::loadView('admin.reports.storewise_orders.print', compact('title', 'informations', 'report_title', 'data'));
            return $pdf->stream('storewise_orders_report' . date('d_m_Y_H_i_s') . '.pdf');
        }
        $title = 'Storewise Order Report';
        $filter_link = Route('admin.storewise-orders.index');
        $stores = Store::where('status', 1)->orderBy('name', 'asc')->get();
        return view('admin.reports.storewise_orders.index', compact('title', 'filter_link', 'data', 'stores'));
    }

    public function storewiseOrder(Request $request)
    {
        $data = Store::findOrFail($request->store_id);

        if (!is_null($request->print)) {
            if (Auth::user()->company_id) {
                $company = Company::find(Auth::user()->company_id);
                $title = $company->name;
                $informations = $company->address . '</br>' . $company->phone . ', ' . $company->email . ', ' . $company->website;
            } else {
                $title = 'Company Name Goes Here.';
                $informations = 'Company address will goes here </br> Mobile: 0967XXXXXX, Email: youremail@gmail.com, www.website.com';
            }

            $report_title = $data->name;
            $pdf = Pdf::loadView('admin.reports.storewise_orders.single_area_print', compact('title', 'informations', 'report_title', 'data'));
            $pdf->setPaper('A4', 'landscape');
            return $pdf->stream('storewise_order_' . date('d_m_Y_H_i_s') . '.pdf');
        }

        $title = $data->name;
        $filter_link = Route('admin.storewise-order.index');
        return view('admin.reports.storewise_orders.single_area', compact('title', 'filter_link', 'data'));
    }

    public function onRouteOrders(Request $request, OnRouteOrdersDataTable $dataTable)
    {
        if (!is_null($request->print)) {
            if (Auth::user()->company_id) {
                $company = Company::find(Auth::user()->company_id);
                $title = $company->name;
                $informations = $company->address . '</br>' . $company->phone . ', ' . $company->email . ', ' . $company->website;
            } else {
                $title = 'Company Name Goes Here.';
                $informations = 'Company address will goes here </br> Mobile: 0967XXXXXX, Email: youremail@gmail.com, www.website.com';
            }

            $delivery_man = DeliveryAgent::find($request->delivery_agent_id);
            $report_title = 'Pending Order List - ' . @$delivery_man->name;

            $query = Order::with(['products', 'area'])->where('status', 'On Route');
            if (Auth::user()->stores) {
                $query->whereIn('store_id', Auth::user()->stores);
            }
            if (request('delivery_agent_id')) {
                $query->where('delivery_agent_id', request('delivery_agent_id'));
            }
            $data = $query->get();

            $pdf = Pdf::loadView('admin.reports.on_route_orders.print', compact('title', 'informations', 'report_title', 'data'));
            $pdf->setPaper('A4', 'landscape');
            return $pdf->stream('on_route_orders_' . date('d_m_Y_H_i_s') . '.pdf');
        }

        $title = 'On Route Orders';
        $filter_link = Route('admin.on-route-orders.index');
        $deliverAgents = DeliveryAgent::where('status', 1)->orderBy('name', 'asc')->get();
        return $dataTable->render('admin.reports.on_route_orders.index', compact('title', 'filter_link', 'deliverAgents'));
    }

    public function deliverymanDeliveryStatement(Request $request, DeliveryManDeliveryStatementDataTable $dataTable)
    {
        if ($request->ajax() && $request->has('get_delivery_men')) {
            $query = DeliveryMan::where('status', 1);
            if ($request->store_id) {
                $query->where('store_id', $request->store_id);
            }
            $delivery_men = $query->orderBy('name', 'asc')->get();
            return response()->json(['status' => 'success', 'delivery_men' => $delivery_men]);
        }

        if (!is_null($request->view_orders)) {
            $query = Order::whereIn('status', ['Delivered', 'Collected']);
            if ($request->start_date & $request->end_date) {
                $query->where('date', '>=', date('Y-m-d', strtotime($request->start_date)))->where('date', '<=', date('Y-m-d', strtotime($request->end_date)));
            } elseif ($request->date) {
                $query->where('date', date('Y-m-d', strtotime($request->date)));
            }
            if ($request->delivery_man_id) {
                $delivery_man = DeliveryMan::find($request->delivery_man_id);
                $query->where('delivery_man_id', $request->delivery_man_id);
            }
            $data = $query->get();

            if (!is_null($request->print)) {
                if (Auth::user()->company_id) {
                    $company = Company::find(Auth::user()->company_id);
                    $title = $company->name;
                    $informations = $company->address . '</br>' . $company->phone . ', ' . $company->email . ', ' . $company->website;
                } else {
                    $title = 'Company Name Goes Here.';
                    $informations = 'Company address will goes here </br> Mobile: 0967XXXXXX, Email: youremail@gmail.com, www.website.com';
                }

                if ($request->start_date & $request->end_date) {
                    $report_title = @$delivery_man->name . '<br> <span class="text-sm">' . date('d-m-Y', strtotime($request->start_date)) . ' to ' . date('d-m-Y', strtotime($request->end_date)) . '</span>';
                } elseif ($request->date) {
                    $report_title = @$delivery_man->name . '<br> <span class="text-sm">' . date('d-m-Y', strtotime($request->date)) . '</span>';
                }

                $pdf = Pdf::loadView('admin.reports.delivery_man_delivery_statement.single_area_print', compact('title', 'informations', 'report_title', 'data'));
                $pdf->setPaper('A4', 'landscape');
                return $pdf->stream('delivery_man_wise_orders_' . date('d_m_Y_H_i_s') . '.pdf');
            }

            $filter_link = Route('admin.deliveryman-delivery-statement.index');
            $title = @$delivery_man->name;
            return view('admin.reports.delivery_man_delivery_statement.single_area', compact('data', 'filter_link', 'title'));
        }

        if (!is_null($request->print)) {
            if (Auth::user()->company_id) {
                $company = Company::find(Auth::user()->company_id);
                $title = $company->name;
                $informations = $company->address . '</br>' . $company->phone . ', ' . $company->email . ', ' . $company->website;
            } else {
                $title = 'Company Name Goes Here.';
                $informations = 'Company address will goes here </br> Mobile: 0967XXXXXX, Email: youremail@gmail.com, www.website.com';
            }

            $report_title = 'Delivery Man Delivery Statement <br> <span class="text-sm">' . date('F', strtotime('01-' . $request->month . '-' . $request->year)) . ' ' . date('Y', strtotime($request->year)) . '</span>';

            $query = DeliveryMan::where('status', 1);
            if ($request->store_id) {
                $query->where('store_id', $request->store_id);
            }
            if ($request->delivery_man_id) {
                $query->whereIn('id', $request->delivery_man_id);
            }
            $data = $query->get();

            $pdf = Pdf::loadView('admin.reports.delivery_man_delivery_statement.print', compact('title', 'informations', 'report_title', 'data'));
            $pdf->setPaper('A2', 'potrait');
            return $pdf->stream('delivery_man_delivery_statement_' . date('d_m_Y_H_i_s') . '.pdf');
        }

        $filter_link = Route('admin.deliveryman-delivery-statement.index');
        $title = 'Delivery Man Delivery Statement';
        $stores = Store::where('status', 1)->orderBy('name', 'asc')->get();
        $query = DeliveryMan::where('status', 1);
        if ($request->store_id) {
            $query->where('store_id', $request->store_id);
        }
        $delivery_men = $query->orderBy('name', 'asc')->get();
        return $dataTable->render('admin.reports.delivery_man_delivery_statement.index', compact('title', 'filter_link', 'stores', 'delivery_men'));
    }

    public function customerList(Request $request, CustomerListDataTable $dataTable)
    {
        if (!is_null($request->print)) {
            if (Auth::user()->company_id) {
                $company = Company::find(Auth::user()->company_id);
                $title = $company->name;
                $informations = $company->address . '</br>' . $company->phone . ', ' . $company->email . ', ' . $company->website;
            } else {
                $title = 'Company Name Goes Here.';
                $informations = 'Company address will goes here </br> Mobile: 0967XXXXXX, Email: youremail@gmail.com, www.website.com';
            }

            $report_title = 'Customer List Report';
            $query = Order::with('area');
            if (request('area_id')) {
                $query->where('area_id', request('area_id'));
            }
            $data = $query->groupBy('user_name')->groupBy('user_name')->select('*', DB::raw('SUM(total) as amount'), DB::raw('count(*) as total_count'))->orderBy('total_count', 'desc')->get();

            $pdf = Pdf::loadView('admin.reports.customer_list.print', compact('title', 'informations', 'report_title', 'data'));
            $pdf->setPaper('A4', 'landscape');
            return $pdf->stream('customer_list_' . date('d_m_Y_H_i_s') . '.pdf');
        }

        $filter_link = Route('admin.customer-list.index');
        $title = 'Customer List';
        $areas = Area::where('status', 1)->orderBy('name', 'asc')->get();
        return $dataTable->render('admin.reports.customer_list.index', compact('title', 'filter_link', 'areas'));
    }

    public function monthlySummarySheet(Request $request)
    {
        $start_sales = DB::table('view_online_sales')->where('is_stock', 1)->orderBy('date', 'asc')->first();
        // $start_date = date('Y-m-d', strtotime($start_sales->date));
        $start_date = date('Y-m-d', strtotime('2025-01-01'));
        $periods = CarbonPeriod::create($start_date, '1 month', date('Y-m-d'))->toArray();
        $data = [];
        $expense_heads = CoaSetup::where('head_code', 'like', '401%')->where('head_code', '!=', '401')->whereNotIn('head_code', ['40108', '40109', '403', '40107', '40105'])->get();
        foreach ($periods as $period) {
            $lineData = [
                'date' => $period->format('F Y'),
            ];
            $ld = $lineData;

            $start_date = date('Y-m-01', strtotime($period));
            $end_date = date('Y-m-t', strtotime($period));
            $product_ids = Product::pluck('id')->toArray();

            $total_purchases = 0;
            foreach ($product_ids as $product_id) {
                $lifting_amount = DB::table('view_liftings')->where('product_id', $product_id)->sum('amount') - DB::table('view_lifting_returns')->where('product_id', $product_id)->sum('amount');
                $lifting_qty = DB::table('view_liftings')->where('product_id', $product_id)->sum('qty') - DB::table('view_lifting_returns')->where('product_id', $product_id)->sum('qty');
                $avarage_rate = $lifting_qty > 0 ? $lifting_amount / $lifting_qty : 0;

                $sales_qty = DB::table('view_sales')->where('product_id', $product_id)->where('date', '>=', $start_date)->where('date', '<=', $end_date)->sum('qty');
                $sales_returns_qty = DB::table('view_sales_returns')->where('product_id', $product_id)->where('date', '>=', $start_date)->where('date', '<=', $end_date)->sum('qty');
                $online_sales_qty = OrderProduct::whereHas('order', function ($query) use ($start_date, $end_date) {
                    $query->where('collected_at', '>=', $start_date)->where('collected_at', '<=', $end_date)->whereIn('status', ['Collected']);
                })->where('product_id', $product_id)->sum(DB::raw('quantity - return_quantity'));
                $total_sales_qty = $sales_qty - $sales_returns_qty + $online_sales_qty;
                $total_purchases += $total_sales_qty * $avarage_rate;
            }

            $sales_amount = DB::table('view_sales')->where('date', '>=', $start_date)->where('date', '<=', $end_date)->sum('amount');
            $sales_returns_amount = DB::table('view_sales_returns')->where('date', '>=', $start_date)->where('date', '<=', $end_date)->sum('amount');
            $online_sales_amount = OrderProduct::whereHas('order', function ($query) use ($start_date, $end_date) {
                $query->where('collected_at', '>=', $start_date)->where('collected_at', '<=', $end_date)->whereIn('status', ['Collected']);
            })->sum(DB::raw('subtotal - return_amount - discount'));
            $shipping_charges = Order::where('collected_at', '>=', $start_date)->where('collected_at', '<=', $end_date)->whereIn('status', ['Collected'])->sum('shipping_charge');
            $sales = $sales_amount - $sales_returns_amount + $online_sales_amount + $shipping_charges;
            $ld['sales'] = $sales;
            $ld['purchases'] = round($total_purchases);

            $expense = 0;
            foreach ($expense_heads as $item) {
                $amount = AccountTransaction::where('voucher_date', '>=', $start_date)->where('voucher_date', '<=', $end_date)->where('coa_setup_id', $item->id)->sum('debit_amount');
                $expense += $amount;
                $ld[$item->head_name] = $amount;
            }

            $total_monthly_orders = Order::where('date', '>=', $start_date)->where('date', '<=', $end_date)->count();
            $total_monthly_delivered_orders = Order::whereIn('status', ['Delivered', 'Collected'])->where('date', '>=', $start_date)->where('date', '<=', $end_date)->count();
            $total_monthly_order_amount =  $total_monthly_orders > 0 ? Order::where('date', '>=', $start_date)->where('date', '<=', $end_date)->sum(DB::raw('due - total_return')) / $total_monthly_orders : 0;
            $ld['total_monthly_orders'] = $total_monthly_orders;
            $ld['total_monthly_delivered_orders'] = $total_monthly_delivered_orders;
            $ld['total_monthly_order_amount'] = $total_monthly_order_amount;

            // Management, Delivery, Sales Commission Cost
            $additionalCost = AdditionalCost::first();
            $costQuery = Order::whereIn('status', ['Collected'])->where('collected_at', '>=', $start_date)->where('collected_at', '<=', $end_date);
            $managementCost = round(((clone $costQuery)->count() * $additionalCost->management_cost) + ((clone $costQuery)->sum(DB::raw('due - total_return')) * $additionalCost->management_cost_percentage) / 100);
            $salesCommission = round(((clone $costQuery)->count() * $additionalCost->moderator_cost) + ((clone $costQuery)->sum(DB::raw('due - total_return')) * $additionalCost->moderator_cost_percentage) / 100);
            $ld['management_cost'] = $managementCost;
            $ld['sales_commission'] = $salesCommission;

            $deliveryCostQuery = Order::where('collected_at', '>=', $start_date)->where('collected_at', '<=', $end_date);
            $deliveryCost = (clone $deliveryCostQuery)->whereIn('status', ['Collected'])->sum('delivery_cost') + (clone $deliveryCostQuery)->whereIn('status', ['Returned'])->sum('return_cost');
            $ld['delivery_cost'] = $deliveryCost;
            // Management, Delivery, Sales Commission Cost


            // Product Wise Profit
            $products = Product::get(['id']);
            $liftings = DB::table('view_liftings')->whereNotNull('date')->where('date', '>=', $start_date)->where('date', '<=', $end_date)->get();
            $lifting_returns = DB::table('view_lifting_returns')->whereNotNull('date')->where('date', '>=', $start_date)->where('date', '<=', $end_date)->get();
            $Psales = DB::table('view_sales')->whereNotNull('date')->where('date', '>=', $start_date)->where('date', '<=', $end_date)->get();
            $online_sales = DB::table('view_online_sales')->where('is_stock', 1)->whereIn('status', ['Collected'])->where('collected_at', '>=', $start_date)->where('collected_at', '<=', $end_date)->get();
            $sales_returns = DB::table('view_sales_returns')->whereNotNull('date')->where('date', '>=', $start_date)->where('date', '<=', $end_date)->get();

            $totalProductProfit = 0;
            $totalLiftingAmount = 0;
            $totalSalesAmount = 0;
            foreach ($products as $row) {
                $sales_qty =
                    $online_sales->where('product_id', $row->id)->sum('qty') +
                    $Psales->where('product_id', $row->id)->sum('qty') -
                    $sales_returns->where('product_id', $row->id)->sum('qty');
                if ($sales_qty == 0) {
                    continue;
                }
                $sales_amount =
                    $online_sales->where('product_id', $row->id)->sum('amount') +
                    $Psales->where('product_id', $row->id)->sum('amount') -
                    $sales_returns->where('product_id', $row->id)->sum('amount');

                $lifting_amount = $liftings->where('product_id', $row->id)->sum('amount') - $lifting_returns->where('product_id', $row->id)->sum('amount');
                $lifting_qty = $liftings->where('product_id', $row->id)->sum('qty') - $lifting_returns->where('product_id', $row->id)->sum('qty');
                if ($lifting_qty == 0) {
                    $customQuery = DB::table('view_liftings')->whereNotNull('date')->where('product_id', $row->id)->orderBy('date', 'desc')->first();
                    $lifting_amount = $customQuery->amount;
                    $lifting_qty = $customQuery->qty;
                }

                $avarage_rate = $lifting_amount / $lifting_qty;
                $absolute_lifting = $sales_qty * $avarage_rate;

                $totalLiftingAmount += $absolute_lifting;
                $totalSalesAmount += $sales_amount;
                $totalProductProfit += $sales_amount - $absolute_lifting;
            }

            if ($totalLiftingAmount != 0 && $totalSalesAmount != 0) {
                $percentage = round(($totalProductProfit / $totalSalesAmount) * 100, 2);
            } elseif ($totalLiftingAmount == 0 && $totalSalesAmount != 0) {
                $percentage = 100;
            } else {
                $percentage = 0;
            }

            $totalShare = \App\Models\Invest::where('approved', 1)->where('sattled', 0)->sum('amount');
            $investorPercentage = ($percentage * 20) / 100;
            $profitDistribution = round(($totalShare * $investorPercentage) / 100, 2);
            $ld['profit_distribution'] = $profitDistribution;
            // Product Wise Profit


            $total_profit = $sales - $total_purchases - $expense - $managementCost - $salesCommission - $deliveryCost - $profitDistribution;
            $ld['net_profit'] = $total_profit;

            array_push($data, $ld);
        }

        if (!is_null($request->print)) {
            if (Auth::user()->company_id) {
                $company = Company::find(Auth::user()->company_id);
                $title = $company->name;
                $informations = $company->address . '</br>' . $company->phone . ', ' . $company->email . ', ' . $company->website;
            } else {
                $title = 'Company Name Goes Here.';
                $informations = 'Company address will goes here </br> Mobile: 0967XXXXXX, Email: youremail@gmail.com, www.website.com';
            }

            $report_title = 'Monthly Summary Sheet';
            $pdf = Pdf::loadView('admin.reports.monthly_summary_sheet.print', compact('title', 'informations', 'report_title', 'expense_heads', 'data'));
            $pdf->setPaper('A1');
            return $pdf->stream('monthly_summary_sheet_' . date('d_m_Y_H_i_s') . '.pdf');
        }

        $title = 'Monthly Summary Sheet';
        $filter_form = '<form action="' . Route('admin.monthly-summary-sheet.index') . '" id="filter_form" class="filter_form" target="_blank" method="GET"><input type="hidden" name="print" value="true"></form>';
        return view('admin.reports.monthly_summary_sheet.index', compact('title', 'expense_heads', 'data', 'filter_form'));
    }

    public function monthlyChart(Request $request)
    {
        if ($request->ajax()) {
            $months = [];
            $sales = [];
            $profit = [];
            $currentMonth = Carbon::now();
            $expense_heads = CoaSetup::where('head_code', 'like', '401%')->whereNot('head_code', '401')->pluck('id')->toArray();
            for ($i = 0; $i < 12; $i++) {
                $month = clone $currentMonth;
                $month = $month->modify("-$i month")->format('Y-m');
                $months[] = $month;

                $start_date = date('Y-m-01', strtotime($month));
                $end_date = date('Y-m-t', strtotime($start_date));
                $product_ids = DB::table('view_liftings')->groupBy('product_id')->pluck('product_id')->toArray();

                $purchases = 0;
                foreach ($product_ids as $product_id) {
                    $lifting_amount = DB::table('view_liftings')->where('product_id', $product_id)->sum('amount') - DB::table('view_lifting_returns')->where('product_id', $product_id)->sum('amount');
                    $lifting_qty = DB::table('view_liftings')->where('product_id', $product_id)->sum('qty') - DB::table('view_lifting_returns')->where('product_id', $product_id)->sum('qty');
                    $avarage_rate = $lifting_amount / $lifting_qty;

                    $sales_qty = DB::table('view_sales')->where('product_id', $product_id)->where('date', '>=', $start_date)->where('date', '<=', $end_date)->sum('qty');
                    $sales_returns_qty = DB::table('view_sales_returns')->where('product_id', $product_id)->where('date', '>=', $start_date)->where('date', '<=', $end_date)->sum('qty');
                    $online_sales_qty = OrderProduct::whereHas('order', function ($query) use ($start_date, $end_date) {
                        $query->where('date', '>=', $start_date)->where('date', '<=', $end_date)->where('collected', 1);
                    })->where('product_id', $product_id)->sum('quantity');
                    $total_sales_qty = $sales_qty - $sales_returns_qty + $online_sales_qty;
                    $purchases += $total_sales_qty * $avarage_rate;
                }

                $sales_amount = DB::table('view_sales')->where('date', '>=', $start_date)->where('date', '<=', $end_date)->sum('amount');
                $sales_returns_amount = DB::table('view_sales_returns')->where('date', '>=', $start_date)->where('date', '<=', $end_date)->sum('amount');
                $online_sales_amount = OrderProduct::whereHas('order', function ($query) use ($start_date, $end_date) {
                    $query->where('date', '>=', $start_date)->where('date', '<=', $end_date)->where('collected', 1);
                })->sum(DB::raw('subtotal - return_amount'));
                $monthly_sales = $sales_amount - $sales_returns_amount + $online_sales_amount;

                $expense = AccountTransaction::where('voucher_date', '>=', $start_date)->where('voucher_date', '<=', $end_date)->whereIn('coa_setup_id', $expense_heads)->sum('debit_amount');
                $total_profit = $monthly_sales - round($purchases) - $expense;

                $sales[] = $monthly_sales;
                $profit[] = $total_profit;
            }

            $months = array_reverse($months);
            $sales = array_reverse($sales);
            $profit = array_reverse($profit);
            return response()->json(['status' => 'success', 'months' => $months, 'sales' => $sales, 'profit' => $profit]);
        }

        $title = 'Monthly Chart';
        $filter_form = '';
        return view('admin.reports.monthly_chart.index', compact('title', 'filter_form'));
    }

    public function receivePayment(Request $request)
    {
        $date_range = explode('to', $request->date_range);
        $start_date = !is_null($request->date_range) ? date('Y-m-d', strtotime($date_range[0])) : date('Y-m-01');
        $end_date = !is_null($request->date_range) ? date('Y-m-d', strtotime($date_range[1])) : date('Y-m-t');

        $data = array();
        if ($request->has('filter')) {
            $data = AccountTransaction::with('coa')
                ->where('voucher_date', '>=', $start_date)
                ->where('voucher_date', '<=', $end_date)
                ->where(function ($q) {
                    $q->where('coa_head_code', 'LIKE', '10103%')
                        ->orWhere('coa_head_code', 'LIKE', '10102%');
                })
                ->groupBy('coa_setup_id')
                ->select('coa_head_code', 'coa_setup_id', DB::raw('SUM(debit_amount) as debit_amount'), DB::raw('SUM(credit_amount) as credit_amount'))
                ->get();
        }

        if (!is_null($request->print)) {
            $report_title = 'Receive Payment Report <br> <span class="text-sm">' . date('d-m-Y', strtotime($start_date)) . ' To ' . date('d-m-Y', strtotime($end_date)) . '</span>';
            $pdf = Pdf::loadView('admin.reports.recieve_payment.print', compact('report_title', 'data'));
            return $pdf->stream('recieve_payment_' . date('d_m_Y_h_i_s') . '.pdf');
        }

        $title = 'Receive Payment Report';
        $buttons = '<button type="button" class="btn btn-sm btn-primary text-uppercase getPdf">Print</button><button type="submit" class="btn btn-sm btn-primary text-uppercase" id="filter_btn">Search</button>';
        $filter_link = Route('admin.receive-payment.index');
        return view('admin.reports.recieve_payment.index', compact('title', 'start_date', 'end_date', 'data', 'buttons', 'filter_link'));
    }

    public function receivePaymentHeadDetails(Request $request)
    {
        $title = 'Head Transactions';
        $data = AccountTransaction::with('coa')
            ->where('voucher_date', '>=', $request->start_date)
            ->where('voucher_date', '<=', $request->end_date)
            ->where('coa_setup_id', $request->coa_setup_id)
            ->get();

        if ($request->has('print')) {
            $report_title = 'Head Transactions Details <br> <span class="text-sm">' . date('d-m-Y', strtotime($request->start_date)) . ' To ' . date('d-m-Y', strtotime($request->end_date)) . '</span>';
            $pdf = Pdf::loadView('admin.reports.recieve_payment.details_print', compact('report_title', 'data'));
            return $pdf->stream('transaction_details' . date('d_m_Y_h_i_s') . '.pdf');
        }
        return view('admin.reports.recieve_payment.details', compact('title', 'data'));
    }

    public function collectionReport(Request $request, CollectionReportDataTable $dataTable)
    {
        if (!is_null($request->print)) {
            $start_date = !is_null(request('start_date')) ? date('Y-m-d', strtotime(request('start_date'))) : date('Y-m-01');
            $end_date = !is_null(request('end_date')) ? date('Y-m-d', strtotime(request('end_date'))) : date('Y-m-t');
            $query = Order::where('collected_at', '>=', $start_date)->where('collected_at', '<=', $end_date);
            if (request('delivery_man_id')) $query->where('delivery_man_id', request('delivery_man_id'));
            if (request('store_id')) $query->where('store_id', request('store_id'));
            $data = $query->where('collected', 1)->get();

            if (Auth::user()->company_id) {
                $company = Company::find(Auth::user()->company_id);
                $title = $company->name;
                $informations = $company->address . '</br>' . $company->phone . ', ' . $company->email . ', ' . $company->website;
            } else {
                $title = 'Company Name Goes Here.';
                $informations = 'Company address will goes here </br> Mobile: 0967XXXXXX, Email: youremail@gmail.com, www.website.com';
            }

            $report_title = 'Daily Collection Report';
            $pdf = Pdf::loadView('admin.reports.collection_report.print', compact('title', 'informations', 'report_title', 'data'));
            $pdf->setPaper('A3');
            return $pdf->stream('collection_report_' . date('d_m_Y_H_i_s') . '.pdf');
        }

        $title = 'Daily Collection Report';
        $delivery_men = DeliveryMan::where('status', 1)->get();
        $stores = Store::where('status', 1)->get();
        return $dataTable->render('admin.reports.collection_report.index', compact('title', 'delivery_men', 'stores'));
    }

    public function moderatorOrders(Request $request, ModeratorOrdersDataTable $dataTable)
    {
        if (!is_null($request->view_orders)) {
            $query = Order::query();
            $start_date = request('start_date') ? date('Y-m-d', strtotime(request('start_date'))) : date('Y-m-01');
            $end_date = request('end_date') ? date('Y-m-d', strtotime(request('end_date'))) : date('Y-m-t');
            if ($request->status) {
                $query->where('status', $request->status);
            }
            if ($request->moderator_id) {
                $moderator = User::find($request->moderator_id);
                $query->where('created_by', $request->moderator_id);
            }
            $query->where('date', '>=', $start_date)->where('date', '<=', $end_date);
            $data = $query->get();

            if (!is_null($request->print)) {
                if (Auth::user()->company_id) {
                    $company = Company::find(Auth::user()->company_id);
                    $title = $company->name;
                    $informations = $company->address . '</br>' . $company->phone . ', ' . $company->email . ', ' . $company->website;
                } else {
                    $title = 'Company Name Goes Here.';
                    $informations = 'Company address will goes here </br> Mobile: 0967XXXXXX, Email: youremail@gmail.com, www.website.com';
                }

                $report_title = @$moderator->name;

                $pdf = Pdf::loadView('admin.reports.moderator_orders.single_print', compact('title', 'informations', 'report_title', 'data'));
                $pdf->setPaper('A2');
                return $pdf->stream('moderator_orders_' . date('d_m_Y_H_i_s') . '.pdf');
            }

            $filter_link = Route('admin.moderator-orders.index');
            $title = @$moderator->name;
            return view('admin.reports.moderator_orders.single', compact('data', 'filter_link', 'title'));
        }

        if (!is_null($request->print)) {
            if (Auth::user()->company_id) {
                $company = Company::find(Auth::user()->company_id);
                $title = $company->name;
                $informations = $company->address . '</br>' . $company->phone . ', ' . $company->email . ', ' . $company->website;
            } else {
                $title = 'Company Name Goes Here.';
                $informations = 'Company address will goes here </br> Mobile: 0967XXXXXX, Email: youremail@gmail.com, www.website.com';
            }

            $report_title = 'Moderator Orders';

            $query = User::with('moderatorOrders');
            if (Auth::user()->hasRole('Moderator')) {
                $query->where('id', Auth::user()->id);
            }
            if (request('moderator_id')) {
                $query->whereIn('id', request('moderator_id'));
            }
            $data = $query->whereHas('moderatorOrders', function ($q) {
                $start_date = request('start_date') ? date('Y-m-d', strtotime(request('start_date'))) : date('Y-m-01');
                $end_date = request('end_date') ? date('Y-m-d', strtotime(request('end_date'))) : date('Y-m-t');
                $q->where('date', '>=', $start_date)->where('date', '<=', $end_date);
                if (!is_null(request('store_id'))) {
                    $q->where('store_id', request('store_id'));
                }
            })->where('status', 1)->orderBy('name', 'asc')->get();

            $pdf = Pdf::loadView('admin.reports.moderator_orders.print', compact('title', 'informations', 'report_title', 'data'));
            $pdf->setPaper('A4', 'landscape');
            return $pdf->stream('moderator_orders_' . date('d_m_Y_H_i_s') . '.pdf');
        }

        $title = 'Moderator Orders';
        $query = User::query();
        if (Auth::user()->hasRole('Moderator')) {
            $query->where('id', Auth::user()->id);
        }
        $moderators = $query->where('status', 1)->orderBy('name', 'asc')->get();
        $stores = Store::where('status', 1)->get();
        return $dataTable->render('admin.reports.moderator_orders.index', compact('title', 'moderators', 'stores'));
    }

    public function deliverymanOrders(Request $request, DeliverymanOrdersDataTable $dataTable)
    {
        if ($request->ajax() && $request->has('get_delivery_men')) {
            $query = DeliveryMan::where('status', 1);
            if ($request->store_id) {
                $query->where('store_id', $request->store_id);
            }
            $delivery_men = $query->orderBy('name', 'asc')->get();
            return response()->json(['status' => 'success', 'delivery_men' => $delivery_men]);
        }

        if (!is_null($request->view_orders)) {
            $query = Order::query();
            $start_date = request('start_date') ? date('Y-m-d', strtotime(request('start_date'))) : date('Y-m-01');
            $end_date = request('end_date') ? date('Y-m-d', strtotime(request('end_date'))) : date('Y-m-t');
            if ($request->status) {
                $query->where('status', $request->status);
            }
            if ($request->delivery_agent_id) {
                $delivery_man = DeliveryAgent::find($request->delivery_agent_id);
                $query->where('delivery_agent_id', $request->delivery_agent_id);
            }
            $query->where('date', '>=', $start_date)->where('date', '<=', $end_date);
            $data = $query->get();

            if (!is_null($request->print)) {
                if (Auth::user()->company_id) {
                    $company = Company::find(Auth::user()->company_id);
                    $title = $company->name;
                    $informations = $company->address . '</br>' . $company->phone . ', ' . $company->email . ', ' . $company->website;
                } else {
                    $title = 'Company Name Goes Here.';
                    $informations = 'Company address will goes here </br> Mobile: 0967XXXXXX, Email: youremail@gmail.com, www.website.com';
                }

                $report_title = @$delivery_man->name;

                $pdf = Pdf::loadView('admin.reports.deliveryman_orders.single_deliveryman_print', compact('title', 'informations', 'report_title', 'data'));
                $pdf->setPaper('A2');
                return $pdf->stream('deliveryman_orders_' . date('d_m_Y_H_i_s') . '.pdf');
            }

            $filter_link = Route('admin.deliveryman-orders.index');
            $title = @$delivery_man->name;
            return view('admin.reports.deliveryman_orders.single_deliveryman', compact('data', 'filter_link', 'title'));
        }

        if (!is_null($request->print)) {
            if (Auth::user()->company_id) {
                $company = Company::find(Auth::user()->company_id);
                $title = $company->name;
                $informations = $company->address . '</br>' . $company->phone . ', ' . $company->email . ', ' . $company->website;
            } else {
                $title = 'Company Name Goes Here.';
                $informations = 'Company address will goes here </br> Mobile: 0967XXXXXX, Email: youremail@gmail.com, www.website.com';
            }

            $report_title = 'Delivery Agent Orders';

            $query = DeliveryAgent::with('orders')->whereHas('orders', function ($q) {
                $start_date = request('start_date') ? date('Y-m-d', strtotime(request('start_date'))) : date('Y-m-01');
                $end_date = request('end_date') ? date('Y-m-d', strtotime(request('end_date'))) : date('Y-m-t');
                $q->where('date', '>=', $start_date)->where('date', '<=', $end_date);
                if (request('store_id')) {
                    $q->where('store_id', request('store_id'));
                }
            });
            $data = $query->where('status', 1)->get();

            $pdf = Pdf::loadView('admin.reports.deliveryman_orders.print', compact('title', 'informations', 'report_title', 'data'));
            $pdf->setPaper('A4', 'potrait');
            return $pdf->stream('deliveryman_orders_' . date('d_m_Y_H_i_s') . '.pdf');
        }

        $title = 'Delivery Agent Orders';
        $delivery_agents = DeliveryAgent::where('status', 1)->orderBy('name', 'asc')->get();
        $stores = Store::where('status', 1)->get();
        return $dataTable->render('admin.reports.deliveryman_orders.index', compact('title', 'delivery_agents', 'stores'));
    }

    public function courierReport(Request $request, CourierReportDataTable $dataTable)
    {
        if (!is_null($request->print)) {
            if (Auth::user()->company_id) {
                $company = Company::find(Auth::user()->company_id);
                $title = $company->name;
                $informations = $company->address . '</br>' . $company->phone . ', ' . $company->email . ', ' . $company->website;
            } else {
                $title = 'Company Name Goes Here.';
                $informations = 'Company address will goes here </br> Mobile: 0967XXXXXX, Email: youremail@gmail.com, www.website.com';
            }

            $report_title = 'Courier Report';
            $start_date = request('start_date') ? date('Y-m-d', strtotime(request('start_date'))) : date('Y-m-01');
            $end_date = request('end_date') ? date('Y-m-d', strtotime(request('end_date'))) : date('Y-m-t');

            $query = Order::with(['store', 'agent']);
            if (!is_null(request('store_id'))) {
                $query->where('store_id', request('store_id'));
            }
            if (!is_null(request('delivery_agent_id'))) {
                $query->where('delivery_agent_id', request('delivery_agent_id'));
            }
            $data = $query->where('delivered_at', '>=', $start_date)->where('delivered_at', '<=', $end_date)->whereIn('status', ['Delivered', 'Collected', 'Returned'])->get();

            $pdf = Pdf::loadView('admin.reports.courier_report.print', compact('title', 'informations', 'report_title', 'data'));
            $pdf->setPaper('A2');
            return $pdf->stream('courier_report_' . date('d_m_Y_H_i_s') . '.pdf');
        }

        $title = 'Courier Report';
        $stores = Store::where('status', 1)->get();
        $agents = DeliveryAgent::where('status', 1)->orderBy('name', 'asc')->get();
        return $dataTable->render('admin.reports.courier_report.index', compact('title', 'agents', 'stores'));
    }
}
