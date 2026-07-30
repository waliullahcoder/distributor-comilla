@extends('layouts.admin.app')
@section('content')
    @php
        $currentRouteName = \Request::route()->getName();
        $link = Route($currentRouteName);
    @endphp
    @if (Auth::user()->hasRole('Moderator'))
        <div class="row g-4">
            <div class="col-md-4">
                <div class="info-box bg-info">
                    <div class="info-area">
                        <span class="box-amount">
                            <span style="min-width: 50px;" class="d-inline-block">{{ number_format($totalClients) }}</span>
                            <span class="fs-12"> (Total Clients)</span>
                            <br>
                            <hr class="m-0">
                            <span style="min-width: 50px;"
                                class="d-inline-block">{{ number_format($newClientsThisMonth) }}</span> <span
                                class="fs-12"> (New Clients)</span>
                            <br>
                            <hr class="m-0">
                            <span style="min-width: 50px;"
                                class="d-inline-block">{{ number_format($prevMonthClients) }}</span> <span class="fs-12">
                                (Last Month Clients)</span>
                            <hr class="m-0">
                        </span>
                        <span class="box-text mt-4">Client Details</span>
                    </div>
                    <div class="icon-area fs-28"><i class="fad fa-user-tie"></i></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="info-box bg-success">
                    <div class="info-area">
                        <span class="box-amount">
                            <span style="min-width: 50px;" class="d-inline-block">{{ number_format($totalOrders) }}</span>
                            <span class="fs-12"> (Total Orders)</span>
                            <br>
                            <hr class="m-0">
                            <span style="min-width: 50px;"
                                class="d-inline-block">{{ number_format($thisMonthOrders) }}</span> <span class="fs-12">
                                (This Month Orders)</span>
                            <br>
                            <hr class="m-0">
                            <span style="min-width: 50px;"
                                class="d-inline-block">{{ number_format($prevMonthOrders) }}</span> <span class="fs-12">
                                (Last Month Orders)</span>
                            <hr class="m-0">
                        </span>
                        <span class="box-text mt-4">Order Details</span>
                    </div>
                    <div class="icon-area"><i class="fal fa-box-check"></i></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="info-box bg-danger">
                    <div class="info-area">
                        <span class="box-amount">
                            <span style="min-width: 80px;" class="d-inline-block">{{ number_format($totalSales) }}</span>
                            <span class="fs-12"> (Total Sales)</span>
                            <br>
                            <hr class="m-0">
                            <span style="min-width: 80px;"
                                class="d-inline-block">{{ number_format($thisMonthSales) }}</span> <span class="fs-12">
                                (This Month Sales)</span>
                            <br>
                            <hr class="m-0">
                            <span style="min-width: 80px;"
                                class="d-inline-block">{{ number_format($prevMonthSales) }}</span> <span class="fs-12">
                                (Last Month Sales)</span>
                            <hr class="m-0">
                        </span>
                        <span class="box-text mt-4">Sales Details</span>
                    </div>
                    <div class="icon-area fs-27"><i class="fas fa-sack-dollar"></i></div>
                </div>
            </div>
            <div class="col-12">
                <div class="card" style="min-height: 610px;">
                    <div class="card-body p-2">
                        <table class="dataTable table align-middle" style="width:100%">
                            <thead>
                                <tr class="text-nowrap">
                                    <th width="3">SL#</th>
                                    <th>Order Date</th>
                                    <th>Target Delivery</th>
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th width="150">Address</th>
                                    <th>Amount</th>
                                    <th width="110" class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="row g-4">
            <div class="col-xl-8">
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="info-box bg-info">
                            <div class="info-area">
                                <span class="box-amount">
                                    <span style="min-width: 50px;"
                                        class="d-inline-block">{{ number_format($totalClients) }}</span> <span
                                        class="fs-12"> (Total Clients)</span>
                                    <br>
                                    <hr class="m-0">
                                    <span style="min-width: 50px;"
                                        class="d-inline-block">{{ number_format($newClientsThisMonth) }}</span> <span
                                        class="fs-12"> (New Clients)</span>
                                    <br>
                                    <hr class="m-0">
                                    <span style="min-width: 50px;"
                                        class="d-inline-block">{{ number_format($prevMonthClients) }}</span> <span
                                        class="fs-12"> (Last Month Clients)</span>
                                    <hr class="m-0">
                                </span>
                                <span class="box-text mt-4">Client Details</span>
                            </div>
                            <div class="icon-area fs-28"><i class="fad fa-user-tie"></i></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-box bg-success">
                            <div class="info-area">
                                <span class="box-amount">
                                    <span style="min-width: 50px;"
                                        class="d-inline-block">{{ number_format($totalOrders) }}</span> <span
                                        class="fs-12"> (Total Orders)</span>
                                    <br>
                                    <hr class="m-0">
                                    <span style="min-width: 50px;"
                                        class="d-inline-block">{{ number_format($thisMonthOrders) }}</span> <span
                                        class="fs-12"> (This Month Orders)</span>
                                    <br>
                                    <hr class="m-0">
                                    <span style="min-width: 50px;"
                                        class="d-inline-block">{{ number_format($prevMonthOrders) }}</span> <span
                                        class="fs-12"> (Last Month Orders)</span>
                                    <hr class="m-0">
                                </span>
                                <span class="box-text mt-4">Order Details</span>
                            </div>
                            <div class="icon-area"><i class="fal fa-box-check"></i></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-box bg-danger">
                            <div class="info-area">
                                <span class="box-amount">
                                    <span style="min-width: 80px;"
                                        class="d-inline-block">{{ number_format($totalSales) }}</span> <span
                                        class="fs-12"> (Total Sales)</span>
                                    <br>
                                    <hr class="m-0">
                                    <span style="min-width: 80px;"
                                        class="d-inline-block">{{ number_format($thisMonthSales) }}</span> <span
                                        class="fs-12"> (This Month Sales)</span>
                                    <br>
                                    <hr class="m-0">
                                    <span style="min-width: 80px;"
                                        class="d-inline-block">{{ number_format($prevMonthSales) }}</span> <span
                                        class="fs-12"> (Last Month Sales)</span>
                                    <hr class="m-0">
                                </span>
                                <span class="box-text mt-4">Sales Details</span>
                            </div>
                            <div class="icon-area fs-27"><i class="fas fa-sack-dollar"></i></div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card" style="min-height: 485px;">
                            <div class="card-body p-2">
                                <table class="dataTable table align-middle" style="width:100%">
                                    <thead>
                                        <tr class="text-nowrap">
                                            <th width="3">SL#</th>
                                            <th>Order Date</th>
                                            <th>Target Delivery</th>
                                            <th>Name</th>
                                            <th>Phone</th>
                                            <th width="150">Address</th>
                                            <th>Amount</th>
                                            <th width="110" class="text-end">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="row g-4">
                    <div class="col-12">
                        <div class="card"
                            style="min-height: {{ !Auth::user()->hasRole('Store Keeper') ? '235px' : '787px' }};">
                            <div class="card-header pe-2">
                                <div class="d-flex justify-content-between align-items-center gap-3">
                                    <div class="flex-grow-1">
                                        <h5 class="h6 mb-0 text-uppercase">Popular Items</h5>
                                    </div>
                                    <div class="flex-shrink-0" style="width: 200px;">
                                        <form action="" method="GET"
                                            class="d-flex gap-2 align-items-center justify-content-sm-end justify-content-center flex-wrap">
                                            <select name="year" class="form-select select-sm"
                                                data-placeholder="Select Year" style="max-width: 90px;">
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
                                            <select name="month" class="form-select select-sm"
                                                data-placeholder="Select Month" style="max-width: 90px;">
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
                                <table class="table align-middle mb-0">
                                    <thead class="bg-primary">
                                        <tr>
                                            <th class="text-center text-white" width="20">SL#</th>
                                            <th class="text-white">Product Name</th>
                                            <th class="text-white text-end">QTY (KG)</th>
                                            <th class="text-white text-end">Amount (TK.)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($ranked_products as $item)
                                            <tr>
                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                <td>
                                                    <a href="{{ Route('admin.product-statement.index') }}?print=&filter=1&store_id=&date_range={{ date('01-m-Y') }}+to+{{ date('t-m-Y') }}&product_id={{ $item->product_id }}"
                                                        target="_blank">{{ $item->product->name }}</a>
                                                </td>
                                                <td class="text-end">{{ $item->total_qty }}</td>
                                                </td>
                                                <td class="text-end">{{ number_format($item->total_amount) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @if (!Auth::user()->hasRole('Store Keeper'))
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header pe-2">
                                    <div class="d-flex justify-content-between align-items-center gap-3">
                                        <div class="flex-grow-1">
                                            <h5 class="h6 mb-0 text-uppercase">Business Summary</h5>
                                        </div>
                                        <div class="flex-shrink-0" style="width: 200px;">
                                            <form action="" method="GET"
                                                class="d-flex gap-2 align-items-center justify-content-sm-end justify-content-center flex-wrap">
                                                <select name="year" class="form-select select-sm"
                                                    data-placeholder="Select Year" style="max-width: 90px;">
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
                                                <select name="month" class="form-select select-sm"
                                                    data-placeholder="Select Month" style="max-width: 90px;">
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
                                                    $managementCost +
                                                    $deliveryCost +
                                                    $profitDistribution +
                                                    $salesCommission;
                                            @endphp
                                            @foreach ($expense_heads as $item)
                                                <tr>
                                                    <th>{{ @$item->head_name }}</th>
                                                    <td class="text-center" width="30">=&gt;</td>
                                                    <td class="text-end">
                                                        @php
                                                            $amount = \App\Models\AccountTransaction::whereBetween('voucher_date', [$start_date, $end_date])
                                                                ->where('coa_setup_id', $item->id)
                                                                ->sum('debit_amount');
                                                            $total_expense += $amount;
                                                        @endphp
                                                        {{ number_format($amount) }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                            <tr>
                                                <th>Operational Cost</th>
                                                <td class="text-center" width="30">=&gt;</td>
                                                <td class="text-end">{{ number_format($operationalCost) }}</td>
                                            </tr>
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
                                                <th>Moderator Commission</th>
                                                <td class="text-center" width="30">=&gt;</td>
                                                <td class="text-end">{{ number_format($moderatorCommission) }}</td>
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
                                                        $total_profit =
                                                            $total_sales - $total_purchases - $total_expense;
                                                    @endphp
                                                    {{ $total_profit >= 0 ? number_format($total_profit) : '(' . number_format(abs($total_profit)) . ')' }}
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif
@endsection

@push('js')
    <script type="text/javascript">
        $(document).ready(function() {
            var table = $('.dataTable').dataTable({
                processing: true,
                serverSide: true,
                scrollX: true,
                ajax: {
                    url: "{{ $link }}",
                    type: "GET",
                    data: function(data) {
                        data.type = $('#filter').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: "text-center",
                        width: '40',
                    },
                    {
                        data: 'order_date',
                        name: 'order_date',
                        orderable: false,
                        searchable: false,
                        className: 'text-nowrap'
                    },
                    {
                        data: 'potential_delivery_date',
                        name: 'potential_delivery_date',
                        orderable: false,
                        searchable: false,
                        className: 'text-nowrap'
                    },
                    {
                        data: 'user_name',
                        name: 'user_name'
                    },
                    {
                        data: 'user_phone',
                        name: 'user_phone'
                    },
                    {
                        data: 'shipping_address',
                        name: 'shipping_address',
                        width: '150',
                    },
                    {
                        data: 'due',
                        name: 'due',
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        className: "text-end",
                    },
                ],
                "fnDrawCallback": function(oSettings) {
                    const tooltips = document.querySelectorAll('.tt');
                    tooltips.forEach(t => {
                        new bootstrap.Tooltip(t);
                    });
                }
            });

            $(document).on('change', '[name="month"],[name="year"]', function(e) {
                $(this).closest('form')[0].submit();
            });
        });
    </script>
@endpush
