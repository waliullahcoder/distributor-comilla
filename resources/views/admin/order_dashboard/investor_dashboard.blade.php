@extends('layouts.admin.app')
@section('content')
    <div class="row g-3">
        <div class="col-xxl-8">
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="custom_info-box bg-primary hover-expand-effect">
                        <div class="info-box__icon">
                            <span class="material-symbols-outlined">credit_score</span>
                        </div>
                        <div class="info-box__content text-white">
                            <div class="text">INVEST AMOUNT</div>
                            <div class="number count-to">{{ number_format($total_invests) }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="custom_info-box bg-orange hover-expand-effect">
                        <div class="info-box__icon">
                            <span class="material-symbols-outlined">crowdsource</span>
                        </div>
                        <div class="info-box__content">
                            <div class="text">PROFIT DUE</div>
                            <div class="number count-to">{{ number_format($total_due) }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="custom_info-box bg-light-green hover-expand-effect">
                        <div class="info-box__icon">
                            <span class="material-symbols-outlined">crowdsource</span>
                        </div>
                        <div class="info-box__content">
                            <div class="text">PROFIT WITHDRAWAL</div>
                            <div class="number count-to">{{ number_format($total_withdraw) }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="chart-box">
                        <div class="px-4 pt-3">
                            <h6 class="m-0 fw-500">Monthly Profit Chart</h6>
                            <div class="fs-12">Last 12 Months Profit</div>
                        </div>
                        <div class="chart-body">
                            <div>
                                <canvas id="bar_chart1" style="height: 400px; display: block; width: 100%;" width="100%"
                                    height="400" class="chartjs-render-monitor"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-4">
            <div class="card">
                <div class="card-header pe-2">
                    <div class="d-flex justify-content-between align-items-center gap-3">
                        <div class="flex-grow-1">
                            <h5 class="h6 mb-0 text-uppercase">Business Summary</h5>
                        </div>
                        <div class="flex-shrink-0" style="width: 200px;">
                            <form action="" method="GET"
                                class="d-flex gap-2 align-items-center justify-content-sm-end justify-content-center flex-wrap">
                                <select name="year" class="form-select select-sm" data-placeholder="Select Year"
                                    style="max-width: 90px;">
                                    <option value="2024"
                                        {{ (is_null(request('year')) && date('Y') == '2024') || request('year') == '2024' ? 'selected' : '' }}>
                                        2024</option>
                                    <option value="2025"
                                        {{ (is_null(request('year')) && date('Y') == '2025') || request('year') == '2025' ? 'selected' : '' }}>
                                        2025</option>
                                    <option value="2026"
                                        {{ (is_null(request('year')) && date('Y') == '2026') || request('year') == '2026' ? 'selected' : '' }}>
                                        2026</option>
                                    <option value="2027"
                                        {{ (is_null(request('year')) && date('Y') == '2027') || request('year') == '2027' ? 'selected' : '' }}>
                                        2027</option>
                                    <option value="2028"
                                        {{ (is_null(request('year')) && date('Y') == '2028') || request('year') == '2028' ? 'selected' : '' }}>
                                        2028</option>
                                    <option value="2029"
                                        {{ (is_null(request('year')) && date('Y') == '2029') || request('year') == '2029' ? 'selected' : '' }}>
                                        2029</option>
                                    <option value="2030"
                                        {{ (is_null(request('year')) && date('Y') == '2030') || request('year') == '2030' ? 'selected' : '' }}>
                                        2030</option>
                                </select>
                                <select name="month" class="form-select select-sm" data-placeholder="Select Month"
                                    style="max-width: 90px;">
                                    @for ($m = 1; $m <= 12; $m++)
                                        <option value="{{ date('F', mktime(0, 0, 0, $m, 1, date('Y'))) }}"
                                            {{ (is_null(request('month')) && date('F') == date('F', mktime(0, 0, 0, $m, 1, date('Y')))) || request('month') == date('F', mktime(0, 0, 0, $m, 1, date('Y'))) ? 'selected' : '' }}>
                                            {{ date('F', mktime(0, 0, 0, $m, 1, date('Y'))) }}</option>
                                    @endfor
                                </select>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-body p-2">
                    <table class="table table-bordered mb-0 table-sm">
                        <thead>
                            <tr class="text-white bg-primary">
                                <th>Name</th>
                                <td></td>
                                <th class="text-end">AMOUNT</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="bg-light">
                                <th>Monthly Total Orders</th>
                                <td class="text-center" width="30">=&gt;</td>
                                <td class="text-end">{{ number_format($total_monthly_orders) }}</td>
                            </tr>
                            <tr class="bg-light">
                                <th>Monthly Total Delivered </th>
                                <td class="text-center" width="30">=&gt;</td>
                                <td class="text-end">{{ number_format($total_monthly_delivered_orders) }}
                                </td>
                            </tr>
                            <tr class="bg-light">
                                <th>Avarage Order Value </th>
                                <td class="text-center" width="30">=&gt;</td>
                                <td class="text-end">{{ number_format($total_monthly_order_amount) }}</td>
                            </tr>
                            <tr class="bg-light">
                                <th>Total Sales</th>
                                <td class="text-center" width="30">=&gt;</td>
                                <td class="text-end">{{ number_format($total_sales) }}</td>
                            </tr>
                            <tr class="bg-light">
                                <th>Total Purchase</th>
                                <td class="text-center" width="30">=&gt;</td>
                                <td class="text-end">{{ number_format($total_purchases) }}</td>
                            </tr>
                            @php
                                $total_expense =
                                    $managementCost + $deliveryCost + $profitDistribution + $salesCommission;
                            @endphp
                            @foreach ($expense_heads as $item)
                                <tr>
                                    <th>{{ @$item->head_name }}</th>
                                    <td class="text-center" width="30">=&gt;</td>
                                    <td class="text-end">
                                        @php
                                            $amount = \App\Models\AccountTransaction::where(
                                                'voucher_date',
                                                '>=',
                                                $start_date,
                                            )
                                                ->where('voucher_date', '<=', $end_date)
                                                ->where('coa_setup_id', $item->id)
                                                ->sum('debit_amount');
                                            $total_expense += $amount;
                                        @endphp
                                        {{ number_format($amount) }}
                                    </td>
                                </tr>
                            @endforeach
                            <tr class="bg-light">
                                <th>Management Cost</th>
                                <td class="text-center" width="30">=&gt;</td>
                                <td class="text-end">{{ number_format($managementCost) }}</td>
                            </tr>
                            <tr class="bg-light">
                                <th>Delivery Cost</th>
                                <td class="text-center" width="30">=&gt;</td>
                                <td class="text-end">{{ number_format($deliveryCost) }}</td>
                            </tr>
                            <tr class="bg-light">
                                <th>Sales Commission</th>
                                <td class="text-center" width="30">=&gt;</td>
                                <td class="text-end">{{ number_format($salesCommission) }}</td>
                            </tr>
                            <tr class="bg-light">
                                <th>Profit Distribution</th>
                                <td class="text-center" width="30">=&gt;</td>
                                <td class="text-end">{{ number_format($profitDistribution) }}</td>
                            </tr>
                            <tr class="bg-light">
                                <th>Total Share</th>
                                <td class="text-center" width="30">=&gt;</td>
                                <td class="text-end">{{ number_format($totalShareQty) }}</td>
                            </tr>
                            <tr class="bg-light">
                                <th>Per Share Profit</th>
                                <td class="text-center" width="30">=&gt;</td>
                                <td class="text-end">{{ number_format($perShareProfit) }}</td>
                            </tr>
                        </tbody>
                        <tfoot class="bg-primary text-white">
                            <tr>
                                <th>Monthly Net Profit</th>
                                <td class="text-center" width="30">=&gt;</td>
                                <td class="text-end">
                                    @php
                                        $total_profit = $total_sales - $total_purchases - $total_expense;
                                    @endphp
                                    {{ $total_profit >= 0 ? number_format($total_profit) : '(' . number_format(abs($total_profit)) . ')' }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.min.js"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            $.ajax({
                type: 'GET',
                url: "{{ request()->fullUrl() }}",
                data: {},
                success: function(response) {
                    if (response.status == 'success') {
                        var barData = {
                            labels: response.monthlyData.months,
                            datasets: [{
                                label: "Monthly Profit",
                                backgroundColor: '#009432',
                                data: response.monthlyData.profits
                            }]
                        };
                        var barOptions = {
                            responsive: true,
                            maintainAspectRatio: false
                        };
                        var ctx = document.getElementById("bar_chart1").getContext("2d");
                        new Chart(ctx, {
                            type: 'bar',
                            data: barData,
                            options: barOptions
                        });
                    }
                }
            });

            $(document).on('change', '[name="month"],[name="year"]', function(e) {
                $(this).closest('form')[0].submit();
            });
        });
    </script>
@endpush
