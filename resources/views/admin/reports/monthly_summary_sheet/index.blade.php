@extends('layouts.admin.report_app')

@section('content')
    <div class="table-responsive">
        <table id="dataTable" class="table table-bordered table-sm">
            <thead class="text-nowrap">
                <tr>
                    <th class="text-center" width="20">SL#</th>
                    <th>Month</th>
                    <th class="text-end">Total Orders</th>
                    <th class="text-end">Total Delivered</th>
                    <th class="text-end">Avarage Order Value</th>
                    <th class="text-end">Total Sales</th>
                    <th class="text-end">Total Purchases</th>
                    @foreach ($expense_heads as $key => $item)
                        <th class="text-end">{{ $item->head_name }}</th>
                        @php
                            ${'total_' . $key} = 0;
                        @endphp
                    @endforeach
                    <th class="text-end">Management Cost</th>
                    <th class="text-end">Sales Commission</th>
                    <th class="text-end">Delivery Cost</th>
                    <th class="text-end">Profit Distribution</th>
                    <th class="text-end">Net Profit</th>
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
                        <td class="text-end">{{ number_format($row['total_monthly_orders']) }}</td>
                        <td class="text-end">{{ number_format($row['total_monthly_delivered_orders']) }}</td>
                        <td class="text-end">{{ number_format($row['total_monthly_order_amount']) }}</td>
                        <td class="text-end">{{ number_format($row['sales']) }}</td>
                        <td class="text-end">{{ number_format($row['purchases']) }}</td>
                        @foreach ($expense_heads as $key => $item)
                            <td class="text-end">{{ number_format($row[$item->head_name]) }}</td>
                            @php
                                ${'total_' . $key} += $row[$item->head_name];
                            @endphp
                        @endforeach
                        <td class="text-end">{{ number_format($row['management_cost']) }}</td>
                        <td class="text-end">{{ number_format($row['sales_commission']) }}</td>
                        <td class="text-end">{{ number_format($row['delivery_cost']) }}</td>
                        <td class="text-end">{{ number_format($row['profit_distribution']) }}</td>
                        <td class="text-end">
                            {{ $row['net_profit'] >= 0 ? number_format($row['net_profit']) : '(' . number_format(abs($row['net_profit'])) . ')' }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="bg-primary">
                    <th class="text-end text-white" colspan="5">Total Summary</th>
                    <th class="text-end text-white">{{ number_format($total_sales) }}</th>
                    <th class="text-end text-white">{{ number_format($total_purchase) }}</th>
                    @foreach ($expense_heads as $key => $item)
                        <th class="text-end text-white">{{ number_format(${'total_' . $key}) }}</th>
                    @endforeach
                    <th class="text-end text-white">{{ number_format($management_cost) }}</th>
                    <th class="text-end text-white">{{ number_format($sales_commission) }}</th>
                    <th class="text-end text-white">{{ number_format($delivery_cost) }}</th>
                    <th class="text-end text-white">{{ number_format($profit_distribution) }}</th>
                    <th class="text-end text-white">
                        {{ $total_profit >= 0 ? number_format($total_profit) : '(' . number_format(abs($total_profit)) . ')' }}
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
                dom: 'Bfrtip',
                scrollX: true,
                buttons: [
                    'excelHtml5',
                    {
                        'text': '<i class="fal fa-file-pdf"></i> Print',
                        'className': 'getPdf',
                    },
                ]
            });

            $(document).on('click', '.getPdf', function(e) {
                $('.filter_form')[0].submit();
            });
        });
    </script>
@endpush
