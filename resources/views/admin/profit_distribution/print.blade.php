@extends('layouts.admin.print_app')
@section('content')
    <div class="content-wrapper">
        <table class="table mb-3 info-table" style="border-bottom: 1px solid #ddd;">
            <tr>
                <td>
                    <b class="d-inline-block" style="min-width: 70px;">Profit No</b><b class="d-inline-block"> : </b>
                    <span class="d-inline-block" style="min-width: 200px;">{{ @$data->serial_no }}</span>
                </td>
                <td class="text-right">
                    <b class="d-inline-block text-left">Date </b> <b class="d-inline-block"> : </b>
                    <span class="d-inline-block"
                        style="min-width: 80px;">{{ date('d-m-Y', strtotime(@$data->date)) }}</span>
                </td>
            </tr>
            <tr>
                <td>
                    <b class="d-inline-block" style="min-width: 70px;">Year</b><b class="d-inline-block"> : </b>
                    <span class="d-inline-block" style="min-width: 200px;">{{ @$data->year }}</span>
                </td>
                <td class="text-right">
                    <b class="d-inline-block text-left">Month </b> <b class="d-inline-block"> : </b>
                    <span class="d-inline-block" style="min-width: 80px;">{{ @$data->month }}</span>
                </td>
            </tr>
        </table>
        <table class="table table-bordered table-condensed table-striped align-middle mb-3">
            <thead>
                <tr class="text-nowrap">
                    <th class="text-center" width="30">SL#</th>
                    <th>Investor Name</th>
                    <th class="text-right">Share Qty</th>
                    <th class="text-right">Share Amount</th>
                    <th class="text-right">Profit Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data->list as $row)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ @$row->investor->name }}</td>
                        <td class="text-right">{{ number_format(@$row->invest_qty) }}</td>
                        <td class="text-right">{{ number_format(@$row->invest_amount) }}</td>
                        <td class="text-right">{{ number_format(@$row->profit_amount) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td class="text-right" colspan="2">Total</td>
                    <td class="text-right">
                        {{ number_format($data->list->sum('invest_qty')) }}
                    </td>
                    <td class="text-right">
                        {{ number_format($data->list->sum('invest_amount')) }}
                    </td>
                    <td class="text-right">
                        {{ number_format($data->list->sum('profit_amount')) }}
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
@endsection
