@extends('layouts.admin.edit_app')

@section('content')
    <input type="hidden" name="generate" value="1">
    <div class="row g-3">
        <div class="col-lg-3 col-md-4 col-sm-6">
            <label for="date" class="form-label"><b>Date <span class="text-danger">*</span></b></label>
            <input type="text" class="form-control date_picker" id="date" name="date"
                value="{{ date('d-m-Y', strtotime($data->date)) }}" placeholder="Distribute Date" required>
        </div>
        <div class="col-lg-3 col-md-4 col-sm-6">
            <label for="month" class="form-label"><b>Month <span class="text-danger">*</span></b></label>
            <select class="form-select select" name="month" id="month" data-placeholder="Select Month." required>
                @php
                    $months = [
                        'January',
                        'February',
                        'March',
                        'April',
                        'May',
                        'June',
                        'July',
                        'August',
                        'September',
                        'October',
                        'November',
                        'December',
                    ];
                @endphp
                @foreach ($months as $item)
                    <option value="{{ $item }}"
                        {{ (is_null(request('month')) && $data->month == $item) || request('month') == $item ? 'selected' : '' }}>
                        {{ $item }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-lg-3 col-md-4 col-sm-6">
            <label for="year" class="form-label"><b>Year <span class="text-danger">*</span></b></label>
            <select class="form-select select" name="year" id="year" data-placeholder="Select Year." required>
                @for ($i = 2015; $i <= 2055; $i++)
                    <option value="{{ $i }}"
                        {{ (is_null(request('year')) && $data->year == $i) || request('year') == $i ? 'selected' : '' }}>
                        {{ $i }}</option>
                @endfor
            </select>
        </div>
        <div class="col-lg-3 col-md-4 col-sm-6">
            <label for="serial_no" class="form-label"><b>Serial No <span class="text-danger">*</span></b></label>
            <input type="text" class="form-control" id="serial_no" name="serial_no" value="{{ $data->serial_no }}"
                placeholder="Serial No." readonly required>
        </div>
        <div class="col-lg-3 col-md-4 col-sm-6">
            <label for="sales_amount" class="form-label"><b>Total Sales</b></label>
            <input type="text" class="form-control" id="sales_amount" name="sales_amount"
                value="{{ @$generatedData['sales_amount'] ?? @$data->sales_amount }}" readonly>
        </div>
        <div class="col-lg-3 col-md-4 col-sm-6">
            <label for="purchase_amount" class="form-label"><b>Total Purchase</b></label>
            <input type="text" class="form-control" id="purchase_amount" name="purchase_amount"
                value="{{ @$generatedData['purchase_amount'] ?? @$data->purchase_amount }}" readonly>
        </div>
        <div class="col-lg-3 col-md-4 col-sm-6">
            <label for="monthly_cost" class="form-label"><b>Monthly Cost</b></label>
            <input type="text" class="form-control" id="monthly_cost" name="monthly_cost"
                value="{{ @$generatedData['monthly_cost'] ?? @$data->monthly_cost }}" readonly>
        </div>
        <div class="col-lg-3 col-md-4 col-sm-6">
            <label for="management_cost" class="form-label"><b>Management Cost</b></label>
            <input type="text" class="form-control" id="management_cost" name="management_cost"
                value="{{ @$generatedData['management_cost'] ?? @$data->management_cost }}" readonly>
        </div>
        <div class="col-lg-3 col-md-4 col-sm-6">
            <label for="delivery_cost" class="form-label"><b>Delivery Cost</b></label>
            <input type="text" class="form-control" id="delivery_cost" name="delivery_cost"
                value="{{ @$generatedData['delivery_cost'] ?? @$data->delivery_cost }}" readonly>
        </div>
        <div class="col-lg-3 col-md-4 col-sm-6">
            <label for="sales_commission" class="form-label"><b>Sales Commission</b></label>
            <input type="text" class="form-control" id="sales_commission" name="sales_commission"
                value="{{ @$generatedData['sales_commission'] ?? @$data->sales_commission }}" readonly>
        </div>
        <div class="col-lg-3 col-md-4 col-sm-6">
            <label for="investor_profit" class="form-label"><b>Investor Profit</b></label>
            <input type="text" class="form-control" id="investor_profit" name="investor_profit"
                value="{{ @$generatedData['investor_profit'] ?? @$data->investor_profit }}" readonly>
        </div>
        <div class="col-lg-3 col-md-4 col-sm-6">
            <label for="net_profit" class="form-label"><b>Net Profit</b></label>
            <input type="text" class="form-control" id="net_profit" name="net_profit"
                value="{{ @$generatedData['net_profit'] ?? @$data->net_profit }}" readonly>
        </div>
        <div class="col-12 text-end">
            <button type="button" id="search" class="btn btn-outline-primary"><i class="fa fa-search"></i>
                Generate</button>
        </div>
        <div class="col-12">
            <div class="table-responsive" id="table">
                <table class="table mb-0">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th class="text-center" width="30">SL#</th>
                            <th>Investor</th>
                            <th class="text-center" width="150">Invest Qty</th>
                            <th class="text-center" width="150">Invest Amount</th>
                            <th class="text-center" width="150">Profit Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $key = 1;
                            $total_invest_qty = 0;
                            $total_invest_amount = 0;
                            $total_investor_profit_amount = 0;
                        @endphp
                        @if (isset($generatedData['investors']))
                            @foreach ($generatedData['investors'] as $row)
                                @php
                                    $invest_qty = $generatedData['all_renews']
                                        ->where('investor_id', $row->id)
                                        ->sum('qty');
                                    $invest_amount = $generatedData['all_renews']
                                        ->where('investor_id', $row->id)
                                        ->sum('amount');
                                    $total_invest_qty += $invest_qty;
                                    $total_invest_amount += $invest_amount;
                                    $profit_amount = $generatedData['perShareProfit'] * $invest_qty;
                                    $total_investor_profit_amount += $profit_amount;
                                @endphp
                                <tr>
                                    <td class="text-center" width="30">{{ $key++ }}</td>
                                    <td class="text-nowrap">{{ $row->name }}</td>
                                    <td class="text-center">
                                        <input type="hidden" name="investor_id[]" value="{{ $row->id }}">
                                        <input type="number" class="form-control input-sm mx-auto text-center"
                                            style="min-height: auto; width: 150px;" id="qty_{{ $row->id }}"
                                            name="qty[{{ $row->id }}]" placeholder="Quantity"
                                            value="{{ $invest_qty }}" readonly>
                                    </td>
                                    <td class="text-center">
                                        <input type="text" class="form-control input-sm mx-auto text-center"
                                            style="min-height: auto; width: 150px;" id="amount_{{ $row->id }}"
                                            name="amount[{{ $row->id }}]" placeholder="Amount"
                                            value="{{ $invest_amount }}" readonly>
                                    </td>
                                    <td class="text-center">
                                        <input type="text" class="form-control input-sm mx-auto text-center"
                                            style="min-height: auto; width: 150px;"
                                            id="profit_amount_{{ $row->id }}"
                                            name="profit_amount[{{ $row->id }}]" placeholder="Profit Amount"
                                            value="{{ $profit_amount }}" readonly>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                    <tfoot class="bg-primary text-white">
                        <tr>
                            <th class="text-end" colspan="2">Total</th>
                            <th class="text-center">
                                <input type="text" class="form-control input-sm mx-auto text-center"
                                    style="min-height: auto; width: 150px;" value="{{ $total_invest_qty }}"
                                    placeholder="Total Invest Qty" readonly>
                            </th>
                            <th class="text-center">
                                <input type="text" class="form-control input-sm mx-auto text-center"
                                    style="min-height: auto; width: 150px;" value="{{ $total_invest_amount }}"
                                    placeholder="Total Invest Amount" readonly>
                            </th>
                            <th class="text-center">
                                <input type="text" class="form-control input-sm mx-auto text-center"
                                    style="min-height: auto; width: 150px;" value="{{ $total_investor_profit_amount }}"
                                    placeholder="Net Investor Profit" readonly>
                            </th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script type="text/javascript">
        $(document).ready(function() {
            $(".date_picker").datepicker({
                format: 'dd-mm-yyyy',
                changeMonth: true,
                changeYear: true,
            });

            $(document).on('click', '#search', function(e) {
                e.preventDefault();
                $('#update_form').attr('method', 'GET');
                $("[name='_method']").val('GET');
                $('#update_form')[0].submit();
            });

            $(document).on('submit', '#update_form', function(e) {
                $('#update_form').attr('method', 'POST');
                $("[name='_method']").val('PUT');
            });
        });
    </script>
@endpush
