<?php

namespace App\DataTables;

use App\Models\User;
use App\Models\Order;
use App\Models\OrderProduct;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class ModeratorOrdersDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $cost = \App\Models\AdditionalCost::first();
        $start_date = request('start_date') ? date('Y-m-d', strtotime(request('start_date'))) : date('Y-m-01');
        $end_date = request('end_date') ? date('Y-m-d', strtotime(request('end_date'))) : date('Y-m-t');

        return (new EloquentDataTable($query))
            ->addIndexColumn()
            // ->setRowClass(function ($row) {
            //     return $row->leader ? 'table-success' : ''; 
            // })
            ->addColumn('issue', function ($row) {
                $start_date = request('start_date') ? date('Y-m-d', strtotime(request('start_date'))) : date('Y-m-01');
                $end_date = request('end_date') ? date('Y-m-d', strtotime(request('end_date'))) : date('Y-m-t');
                $count = $row->moderatorOrders->where('date', '>=', $start_date)->where('date', '<=', $end_date)->count();
                if ($count > 0) {
                    return '<a href="' . Route('admin.moderator-orders.index') . '?view_orders=true&moderator_id=' . $row->id . '&start_date=' . $start_date . '&end_date=' . $end_date . '">' . $count . '</a>';
                } else {
                    return '-';
                }
            })
            ->addColumn('pending', function ($row) {
                $start_date = request('start_date') ? date('Y-m-d', strtotime(request('start_date'))) : date('Y-m-01');
                $end_date = request('end_date') ? date('Y-m-d', strtotime(request('end_date'))) : date('Y-m-t');
                $count = $row->moderatorOrders->where('date', '>=', $start_date)->where('date', '<=', $end_date)->where('status', 'Pending')->count();
                if ($count > 0) {
                    return '<a href="' . Route('admin.moderator-orders.index') . '?view_orders=true&moderator_id=' . $row->id . '&status=Pending&start_date=' . $start_date . '&end_date=' . $end_date . '">' . $count . '</a>';
                } else {
                    return '-';
                }
            })
            ->addColumn('forward', function ($row) {
                $start_date = request('start_date') ? date('Y-m-d', strtotime(request('start_date'))) : date('Y-m-01');
                $end_date = request('end_date') ? date('Y-m-d', strtotime(request('end_date'))) : date('Y-m-t');
                $count = $row->moderatorOrders->where('date', '>=', $start_date)->where('date', '<=', $end_date)->where('status', 'Forward')->count();
                if ($count > 0) {
                    return '<a href="' . Route('admin.moderator-orders.index') . '?view_orders=true&moderator_id=' . $row->id . '&status=Forward&start_date=' . $start_date . '&end_date=' . $end_date . '">' . $count . '</a>';
                } else {
                    return '-';
                }
            })
            ->addColumn('on_route', function ($row) {
                $start_date = request('start_date') ? date('Y-m-d', strtotime(request('start_date'))) : date('Y-m-01');
                $end_date = request('end_date') ? date('Y-m-d', strtotime(request('end_date'))) : date('Y-m-t');
                $count = $row->moderatorOrders->where('date', '>=', $start_date)->where('date', '<=', $end_date)->where('status', 'On Route')->count();
                if ($count > 0) {
                    return '<a href="' . Route('admin.moderator-orders.index') . '?view_orders=true&moderator_id=' . $row->id . '&status=On Route&start_date=' . $start_date . '&end_date=' . $end_date . '">' . $count . '</a>';
                } else {
                    return '-';
                }
            })
            ->addColumn('returned', function ($row) {
                $start_date = request('start_date') ? date('Y-m-d', strtotime(request('start_date'))) : date('Y-m-01');
                $end_date = request('end_date') ? date('Y-m-d', strtotime(request('end_date'))) : date('Y-m-t');
                $count = $row->moderatorOrders->where('date', '>=', $start_date)->where('date', '<=', $end_date)->where('status', 'Returned')->count();
                if ($count > 0) {
                    return '<a href="' . Route('admin.moderator-orders.index') . '?view_orders=true&moderator_id=' . $row->id . '&status=Returned&start_date=' . $start_date . '&end_date=' . $end_date . '">' . $count . '</a>';
                } else {
                    return '-';
                }
            })
            ->addColumn('delivered', function ($row) {
                $start_date = request('start_date') ? date('Y-m-d', strtotime(request('start_date'))) : date('Y-m-01');
                $end_date = request('end_date') ? date('Y-m-d', strtotime(request('end_date'))) : date('Y-m-t');
                $count = $row->moderatorOrders->where('date', '>=', $start_date)->where('date', '<=', $end_date)->where('status', 'Delivered')->count();
                if ($count > 0) {
                    return '<a href="' . Route('admin.moderator-orders.index') . '?view_orders=true&moderator_id=' . $row->id . '&status=Delivered&start_date=' . $start_date . '&end_date=' . $end_date . '">' . $count . '</a>';
                } else {
                    return '-';
                }
            })
            ->addColumn('collected', function ($row) {
                $start_date = request('start_date') ? date('Y-m-d', strtotime(request('start_date'))) : date('Y-m-01');
                $end_date = request('end_date') ? date('Y-m-d', strtotime(request('end_date'))) : date('Y-m-t');
                $count = $row->moderatorOrders->where('date', '>=', $start_date)->where('date', '<=', $end_date)->where('status', 'Collected')->count();
                if ($count > 0) {
                    return '<a href="' . Route('admin.moderator-orders.index') . '?view_orders=true&moderator_id=' . $row->id . '&status=Collected&start_date=' . $start_date . '&end_date=' . $end_date . '">' . $count . '</a>';
                } else {
                    return '-';
                }
            })
            ->addColumn('cancelled', function ($row) {
                $start_date = request('start_date') ? date('Y-m-d', strtotime(request('start_date'))) : date('Y-m-01');
                $end_date = request('end_date') ? date('Y-m-d', strtotime(request('end_date'))) : date('Y-m-t');
                $count = $row->moderatorOrders->where('date', '>=', $start_date)->where('date', '<=', $end_date)->where('status', 'Cancelled')->count();
                if ($count > 0) {
                    return '<a href="' . Route('admin.moderator-orders.index') . '?view_orders=true&moderator_id=' . $row->id . '&status=Cancelled&start_date=' . $start_date . '&end_date=' . $end_date . '">' . $count . '</a>';
                } else {
                    return '-';
                }
            })
            ->addColumn('collected_amount', function ($row) {
                $start_date = request('start_date') ? date('Y-m-d', strtotime(request('start_date'))) : date('Y-m-01');
                $end_date = request('end_date') ? date('Y-m-d', strtotime(request('end_date'))) : date('Y-m-t');
                $amount = $row->moderatorOrders->where('date', '>=', $start_date)->where('date', '<=', $end_date)->where('collected', true)->sum(function ($order) {
                        return $order->sub_total - $order->discount;
                    });
                if ($amount > 0) {
                    return $amount;
                } else {
                    return '-';
                }
            })
            ->addColumn('moderator_earn', function ($row) use ($cost) {
                $start_date = request('start_date') ? date('Y-m-d', strtotime(request('start_date'))) : date('Y-m-01');
                $end_date = request('end_date') ? date('Y-m-d', strtotime(request('end_date'))) : date('Y-m-t');
                $orders = $row->moderatorOrders->where('date', '>=', $start_date)->where('date', '<=', $end_date)->whereIn('status', ['Delivered', 'Collected'])->where('commission', false);
                
                $qty = $orders->count();
                $qtyCommission = $qty * $cost->moderator_cost;
                $amount = $orders->sum(function ($order) {
                    return $order->sub_total - $order->discount;
                });
                $amountCommission = ($cost->moderator_cost_percentage / 100) * $amount;
                $moderatorCommission = round($qtyCommission + $amountCommission, 2);

                if ($moderatorCommission > 0) {
                    return $moderatorCommission;
                } else {
                    return '-';
                }
            })
            ->rawColumns(['issue', 'pending', 'forward', 'on_route', 'delivered', 'returned', 'collected', 'cancelled'])
            ->with([
                'total_issue' => $query->get()->sum(function ($row) use ($start_date, $end_date) {
                    return $row->moderatorOrders->whereBetween('date', [$start_date, $end_date])->whereIn('status', ['Pending', 'Forward', 'On Route', 'Returned', 'Delivered', 'Collected', 'Cancelled'])->count();
                }),
                'total_pending' => $query->get()->sum(function ($row) use ($start_date, $end_date) {
                    return $row->moderatorOrders->whereBetween('date', [$start_date, $end_date])->where('status', 'Pending')->count();
                }),
                'total_forward' => $query->get()->sum(function ($row) use ($start_date, $end_date) {
                    return $row->moderatorOrders->whereBetween('date', [$start_date, $end_date])->where('status', 'Forward')->count();
                }),
                'total_on_route' => $query->get()->sum(function ($row) use ($start_date, $end_date) {
                    return $row->moderatorOrders->whereBetween('date', [$start_date, $end_date])->where('status', 'On Route')->count();
                }),
                'total_returned' => $query->get()->sum(function ($row) use ($start_date, $end_date) {
                    return $row->moderatorOrders->whereBetween('date', [$start_date, $end_date])->where('status', 'Returned')->count();
                }),
                'total_delivered' => $query->get()->sum(function ($row) use ($start_date, $end_date) {
                    return $row->moderatorOrders->whereBetween('date', [$start_date, $end_date])->where('status', 'Delivered')->count();
                }),
                'total_collected' => $query->get()->sum(function ($row) use ($start_date, $end_date) {
                    return $row->moderatorOrders->whereBetween('date', [$start_date, $end_date])->where('status', 'Collected')->count();
                }),
                'total_cancelled' => $query->get()->sum(function ($row) use ($start_date, $end_date) {
                    return $row->moderatorOrders->whereBetween('date', [$start_date, $end_date])->where('status', 'Cancelled')->count();
                }),
                'total_collected_amount' => $query->get()->sum(function ($row) use ($start_date, $end_date) {
                    return $row->moderatorOrders->whereBetween('date', [$start_date, $end_date])->where('collected', true)->sum(function ($order) {
                        return $order->sub_total - $order->discount;
                    });
                }),
                'total_moderator_earn' => round(
                    $query->get()->sum(function ($row) use ($cost) {
                        $start_date = request('start_date') ? date('Y-m-d', strtotime(request('start_date'))) : date('Y-m-01');
                        $end_date = request('end_date') ? date('Y-m-d', strtotime(request('end_date'))) : date('Y-m-t');

                        $orders = $row->moderatorOrders
                            ->where('date', '>=', $start_date)
                            ->where('date', '<=', $end_date)
                            ->whereIn('status', ['Delivered', 'Collected'])
                            ->where('commission', false);

                        $qty = $orders->count();
                        $qtyCommission = $qty * $cost->moderator_cost;

                        $amount = $orders->sum(function ($order) {
                            return $order->sub_total - $order->discount;
                        });
                        $amountCommission = ($cost->moderator_cost_percentage / 100) * $amount;

                        $moderatorCommission = $qtyCommission + $amountCommission;

                        return $moderatorCommission; // keep raw for summation
                    }),
                    2 // ✅ round final sum to 2 decimals
                ),
            ]);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(User $model): QueryBuilder
    {
        $start_date = request('start_date') ? date('Y-m-d', strtotime(request('start_date'))) : date('Y-m-01');
        $end_date = request('end_date') ? date('Y-m-d', strtotime(request('end_date'))) : date('Y-m-t');

        $query = $model->with('moderatorOrders')
            ->withCount(['moderatorOrders as total_sales' => function ($q) use ($start_date, $end_date) {
                $q->whereBetween('date', [$start_date, $end_date]);
                // $q->whereIn('status', ['Delivered', 'Collected']);
            }])
            // ->whereHas('roles', function ($q) {
            //     $q->where('name', 'Moderator');
            // })
            ->whereHas('moderatorOrders', function ($q) use ($start_date, $end_date) {
                $q->whereBetween('date', [$start_date, $end_date]);
                if (!is_null(request('store_id'))) {
                    $q->where('store_id', request('store_id'));
                }
            })
            ->where('status', 1);

        if (Auth::user()->hasRole('Moderator')) {
            $query->where('id', Auth::user()->id);
        }

        if (request('moderator_id')) {
            $query->whereIn('id', request('moderator_id'));
        }

        // 🔥 Order by highest total sales (DESC)
        return $query->orderByDesc('total_sales');
    }


    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('dataTable')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->selectStyleSingle()
            ->parameters([
                'dom' => "<'row g-2'<'col-sm-4'l><'col-sm-8 text-end'<'d-lg-flex justify-content-end'<'mb-2 mb-lg-0 me-1'f>B>>>t<'d-lg-flex align-items-center mt-2'<'me-auto mb-lg-0 mb-2'i><'mb-0'p>>",
                'lengthMenu' => [10, 20, 30, 40, 50],
                'buttons'      => [
                    Button::make('reload'),
                    [
                        'extend'  => 'excel',
                        'text'    => '<i class="fal fa-file-spreadsheet"></i> Exel',
                    ],
                    [
                        'text'    => '<i class="fal fa-file-pdf"></i> Print',
                        'className' => 'getPdf',
                    ],
                ],
                'drawCallback' => 'function() {
                    let json = this.api().ajax.json();
                    $("#issue").html(json.total_issue ?? 0);
                    $("#pending").html(json.total_pending ?? 0);
                    $("#forward").html(json.total_forward ?? 0);
                    $("#on_route").html(json.total_on_route ?? 0);
                    $("#returned").html(json.total_returned ?? 0);
                    $("#delivered").html(json.total_delivered ?? 0);
                    $("#collected").html(json.total_collected ?? 0);
                    $("#cancelled").html(json.total_cancelled ?? 0);
                    $("#collected_amount").html(json.total_collected_amount ?? 0);
                    $("#moderator_earn").html(json.total_moderator_earn ?? 0);
                }',
                'responsive' => true,
            ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make([
                'data'      => "DT_RowIndex",
                'name'      => "DT_RowIndex",
                'title'     => 'SL#',
                'orderable' => false,
                'searchable' => false,
                'width'     => '30',
                'class'     => 'text-center',
            ]),
            Column::make([
                'data'      => 'name',
                'name'      => 'name',
                'title'     => 'Moderator',
                'class'     => 'text-nowrap',
            ]),
            Column::make([
                'data'      => 'moderator_earn',
                'name'      => 'moderator_earn',
                'title'     => 'Moderator Earned',
                'footer'    => '<span id="moderator_earn"></span>',
                'orderable' => false,
                'searchable' => false,
                'width'     => '100',
                'class'     => 'text-center',
            ]),
            Column::make([
                'data'      => 'issue',
                'name'      => 'issue',
                'title'     => 'Orders',
                'footer'    => '<span id="issue"></span>',
                'orderable' => false,
                'searchable' => false,
                'width'     => '100',
                'class'     => 'text-center',
            ]),
            Column::make([
                'data'      => 'collected',
                'name'      => 'collected',
                'title'     => 'Successfull',
                'footer'    => '<span id="collected"></span>',
                'orderable' => false,
                'searchable' => false,
                'width'     => '100',
                'class'     => 'text-center',
            ]),
            Column::make([
                'data'      => 'collected_amount',
                'name'      => 'collected_amount',
                'title'     => 'Sales Amount',
                'footer'    => '<span id="collected_amount"></span>',
                'orderable' => false,
                'searchable' => false,
                'width'     => '100',
                'class'     => 'text-center',
            ]),
            Column::make([
                'data'      => 'pending',
                'name'      => 'pending',
                'title'     => 'Pending',
                'footer'    => '<span id="pending"></span>',
                'orderable' => false,
                'searchable' => false,
                'width'     => '100',
                'class'     => 'text-center',
            ]),
            Column::make([
                'data'      => 'forward',
                'name'      => 'forward',
                'title'     => 'Forward',
                'footer'    => '<span id="forward"></span>',
                'orderable' => false,
                'searchable' => false,
                'width'     => '100',
                'class'     => 'text-center',
            ]),
            Column::make([
                'data'      => 'on_route',
                'name'      => 'on_route',
                'title'     => 'On Route',
                'footer'    => '<span id="on_route"></span>',
                'orderable' => false,
                'searchable' => false,
                'width'     => '100',
                'class'     => 'text-center',
            ]),
            Column::make([
                'data'      => 'returned',
                'name'      => 'returned',
                'title'     => 'Return',
                'footer'    => '<span id="returned"></span>',
                'orderable' => false,
                'searchable' => false,
                'width'     => '100',
                'class'     => 'text-center',
            ]),
            Column::make([
                'data'      => 'delivered',
                'name'      => 'delivered',
                'title'     => 'Delivered',
                'footer'    => '<span id="delivered"></span>',
                'orderable' => false,
                'searchable' => false,
                'width'     => '100',
                'class'     => 'text-center',
            ]),
            Column::make([
                'data'      => 'cancelled',
                'name'      => 'cancelled',
                'title'     => 'Cancelled',
                'footer'    => '<span id="cancelled"></span>',
                'orderable' => false,
                'searchable' => false,
                'width'     => '100',
                'class'     => 'text-center',
            ]),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'moderator_orders_' . date('d_m_Y_h_i_s_A');
    }
}
