@extends('layouts.admin.report_app')

@section('form')
    <input type="hidden" name="print" value="">
    <input type="hidden" name="filter" value="1">
    <div class="row g-3">
        <div class="col-md-3 col-sm-6">
            <label for="date_range" class="form-label"><b>Date</b></label>
            <input type="text" class="form-control date-range" name="date_range" id="date_range"
                placeholder="{{ __('Select Date Range') }}" data-time-picker="true" data-format="DD-MM-Y"
                data-separator=" to " autocomplete="off" required
                value="{{ date('d-m-Y', strtotime($start_date)) . ' to ' . date('d-m-Y', strtotime($end_date)) }}">
        </div>
        <div class="col-md-3 col-sm-6">
            <label for="category_id" class="form-label"><b>Category</b></label>
            <select name="category_id[]" id="category_id" class="select form-select" data-placeholder="Select Category"
                multiple>
                <option value=""></option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}"
                        {{ is_array($category_id) && in_array($category->id, $category_id) ? 'selected' : '' }}>
                        {{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6">
            <label for="product_id" class="form-label"><b>Products</b></label>
            <select name="product_id[]" id="product_id" class="select form-select" data-placeholder="Select Product"
                multiple>
                <option value=""></option>
                @foreach ($products as $product)
                    <option value="{{ $product->id }}"
                        {{ is_array($product_id) && in_array($product->id, $product_id) ? 'selected' : '' }}>
                        {{ $product->name }} ({{ $product->code }})</option>
                @endforeach
            </select>
        </div>
    </div>
@endsection

@section('content')
    <div class="table-responsive">
        <table id="dataTable" class="table table-bordered table-sm">
            <thead class="text-nowrap">
                <tr>
                    <th class="px-3 text-center" width="20">SL#</th>
                    <th class="px-3">Category Name</th>
                    <th class="px-3">Product Name</th>
                    <th class="px-3 text-center">Qty</th>
                    <th class="px-3 text-end">Sales Amount</th>
                    <th class="px-3 text-end">Lifting Amount</th>
                    <th class="px-3 text-end">Profit Amount</th>
                    <th class="px-3 text-center">Profit</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $total_lifting_amount = 0;
                    $total_sales_qty = 0;
                    $total_sales_amount = 0;
                    $total_prfit = 0;
                @endphp
                @if (count($data) > 0)
                    @foreach ($data['searched_products'] as $row)
                        @php
                            $sales_qty =
                                $data['online_sales']
                                    ->where('product_id', $row->product_id)
                                    ->where('date', '>=', $data['start_date'])
                                    ->where('date', '<=', $data['end_date'])
                                    ->sum('qty') +
                                $data['sales']
                                    ->where('product_id', $row->product_id)
                                    ->where('date', '>=', $data['start_date'])
                                    ->where('date', '<=', $data['end_date'])
                                    ->sum('qty') -
                                $data['sales_returns']
                                    ->where('product_id', $row->product_id)
                                    ->where('date', '>=', $data['start_date'])
                                    ->where('date', '<=', $data['end_date'])
                                    ->sum('qty');
                            if ($sales_qty == 0) {
                                continue;
                            }
                            $sales_amount =
                                $data['online_sales']
                                    ->where('product_id', $row->product_id)
                                    ->where('date', '>=', $data['start_date'])
                                    ->where('date', '<=', $data['end_date'])
                                    ->sum('amount') +
                                $data['sales']
                                    ->where('product_id', $row->product_id)
                                    ->where('date', '>=', $data['start_date'])
                                    ->where('date', '<=', $data['end_date'])
                                    ->sum('amount') -
                                $data['sales_returns']
                                    ->where('product_id', $row->product_id)
                                    ->where('date', '>=', $data['start_date'])
                                    ->where('date', '<=', $data['end_date'])
                                    ->sum('amount');

                            $lifting_amount =
                                $data['liftings']->where('product_id', $row->product_id)->sum('amount') -
                                $data['lifting_returns']->where('product_id', $row->product_id)->sum('amount');
                            $lifting_qty =
                                $data['liftings']->where('product_id', $row->product_id)->sum('qty') -
                                $data['lifting_returns']->where('product_id', $row->product_id)->sum('qty');
                            $avarage_rate = $lifting_amount / $lifting_qty;
                            $absolute_lifting = $sales_qty * $avarage_rate;

                            $profit = $sales_amount - $absolute_lifting > 0 ? $sales_amount - $absolute_lifting : 0;
                            if ($absolute_lifting < $sales_amount && $absolute_lifting != 0 && $sales_amount != 0) {
                                $percentage = ($profit / $sales_amount) * 100;
                            } elseif ($absolute_lifting == 0 && $sales_amount != 0) {
                                $percentage = 100;
                            } else {
                                $percentage = 0;
                            }
                            $total_lifting_amount += $absolute_lifting;
                            $total_sales_amount += $sales_amount;
                            $total_sales_qty += $sales_qty;
                            $total_prfit += $profit;
                        @endphp
                        <tr>
                            <td class="px-3 text-center" width="20">{{ $loop->iteration }}</td>
                            <td class="px-3">{{ $row->category_name }}</td>
                            <td class="px-3">{{ $row->name }}</td>
                            <td class="px-3 text-center">{{ number_format($sales_qty, 2, '.', ',') }}</td>
                            <td class="px-3 text-end">{{ number_format($sales_amount, 2, '.', ',') }}</td>
                            <td class="px-3 text-end">{{ number_format($absolute_lifting, 2, '.', ',') }}</td>
                            <td class="px-3 text-end">{{ number_format($profit, 2, '.', ',') }}</td>
                            <td class="px-3 text-center">
                                <div class="progress">
                                    <div class="progress-bar progress-bar-success" role="progressbar"
                                        style="width:{{ round($percentage) }}%; height:5px;"
                                        aria-valuenow="{{ round($percentage) }}" aria-valuemin="0" aria-valuemax="100">
                                    </div>
                                </div>
                                <span class="progress-parcent">{{ number_format($percentage, 2, '.', ',') }}%</span>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
            <tfoot>
                <tr class="bg-primary">
                    <th class="text-white text-end" colspan="3">Total Summary</th>
                    <th class="text-white text-center">{{ number_format($total_sales_qty, 2, '.', ',') }}</th>
                    <th class="text-white text-end">{{ number_format($total_sales_amount, 2, '.', ',') }}</th>
                    <th class="text-white text-end">{{ number_format($total_lifting_amount, 2, '.', ',') }}</th>
                    <th class="text-white text-end">{{ number_format($total_prfit, 2, '.', ',') }}</th>
                    @php
                        $profit =
                            $total_sales_amount - $total_lifting_amount > 0
                                ? $total_sales_amount - $total_lifting_amount
                                : 0;
                        if (
                            $total_lifting_amount < $total_sales_amount &&
                            $total_lifting_amount != 0 &&
                            $total_sales_amount != 0
                        ) {
                            $percentage = ($profit / $total_sales_amount) * 100;
                        } elseif ($total_lifting_amount == 0 && $total_sales_amount != 0) {
                            $percentage = 100;
                        } else {
                            $percentage = 0;
                        }
                    @endphp
                    <th class="text-center">
                        <div class="progress">
                            <div class="progress-bar progress-bar-success" role="progressbar"
                                style="width:{{ round($percentage) }}%; height:5px;"
                                aria-valuenow="{{ round($percentage) }}" aria-valuemin="0" aria-valuemax="100">
                            </div>
                        </div>
                        <span class="progress-parcent text-white">{{ number_format($percentage, 2, '.', ',') }}%</span>
                    </th>
                </tr>
            </tfoot>
        </table>
    </div>
@endsection

@push('js')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#dataTable').DataTable({
                order: false,
                paging: false,
                dom: 'Bfrtip',
                buttons: [
                    'excelHtml5',
                    {
                        'text': '<i class="fal fa-file-pdf"></i> Print',
                        'className': 'getPdf',
                    },
                ]
            });

            $(document).on('change', '#category_id', function() {
                let category_id = $(this).val();
                let url = "{{ Route('admin.product-wise-profit.index') }}";
                $.ajax({
                    url: url,
                    type: "POST",
                    data: {
                        _method: 'GET',
                        category_id: category_id,
                        get_products: true,
                    },
                    success: function(response) {
                        if (response.status == 'success') {
                            $('#product_id option').remove();
                            $('#product_id').append('<option value=""></option>');
                            $.each(response.products, function(key, value) {
                                var html =
                                    `<option value="${value.id}">${value.name} (${value.code})</option>`;
                                $('#product_id').append(html);
                            });
                        }
                    }
                });
            });

            $(document).on('click', '.getPdf', function(e) {
                e.preventDefault();
                $('input[name="print"]').val('true');
                $('.filter_form')[0].setAttribute("target", "_blank");
                $('.filter_form').submit();
            });

            $(document).on('click', '#filter_btn', function(e) {
                e.preventDefault();
                $('input[name="print"]').val('');
                $('.filter_form')[0].setAttribute("target", "_self");
                $('.filter_form').submit();
            });
        });
    </script>
@endpush
