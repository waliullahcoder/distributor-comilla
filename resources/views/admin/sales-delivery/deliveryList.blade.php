@extends('layouts.admin.report_app')

@section('form')
    <div class="row g-3">
        <input type="hidden" name="filter" value="1">
        <div class="col-sm-6">
            <label for="client_id" class="form-label"><b>Client <span class="text-danger">*</span></b></label>
           <select name="client_id" id="client_id" class="form-select select" data-placeholder="Select Client">
                <option value="">All Client</option>
                @foreach ($clients as $client)
                    <option value="{{ $client->id }}"
                        {{ request('client_id') == $client->id ? 'selected' : '' }}>
                        {{ $client->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-sm-6">
            <label for="date_range" class="form-label"><b>Date <span class="text-danger">*</span></b></label>
            <input type="text" class="form-control date-range" name="date_range" id="date_range"
                placeholder="{{ __('Select Date Range') }}" data-time-picker="true" data-format="DD-MM-Y"
                data-separator=" to " autocomplete="off"
                value="{{ !is_null($start_date) && !is_null($end_date) ? date('d-m-Y', strtotime($start_date)) . ' to ' . date('d-m-Y', strtotime($end_date)) : date('01-m-Y') . ' to ' . date('t-m-Y') }}">
        </div>
    </div>
@endsection

@section('content')
    <form action="{{ Route('admin.client.delivery_report.index') }}" id="print-form" method="GET" target="_blank">
        <input type="hidden" name="print" value="true">
        <input type="hidden" name="client_id" class="client_id">
        <input type="hidden" name="date_range" class="date_range">
    </form>
    <div class="table-responsive">
        <table id="dataTable" name="paymentRecordTable" class="table table-bordered table-sm">
            <thead>
                <tr>
                    <th class="text-center" width="20px">Sl#</th>
                    <th width="100px">Date</th>
                    <th width="100px">Client</th>
                    <th class="text-end" width="100px">Sales Qty</th>
                    <th class="text-end" width="100px">Delivery Qty</th>
                    <th class="text-end" width="100px">Pending Qty</th>
                    <th class="text-end" width="100px">Action</th>
                </tr>
            </thead>
           
            @if (count($clients) > 0)
                <tbody>
                    @foreach($data as $row)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td style="width:33%">{{ \Carbon\Carbon::parse($row->sale_date)->format('F j, Y h:i A') }}</td>
                        <td>{{ $row->name }}</td>
                        <td class="text-end">{{ number_format($row->total_qty,2) }}</td>
                        <td class="text-end">{{ number_format($row->total_delivery,2) }}</td>
                        <td class="text-end">{{ number_format(($row->total_qty-$row->total_delivery),2) }}</td>
                        <td style="width:20%">
                            <a class="btn btn-sm btn-primary" href="{{Route('admin.sales.delivery.edit', $row->id)}}" target="_blank"  title="Delivery"><i class="fal fa-edit"></i> Delivery</a>
                            <a class="btn btn-sm btn-warning" href="{{route('admin.print.delivery', $row->id)}}" target="_blank"  title="Delivery"><i class="fal fa-truck"></i> Print</a>
                        </td>
                    </tr>
                    @endforeach
                    </tbody>

                    <tfoot>
                    <tr>
                        <th colspan="3" class="text-end">Grand Total</th>
                        <th class="text-end">{{ number_format($grand_total_qty,2) }}</th>
                        <th class="text-end">{{ number_format($grand_total_delivery,2) }}</th>
                        <th class="text-end">{{ number_format(($grand_total_qty-$grand_total_delivery),2) }}</th>
                        <th class="text-end"></th>
                    </tr>
                    </tfoot>
            @endif
        </table>
    </div>
@endsection

