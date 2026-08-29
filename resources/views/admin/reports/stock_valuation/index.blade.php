@extends('layouts.admin.report_app')

@section('form')
    <input type="hidden" name="print" value="">
    <input type="hidden" name="filter" value="1">
    <div class="row g-3">
        <div class="col-md-4 col-sm-6">
            <label for="store_id" class="form-label"><b>Store</b></label>
            <select name="store_id[]" id="store_id" class="form-select select" data-placeholder="Select Store" multiple>
                <option value=""></option>
                @foreach ($stores as $store)
                    <option value="{{ $store->id }}"
                        {{ is_array($store_id) && in_array($store->id, $store_id) ? 'selected' : '' }}>
                        {{ $store->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4 col-sm-6">
            <label for="category_id" class="form-label"><b>Category</b></label>
            <select name="category_id[]" id="category_id" class="form-select select" data-placeholder="Select Category"
                multiple>
                <option value=""></option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}"
                        {{ is_array($category_id) && in_array($category->id, $category_id) ? 'selected' : '' }}>
                        {{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4 col-sm-6">
            <label for="product_id" class="form-label"><b>Products</b></label>
            <select name="product_id[]" id="product_id" class="form-select select" data-placeholder="Select Products"
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
            <thead>
                <tr>
                    <th class="text-center px-3" width="40px" rowspan="2">SL#</th>
                    <th class="px-3" rowspan="2">Product Name</th>
                    <th class="px-3" rowspan="2">UOM</th>
                    <th class="px-3 text-center" rowspan="2">Stock Qty</th>
                    <th class="px-3 text-center" colspan="2">Purchase value</th>
                    <th class="px-3 text-center" colspan="2">Whole Sales value</th>
                    <th class="px-3 text-center" colspan="2">Retail value</th>
                </tr>
                <tr>
                    <th class="px-3 text-center">Cost</th>
                    <th class="px-3 text-center">Value</th>
                    <th class="px-3 text-center">Rate</th>
                    <th class="px-3 text-center">Value</th>
                    <th class="px-3 text-center">Rate</th>
                    <th class="px-3 text-center">Value</th>
                </tr>
            </thead>
            <tbody>
                @if (count($data) > 0)
                    @php
                        $total_lifting_amount = 0;
                        $total_sales_amount = 0;
                        $total_retail_amount = 0;
                    @endphp
                    @foreach ($data['product_prices'] as $row)
                        @php
                          $lifting_amount = \DB::table('view_liftings')
                                ->where('product_id', $row->product_id)
                                ->sum('amount');

                            $lifting_qty = \DB::table('view_liftings')
                                ->where('product_id', $row->product_id)
                                ->sum('qty');

                            $avarage_lifting_price = $lifting_qty > 0
                                ? $lifting_amount / $lifting_qty
                                : 0;

                            $liftings = $data['liftings']->where('product_id', $row->product_id)->sum('qty');
                            $lifting_returns = $data['lifting_returns']->where('product_id', $row->product_id)->sum('qty');
                            $sales = $data['sales']->where('product_id', $row->product_id)->sum('qty');
                            $sales_returns = $data['sales_returns']->where('product_id', $row->product_id)->sum('qty');
                            $online_sales = $data['online_sales']->where('product_id', $row->product_id)->sum('qty');
                            $transfers = $data['transfer_or_receives']
                                ->whereIn('host_id', $data['store_id'])
                                ->where('product_id', $row->product_id)
                                ->sum('qty');
                            $receives = $data['transfer_or_receives']
                                ->whereIn('destination_id', $data['store_id'])
                                ->where('product_id', $row->product_id)
                                ->sum('qty');
                            $balance_qty = $liftings + $sales_returns + $receives - $lifting_returns - $sales - $transfers - $online_sales;

                            $total_lifting_amount += $balance_qty * $avarage_lifting_price;
                            $total_sales_amount += $balance_qty * $row->sale_price;
                            $total_retail_amount += $balance_qty * $row->online_price;
                        @endphp

                        @if ($balance_qty == 0)
                            @continue
                        @endif
                        <tr>
                            <td class="px-3 text-center">{{ $loop->iteration }}</td>
                            <td class="px-3">{{ $row->name }}</td>
                            <td class="px-3">{{ $row->attribute_name }}</td>
                            <td class="px-3 text-center">{{ number_format($balance_qty, 2, '.', ',') }}</td>
                            <td class="px-3 text-center">
                                {{ number_format($avarage_lifting_price, 2, '.', ',') }}</td>
                            <td class="px-3 text-center">
                                {{ number_format($balance_qty * $avarage_lifting_price, 2, '.', ',') }}
                            </td>
                            <td class="px-3 text-center">{{ number_format($row->sale_price, 2, '.', ',') }}</td>
                            <td class="px-3 text-center">
                                {{ number_format($balance_qty * $row->sale_price, 2, '.', ',') }}
                            </td>
                            <td class="px-3 text-center">{{ number_format($row->online_price, 2, '.', ',') }}</td>
                            <td class="px-3 text-center">
                                {{ number_format($balance_qty * $row->online_price, 2, '.', ',') }}
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
            @if (count($data) > 0)
                <tfoot>
                    <tr class="bg-primary">
                        <th colspan="5" class="text-white text-end">Total Summary</th>
                        <th colspan="1" class="text-white text-center">
                            {{ number_format($total_lifting_amount, 2, '.', '') }}</th>
                        <th colspan="1" class="text-white text-center"></th>
                        <th colspan="1" class="text-white text-center">
                            {{ number_format($total_sales_amount, 2, '.', '') }}
                        </th>
                        <th colspan="1" class="text-white text-center"></th>
                        <th colspan="1" class="text-white text-center">
                            {{ number_format($total_retail_amount, 2, '.', '') }}
                        </th>
                    </tr>
                </tfoot>
            @endif
        </table>
    </div>
@endsection

@push('js')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#dataTable').DataTable({
                "order": false,
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
                let url = "{{ Route('admin.stock-status.index') }}";
                $.ajax({
                    url: url,
                    type: "POST",
                    data: {
                        _method: 'GET',
                        category_id: category_id,
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
                            $('.select').select2();
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
