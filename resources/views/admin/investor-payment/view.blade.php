@extends('layouts.admin.app')

@section('content')
    <div class="card">
        <div class="card-header pe-2 py-2">
            <div class="d-flex flex-wrap justify-content-between gap-2 align-items-center">
                <h6 class="h6 mb-0 text-uppercase text-nowrap flex-grow-1">
                    {{ @$title ?? 'Please Set Title' }}</h6>
                <a href="{{ Route('admin.invest-renew.index') }}" class="btn btn-primary btn-sm">Go Back</a>
            </div>
        </div>
        <div class="card-body px-3">
            <div class="table-responsive-sm">
                <table class="table table-borderless table-striped mb-0">
                    <tbody>
                        <tr>
                            <th width="200">Investor</th>
                            <th width="10">:</th>
                            <td>{{ @$data->investor->name }}</td>
                        </tr>
                        <tr>
                            <th width="200">Payment No.</th>
                            <th width="10">:</th>
                            <td>{{ @$data->payment_no }}</td>
                        </tr>
                        <tr>
                            <th width="200">Date</th>
                            <th width="10">:</th>
                            <td>{{ date('d-m-Y', strtotime(@$data->date)) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="table-responsive-sm mt-4">
                <table class="table table-borderless table-striped mb-0">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th class="text-center" width="30">SL#</th>
                            <th>Year</th>
                            <th>Month</th>
                            <th>Week No</th>
                            <th class="text-end">Invest Qty</th>
                            <th class="text-end">Invest Amount</th>
                            <th class="text-end">Profit Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data->list as $row)
                            <tr>
                                <td class="text-center" width="30">{{ $loop->iteration }}</td>
                                <td>{{ $row->year }}</td>
                                <td>{{ $row->month }}</td>
                                <td>{{ $row->week_no }}</td>
                                <td class="text-end">{{ $row->invest_qty }}</td>
                                <td class="text-end">{{ $row->invest_amount }}</td>
                                <td class="text-end">{{ $row->profit_amount }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
