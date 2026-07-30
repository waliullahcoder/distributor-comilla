<?php

namespace App\DataTables;

use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\DeliveryAgent;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class DeliverymanOrdersDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('issue', function ($row) {
                $start_date = request('start_date') ? date('Y-m-d', strtotime(request('start_date'))) : date('Y-m-01');
                $end_date = request('end_date') ? date('Y-m-d', strtotime(request('end_date'))) : date('Y-m-t');
                $count = $row->orders->where('date', '>=', $start_date)->where('date', '<=', $end_date)->count();
                if ($count > 0) {
                    return '<a href="' . Route('admin.deliveryman-orders.index') . '?view_orders=true&delivery_agent_id=' . $row->id . '&start_date=' . $start_date . '&end_date=' . $end_date . '">' . $count . '</a>';
                } else {
                    return '-';
                }
            })
            ->addColumn('on_route', function ($row) {
                $start_date = request('start_date') ? date('Y-m-d', strtotime(request('start_date'))) : date('Y-m-01');
                $end_date = request('end_date') ? date('Y-m-d', strtotime(request('end_date'))) : date('Y-m-t');
                $count = $row->orders->where('date', '>=', $start_date)->where('date', '<=', $end_date)->where('status', 'On Route')->count();
                if ($count > 0) {
                    return '<a href="' . Route('admin.deliveryman-orders.index') . '?view_orders=true&delivery_agent_id=' . $row->id . '&status=On Route&start_date=' . $start_date . '&end_date=' . $end_date . '">' . $count . '</a>';
                } else {
                    return '-';
                }
            })
            ->addColumn('returned', function ($row) {
                $start_date = request('start_date') ? date('Y-m-d', strtotime(request('start_date'))) : date('Y-m-01');
                $end_date = request('end_date') ? date('Y-m-d', strtotime(request('end_date'))) : date('Y-m-t');
                $count = $row->orders->where('date', '>=', $start_date)->where('date', '<=', $end_date)->where('status', 'Returned')->count();
                if ($count > 0) {
                    return '<a href="' . Route('admin.deliveryman-orders.index') . '?view_orders=true&delivery_agent_id=' . $row->id . '&status=Returned&start_date=' . $start_date . '&end_date=' . $end_date . '">' . $count . '</a>';
                } else {
                    return '-';
                }
            })
            ->addColumn('delivered', function ($row) {
                $start_date = request('start_date') ? date('Y-m-d', strtotime(request('start_date'))) : date('Y-m-01');
                $end_date = request('end_date') ? date('Y-m-d', strtotime(request('end_date'))) : date('Y-m-t');
                $count = $row->orders->where('date', '>=', $start_date)->where('date', '<=', $end_date)->where('status', 'Delivered')->count();
                if ($count > 0) {
                    return '<a href="' . Route('admin.deliveryman-orders.index') . '?view_orders=true&delivery_agent_id=' . $row->id . '&status=Delivered&start_date=' . $start_date . '&end_date=' . $end_date . '">' . $count . '</a>';
                } else {
                    return '-';
                }
            })
            ->addColumn('collected', function ($row) {
                $start_date = request('start_date') ? date('Y-m-d', strtotime(request('start_date'))) : date('Y-m-01');
                $end_date = request('end_date') ? date('Y-m-d', strtotime(request('end_date'))) : date('Y-m-t');
                $count = $row->orders->where('date', '>=', $start_date)->where('date', '<=', $end_date)->where('status', 'Collected')->count();
                if ($count > 0) {
                    return '<a href="' . Route('admin.deliveryman-orders.index') . '?view_orders=true&delivery_agent_id=' . $row->id . '&status=Collected&start_date=' . $start_date . '&end_date=' . $end_date . '">' . $count . '</a>';
                } else {
                    return '-';
                }
            })
            ->addColumn('cancelled', function ($row) {
                $start_date = request('start_date') ? date('Y-m-d', strtotime(request('start_date'))) : date('Y-m-01');
                $end_date = request('end_date') ? date('Y-m-d', strtotime(request('end_date'))) : date('Y-m-t');
                $count = $row->orders->where('date', '>=', $start_date)->where('date', '<=', $end_date)->where('status', 'Cancelled')->count();
                if ($count > 0) {
                    return '<a href="' . Route('admin.deliveryman-orders.index') . '?view_orders=true&delivery_agent_id=' . $row->id . '&status=Cancelled&start_date=' . $start_date . '&end_date=' . $end_date . '">' . $count . '</a>';
                } else {
                    return '-';
                }
            })
            ->addColumn('collected_amount', function ($row) {
                $qq = OrderProduct::whereHas('order', function ($query) use ($row) {
                    $start_date = request('start_date') ? date('Y-m-d', strtotime(request('start_date'))) : date('Y-m-01');
                    $end_date = request('end_date') ? date('Y-m-d', strtotime(request('end_date'))) : date('Y-m-t');
                    $query->where('date', '>=', $start_date)->where('date', '<=', $end_date)->where('collected', 1);
                    $query->where('delivery_agent_id', $row->id);
                });
                $amount = $qq->sum(DB::raw('subtotal - discount'));
                $order_ids = $qq->groupBy('order_id')->pluck('order_id')->toArray();
                $shipping_charge = Order::whereIn('id', $order_ids)->sum('shipping_charge');
                $count = round($amount + $shipping_charge);
                if ($count > 0) {
                    return $count;
                } else {
                    return '-';
                }
            })
            ->addColumn('total_issue', function ($row) {
                $start_date = request('start_date') ? date('Y-m-d', strtotime(request('start_date'))) : date('Y-m-01');
                $end_date = request('end_date') ? date('Y-m-d', strtotime(request('end_date'))) : date('Y-m-t');
                $count = Order::whereHas('agent', function ($query) {
                    $query->where('status', 1);
                })->where('date', '>=', $start_date)->where('date', '<=', $end_date)->count();
                return $count > 0 ? $count : '-';
            })
            ->addColumn('total_on_route', function ($row) {
                $start_date = request('start_date') ? date('Y-m-d', strtotime(request('start_date'))) : date('Y-m-01');
                $end_date = request('end_date') ? date('Y-m-d', strtotime(request('end_date'))) : date('Y-m-t');
                $count = Order::whereHas('agent', function ($query) {
                    $query->where('status', 1);
                })->where('date', '>=', $start_date)->where('date', '<=', $end_date)->where('status', 'On Route')->count();
                return $count > 0 ? $count : '-';
            })
            ->addColumn('total_returned', function ($row) {
                $start_date = request('start_date') ? date('Y-m-d', strtotime(request('start_date'))) : date('Y-m-01');
                $end_date = request('end_date') ? date('Y-m-d', strtotime(request('end_date'))) : date('Y-m-t');
                $count = Order::whereHas('agent', function ($query) {
                    $query->where('status', 1);
                })->where('date', '>=', $start_date)->where('date', '<=', $end_date)->where('status', 'Returned')->count();
                return $count > 0 ? $count : '-';
            })
            ->addColumn('total_delivered', function ($row) {
                $start_date = request('start_date') ? date('Y-m-d', strtotime(request('start_date'))) : date('Y-m-01');
                $end_date = request('end_date') ? date('Y-m-d', strtotime(request('end_date'))) : date('Y-m-t');
                $count = Order::whereHas('agent', function ($query) {
                    $query->where('status', 1);
                })->where('date', '>=', $start_date)->where('date', '<=', $end_date)->where('status', 'Delivered')->count();
                return $count > 0 ? $count : '-';
            })
            ->addColumn('total_collected', function ($row) {
                $start_date = request('start_date') ? date('Y-m-d', strtotime(request('start_date'))) : date('Y-m-01');
                $end_date = request('end_date') ? date('Y-m-d', strtotime(request('end_date'))) : date('Y-m-t');
                $count = Order::whereHas('agent', function ($query) {
                    $query->where('status', 1);
                })->where('date', '>=', $start_date)->where('date', '<=', $end_date)->where('status', 'Collected')->count();
                return $count > 0 ? $count : '-';
            })
            ->addColumn('total_cancelled', function ($row) {
                $start_date = request('start_date') ? date('Y-m-d', strtotime(request('start_date'))) : date('Y-m-01');
                $end_date = request('end_date') ? date('Y-m-d', strtotime(request('end_date'))) : date('Y-m-t');
                $count = Order::whereHas('agent', function ($query) {
                    $query->where('status', 1);
                })->where('date', '>=', $start_date)->where('date', '<=', $end_date)->where('status', 'Cancelled')->count();
                return $count > 0 ? $count : '-';
            })
            ->addColumn('total_collected_amount', function ($row) {
                $qq = OrderProduct::whereHas('order', function ($query) {
                    $start_date = request('start_date') ? date('Y-m-d', strtotime(request('start_date'))) : date('Y-m-01');
                    $end_date = request('end_date') ? date('Y-m-d', strtotime(request('end_date'))) : date('Y-m-t');
                    $query->where('date', '>=', $start_date)->where('date', '<=', $end_date)->where('collected', 1);
                    $query->whereHas('agent', function ($q) {
                        $q->where('status', 1);
                    });
                });
                $amount = $qq->sum(DB::raw('subtotal - discount'));
                $order_ids = $qq->groupBy('order_id')->pluck('order_id')->toArray();
                $shipping_charge = Order::whereIn('id', $order_ids)->sum('shipping_charge');

                $count = round($amount + $shipping_charge);
                return $count > 0 ? $count : '-';
            })
            ->rawColumns(['issue', 'on_route', 'delivered', 'returned', 'collected', 'cancelled']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(DeliveryAgent $model): QueryBuilder
    {
        $query = $model;
        return $query->whereHas('orders', function ($q) {
            $start_date = request('start_date') ? date('Y-m-d', strtotime(request('start_date'))) : date('Y-m-01');
            $end_date = request('end_date') ? date('Y-m-d', strtotime(request('end_date'))) : date('Y-m-t');
            $q->where('date', '>=', $start_date)->where('date', '<=', $end_date);
            if (!is_null(request('store_id'))) {
                $q->where('store_id', request('store_id'));
            }
        })->where('status', 1);
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
            ->dom('Bfrtip')
            ->selectStyleSingle()
            ->parameters([
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
                    let data = this.api().ajax.json().data;
                    var total_issue = 0;
                    var total_on_route = 0;
                    var total_returned = 0;
                    var total_delivered = 0;
                    var total_collected = 0;
                    var total_cancelled = 0;
                    var total_collected_amount = 0;

                    data.forEach(function(item, index){
                        total_issue = item.total_issue;
                        total_on_route = item.total_on_route;
                        total_returned = item.total_returned;
                        total_delivered = item.total_delivered;
                        total_collected = item.total_collected;
                        total_cancelled = item.total_cancelled;
                        total_collected_amount = item.total_collected_amount;
                    });
                    $("#issue").html(total_issue);
                    $("#on_route").html(total_on_route);
                    $("#returned").html(total_returned);
                    $("#delivered").html(total_delivered);
                    $("#collected").html(total_collected);
                    $("#cancelled").html(total_cancelled);
                    $("#collected_amount").html(total_collected_amount);
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
                'title'     => 'Delivery Man Name',
                'class'     => 'text-nowrap',
            ]),
            Column::make([
                'data'      => 'issue',
                'name'      => 'issue',
                'title'     => 'Issue',
                'footer'    => '<span id="issue"></span>',
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
            Column::make([
                'data'      => 'collected',
                'name'      => 'collected',
                'title'     => 'Collected',
                'footer'    => '<span id="collected"></span>',
                'orderable' => false,
                'searchable' => false,
                'width'     => '100',
                'class'     => 'text-center',
            ]),
            Column::make([
                'data'      => 'collected_amount',
                'name'      => 'collected_amount',
                'title'     => 'Collected Amount',
                'footer'    => '<span id="collected_amount"></span>',
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
        return 'deliveryman_orders_' . date('d_m_Y_h_i_s_A');
    }
}
