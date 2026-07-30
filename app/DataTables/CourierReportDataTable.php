<?php

namespace App\DataTables;

use App\Models\Order;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class CourierReportDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $start_date = request('start_date') ? date('Y-m-d', strtotime(request('start_date'))) : date('Y-m-01');
        $end_date = request('end_date') ? date('Y-m-d', strtotime(request('end_date'))) : date('Y-m-t');

        $query = Order::with(['store', 'agent']);
        if (!is_null(request('store_id'))) {
            $query->where('store_id', request('store_id'));
        }
        if (!is_null(request('delivery_agent_id'))) {
            $query->where('delivery_agent_id', request('delivery_agent_id'));
        }
        $sumQuery = $query->where('delivered_at', '>=', $start_date)->where('delivered_at', '<=', $end_date)->whereIn('status', ['Delivered', 'Collected', 'Returned'])->get();

        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('update_date', function ($row) {
                return date('d-m-Y', strtotime($row->delivered_at));
            })
            ->addColumn('delivery_cost', function ($row) {
                if ($row->status == 'Returned') {
                    return 0;
                }
                return $row->delivery_cost;
            })
            ->addColumn('return_cost', function ($row) {
                if ($row->status == 'Returned') {
                    return $row->return_cost;
                }
                return 0;
            })
            ->addColumn('payable', function ($row) {
                $courier_cost = $row->delivery_cost;
                if ($row->status == 'Returned') {
                    $courier_cost = $row->return_cost;
                }
                return number_format($row->receive - $courier_cost);
            })

            ->addColumn('total_amount', function ($row) use ($sumQuery) {
                return $sumQuery->sum('due');
            })
            ->addColumn('total_receive', function ($row) use ($sumQuery) {
                return $sumQuery->sum('receive');
            })
            ->addColumn('total_delivery_cost', function ($row) use ($sumQuery) {
                return $sumQuery->where('status', '!=', 'Returned')->sum('delivery_cost');
            })
            ->addColumn('total_return_cost', function ($row) use ($sumQuery) {
                return $sumQuery->where('status', 'Returned')->sum('return_cost');
            })
            ->addColumn('total_payable', function ($row) use ($sumQuery) {
                $deliveryCost = $sumQuery->where('status', '!=', 'Returned')->sum('delivery_cost');
                $returnCost = $sumQuery->where('status', 'Returned')->sum('return_cost');
                return $sumQuery->sum('receive') - $deliveryCost - $returnCost;
            });
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Order $model): QueryBuilder
    {
        $start_date = request('start_date') ? date('Y-m-d', strtotime(request('start_date'))) : date('Y-m-01');
        $end_date = request('end_date') ? date('Y-m-d', strtotime(request('end_date'))) : date('Y-m-t');

        $query = $model->with(['store', 'agent']);
        if (!is_null(request('store_id'))) {
            $query->where('store_id', request('store_id'));
        }
        if (!is_null(request('delivery_agent_id'))) {
            $query->where('delivery_agent_id', request('delivery_agent_id'));
        }
        return $query->where('delivered_at', '>=', $start_date)->where('delivered_at', '<=', $end_date)->whereIn('status', ['Delivered', 'Collected', 'Returned']);
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
                    var totalAmount = 0;
                    var totalReceive = 0;
                    var totalDeliveryCost = 0;
                    var totalReturnCost = 0;
                    var totalPayable = 0;

                    data.forEach(function(item, index){
                        totalAmount = item.total_amount;
                        totalReceive = item.total_receive;
                        totalDeliveryCost = item.total_delivery_cost;
                        totalReturnCost = item.total_return_cost;
                        totalPayable = item.total_payable;
                    });

                    $("#totalAmount").html(totalAmount);
                    $("#totalReceive").html(totalReceive);
                    $("#totalDeliveryCost").html(totalDeliveryCost);
                    $("#totalReturnCost").html(totalReturnCost);
                    $("#totalPayable").html(totalPayable);
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
                'data'      => 'agent.name',
                'name'      => 'agent.name',
                'title'     => 'Courier',
                'class'     => 'text-nowrap',
                'defaultContent'     => '',
            ]),
            Column::make([
                'data'      => 'update_date',
                'name'      => 'update_date',
                'title'     => 'Update Date',
                'class'     => 'text-nowrap',
                'orderable' => false,
                'searchable' => false,
            ]),
            Column::make([
                'data'      => 'invoice',
                'name'      => 'invoice',
                'title'     => 'Invoice',
                'class'     => 'text-nowrap',
            ]),
            Column::make([
                'data'      => 'user_name',
                'name'      => 'user_name',
                'title'     => 'Name',
            ]),
            Column::make([
                'data'      => 'user_phone',
                'name'      => 'user_phone',
                'title'     => 'Phone',
            ]),
            Column::make([
                'data'      => 'due',
                'name'      => 'due',
                'title'     => 'Amount',
                'orderable' => false,
                'searchable' => false,
                'class'     => 'text-nowrap',
                'footer'    => '<span id="totalAmount"></span>',
            ]),
            Column::make([
                'data'      => 'receive',
                'name'      => 'receive',
                'title'     => 'Collect',
                'footer'    => '<span id="totalReceive"></span>',
            ]),
            Column::make([
                'data'      => 'delivery_cost',
                'name'      => 'delivery_cost',
                'title'     => 'Delivery',
                'orderable' => false,
                'searchable' => false,
                'footer'    => '<span id="totalDeliveryCost"></span>',
            ]),
            Column::make([
                'data'      => 'return_cost',
                'name'      => 'return_cost',
                'title'     => 'Return',
                'orderable' => false,
                'searchable' => false,
                'footer'    => '<span id="totalReturnCost"></span>',
            ]),
            Column::make([
                'data'      => 'payable',
                'name'      => 'payable',
                'title'     => 'Payable',
                'orderable' => false,
                'searchable' => false,
                'footer'    => '<span id="totalPayable"></span>',
            ]),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'courier_repot_' . date('d_m_Y_h_i_s_A');
    }
}
