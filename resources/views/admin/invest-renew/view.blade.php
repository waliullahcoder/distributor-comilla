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
                            <th width="200">Serial No.</th>
                            <th width="10">:</th>
                            <td>{{ @$data->serial_no }}</td>
                        </tr>
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
                            <th width="200">Qty</th>
                            <th width="10">:</th>
                            <td>{{ @$data->list->sum('qty') }}</td>
                        </tr>
                        <tr>
                            <th width="200">Amount</th>
                            <th width="10">:</th>
                            <td>{{ @$data->list->sum('amount') }}</td>
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
                            <th class="text-center">Qty</th>
                            <th class="text-center">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data->list as $row)
                            <tr>
                                <td class="text-center" width="30">{{ $loop->iteration }}</td>
                                <td>{{ $row->investor->name }}</td>
                                <td class="text-center">{{ $row->qty }}</td>
                                <td class="text-center">{{ $row->amount }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
