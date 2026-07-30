<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\Product;
use App\Models\CoaSetup;
use App\Models\Investor;
use App\Models\OrderProduct;
use Illuminate\Http\Request;
use App\Models\AdditionalCost;
use App\Models\InvestRenewList;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\ProfitDistribution;
use Illuminate\Support\Facades\DB;
use App\Models\AccountTransaction;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\ProfitDistributionList;
use Yajra\DataTables\Facades\DataTables;
use App\Services\ActionButtons\ActionButtons;

class ProfitDistributionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (!is_null($request->generate)) {
            $title = 'Generate Profit';
            $serial_no = $this->SerialNo();
            $generatedData = [];
            $start_date = date('Y-m-01', strtotime($request->month . '-' . $request->year));
            $end_date = date('Y-m-t', strtotime($request->month . '-' . $request->year));
            $check_data = ProfitDistribution::where('year', $request->year)->where('month', $request->month)->first();
            if (is_null($check_data)) {

                // Product Wise Profit
                $products = Product::get(['id']);
                $liftings = DB::table('view_liftings')->whereNotNull('date')->where('date', '>=', $start_date)->where('date', '<=', $end_date)->get();
                $lifting_returns = DB::table('view_lifting_returns')->whereNotNull('date')->where('date', '>=', $start_date)->where('date', '<=', $end_date)->get();
                $sales = DB::table('view_sales')->whereNotNull('date')->where('date', '>=', $start_date)->where('date', '<=', $end_date)->get();
                $online_sales = DB::table('view_online_sales')->where('is_stock', 1)->whereIn('status', ['Collected'])->where('collected_at', '>=', $start_date)->where('collected_at', '<=', $end_date)->get();
                $sales_returns = DB::table('view_sales_returns')->whereNotNull('date')->where('date', '>=', $start_date)->where('date', '<=', $end_date)->get();

                $totalProductProfit = 0;
                $totalLiftingAmount = 0;
                $totalSalesAmount = 0;
                foreach ($products as $row) {
                    $sales_qty =
                        $online_sales->where('product_id', $row->id)->sum('qty') +
                        $sales->where('product_id', $row->id)->sum('qty') -
                        $sales_returns->where('product_id', $row->id)->sum('qty');
                    if ($sales_qty == 0) {
                        continue;
                    }
                    $sales_amount =
                        $online_sales->where('product_id', $row->id)->sum('amount') +
                        $sales->where('product_id', $row->id)->sum('amount') -
                        $sales_returns->where('product_id', $row->id)->sum('amount');

                    $lifting_amount = $liftings->where('product_id', $row->id)->sum('amount') - $lifting_returns->where('product_id', $row->id)->sum('amount');
                    $lifting_qty = $liftings->where('product_id', $row->id)->sum('qty') - $lifting_returns->where('product_id', $row->id)->sum('qty');
                    $avarage_rate = $lifting_qty > 0 ? $lifting_amount / $lifting_qty : 0;
                    $absolute_lifting = $sales_qty * $avarage_rate;

                    $totalLiftingAmount += $absolute_lifting;
                    $totalSalesAmount += $sales_amount;
                    $profit = $sales_amount - $absolute_lifting > 0 ? $sales_amount - $absolute_lifting : 0;
                    $totalProductProfit += $profit;
                }

                if ($totalLiftingAmount != 0 && $totalSalesAmount != 0) {
                    $percentage = round(($totalProductProfit / $totalSalesAmount) * 100, 2);
                } elseif ($totalLiftingAmount == 0 && $totalSalesAmount != 0) {
                    $percentage = 100;
                } else {
                    $percentage = 0;
                }

                $totalShare = InvestRenewList::where('month', $request->month)->where('year', $request->year)->sum('amount');
                $totalShareQty = InvestRenewList::where('month', $request->month)->where('year', $request->year)->sum('qty');
                $investorPercentage = ($percentage * 20) / 100;
                $profitDistribution = round(($totalShare * $investorPercentage) / 100, 2);
                $perShareProfit = $totalShareQty > 0 ? $profitDistribution / $totalShareQty : 0;
                // Product Wise Profit

                $orders = Order::get();
                $product_ids = Product::pluck('id')->toArray();

                $total_monthly_orders = Order::where('date', '>=', $start_date)->where('date', '<=', $end_date)->count();
                $deliveredOrdersQuery = Order::whereIn('status', ['Delivered', 'Collected'])->where('date', '>=', $start_date)->where('date', '<=', $end_date);
                $total_monthly_delivered_orders = (clone $deliveredOrdersQuery)->count();
                $total_monthly_order_amount = $total_monthly_orders > 0 ? Order::where('date', '>=', $start_date)->where('date', '<=', $end_date)->sum(DB::raw('due - total_return')) / $total_monthly_orders : 0;

                // Management, Delivery, Sales Commission Cost
                $additionalCost = AdditionalCost::first();
                $costQuery = Order::whereIn('status', ['Collected'])->where('collected_at', '>=', $start_date)->where('collected_at', '<=', $end_date);
                $managementCost = round(((clone $costQuery)->count() * $additionalCost->management_cost) + ((clone $costQuery)->sum(DB::raw('due - total_return')) * $additionalCost->management_cost_percentage) / 100);
                $salesCommission = round(((clone $costQuery)->count() * $additionalCost->moderator_cost) + ((clone $costQuery)->sum(DB::raw('due - total_return')) * $additionalCost->moderator_cost_percentage) / 100);

                $deliveryCostQuery = Order::where('collected_at', '>=', $start_date)->where('collected_at', '<=', $end_date);
                $deliveryCost = (clone $deliveryCostQuery)->whereIn('status', ['Collected'])->sum('delivery_cost') + (clone $deliveryCostQuery)->whereIn('status', ['Returned'])->sum('return_cost');
                // Management, Delivery, Sales Commission Cost

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
                $total_sales = $sales_amount - $sales_returns_amount + $online_sales_amount + $shipping_charges;
                $expense_heads = CoaSetup::where('head_code', 'like', '401%')->where('head_code', '!=', '401')->whereNotIn('head_code', ['40108', '40109', '403', '40107', '40105'])->pluck('id')->toArray();

                $monthly_cost = AccountTransaction::where('voucher_date', '>=', $start_date)->where('voucher_date', '<=', $end_date)
                    ->whereIn('coa_setup_id', $expense_heads)
                    ->sum('debit_amount');

                $netProfit = $total_sales - $total_purchases - $monthly_cost - $managementCost - $deliveryCost - $profitDistribution - $salesCommission;

                $all_renews = InvestRenewList::where('month', $request->month)->where('year', $request->year)->get();
                $investors = Investor::whereHas('renews', function ($query) use ($request) {
                    $query->where('month', $request->month)->where('year', $request->year);
                })->orderBy('name', 'asc')->get();

                $generatedData = [
                    'sales_amount' => round($total_sales),
                    'purchase_amount' => round($total_purchases),
                    'monthly_cost' => round($monthly_cost),
                    'management_cost' => round($managementCost),
                    'delivery_cost' => round($deliveryCost),
                    'investor_profit' => round($profitDistribution),
                    'sales_commission' => round($salesCommission),
                    'net_profit' => round($netProfit),
                    'investors' => $investors,
                    'all_renews' => $all_renews,
                    'perShareProfit' => $perShareProfit,
                ];
            }
            return view('admin.profit_distribution.create', compact('title', 'serial_no', 'generatedData'));
        }

        if (request()->ajax()) {
            $model = ProfitDistribution::orderBy('id', 'desc');
            $type = request('type');
            if (!empty($type) && $type == 'trash') {
                $model->onlyTrashed();
            }
            return DataTables::eloquent($model)
                ->addIndexColumn()
                ->addColumn('date', function ($row) {
                    return date('d-m-Y', strtotime(@$row->date));
                })
                ->addColumn('investors', function ($row) {
                    return $row->list->pluck('investor.name')->toArray();
                })
                ->addColumn('total_share', function ($row) {
                    return $row->list->sum('invest_qty');
                })
                ->addColumn('actions', function ($row) {
                    $type = request('type');
                    $data = [
                        'id' => $row->id,
                        'edit' => !empty($type) && $type == 'trash' ? false : true,
                    ];

                    $delete = 'yes';
                    $edit = 'yes';
                    $paid = $row->list->where('paid', 1)->count();
                    if ($paid > 0) {
                        $delete = 'no';
                        $edit = 'no';
                    }
                    $addition_btns = [
                        [
                            'parameter' => true,
                            'target' => '_self',
                            'title' => 'View Profit',
                            'route' => 'admin.profit-distribute.show',
                            'icon' => '<i class="fas fa-eye"></i>',
                            'class' => 'btn btn-sm btn-primary mw-fit',
                        ],
                        [
                            'parameter' => true,
                            'target' => '_self',
                            'title' => 'Print Profit',
                            'route' => 'admin.profit-distribute.print',
                            'icon' => '<i class="fas fa-print"></i>',
                            'class' => 'btn btn-sm btn-secondary mw-fit',
                        ]
                    ];

                    return ActionButtons::actions($data, $addition_btns, $delete, $edit);
                })
                ->rawColumns(['checkbox', 'actions'])
                ->make(true);
        }

        $title = "Profit Distributions";
        return view('admin.profit_distribution.index', compact('title'));
    }

    public function SerialNo()
    {
        $data = ProfitDistribution::withTrashed()->select(['serial_no'])->whereDate('created_at', '>=', date('Y-m-01'))->whereDate('created_at', '<=', date('Y-m-t'))->orderBy('id', 'desc')->first();
        if ($data) {
            $trim = str_replace("PD", '', $data->serial_no);
            $dataPrefix = (int)$trim + 1;
            $SerialNo = "PD" . $dataPrefix;
        } else {
            $SerialNo = "PD" . date('y') . date('m') . '001';
        }
        return $SerialNo;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $title = 'Generate Profit';
        $current_time = Carbon::now();
        $serial_no = $this->SerialNo();
        $data = [];
        return view('admin.profit_distribution.create', compact('title', 'current_time', 'serial_no', 'data'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'year' => 'required',
            'month' => 'required',
            'date' => 'required'
        ]);

        DB::transaction(function () use ($request) {
            $data = ProfitDistribution::create([
                'serial_no' => $this->SerialNo(),
                'year' => $request->year,
                'month' => $request->month,
                'date' =>  date('Y-m-d', strtotime($request->date)),
                'sales_amount' => $request->sales_amount,
                'purchase_amount' => $request->purchase_amount,
                'monthly_cost' => $request->monthly_cost,
                'management_cost' => $request->management_cost,
                'delivery_cost' => $request->delivery_cost,
                'investor_profit' => $request->investor_profit,
                'sales_commission' => $request->sales_commission,
                'net_profit' => $request->net_profit,
                'created_by' => Auth::user()->id,
            ]);

            foreach ($request->investor_id as $investor_id) {
                ProfitDistributionList::create([
                    'profit_distribution_id' => $data->id,
                    'investor_id' => $investor_id,
                    'date' => date('Y-m-d', strtotime($request->date)),
                    'month' => $request->month,
                    'year' => $request->year,
                    'invest_qty' => $request->qty[$investor_id],
                    'invest_amount' => $request->amount[$investor_id],
                    'profit_amount' => $request->profit_amount[$investor_id]
                ]);
            }
        });

        return redirect()->route('admin.profit-distribute.index')->withSuccessMessage('Added Successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        if (!is_null($request->generate)) {
            $start_date = date('Y-m-01', strtotime($request->month . '-' . $request->year));
            $end_date = date('Y-m-t', strtotime($request->month . '-' . $request->year));
            $check_data = ProfitDistribution::whereNotIn('id', [$id])->where('year', $request->year)->where('month', $request->month)->first();
            if (is_null($check_data)) {

                // Product Wise Profit
                $products = Product::get(['id']);
                $liftings = DB::table('view_liftings')->whereNotNull('date')->where('date', '>=', $start_date)->where('date', '<=', $end_date)->get();
                $lifting_returns = DB::table('view_lifting_returns')->whereNotNull('date')->where('date', '>=', $start_date)->where('date', '<=', $end_date)->get();
                $sales = DB::table('view_sales')->whereNotNull('date')->where('date', '>=', $start_date)->where('date', '<=', $end_date)->get();
                $online_sales = DB::table('view_online_sales')->where('is_stock', 1)->whereIn('status', ['Collected'])->where('collected_at', '>=', $start_date)->where('collected_at', '<=', $end_date)->get();
                $sales_returns = DB::table('view_sales_returns')->whereNotNull('date')->where('date', '>=', $start_date)->where('date', '<=', $end_date)->get();

                $totalProductProfit = 0;
                $totalLiftingAmount = 0;
                $totalSalesAmount = 0;
                foreach ($products as $row) {
                    $sales_qty =
                        $online_sales->where('product_id', $row->id)->sum('qty') +
                        $sales->where('product_id', $row->id)->sum('qty') -
                        $sales_returns->where('product_id', $row->id)->sum('qty');
                    if ($sales_qty == 0) {
                        continue;
                    }
                    $sales_amount =
                        $online_sales->where('product_id', $row->id)->sum('amount') +
                        $sales->where('product_id', $row->id)->sum('amount') -
                        $sales_returns->where('product_id', $row->id)->sum('amount');

                    $lifting_amount = $liftings->where('product_id', $row->id)->sum('amount') - $lifting_returns->where('product_id', $row->id)->sum('amount');
                    $lifting_qty = $liftings->where('product_id', $row->id)->sum('qty') - $lifting_returns->where('product_id', $row->id)->sum('qty');
                    $avarage_rate = $lifting_qty > 0 ? $lifting_amount / $lifting_qty : 0;
                    $absolute_lifting = $sales_qty * $avarage_rate;

                    $totalLiftingAmount += $absolute_lifting;
                    $totalSalesAmount += $sales_amount;
                    $profit = $sales_amount - $absolute_lifting > 0 ? $sales_amount - $absolute_lifting : 0;
                    $totalProductProfit += $profit;
                }

                if ($totalLiftingAmount != 0 && $totalSalesAmount != 0) {
                    $percentage = round(($totalProductProfit / $totalSalesAmount) * 100, 2);
                } elseif ($totalLiftingAmount == 0 && $totalSalesAmount != 0) {
                    $percentage = 100;
                } else {
                    $percentage = 0;
                }

                $totalShare = InvestRenewList::where('month', $request->month)->where('year', $request->year)->sum('amount');
                $totalShareQty = InvestRenewList::where('month', $request->month)->where('year', $request->year)->sum('qty');
                $investorPercentage = ($percentage * 20) / 100;
                $profitDistribution = round(($totalShare * $investorPercentage) / 100, 2);
                $perShareProfit = $totalShareQty > 0 ? $profitDistribution / $totalShareQty : 0;
                // Product Wise Profit

                $orders = Order::get();
                $product_ids = Product::pluck('id')->toArray();

                $total_monthly_orders = Order::where('date', '>=', $start_date)->where('date', '<=', $end_date)->count();
                $deliveredOrdersQuery = Order::whereIn('status', ['Delivered', 'Collected'])->where('date', '>=', $start_date)->where('date', '<=', $end_date);
                $total_monthly_delivered_orders = (clone $deliveredOrdersQuery)->count();
                $total_monthly_order_amount = $total_monthly_orders > 0 ? Order::where('date', '>=', $start_date)->where('date', '<=', $end_date)->sum(DB::raw('due - total_return')) / $total_monthly_orders : 0;

                // Management, Delivery, Sales Commission Cost
                $additionalCost = AdditionalCost::first();
                $costQuery = Order::whereIn('status', ['Collected'])->where('collected_at', '>=', $start_date)->where('collected_at', '<=', $end_date);
                $managementCost = round(((clone $costQuery)->count() * $additionalCost->management_cost) + ((clone $costQuery)->sum(DB::raw('due - total_return')) * $additionalCost->management_cost_percentage) / 100);
                $salesCommission = round(((clone $costQuery)->count() * $additionalCost->moderator_cost) + ((clone $costQuery)->sum(DB::raw('due - total_return')) * $additionalCost->moderator_cost_percentage) / 100);

                $deliveryCostQuery = Order::where('collected_at', '>=', $start_date)->where('collected_at', '<=', $end_date);
                $deliveryCost = (clone $deliveryCostQuery)->whereIn('status', ['Collected'])->sum('delivery_cost') + (clone $deliveryCostQuery)->whereIn('status', ['Returned'])->sum('return_cost');
                // Management, Delivery, Sales Commission Cost

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
                $total_sales = $sales_amount - $sales_returns_amount + $online_sales_amount + $shipping_charges;
                $expense_heads = CoaSetup::where('head_code', 'like', '401%')->where('head_code', '!=', '401')->whereNotIn('head_code', ['40108', '40109', '403', '40107', '40105'])->pluck('id')->toArray();

                $monthly_cost = AccountTransaction::where('voucher_date', '>=', $start_date)->where('voucher_date', '<=', $end_date)
                    ->whereIn('coa_setup_id', $expense_heads)
                    ->sum('debit_amount');

                $netProfit = $total_sales - $total_purchases - $monthly_cost - $managementCost - $deliveryCost - $profitDistribution - $salesCommission;

                $all_renews = InvestRenewList::where('month', $request->month)->where('year', $request->year)->get();
                $investors = Investor::whereHas('renews', function ($query) use ($request) {
                    $query->where('month', $request->month)->where('year', $request->year);
                })->orderBy('name', 'asc')->get();

                $generatedData = [
                    'sales_amount' => round($total_sales),
                    'purchase_amount' => round($total_purchases),
                    'monthly_cost' => round($monthly_cost),
                    'management_cost' => round($managementCost),
                    'delivery_cost' => round($deliveryCost),
                    'investor_profit' => round($profitDistribution),
                    'sales_commission' => round($salesCommission),
                    'net_profit' => round($netProfit),
                    'investors' => $investors,
                    'all_renews' => $all_renews,
                    'perShareProfit' => $perShareProfit,
                ];
            }

            $title = 'Update Profit Distribution';
            $data = ProfitDistribution::findOrFail($id);
            return view('admin.profit_distribution.edit', compact('title', 'data', 'generatedData'));
        }

        $data = ProfitDistribution::findOrFail($id);
        $title = 'View Weekly Profit';
        return view("admin.profit_distribution.view", compact('data', 'title'));
    }

    public function print(string $id)
    {
        $data = ProfitDistribution::findOrFail($id);
        $report_title = 'Profit Distribution Report';
        // return view("admin.profit_distribution.print", compact('report_title', 'data'));
        $pdf = Pdf::loadView("admin.profit_distribution.print", compact('report_title', 'data'));
        $pdf->setPaper('A4');
        return $pdf->stream('profit_distribution_print_' . date('d_m_Y_h_i_s') . '.pdf');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $generatedData = [];
        $data = ProfitDistribution::findOrFail($id);
        $start_date = date('Y-m-01', strtotime($data->month . '-' . $data->year));
        $end_date = date('Y-m-t', strtotime($data->month . '-' . $data->year));
        $check_data = ProfitDistribution::whereNotIn('id', [$id])->where('year', $data->year)->where('month', $data->month)->first();
        if (is_null($check_data)) {

            // Product Wise Profit
            $products = Product::get(['id']);
            $liftings = DB::table('view_liftings')->whereNotNull('date')->where('date', '>=', $start_date)->where('date', '<=', $end_date)->get();
            $lifting_returns = DB::table('view_lifting_returns')->whereNotNull('date')->where('date', '>=', $start_date)->where('date', '<=', $end_date)->get();
            $sales = DB::table('view_sales')->whereNotNull('date')->where('date', '>=', $start_date)->where('date', '<=', $end_date)->get();
            $online_sales = DB::table('view_online_sales')->where('is_stock', 1)->whereIn('status', ['Collected'])->where('collected_at', '>=', $start_date)->where('collected_at', '<=', $end_date)->get();
            $sales_returns = DB::table('view_sales_returns')->whereNotNull('date')->where('date', '>=', $start_date)->where('date', '<=', $end_date)->get();

            $totalProductProfit = 0;
            $totalLiftingAmount = 0;
            $totalSalesAmount = 0;
            foreach ($products as $row) {
                $sales_qty =
                    $online_sales->where('product_id', $row->id)->sum('qty') +
                    $sales->where('product_id', $row->id)->sum('qty') -
                    $sales_returns->where('product_id', $row->id)->sum('qty');
                if ($sales_qty == 0) {
                    continue;
                }
                $sales_amount =
                    $online_sales->where('product_id', $row->id)->sum('amount') +
                    $sales->where('product_id', $row->id)->sum('amount') -
                    $sales_returns->where('product_id', $row->id)->sum('amount');

                $lifting_amount = $liftings->where('product_id', $row->id)->sum('amount') - $lifting_returns->where('product_id', $row->id)->sum('amount');
                $lifting_qty = $liftings->where('product_id', $row->id)->sum('qty') - $lifting_returns->where('product_id', $row->id)->sum('qty');
                $avarage_rate = $lifting_qty > 0 ? $lifting_amount / $lifting_qty : 0;
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

            $totalShare = InvestRenewList::where('month', $data->month)->where('year', $data->year)->sum('amount');
            $totalShareQty = InvestRenewList::where('month', $data->month)->where('year', $data->year)->sum('qty');
            $investorPercentage = ($percentage * 20) / 100;
            $profitDistribution = round(($totalShare * $investorPercentage) / 100, 2);
            $perShareProfit = $totalShareQty > 0 ? $profitDistribution / $totalShareQty : 0;
            // Product Wise Profit

            $orders = Order::get();
            $product_ids = Product::pluck('id')->toArray();

            $total_monthly_orders = Order::where('date', '>=', $start_date)->where('date', '<=', $end_date)->count();
            $deliveredOrdersQuery = Order::whereIn('status', ['Delivered', 'Collected'])->where('date', '>=', $start_date)->where('date', '<=', $end_date);
            $total_monthly_delivered_orders = (clone $deliveredOrdersQuery)->count();
            $total_monthly_order_amount = $total_monthly_orders > 0 ? Order::where('date', '>=', $start_date)->where('date', '<=', $end_date)->sum(DB::raw('due - total_return')) / $total_monthly_orders : 0;

            // Management, Delivery, Sales Commission Cost
            $additionalCost = AdditionalCost::first();
            $costQuery = Order::whereIn('status', ['Collected'])->where('collected_at', '>=', $start_date)->where('collected_at', '<=', $end_date);
            $managementCost = round(((clone $costQuery)->count() * $additionalCost->management_cost) + ((clone $costQuery)->sum(DB::raw('due - total_return')) * $additionalCost->management_cost_percentage) / 100);
            $salesCommission = round(((clone $costQuery)->count() * $additionalCost->moderator_cost) + ((clone $costQuery)->sum(DB::raw('due - total_return')) * $additionalCost->moderator_cost_percentage) / 100);

            $deliveryCostQuery = Order::where('collected_at', '>=', $start_date)->where('collected_at', '<=', $end_date);
            $deliveryCost = (clone $deliveryCostQuery)->whereIn('status', ['Collected'])->sum('delivery_cost') + (clone $deliveryCostQuery)->whereIn('status', ['Returned'])->sum('return_cost');
            // Management, Delivery, Sales Commission Cost

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
            $total_sales = $sales_amount - $sales_returns_amount + $online_sales_amount + $shipping_charges;
            $expense_heads = CoaSetup::where('head_code', 'like', '401%')->where('head_code', '!=', '401')->whereNotIn('head_code', ['40108', '40109', '403', '40107', '40105'])->pluck('id')->toArray();

            $monthly_cost = AccountTransaction::where('voucher_date', '>=', $start_date)->where('voucher_date', '<=', $end_date)
                ->whereIn('coa_setup_id', $expense_heads)
                ->sum('debit_amount');

            $netProfit = $total_sales - $total_purchases - $monthly_cost - $managementCost - $deliveryCost - $profitDistribution - $salesCommission;

            $all_renews = InvestRenewList::where('month', $data->month)->where('year', $data->year)->get();
            $investors = Investor::whereHas('renews', function ($query) use ($data) {
                $query->where('month', $data->month)->where('year', $data->year);
            })->orderBy('name', 'asc')->get();

            $generatedData = [
                'sales_amount' => round($total_sales),
                'purchase_amount' => round($total_purchases),
                'monthly_cost' => round($monthly_cost),
                'management_cost' => round($managementCost),
                'delivery_cost' => round($deliveryCost),
                'investor_profit' => round($profitDistribution),
                'sales_commission' => round($salesCommission),
                'net_profit' => round($netProfit),
                'investors' => $investors,
                'all_renews' => $all_renews,
                'perShareProfit' => $perShareProfit,
            ];
        }

        $title = 'Update Profit Distribution';
        $link = Route('admin.profit-distribute.update', $id);
        return view('admin.profit_distribution.edit', compact('title', 'data', 'link', 'generatedData'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'year' => 'required',
            'month' => 'required',
            'date' => 'required'
        ]);

        DB::transaction(function () use ($request, $id) {
            $data = ProfitDistribution::findOrFail($id);
            $data->update([
                'year' => $request->year,
                'month' => $request->month,
                'date' =>  date('Y-m-d', strtotime($request->date)),
                'sales_amount' => $request->sales_amount,
                'purchase_amount' => $request->purchase_amount,
                'monthly_cost' => $request->monthly_cost,
                'management_cost' => $request->management_cost,
                'delivery_cost' => $request->delivery_cost,
                'investor_profit' => $request->investor_profit,
                'sales_commission' => $request->sales_commission,
                'net_profit' => $request->net_profit,
                'updated_by' => Auth::user()->id,
            ]);

            ProfitDistributionList::where('profit_distribution_id', $id)->delete();
            foreach ($request->investor_id as $investor_id) {
                ProfitDistributionList::create([
                    'profit_distribution_id' => $data->id,
                    'investor_id' => $investor_id,
                    'date' => date('Y-m-d', strtotime($request->date)),
                    'month' => $request->month,
                    'year' => $request->year,
                    'invest_qty' => $request->qty[$investor_id],
                    'invest_amount' => $request->amount[$investor_id],
                    'profit_amount' => $request->profit_amount[$investor_id]
                ]);
            }
        });

        return redirect()->route('admin.profit-distribute.index')->withSuccessMessage('Updated Successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Recovery Deleted Data
        if (request()->has('recovery') && request('recovery') == 'true') {
            $data = ProfitDistribution::withTrashed()->findOrFail($id);
            $data->restore();
            return response()->json(['status' => 'success']);
        }

        if (request()->has('parmanent') && request('parmanent') == 'true') {
            $data = ProfitDistribution::withTrashed()->findOrFail($id);
            $data->forceDelete();
            return response()->json(['status' => 'success']);
        }

        $data = ProfitDistribution::withTrashed()->findOrFail($id);
        $data->update(['deleted_by' => Auth::user()->id]);
        $data->forceDelete();
        return response()->json(['status' => 'success']);
    }
}
