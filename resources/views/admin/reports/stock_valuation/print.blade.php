@extends('layouts.admin.print_app')

@section('content')
    <table class="table table-bordered table-condensed table-striped align-middle mb-3">
        <thead>
            <tr>
                <th width="20px" rowspan="2">SL#</th>
                <th rowspan="2">Product Name</th>
                <th rowspan="2">UOM</th>
                <th rowspan="2" class="text-right">Stock Qty</th>
                <th colspan="2" class="text-center">Purchase value</th>
                <th colspan="2" class="px-3 text-center">Whole Sales value</th>
                <th colspan="2" class="px-3 text-center">Retail value</th>
            </tr>
            <tr>
                <th class="text-right">Cost</th>
                <th class="text-right">Value</th>
                <th class="text-right">Rate</th>
                <th class="text-right">Value</th>
                <th class="text-right">Rate</th>
                <th class="text-right">Value</th>
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
                        $lifting_amount = \DB::table('view_liftings')->where('product_id', $row->product_id)->sum('amount');
                        $lifting_qty = \DB::table('view_liftings')->where('product_id', $row->product_id)->sum('qty');
                        $avarage_lifting_price = $lifting_amount / $lifting_qty;

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
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $row->name }}</td>
                        <td>{{ $row->attribute_name }}</td>
                        <td class="text-right">{{ number_format($balance_qty, 2, '.', ',') }}</td>
                        <td class="text-right">
                            {{ number_format($avarage_lifting_price, 2, '.', ',') }}</td>
                        <td class="text-right">
                            {{ number_format($balance_qty * $avarage_lifting_price, 2, '.', ',') }}
                        </td>
                        <td class="text-right">{{ number_format($row->sale_price, 2, '.', ',') }}</td>
                        <td class="text-right">
                            {{ number_format($balance_qty * $row->sale_price, 2, '.', ',') }}
                        </td>
                        <td class="px-3 text-right">{{ number_format($row->online_price, 2, '.', ',') }}</td>
                        <td class="px-3 text-right">
                            {{ number_format($balance_qty * $row->online_price, 2, '.', ',') }}
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
        @if (count($data) > 0)
            <tfoot>
                <tr>
                    <th colspan="5" class="text-right">Total Summary</th>
                    <th colspan="1" class="text-right">
                        {{ number_format($total_lifting_amount, 2, '.', '') }}</th>
                    <th colspan="1" class="text-right"></th>
                    <th colspan="1" class="text-right">
                        {{ number_format($total_sales_amount, 2, '.', '') }}
                    </th>
                    <th colspan="1" class="text-white text-right"></th>
                    <th colspan="1" class="text-white text-right">
                        {{ number_format($total_retail_amount, 2, '.', '') }}
                    </th>
                </tr>
            </tfoot>
        @endif
    </table>
    <div style="padding-top: 30px;">Print Date & Time : {{ date('d-m-Y h:i:s A') }}</div>
@endsection
