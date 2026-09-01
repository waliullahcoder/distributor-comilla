@extends('layouts.admin.report_app')

@section('form')
<div class="row g-3">

    <input type="hidden" name="filter" value="1">

    <div class="col-sm-6">

        <label for="client_id" class="form-label">
            <b>Client</b>
        </label>

        <select name="client_id"
                id="client_id"
                class="form-select select"
                data-placeholder="Select Client">

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

        <label for="date_range" class="form-label">
            <b>Date</b>
        </label>

        <input type="text"
               class="form-control date-range"
               name="date_range"
               id="date_range"
               placeholder="{{ __('Select Date Range') }}"
               data-time-picker="true"
               data-format="DD-MM-Y"
               data-separator=" to "
               autocomplete="off"
               value="{{ !is_null($start_date) && !is_null($end_date)
                    ? date('d-m-Y', strtotime($start_date)) . ' to ' . date('d-m-Y', strtotime($end_date))
                    : date('01-m-Y') . ' to ' . date('t-m-Y') }}">

    </div>

</div>

@endsection

@section('content')
<form action="{{ Route('admin.delivery-statement.index') }}"
      id="print-form"
      method="GET"
      target="_blank">

    <input type="hidden" name="print" value="true">

    <input type="hidden"
           name="client_id"
           class="client_id">

    <input type="hidden"
           name="date_range"
           class="date_range">

</form>


<div class="table-responsive">

    <table id="dataTable"
           class="table table-bordered table-sm">

        <thead>

            <tr>

                <th class="text-center" width="40px">
                    Sl#
                </th>

                <th width="110px">
                    Delivery Date
                </th>

                <th width="110px">
                    Invoice Date
                </th>

                <th>
                    Invoice Details
                </th>

                <th class="text-end" width="130px">
                    Invoice Amount
                </th>

                <th class="text-end" width="130px">
                    Delivery Amount
                </th>

            </tr>

        </thead>


        <tbody>

            @foreach($invoices as $invoice)

                @php
                // dd($invoice);

                @endphp


                <tr>

                    {{-- SL --}}

                    <td class="text-center align-top">
                        {{ $loop->iteration }}
                    </td>


                    {{-- Delivery Date --}}

                    <td class="align-top">

                        {{ $invoice->delivery_date
                            ? \Carbon\Carbon::parse($invoice->delivery_date)->format('F j, Y')
                            : '-' }}

                    </td>


                    {{-- Invoice Date --}}

                    <td class="align-top">

                        {{ $invoice->sales
                            ? \Carbon\Carbon::parse($invoice->sales->date)->format('F j, Y')
                            : '-' }}

                    </td>


                    {{-- Invoice Details --}}

                    <td>

                        <div class="mb-2">

                            <strong>
                                Invoice No:
                            </strong>

                            {{ $invoice->sales->invoice ?? '-' }}

                        </div>


                        <table class="table table-bordered table-sm mb-0">

                            <thead>

                                <tr>

                                    <th>
                                        Product Name
                                    </th>

                                    <th class="text-end">
                                        Product Qty
                                    </th>

                                    <th class="text-end">
                                        Delivery Qty
                                    </th>

                                    <th class="text-end">
                                        Delivery Amount
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                               

                                    @foreach($invoice->salesdelivery as $item)

                                        <tr>

                                            <td>

                                                {{ $item->product->name ?? '-' }}

                                            </td>


                                            <td class="text-end">

                                                {{ number_format(
                                                    $item->qty ?? 0,
                                                    2
                                                ) }}

                                            </td>


                                            <td class="text-end">

                                                {{ number_format(
                                                    $item->delivery ?? 0,
                                                    2
                                                ) }}

                                            </td>


                                            <td class="text-end">

                                                {{ number_format(
                                                    $item->delivery_amount ?? 0,
                                                    2
                                                ) }}

                                            </td>

                                        </tr>

                                    @endforeach

                            </tbody>

                        </table>

                    </td>


                    {{-- Invoice Amount --}}

                    <td class="text-end align-top">

                        {{ number_format(
                            $invoice->total_paid,
                            2
                        ) }}

                    </td>


                    {{-- Delivery Amount --}}

                    <td class="text-end align-top">

                        {{ number_format(
                            $invoice->total_delivery_amount,
                            2
                        ) }}

                    </td>

                </tr>

        

            @endforeach

        </tbody>


        @if($invoices->count() > 0)

            <tfoot>

                <tr>

                    <th colspan="4"
                        class="text-end">

                        Grand Total

                    </th>


                    <th class="text-end">

                        {{ number_format(
                            $grand_total_invoice_amount,
                            2
                        ) }}

                    </th>


                    <th class="text-end">

                        {{ number_format(
                            $grand_total_delivery_amount,
                            2
                        ) }}

                    </th>

                </tr>

            </tfoot>

        @endif

    </table>

</div>
@endsection
