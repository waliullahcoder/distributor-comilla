@extends('layouts.admin.app')

@section('content')
    <div class="card">
        <div class="card-header pe-2 py-2">
            <div class="d-flex flex-wrap justify-content-between gap-2 align-items-center">
                <h6 class="h6 mb-0 text-uppercase text-nowrap flex-grow-1">
                    {{ @$title ?? 'Please Set Title' }}</h6>
                <a href="{{ Route(str_replace('show', 'index', \Request::route()->getName())) }}"
                    class="btn btn-primary btn-sm">Go Back</a>
            </div>
        </div>
        <div class="card-body px-3">
            <div class="table-responsive-sm">
                <table class="table table-borderless table-striped mb-0">
                    <tbody>
                        <tr>
                            <th width="200">Date</th>
                            <th width="10">:</th>
                            <td>{{ date('d-m-Y', strtotime(@$data->date)) }}</td>
                        </tr>
                        <tr>
                            <th width="200">Month</th>
                            <th width="10">:</th>
                            <td>{{ @$data->month }}</td>
                        </tr>
                        <tr>
                            <th width="200">Year</th>
                            <th width="10">:</th>
                            <td>{{ @$data->year }}</td>
                        </tr>
                        <tr>
                            <th width="200">Total Sales</th>
                            <th width="10">:</th>
                            <td>{{ @$data->sales_amount }}</td>
                        </tr>
                        <tr>
                            <th width="200">Total Purchase</th>
                            <th width="10">:</th>
                            <td>{{ @$data->purchase_amount }}</td>
                        </tr>
                        <tr>
                            <th width="200">Monthly Cost</th>
                            <th width="10">:</th>
                            <td>{{ @$data->monthly_cost }}</td>
                        </tr>
                        <tr>
                            <th width="200">Management Cost</th>
                            <th width="10">:</th>
                            <td>{{ @$data->management_cost }}</td>
                        </tr>
                        <tr>
                            <th width="200">Delivery Cost</th>
                            <th width="10">:</th>
                            <td>{{ @$data->delivery_cost }}</td>
                        </tr>
                        <tr>
                            <th width="200">Sales Commission</th>
                            <th width="10">:</th>
                            <td>{{ @$data->sales_commission }}</td>
                        </tr>
                        <tr>
                            <th width="200">Investor Profit</th>
                            <th width="10">:</th>
                            <td>{{ @$data->investor_profit }}</td>
                        </tr>
                        <tr>
                            <th width="200">Net Profit</th>
                            <th width="10">:</th>
                            <td>{{ @$data->net_profit }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="table-responsive-sm mt-4">
                <table class="table table-borderless table-striped mb-0">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th class="text-center" width="30">SL#</th>
                            <th>Investor</th>
                            <th class="text-end">Invest Qty</th>
                            <th class="text-end">Invest Amount</th>
                            <th class="text-end">Profit Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data->list as $row)
                            <tr>
                                <td class="text-center" width="30">{{ $loop->iteration }}</td>
                                <td>{{ @$row->investor->name }}</td>
                                <td class="text-end">{{ $row->invest_qty }}</td>
                                <td class="text-end">{{ $row->invest_amount }}</td>
                                <td class="text-end">{{ $row->profit_amount }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-primary text-white">
                        <tr>
                            <th class="text-end" colspan="2">Total</th>
                            <th class="text-end">{{ $data->list->sum('invest_qty') }}</th>
                            <th class="text-end">{{ $data->list->sum('invest_amount') }}</th>
                            <th class="text-end">{{ $data->list->sum('profit_amount') }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection
