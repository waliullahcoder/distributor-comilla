<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Order;
use App\Models\CoaSetup;
use Illuminate\Http\Request;
use App\Models\AdminSetting;
use App\Models\AdditionalCost;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\ModeratorPayment;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\ModeratorPaymentList;
use App\Models\ModeratorPaymentOrder;
use App\Models\AccountTransactionAuto;
use Yajra\DataTables\Facades\DataTables;
use App\Services\ActionButtons\ActionButtons;

class ModeratorPaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (request()->ajax()) {
            $model = ModeratorPayment::with('transactions')->orderBy('id', 'desc');
            $type = request('type');
            if (!empty($type) && $type == 'trash') {
                $model->onlyTrashed();
            }
            return DataTables::eloquent($model)
                ->addIndexColumn()
                ->addColumn('date', function ($row) {
                    return date('d-m-Y', strtotime(@$row->date));
                })
                ->addColumn('moderators', function ($row) {
                    return $row->moderator_names ?? '';
                })
                ->addColumn('actions', function ($row) {
                    $type = request('type');
                    $data = [
                        'id' => $row->id,
                        'edit' => !empty($type) && $type == 'trash' ? false : true,
                    ];

                    $delete = 'yes';
                    $edit = 'yes';
                    if (count($row->transactions) > 0) {
                        $delete = 'no';
                        $edit = 'no';
                    }
                    $addition_btns = [
                        [
                            'parameter' => true,
                            'target' => '_self',
                            'title' => 'View Payments',
                            'route' => 'admin.moderator-payment.show',
                            'icon' => '<i class="fas fa-eye"></i>',
                            'class' => 'btn btn-sm btn-primary mw-fit',
                        ],
                        [
                            'parameter' => true,
                            'target' => '_self',
                            'title' => 'Print Payments',
                            'route' => 'admin.moderator-payment.print',
                            'icon' => '<i class="fas fa-print"></i>',
                            'class' => 'btn btn-sm btn-secondary mw-fit',
                        ]
                    ];

                    return ActionButtons::actions($data, $addition_btns, $delete, $edit);
                })
                ->rawColumns(['checkbox', 'actions'])
                ->make(true);
        }

        $title = "Moderator Payment";
        return view('admin.moderator-payment.index', compact('title'));
    }

    public function SerialNo()
    {
        $data = ModeratorPayment::withTrashed()->select(['serial_no'])->whereDate('created_at', '>=', date('Y-m-01'))->whereDate('created_at', '<=', date('Y-m-t'))->orderBy('id', 'desc')->first();
        if ($data) {
            $trim = str_replace("MP", '', $data->serial_no);
            $dataPrefix = (int)$trim + 1;
            $SerialNo = "MP" . $dataPrefix;
        } else {
            $SerialNo = "MP" . date('y') . date('m') . '001';
        }
        return $SerialNo;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        if ($request->ajax()) {
            $start_date = date('Y-m-01', strtotime($request->month . '-' . $request->year));
            $end_date = date('Y-m-t', strtotime($request->month . '-' . $request->year));
            $moderators = [];
            $check_payment = ModeratorPayment::where('month', $request->month)->where('year', $request->year)->count();
            if ($check_payment == 0) {
                $moderators = User::with(['moderatorOrders', 'leader'])->whereHas('roles', function ($query) {
                    // $query->where('name', 'Moderator');
                })->whereHas('moderatorOrders', function ($q) use ($start_date, $end_date) {
                    $q->where(function ($query) use ($start_date, $end_date) {
                        $query->whereBetween('delivered_at', [$start_date, $end_date])
                            ->orWhere('delivered_at', '<', $start_date);
                    })
                        ->whereIn('status', ['Delivered', 'Collected'])
                        ->where('commission', false);
                })->where('status', true)->orderBy('name', 'asc')->get();
            }
            return response()->json([
                'status' => 'success',
                'data'  => view('admin.moderator-payment.partial.create-rows', ['moderators' => $moderators, 'month' => $request->month, 'year' => $request->year])->render()
            ]);
        }

        $title = 'Make Payment';
        $current_time = Carbon::now();
        $month = date('F');
        $year = date('Y');
        $moderators = [];
        $cash_heads = CoaSetup::with('parent')->whereHas('parent', function ($query) {
            $query->where('head_name', 'Cash In Hand');
        })->get();
        $check_payment = ModeratorPayment::where('month', date('F'))->where('year', date('Y'))->count();
        if ($check_payment == 0) {
            $moderators = User::with(['moderatorOrders', 'leader'])->whereHas('roles', function ($query) {
                // $query->where('name', 'Moderator');
            })->whereHas('moderatorOrders', function ($q) {
                $q->where(function ($query) {
                    $start_date = date('Y-m-01');
                    $end_date = date('Y-m-t');
                    $query->whereBetween('delivered_at', [$start_date, $end_date])
                        ->orWhere('delivered_at', '<', $start_date);
                })
                    ->whereIn('status', ['Delivered', 'Collected'])
                    ->where('commission', false);
            })->where('status', true)->orderBy('name', 'asc')->get();
        }
        return view('admin.moderator-payment.create', compact('title', 'current_time', 'month', 'year', 'cash_heads', 'moderators'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required',
            'month' => 'required',
            'year' => 'required',
            'coa_setup_id' => 'required',
            'user_id' => 'required',
            'member_order_qty' => 'required'
        ]);

        try {
            DB::transaction(function () use ($request) {
                $data = ModeratorPayment::create([
                    'coa_setup_id' => $request->coa_setup_id,
                    'serial_no' => $this->SerialNo(),
                    'year' => $request->year,
                    'month' => $request->month,
                    'date' => date('Y-m-d', strtotime($request->date)),
                    'member_order_qty' => $request->member_order_qty,
                    'member_order_amout' => $request->member_order_amout,
                    'member_qty_commission' => $request->member_qty_commission,
                    'member_amount_commission' => $request->member_amount_commission,
                    'leader_order_qty' => $request->leader_order_qty,
                    'leader_order_amout' => $request->leader_order_amout,
                    'leader_qty_commission' => $request->leader_qty_commission,
                    'leader_amount_commission' => $request->leader_amount_commission,
                    'total_commission' => $request->total_commission,
                    'created_by' => Auth::user()->id,
                ]);

                $start_date = date('Y-m-01', strtotime($request->month . '-' . $request->year));
                $end_date = date('Y-m-t', strtotime($request->month . '-' . $request->year));
                $cost = AdditionalCost::first();

                foreach ($request->user_id as $user_id) {
                    $user = User::find($user_id);
                    $totalCommission = 0;
                    $orders = Order::where('created_by', $user_id)
                        ->where(function ($query) use ($start_date, $end_date) {
                            $query
                                ->whereBetween('delivered_at', [$start_date, $end_date])
                                ->orWhere('delivered_at', '<', $start_date);
                        })
                        ->whereIn('status', ['Delivered', 'Collected'])
                        ->where('commission', false)
                        ->get();
                    $qty = $orders->count();
                    $qtyCommission = $qty * $cost->moderator_cost;
                    $totalCommission += $qtyCommission;
                    $amount = $orders->sum(function ($order) {
                        return $order->sub_total - $order->discount;
                    });
                    $amountCommission = ($cost->moderator_cost_percentage / 100) * $amount;
                    $totalCommission += $amountCommission;
                    if ($user->leader) {
                        $moderator_ids = $user->leader->members->pluck('user_id')->toArray();
                        $leaderOrders = Order::whereIn('created_by', $moderator_ids)
                            ->whereNot('created_by', $user_id)
                            ->where(function ($query) use ($start_date, $end_date) {
                                $query
                                    ->whereBetween('delivered_at', [$start_date, $end_date])
                                    ->orWhere('delivered_at', '<', $start_date);
                            })
                            ->whereIn('status', ['Delivered', 'Collected'])
                            ->where('commission', false)
                            ->get();
                        $leaderQty = $leaderOrders->count();
                        $leaderQtyCommission = $leaderQty * $cost->team_leader_cost;
                        $totalCommission += $leaderQtyCommission;
                        $leaderAmount = $leaderOrders->sum(function ($order) {
                            return $order->sub_total - $order->discount;
                        });
                        $leaderAmountCommission = ($cost->team_leader_percentage / 100) * $leaderAmount;
                        $totalCommission += $leaderAmountCommission;
                    }

                    ModeratorPaymentList::create([
                        'moderator_payment_id' => $data->id,
                        'user_id' => $user_id,
                        'year' => $request->year,
                        'month' => $request->month,
                        'order_qty' => $qty,
                        'order_amount' => $amount,
                        'qty_commission' => $qtyCommission,
                        'amount_commission' => $amountCommission,
                        'leader_qty' => $leaderQty ?? 0,
                        'leader_amount' => $leaderAmount ?? 0,
                        'leader_qty_commission' => $leaderQtyCommission ?? 0,
                        'leader_amount_commission' => $leaderAmountCommission ?? 0,
                        'total_commission' => $totalCommission,
                    ]);

                    foreach ((array) json_decode($request->order_ids[$user_id]) as $order_id) {
                        ModeratorPaymentOrder::create([
                            'moderator_payment_id' => $data->id,
                            'user_id' => $user_id,
                            'order_id' => $order_id
                        ]);
                    }
                }

                foreach ($request->user_id as $user_id) {
                    Order::whereIn('id', json_decode($request->order_ids[$user_id]))->update(['commission' => true]);
                }

                $admin_setting = AdminSetting::first();
                if (@$admin_setting->accounting == 1) {
                    $cash_head = CoaSetup::findOrFail($request->coa_setup_id);
                    $headCode = collect([
                        '0' => 40105,
                        '1' => $cash_head->head_code,
                    ]);
                    $countHead = count($headCode);

                    $debit_amount = collect([
                        '0' => round($data->total_commission),
                        '1' => 0.00
                    ]);

                    $credit_amount = collect([
                        '0' => 0.00,
                        '1' => round($data->total_commission),
                    ]);

                    $postData = [];
                    for ($i = 0; $i < $countHead; $i++) {
                        $coa = CoaSetup::where('company_id', Auth::user()->company_id ?? 1)->where('head_code', $headCode[$i])->first();
                        $postData[] = [
                            'company_id' => Auth::user()->company_id ?? 1,
                            'voucher_no' => $data->serial_no,
                            'voucher_type' => "Moderator Payment",
                            'voucher_date' => date('Y-m-d', strtotime($request->date)),
                            'coa_setup_id' => $coa->id,
                            'coa_head_code' => $headCode[$i],
                            'narration' => 'Moderator Payment against PAYMENT NO - ' . $data->serial_no,
                            'debit_amount' => $debit_amount[$i],
                            'credit_amount' => $credit_amount[$i],
                            'created_by' => Auth::user()->id,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now()
                        ];
                    }
                    AccountTransactionAuto::insert($postData);
                }
            });
        } catch (\Exception $e) {
            return back()->withErrors($e->getMessage());
        }

        return redirect()->Route('admin.moderator-payment.index')->withSuccessMessage('Created Successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $title = 'View Moderator Payment';
        $data = ModeratorPayment::findOrFail($id);
        return view("admin.moderator-payment.view", compact('data', 'title'));
    }

    public function print(string $id)
    {
        $data = ModeratorPayment::findOrFail($id);
        $report_title = 'Moderator Payment Report';
        // return view("admin.moderator-payment.print", compact('report_title', 'data'));
        $pdf = Pdf::loadView("admin.moderator-payment.print", compact('report_title', 'data'));
        $pdf->setPaper('A4', 'landscape');
        return $pdf->stream('moderator_payment_print_' . date('d_m_Y_h_i_s') . '.pdf');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, string $id)
    {
        if ($request->ajax()) {
            $data = ModeratorPayment::findOrFail($id);
            $start_date = date('Y-m-01', strtotime($request->month . '-' . $request->year));
            $end_date = date('Y-m-t', strtotime($request->month . '-' . $request->year));
            $moderators = [];
            $check_payment = ModeratorPayment::whereNot('id', $id)->where('month', $request->month)->where('year', $request->year)->count();
            if ($check_payment == 0) {
                $moderators = User::with(['moderatorOrders', 'leader'])->whereHas('roles', function ($query) {
                    // $query->where('name', 'Moderator');
                })->whereHas('moderatorOrders', function ($q) use ($start_date, $end_date, $data) {
                    $q->where(function ($query) use ($start_date, $end_date) {
                        $query->whereBetween('delivered_at', [$start_date, $end_date])
                            ->orWhere('delivered_at', '<', $start_date);
                    })
                        ->whereIn('status', ['Delivered', 'Collected'])
                        ->where(function ($query) use ($data) {
                            $query->where('commission', false)
                                ->orWhereIn('id', $data->orders->pluck('order_id')->toArray());
                        });
                })->where('status', true)->orderBy('name', 'asc')->get();
            }
            return response()->json([
                'status' => 'success',
                'data'  => view('admin.moderator-payment.partial.edit-rows', ['moderators' => $moderators, 'month' => $request->month, 'year' => $request->year, 'data' => $data])->render()
            ]);
        }

        $title = 'Update Payment';
        $data = ModeratorPayment::findOrFail($id);
        $month = $data->month;
        $year = $data->year;
        $start_date = date('Y-m-01', strtotime($data->month . '-' . $data->year));
        $end_date = date('Y-m-t', strtotime($data->month . '-' . $data->year));
        $moderators = [];
        $cash_heads = CoaSetup::with('parent')->whereHas('parent', function ($query) {
            $query->where('head_name', 'Cash In Hand');
        })->get();
        $check_payment = ModeratorPayment::whereNot('id', $id)->where('month', $data->month)->where('year', $data->year)->count();
        if ($check_payment == 0) {
            $moderators = User::with(['moderatorOrders', 'leader'])->whereHas('roles', function ($query) {
                // $query->where('name', 'Moderator');
            })->whereHas('moderatorOrders', function ($q) use ($start_date, $end_date, $data) {
                $q->where(function ($query)  use ($start_date, $end_date) {
                    $query->whereBetween('delivered_at', [$start_date, $end_date])
                        ->orWhere('delivered_at', '<', $start_date);
                })
                    ->whereIn('status', ['Delivered', 'Collected'])
                    ->where(function ($query) use ($data) {
                        $query->where('commission', false)
                            ->orWhereIn('id', $data->orders->pluck('order_id')->toArray());
                    });
            })->where('status', true)->orderBy('name', 'asc')->get();
        }
        return view('admin.moderator-payment.edit', compact('title', 'data', 'month', 'year', 'cash_heads', 'moderators'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'date' => 'required',
            'month' => 'required',
            'year' => 'required',
            'coa_setup_id' => 'required',
            'user_id' => 'required',
            'member_order_qty' => 'required'
        ]);

        try {
            DB::transaction(function () use ($request, $id) {
                $data = ModeratorPayment::findOrFail($id);

                // Reverse Data
                Order::whereIn('id', $data->orders->pluck('order_id')->toArray())->update(['commission' => false]);
                ModeratorPaymentOrder::where('moderator_payment_id', $id)->delete();
                ModeratorPaymentList::where('moderator_payment_id', $id)->delete();
                AccountTransactionAuto::where('voucher_no', $data->serial_no)->where('voucher_type', 'Moderator Payment')->forceDelete();
                // Reverse Data

                $data->update([
                    'coa_setup_id' => $request->coa_setup_id,
                    'year' => $request->year,
                    'month' => $request->month,
                    'date' => date('Y-m-d', strtotime($request->date)),
                    'member_order_qty' => $request->member_order_qty,
                    'member_order_amout' => $request->member_order_amout,
                    'member_qty_commission' => $request->member_qty_commission,
                    'member_amount_commission' => $request->member_amount_commission,
                    'leader_order_qty' => $request->leader_order_qty,
                    'leader_order_amout' => $request->leader_order_amout,
                    'leader_qty_commission' => $request->leader_qty_commission,
                    'leader_amount_commission' => $request->leader_amount_commission,
                    'total_commission' => $request->total_commission,
                    'updated_by' => Auth::user()->id,
                ]);

                $start_date = date('Y-m-01', strtotime($request->month . '-' . $request->year));
                $end_date = date('Y-m-t', strtotime($request->month . '-' . $request->year));
                $cost = AdditionalCost::first();

                foreach ($request->user_id as $user_id) {
                    $user = User::find($user_id);
                    $totalCommission = 0;
                    $orders = Order::where('created_by', $user_id)
                        ->where(function ($query) use ($start_date, $end_date) {
                            $query
                                ->whereBetween('delivered_at', [$start_date, $end_date])
                                ->orWhere('delivered_at', '<', $start_date);
                        })
                        ->whereIn('status', ['Delivered', 'Collected'])
                        ->where('commission', false)
                        ->get();
                    $qty = $orders->count();
                    $qtyCommission = $qty * $cost->moderator_cost;
                    $totalCommission += $qtyCommission;
                    $amount = $orders->sum(function ($order) {
                        return $order->sub_total - $order->discount;
                    });
                    $amountCommission = ($cost->moderator_cost_percentage / 100) * $amount;
                    $totalCommission += $amountCommission;

                    if ($user->leader) {
                        $moderator_ids = $user->leader->members->pluck('user_id')->toArray();
                        $leaderOrders = Order::whereIn('created_by', $moderator_ids)
                            ->whereNot('created_by', $user_id)
                            ->where(function ($query) use ($start_date, $end_date) {
                                $query
                                    ->whereBetween('delivered_at', [$start_date, $end_date])
                                    ->orWhere('delivered_at', '<', $start_date);
                            })
                            ->whereIn('status', ['Delivered', 'Collected'])
                            ->where('commission', false)
                            ->get();
                        $leaderQty = $leaderOrders->count();
                        $leaderQtyCommission = $leaderQty * $cost->team_leader_cost;
                        $totalCommission += $leaderQtyCommission;
                        $leaderAmount = $leaderOrders->sum(function ($order) {
                            return $order->sub_total - $order->discount;
                        });
                        $leaderAmountCommission = ($cost->team_leader_percentage / 100) * $leaderAmount;
                        $totalCommission += $leaderAmountCommission;
                    }

                    ModeratorPaymentList::create([
                        'moderator_payment_id' => $data->id,
                        'user_id' => $user_id,
                        'year' => $request->year,
                        'month' => $request->month,
                        'order_qty' => $qty,
                        'order_amount' => $amount,
                        'qty_commission' => $qtyCommission,
                        'amount_commission' => $amountCommission,
                        'leader_qty' => $leaderQty ?? 0,
                        'leader_amount' => $leaderAmount ?? 0,
                        'leader_qty_commission' => $leaderQtyCommission ?? 0,
                        'leader_amount_commission' => $leaderAmountCommission ?? 0,
                        'total_commission' => $totalCommission,
                    ]);

                    foreach ((array) json_decode($request->order_ids[$user_id]) as $order_id) {
                        ModeratorPaymentOrder::create([
                            'moderator_payment_id' => $data->id,
                            'user_id' => $user_id,
                            'order_id' => $order_id
                        ]);
                    }
                }

                foreach ($request->user_id as $user_id) {
                    Order::whereIn('id', json_decode($request->order_ids[$user_id]))->update(['commission' => true]);
                }

                $admin_setting = AdminSetting::first();
                if (@$admin_setting->accounting == 1) {
                    $cash_head = CoaSetup::findOrFail($request->coa_setup_id);
                    $headCode = collect([
                        '0' => 40105,
                        '1' => $cash_head->head_code,
                    ]);
                    $countHead = count($headCode);

                    $debit_amount = collect([
                        '0' => round($data->total_commission),
                        '1' => 0.00
                    ]);

                    $credit_amount = collect([
                        '0' => 0.00,
                        '1' => round($data->total_commission),
                    ]);

                    $postData = [];
                    for ($i = 0; $i < $countHead; $i++) {
                        $coa = CoaSetup::where('company_id', Auth::user()->company_id ?? 1)->where('head_code', $headCode[$i])->first();
                        $postData[] = [
                            'company_id' => Auth::user()->company_id ?? 1,
                            'voucher_no' => $data->serial_no,
                            'voucher_type' => "Moderator Payment",
                            'voucher_date' => date('Y-m-d', strtotime($request->date)),
                            'coa_setup_id' => $coa->id,
                            'coa_head_code' => $headCode[$i],
                            'narration' => 'Moderator Payment against PAYMENT NO - ' . $data->serial_no,
                            'debit_amount' => $debit_amount[$i],
                            'credit_amount' => $credit_amount[$i],
                            'created_by' => Auth::user()->id,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now()
                        ];
                    }
                    AccountTransactionAuto::insert($postData);
                }
            });
        } catch (\Exception $e) {
            return back()->withErrors($e->getMessage());
        }

        return redirect()->Route('admin.moderator-payment.index')->withSuccessMessage('Updated Successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            DB::transaction(function () use ($id) {
                $data = ModeratorPayment::findOrFail($id);
                Order::whereIn('id', $data->orders->pluck('order_id')->toArray())->update(['commission' => false]);
                AccountTransactionAuto::where('voucher_no', $data->serial_no)->where('voucher_type', 'Moderator Payment')->forceDelete();
                $data->update(['deleted_by' => Auth::user()->id]);
                $data->forceDelete();
            });
            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}
