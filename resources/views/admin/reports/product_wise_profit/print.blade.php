@extends('layouts.admin.print_app')
@section('content')
    <table class="table table-bordered table-condensed table-striped align-middle">
        <thead class="text-nowrap">
            <tr>
                <th class="px-3 text-center" width="40px">SL#</th>
                <th class="px-3">Category Name</th>
                <th class="px-3">Product Name</th>
                <th class="px-3 text-right">Qty</th>
                <th class="px-3 text-right">Sales Amount</th>
                <th class="px-3 text-right">Lifting Amount</th>
                <th class="px-3 text-right">Profit Amount</th>
                <th class="px-3 text-right">Profit</th>
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
                        <td class="px-3 text-center" width="40px">{{ $loop->iteration }}</td>
                        <td class="px-3">{{ $row->category_name }}</td>
                        <td class="px-3">{{ $row->name }}</td>
                        <td class="px-3 text-right">{{ number_format($sales_qty, 2, '.', ',') }}</td>
                        <td class="px-3 text-right">{{ number_format($sales_amount, 2, '.', ',') }}</td>
                        <td class="px-3 text-right">{{ number_format($absolute_lifting, 2, '.', ',') }}</td>
                        <td class="px-3 text-right">{{ number_format($profit, 2, '.', ',') }}</td>
                        <td class="px-3 text-right">
                            <span class="progress-parcent">{{ number_format($percentage, 2, '.', ',') }}%</span>
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
        <tfoot>
            <tr>
                <th class="text-right" colspan="3">Total Summary</th>
                <th class="text-right">{{ number_format($total_sales_qty, 2, '.', ',') }}</th>
                <th class="text-right">{{ number_format($total_sales_amount, 2, '.', ',') }}</th>
                <th class="text-right">{{ number_format($total_lifting_amount, 2, '.', ',') }}</th>
                <th class="text-right">{{ number_format($total_prfit, 2, '.', ',') }}</th>
                @php
                    $profit = $total_sales_amount - $total_lifting_amount > 0 ? $total_sales_amount - $total_lifting_amount : 0;
                    if ($total_lifting_amount < $total_sales_amount && $total_lifting_amount != 0 && $total_sales_amount != 0) {
                        $percentage = ($profit / $total_sales_amount) * 100;
                    } elseif ($total_lifting_amount == 0 && $total_sales_amount != 0) {
                        $percentage = 100;
                    } else {
                        $percentage = 0;
                    }
                @endphp
                <th class="text-right text-white">{{ number_format($percentage, 2, '.', ',') }}%</th>
            </tr>
        </tfoot>
    </table>
    <div style="padding-top: 10px;">Print Date & Time : {{ date('d-m-Y h:i:s A') }}</div>
@endsection
