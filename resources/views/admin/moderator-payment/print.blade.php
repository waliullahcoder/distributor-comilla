@extends('layouts.admin.print_app')
@section('content')
    <div class="content-wrapper">
        <table class="table mb-3 info-table" style="border-bottom: 1px solid #ddd;">
            <tr>
                <td>
                    <b class="d-inline-block" style="min-width: 70px;">Serial No</b><b class="d-inline-block"> : </b>
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
                    <th class="text-center" width="20">SL#</th>
                    <th>Moderator</th>
                    <th class="text-right">O. Qty</th>
                    <th class="text-right">O. Commission</th>
                    <th class="text-right">O. Value</th>
                    <th class="text-right">O. Value Comm.</th>
                    <th class="text-right">L. O. Qty</th>
                    <th class="text-right">L. O. Commission</th>
                    <th class="text-right">L. Value</th>
                    <th class="text-right">L. Value Comm.</th>
                    <th class="text-right">Total Comm.</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data->list as $row)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $row->user->name ?? '' }}</td>
                        <td class="text-right">{{ $row->order_qty }}</td>
                        <td class="text-right">{{ $row->qty_commission }}</td>
                        <td class="text-right">{{ $row->order_qty }}</td>
                        <td class="text-right">{{ $row->amount_commission }}</td>
                        <td class="text-right">{{ $row->leader_qty }}</td>
                        <td class="text-right">{{ $row->leader_qty_commission }}</td>
                        <td class="text-right">{{ $row->leader_amount }}</td>
                        <td class="text-right">{{ $row->leader_amount_commission }}</td>
                        <td class="text-right">{{ $row->total_commission }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th class="text-right" colspan="2">Total</th>
                    <th class="text-right">{{ $data->list->sum('order_qty') }}</th>
                    <th class="text-right">{{ $data->list->sum('qty_commission') }}</th>
                    <th class="text-right">{{ $data->list->sum('order_amount') }}</th>
                    <th class="text-right">{{ $data->list->sum('amount_commission') }}</th>
                    <th class="text-right">{{ $data->list->sum('leader_qty') }}</th>
                    <th class="text-right">{{ $data->list->sum('leader_qty_commission') }}</th>
                    <th class="text-right">{{ $data->list->sum('leader_amount') }}</th>
                    <th class="text-right">{{ $data->list->sum('leader_amount_commission') }}</th>
                    <th class="text-right">{{ $data->list->sum('total_commission') }}</th>
                </tr>
            </tfoot>
        </table>
    </div>
@endsection
