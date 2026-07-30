<?php

namespace App\DataTables;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ProductListDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('vendor_names', function($row){
                return $row->vendors->pluck('vendor.name')->toArray();
            })
            ->addIndexColumn();
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Product $model): QueryBuilder
    {
        $query = $model->with(['company', 'vendors', 'category', 'attribute', 'price']);

        $category_id = request('category_id');
        $vendor_id = request('vendor_id');
        if (!empty($category_id)) {
            $query->where('category_id', $category_id);
        }
        if (!empty($vendor_id)) {
            $query->whereHas('vendors', function($q) use($vendor_id){
                $q->where('vendor_id', $vendor_id);
            });
        }
        return $query;
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
                'responsive' => true,
                'pageLength' => 20,
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
                'width'     => '60',
                'class'     => 'text-center',
            ]),
            Column::make([
                'data'      => 'vendor_names',
                'name'      => 'vendor_names',
                'title'     => 'Vendor Name',
                'class'     => 'text-nowrap',
            ]),
            Column::make([
                'data'      => 'code',
                'name'      => 'code',
                'title'     => 'Product Code',
                'class'     => 'text-nowrap',
            ]),
            Column::make([
                'data'      => 'name',
                'name'      => 'name',
                'title'     => 'Product Name',
                'class'     => 'text-nowrap',
            ]),
            Column::make([
                'data'      => 'category.name',
                'name'      => 'category.name',
                'title'     => 'Product Category',
                'class'     => 'text-nowrap',
            ]),
            Column::make([
                'data'      => 'attribute.name',
                'name'      => 'attribute.name',
                'title'     => 'UOM',
                'class'     => 'text-nowrap',
            ]),
            Column::make([
                'data'      => 'price.sale_price',
                'name'      => 'price.sale_price',
                'title'     => 'Product Price',
                'class'     => 'text-nowrap',
            ]),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'product_list_' . date('d_m_Y_h_i_s_A');
    }
}
