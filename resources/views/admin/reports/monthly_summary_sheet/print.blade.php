@extends('layouts.admin.print_app')
@section('content')
    <table class="table table-bordered table-condensed table-striped align-middle">
        <thead class="text-nowrap">
            <tr>
                <th class="text-center" width="20">SL#</th>
                <th>Month</th>
                <th class="text-right">Total Orders</th>
                <th class="text-right">Total Delivered</th>
                <th class="text-right">Avarage Order Value</th>
                <th class="text-right">Total Sales</th>
                <th class="text-right">Total Purchases</th>
                @foreach ($expense_heads as $key => $item)
                    <th class="text-right">{{ $item->head_name }}</th>
                    @php
                        ${'total_' . $key} = 0;
                    @endphp
                @endforeach
                <th class="text-right">Management Cost</th>
                <th class="text-right">Sales Commission</th>
                <th class="text-right">Delivery Cost</th>
                <th class="text-right">Profit Distribution</th>
                <th class="text-right">Net Profit</th>
            </tr>
        </thead>
        <tbody>
            @php
                $total_sales = 0;
                $total_purchase = 0;
                $total_profit = 0;
                $management_cost = 0;
                $sales_commission = 0;
                $delivery_cost = 0;
                $profit_distribution = 0;
            @endphp
            @foreach ($data as $row)
                @php
                    $total_sales += $row['sales'];
                    $total_purchase += $row['purchases'];
                    $total_profit += $row['net_profit'];
                    $management_cost += $row['management_cost'];
                    $sales_commission += $row['sales_commission'];
                    $delivery_cost += $row['delivery_cost'];
                    $profit_distribution += $row['profit_distribution'];
                @endphp
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td class="text-nowrap">{{ $row['date'] }}</td>
                    <td class="text-right">{{ number_format($row['total_monthly_orders']) }}</td>
                    <td class="text-right">{{ number_format($row['total_monthly_delivered_orders']) }}</td>
                    <td class="text-right">{{ number_format($row['total_monthly_order_amount']) }}</td>
                    <td class="text-right">{{ number_format($row['sales']) }}</td>
                    <td class="text-right">{{ number_format($row['purchases']) }}</td>
                    @foreach ($expense_heads as $key => $item)
                        <td class="text-right">{{ number_format($row[$item->head_name]) }}</td>
                        @php
                            ${'total_' . $key} += $row[$item->head_name];
                        @endphp
                    @endforeach
                    <td class="text-right">{{ number_format($row['management_cost']) }}</td>
                    <td class="text-right">{{ number_format($row['sales_commission']) }}</td>
                    <td class="text-right">{{ number_format($row['delivery_cost']) }}</td>
                    <td class="text-right">{{ number_format($row['profit_distribution']) }}</td>
                    <td class="text-right">
                        {{ $row['net_profit'] >= 0 ? number_format($row['net_profit']) : '(' . number_format(abs($row['net_profit'])) . ')' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th class="text-right" colspan="5">Total Summary</th>
                <th class="text-right">{{ number_format($total_sales) }}</th>
                <th class="text-right">{{ number_format($total_purchase) }}</th>
                @foreach ($expense_heads as $key => $item)
                    <th class="text-right">{{ number_format(${'total_' . $key}) }}</th>
                @endforeach
                <th class="text-right">{{ number_format($management_cost) }}</th>
                <th class="text-right">{{ number_format($sales_commission) }}</th>
                <th class="text-right">{{ number_format($delivery_cost) }}</th>
                <th class="text-right">{{ number_format($profit_distribution) }}</th>
                <th class="text-right">
                    {{ $total_profit >= 0 ? number_format($total_profit) : '(' . number_format(abs($total_profit)) . ')' }}
                </th>
            </tr>
        </tfoot>
    </table>

    <div style="padding-top: 10px;">Print Date & Time : {{ date('d-m-Y h:i:s A') }}</div>
@endsection
