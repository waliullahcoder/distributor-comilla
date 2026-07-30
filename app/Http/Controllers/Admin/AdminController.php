<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Order;
use App\Models\Invest;
use App\Models\Product;
use App\Models\CoaSetup;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\OrderProduct;
use App\Models\AdditionalCost;
use App\Models\InvestRenewList;
use App\Models\AccountTransaction;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\ModeratorTeam;
use App\Models\ModeratorTeamMember;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\ProfitDistributionList;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::check()) {
            if (Auth::user()->role == 1) {
                $intendedUrl = Session::pull('url.intended', route('admin.dashboard'));
                return redirect()->intended($intendedUrl);
            } elseif (Auth::user()->role == 0) {
                return redirect()->route('customer.profile');
            } elseif (Auth::user()->role == 3) {
                $intendedUrl = Session::pull('url.intended', route('admin.dashboard'));
                return redirect()->intended($intendedUrl);
            }
            return redirect()->back();
        } else {
            return view('admin.auth.login');
        }
    }

    public function login(Request $request)
    {
        $user = User::where('user_name', $request->user_name)->where('status', 0)->first();
        if ($user) {
            return redirect()->back()->withErrors('User not Exists!')->withInput();
        }

        $credentials = $request->only('user_name', 'password');
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            if (Auth::user()->role == 1) {
                $intendedUrl = Session::pull('url.intended', route('admin.dashboard'));
                return redirect()->intended($intendedUrl);
            } elseif (Auth::user()->role == 3) {
                $intendedUrl = Session::pull('url.intended', route('admin.dashboard'));
                return redirect()->intended($intendedUrl);
            }
        } else {
            return redirect()->back()->with('error', 'Invalid Email or Password!')->withInput();
        }
    }

    public function dashboard()
    {
        if (request()->ajax() && Auth::user()->hasRole('Investor')) {
            $months = [];
            $profits = [];
            for ($i = 1; $i <= 12; $i++) {
                $start = date('Y-m-01', strtotime("-$i months"));
                $end = date('Y-m-t', strtotime("-$i months")); // 't' gives last day of the month

                $months[] = date('Y-m', strtotime($start));
                $profits[] = AccountTransaction::whereBetween('voucher_date', [$start, $end])->where('coa_setup_id', Auth::user()->investor->profit_head)->sum('debit_amount');
            }

            $months = array_reverse($months);
            $profits = array_reverse($profits);

            $monthlyData = [
                'months' => $months,
                'profits' => $profits,
            ];
            return response()->json(['status' => 'success', 'monthlyData' => $monthlyData]);
        }

        if (request()->ajax()) {
            $model = Order::with(['address'])->orderBy('potential_delivery_date', 'asc')->where('date', '>=', '2025-01-01');
            if (Auth::user()->hasRole('Moderator')) {
                $model->where('created_by', Auth::user()->id);
            }
            $model->where(function ($query) {
                $query->whereIn('status', ['Pending', 'On Route', 'Forward']);
            });
            $model->whereNotIn('status', ['Cancelled']);
            if (Auth::user()->hasRole('Store Keeper')) {
                $model->whereIn('store_id', Auth::user()->stores);
            }
            return DataTables::eloquent($model)
                ->addIndexColumn()
                ->addColumn('potential_delivery_date', function ($row) {
                    return date('d M Y', strtotime($row->potential_delivery_date));
                })
                ->addColumn('net_amount', function ($row) {
                    return $row->due + $row->shipping_charge;
                })
                ->addColumn('order_date', function ($row) {
                    return date('d M Y', strtotime($row->date));
                })
                ->addColumn('action', function ($row) {
                    if ($row->status == 'Cancelled') {
                        $status = '<span class="btn btn-xs text-white px-2 bg-danger" style="min-width: 80px;">Cancelled</span>';
                    } elseif ($row->status == 'Successed') {
                        $status = '<span class="btn btn-xs text-white px-2 bg-success" style="min-width: 80px;">Successed</span>';
                    } else {
                        $status = '<a class="btn btn-xs text-white px-2 ';
                        if ($row->status == 'Pending') {
                            $status .= 'bg-danger';
                        } elseif ($row->status == 'Forward') {
                            $status .= 'bg-info';
                        } elseif ($row->status == 'Forward') {
                            $status .= 'bg-warning';
                        } elseif ($row->status == 'On Route') {
                            $status .= 'bg-route';
                        } elseif ($row->status == 'Delivered') {
                            $status .= 'bg-delivered';
                        } elseif ($row->status == 'Returned') {
                            $status .= 'bg-secondary';
                        }
                        $status .= '" style="min-width: 80px;" href="' . (!Auth::user()->hasRole('Moderator') && !Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Admin') && !in_array($row->status, ['Delivered']) ? Route('admin.order-dashboard.edit', $row->id) : '') . '">' . $row->status . '</a>';
                    }
                    return $status;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $managementCost = 0;
        $deliveryCost = 0;
        $profitDistribution = 0;
        $moderatorCommission = 0;

        // Dates for previous month
        $firstDayPrevMonth = date('Y-m-01', strtotime('first day of last month'));
        $lastDayPrevMonth = date('Y-m-t', strtotime('last day of last month'));

        $baseQuery = Order::query();

        if (is_array(Auth::user()->stores)) {
            $baseQuery->whereIn('store_id', Auth::user()->stores);
        }

        // Total clients since 2025-01-01
        $totalClients = (clone $baseQuery)
            ->distinct('user_phone')
            ->count('user_phone');
        $totalOrders = (clone $baseQuery);
        if (Auth::user()->hasRole('Moderator')) {
            $totalOrders->where('created_by', Auth::user()->id);
        }
        $totalOrders = $totalOrders->count();
        $thisMonthOrders = (clone $baseQuery);
        if (Auth::user()->hasRole('Moderator')) {
            $thisMonthOrders->where('created_by', Auth::user()->id);
        }
        $thisMonthOrders = $thisMonthOrders->where('date', '>=', date('Y-m-01'))->count();

        $prevMonthOrders = (clone $baseQuery);
        if (Auth::user()->hasRole('Moderator')) {
            $prevMonthOrders->where('created_by', Auth::user()->id);
        }
        $prevMonthOrders = $prevMonthOrders->whereBetween('date', [$firstDayPrevMonth, $lastDayPrevMonth])->count();

        $totalSales = (clone $baseQuery);
        if (Auth::user()->hasRole('Moderator')) {
            $totalSales->where('created_by', Auth::user()->id)->whereIn('status', ['Delivered', 'Collected']);
            $totalSales = $totalSales->sum(DB::raw('sub_total - discount'));
        } else {
            $totalSales = $totalSales->sum('due');
        }

        $thisMonthSales = (clone $baseQuery);
        if (Auth::user()->hasRole('Moderator')) {
            $thisMonthSales->where('created_by', Auth::user()->id)->whereIn('status', ['Delivered', 'Collected']);
            $thisMonthSales = $thisMonthSales->where('date', '>=', date('Y-m-01'))->sum(DB::raw('sub_total - discount'));
        } else {
            $thisMonthSales = $thisMonthSales->where('date', '>=', date('Y-m-01'))->sum('due');
        }

        $prevMonthSales = (clone $baseQuery);
        if (Auth::user()->hasRole('Moderator')) {
            $prevMonthSales->where('created_by', Auth::user()->id)->whereIn('status', ['Delivered', 'Collected']);
            $prevMonthSales = $prevMonthSales->whereBetween('date', [$firstDayPrevMonth, $lastDayPrevMonth])->sum(DB::raw('sub_total - discount'));
        } else {
            $prevMonthSales = $prevMonthSales->whereBetween('date', [$firstDayPrevMonth, $lastDayPrevMonth])->sum('due');
        }


        // ✅ Previous month clients list
        $prevMonthClients = (clone $baseQuery)
            ->whereBetween('date', [$firstDayPrevMonth, $lastDayPrevMonth])
            ->distinct('user_phone')
            ->pluck('user_phone')
            ->count();

        // Clients before this month
        $PrevClients = (clone $baseQuery)
            ->where('date', '<', date('Y-m-01'))
            ->distinct('user_phone')
            ->pluck('user_phone')
            ->toArray();

        // New clients this month
        $newClientsThisMonth = (clone $baseQuery)
            ->where('date', '>=', date('Y-m-01'))
            ->whereNotIn('user_phone', $PrevClients)
            ->distinct('user_phone')
            ->count('user_phone');

        $start_date = request('month') && request('year') ? date('Y-m-01', strtotime(request('year') . '-' . request('month'))) : date('Y-m-01');
        $end_date = request('month') && request('year') ? date('Y-m-t', strtotime(request('year') . '-' . request('month'))) : date('Y-m-t');

        $ranked_products = OrderProduct::with(['order', 'product'])->whereHas('order', function ($query) use ($start_date, $end_date) {
            $query->whereIn('status', ['Delivered', 'Collected'])->where('date', '>=', $start_date)->where('date', '<=', $end_date);
            if (is_array(Auth::user()->stores)) {
                $query->whereIn('store_id', Auth::user()->stores);
            }
        })->groupBy('product_id')->select(['product_id', DB::raw('SUM(quantity) as total_qty'), DB::raw('SUM(subtotal - discount - return_amount) as total_amount')])->orderBy('total_amount', 'desc')->limit(8)->get();

        // Product Wise Profit
        $products = Product::with('price')->get();
        $liftings = DB::table('view_liftings')->whereNotNull('date')->where('date', '>=', $start_date)->where('date', '<=', $end_date)->get();
        $lifting_returns = DB::table('view_lifting_returns')->whereNotNull('date')->where('date', '>=', $start_date)->where('date', '<=', $end_date)->get();
        $sales = DB::table('view_sales')->whereNotNull('date')->where('date', '>=', $start_date)->where('date', '<=', $end_date)->get();
        $online_sales = DB::table('view_online_sales')->whereIn('status', ['Collected'])->where('collected_at', '>=', $start_date)->where('collected_at', '<=', $end_date)->get();
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

            if ($lifting_qty == 0) {
                $customQuery = DB::table('view_liftings')->whereNotNull('date')->where('product_id', $row->id)->orderBy('date', 'desc')->first();
                $lifting_amount = $customQuery->amount;
                $lifting_qty = $customQuery->qty;
            }

            $avarage_rate = $lifting_amount / $lifting_qty;
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

        $totalShare = InvestRenewList::where('month', request('month') ?? date('F'))->where('year', request('year') ?? date('Y'))->sum('amount');
        $totalShareQty = InvestRenewList::where('month', request('month') ?? date('F'))->where('year', request('year') ?? date('Y'))->sum('qty');
        $investorPercentage = ($percentage * 20) / 100;
        $profitDistribution = round(($totalShare * $investorPercentage) / 100, 2);
        $perShareProfit = $totalShareQty > 0 ? $profitDistribution / $totalShareQty : 0;
        // Product Wise Profit

        $orders = Order::get();
        $product_ids = Product::pluck('id')->toArray();

        $total_monthly_orders = Order::where('date', '>=', $start_date)->where('date', '<=', $end_date)->count();
        $deliveredOrdersQuery = Order::whereIn('status', ['Delivered', 'Collected'])->where('delivered_at', '>=', $start_date)->where('delivered_at', '<=', $end_date);
        $total_monthly_delivered_orders = Order::whereIn('status', ['Delivered', 'Collected'])->where('delivered_at', '>=', $start_date)->where('delivered_at', '<=', $end_date)->count();
        $total_monthly_order_amount = $total_monthly_orders > 0 ? Order::where('date', '>=', $start_date)->where('date', '<=', $end_date)->sum(DB::raw('due - total_return')) / $total_monthly_orders : 0;

        // Management, Delivery, Sales Commission Cost
        $additionalCost = AdditionalCost::first();
        $leaders = ModeratorTeam::where('status', true)->pluck('team_leader')->toArray();
        $members = ModeratorTeamMember::whereHas('team', function ($query) {
            $query->where('status', true);
        })->whereNotIn('user_id', $leaders)->pluck('user_id')->toArray();
        $array = array_merge($leaders, $members);

        $costQuery = Order::whereIn('status', ['Collected', 'Delivered'])->where('collected_at', '>=', $start_date)->where('collected_at', '<=', $end_date);
        $managementCost = round(((clone $costQuery)->count() * $additionalCost->management_cost) + ((clone $costQuery)->sum(DB::raw('due - total_return')) * $additionalCost->management_cost_percentage) / 100);
        $moderatorCommission = round(((clone $costQuery)->whereIn('created_by', $array)->count() * $additionalCost->moderator_cost) + ((clone $costQuery)->whereIn('created_by', $array)->sum(DB::raw('sub_total - discount')) * $additionalCost->moderator_cost_percentage) / 100);
        $leaderOrders = (clone $costQuery)->whereIn('created_by', $members)->get();
        $leaderOrderAmount = $leaderOrders->sum('sub_total') - $leaderOrders->sum('discount');
        $salesCommission = (count($leaderOrders) * $additionalCost->team_leader_cost) + (($additionalCost->team_leader_percentage / 100) * $leaderOrderAmount);

        $deliveryCostQuery = Order::where('delivered_at', '>=', $start_date)->where('delivered_at', '<=', $end_date);
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
                $query->where('delivered_at', '>=', $start_date)->where('delivered_at', '<=', $end_date)->whereIn('status', ['Collected', 'Delivered']);
            })->where('product_id', $product_id)->sum(DB::raw('quantity - return_quantity'));
            $total_sales_qty = $sales_qty - $sales_returns_qty + $online_sales_qty;
            $total_purchases += $total_sales_qty * $avarage_rate;
        }

        $sales_amount = DB::table('view_sales')->where('date', '>=', $start_date)->where('date', '<=', $end_date)->sum('amount');
        $sales_returns_amount = DB::table('view_sales_returns')->where('date', '>=', $start_date)->where('date', '<=', $end_date)->sum('amount');
        $online_sales_amount = Order::where('collected_at', '>=', $start_date)->where('collected_at', '<=', $end_date)->whereIn('status', ['Collected'])->sum('due');
        $total_sales = $sales_amount - $sales_returns_amount + $online_sales_amount;
        $expense_heads = CoaSetup::where('head_code', 'like', '401%')->where('head_code', '!=', '401')->whereNotIn('head_code', ['40108', '40109', '403', '40107', '40105'])->get();
        $operationalCost = AccountTransaction::whereHas('coa.parent', function ($query) {
            $query->where('head_code', '404');
        })->where('voucher_date', '>=', $start_date)->where('voucher_date', '<=', $end_date)->sum('debit_amount');

        if (Auth::user()->hasRole('Investor')) {
            $total_due = ProfitDistributionList::whereHas('distribution')->where('investor_id', Auth::user()->investor->id)->where('paid', 0)->sum('profit_amount');
            $total_withdraw = AccountTransaction::where('coa_setup_id', Auth::user()->investor->profit_head)->sum('debit_amount');
            $total_invests = Invest::where('investor_id', Auth::user()->investor->id)->where('sattled', 0)->sum('amount');
            $total_invest_qty = Invest::where('investor_id', Auth::user()->investor->id)->where('sattled', 0)->sum('qty');
            return view('admin.order_dashboard.investor_dashboard', compact('totalClients', 'prevMonthClients', 'newClientsThisMonth', 'totalOrders', 'thisMonthOrders', 'prevMonthOrders', 'totalSales', 'thisMonthSales', 'prevMonthSales', 'ranked_products', 'orders', 'total_sales', 'total_purchases', 'expense_heads', 'operationalCost', 'managementCost', 'deliveryCost', 'totalShareQty', 'investorPercentage', 'profitDistribution', 'perShareProfit', 'moderatorCommission', 'salesCommission', 'total_monthly_orders', 'total_monthly_delivered_orders', 'total_monthly_order_amount', 'start_date', 'end_date', 'total_due', 'total_withdraw', 'total_invests', 'total_invest_qty'));
        }
        if (Auth::user()->hasRole('Moderator')) {
            return view('admin.order_dashboard.index', compact('totalClients', 'prevMonthClients', 'newClientsThisMonth', 'totalOrders', 'thisMonthOrders', 'prevMonthOrders', 'totalSales', 'thisMonthSales', 'prevMonthSales', 'ranked_products', 'start_date', 'end_date'));
        } else {
            return view('admin.order_dashboard.index', compact('totalClients', 'prevMonthClients', 'newClientsThisMonth', 'totalOrders', 'thisMonthOrders', 'prevMonthOrders', 'totalSales', 'thisMonthSales', 'prevMonthSales', 'ranked_products', 'orders', 'total_sales', 'total_purchases', 'expense_heads', 'operationalCost', 'managementCost', 'deliveryCost', 'totalShareQty', 'investorPercentage', 'profitDistribution', 'perShareProfit', 'moderatorCommission', 'salesCommission', 'total_monthly_orders', 'total_monthly_delivered_orders', 'total_monthly_order_amount', 'start_date', 'end_date'));
        }
    }

    /**
     * Manage Sidebar
     */
    public function sidebar()
    {
        if (!Session::has('sidebar-collapse')) {
            Session()->put('sidebar-collapse', 'active');
        } else {
            Session::forget('sidebar-collapse');
        }
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        $admin = Auth::user();
        return view('admin.profile.index', compact('admin'));
    }

    public function changeImages(Request $request)
    {
        $images = User::findOrFail(Auth::user()->id);
        $cover = $request->file('cover_image');
        if (isset($cover)) {
            $path = 'backend/images/avatar/';
            $file_name = 'cover-' . Str::random(40) . '.' . $cover->getClientOriginalExtension();
            $path_file_name = $path . $file_name;
            $cover->move($path, $file_name);
            if (file_exists($images->cover_image)) {
                unlink($images->cover_image);
            }
            $images->cover_image = $path_file_name;
        }

        $profile = $request->file('profile_image');
        if (isset($profile)) {
            $path = 'backend/images/avatar/';
            $file_name = 'profile-' . Str::random(40) . '.' . $profile->getClientOriginalExtension();
            $path_file_name = $path . $file_name;
            $profile->move($path, $file_name);
            if (file_exists($images->image)) {
                unlink($images->image);
            }
            $images->image = $path_file_name;
        }
        $images->save();
        return redirect()->back()->withSuccessMessage('Image Changed Successfully!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $request->validate([
            'email' => 'nullable|unique:users,email,' . Auth::user()->id,
            'name' => 'required',
        ]);
        $admin = User::findOrFail(Auth::user()->id);
        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->phone = $request->phone;
        $admin->address = $request->address;
        $admin->save();
        return redirect()->back()->withSuccessMessage('Information Updated Successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $admin = User::findOrFail(Auth::user()->id);
        if (Hash::check($request->old_password, $admin->password)) {
            $admin->password = bcrypt($request->new_password);
            $admin->save();
            return redirect()->back()->withSuccessMessage('Updated Successfully!');
        } else {
            return redirect()->back()->withErrors('Old Password Does not Matched!');
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('admin.login.index');
    }
}
