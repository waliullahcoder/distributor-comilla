@extends('layouts.admin.report_app')

{{-- =========================================================
PRINT CSS
========================================================= --}}

@push('css')

<style>

    @media print {

        /* Hide everything */
        body * {
            visibility: hidden !important;
        }

        /* Show only statement */
        #printArea,
        #printArea * {
            visibility: visible !important;
        }

        #printArea {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            margin: 0;
            padding: 0;
        }

        /* Remove responsive scrolling on print */
        #printArea .table-responsive {
            overflow: visible !important;
            width: 100% !important;
        }

        /* Main table */
        #printArea #dataTable {
            width: 100% !important;
            border-collapse: collapse !important;
            font-size: 11px !important;
        }

        #printArea #dataTable th,
        #printArea #dataTable td {
            border: 1px solid #000 !important;
            padding: 4px !important;
        }

        /* Inner product table */
        #printArea #dataTable table {
            width: 100% !important;
            border-collapse: collapse !important;
            margin: 0 !important;
        }

        #printArea #dataTable table th,
        #printArea #dataTable table td {
            border: 1px solid #000 !important;
            padding: 3px !important;
            font-size: 10px !important;
        }

        /* Don't split invoice row if possible */
        #printArea #dataTable tbody tr {
            page-break-inside: avoid;
        }

        /* Repeat header on every page */
        #printArea #dataTable thead {
            display: table-header-group;
        }

        #printArea #dataTable tfoot {
            display: table-footer-group;
        }

        @page {
            size: A4 landscape;
            margin: 10mm;
        }

    }

</style>

@endpush

{{-- =========================================================
FILTER FORM
========================================================= --}}

@section('form')
<div class="row g-3">

    <input type="hidden" name="filter" value="1">


    {{-- Client --}}

    <div class="col-sm-6">

        <label for="client_id" class="form-label">
            <b>Client</b>
        </label>

        <select name="client_id"
                id="client_id"
                class="form-select select"
                data-placeholder="Select Client">

            <option value="">
                All Client
            </option>

            @foreach ($clients as $client)

                <option value="{{ $client->id }}"
                    {{ request('client_id') == $client->id ? 'selected' : '' }}>

                    {{ $client->name }}

                </option>

            @endforeach

        </select>

    </div>


    {{-- Date --}}

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

{{-- =========================================================
CONTENT
========================================================= --}}

@section('content')


{{-- =====================================================
    ACTION BUTTONS
====================================================== --}}

<div class="d-flex justify-content-end gap-2 mb-3 no-print">

    {{-- Export --}}

    <button type="button"
            class="btn btn-success btn-sm"
            id="exportBtn">

        <i class="fa fa-file-excel"></i>

        Export

    </button>


    {{-- Print --}}

    <!-- <button type="button"
            class="btn btn-primary btn-sm"
            id="printBtn">

        <i class="fa fa-print"></i>

        Print

    </button> -->

</div>



{{-- =====================================================
    PRINT AREA
====================================================== --}}

<div id="printArea">

    <div class="table-responsive">

        <table id="dataTable"
               class="table table-bordered table-sm">

            {{-- =================================================
                TABLE HEADER
            ================================================== --}}

            <thead>

                <tr>

                    <th class="text-center"
                        width="40px">

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


                    <th class="text-end"
                        width="130px">

                        Invoice Amount

                    </th>


                    <th class="text-end"
                        width="130px">

                        Delivery Amount

                    </th>

                </tr>

            </thead>



            {{-- =================================================
                TABLE BODY
            ================================================== --}}

            <tbody>

                @forelse($invoices as $invoice)

                    <tr>

                        {{-- SL --}}

                        <td class="text-center align-top">

                            {{ $loop->iteration }}

                        </td>



                        {{-- Delivery Date --}}

                        <td class="align-top">

                            {{ $invoice->delivery_date

                                ? \Carbon\Carbon::parse(
                                    $invoice->delivery_date
                                )->format('F j, Y')

                                : '-'

                            }}

                        </td>



                        {{-- Invoice Date --}}

                        <td class="align-top">

                            {{ $invoice->sales

                                ? \Carbon\Carbon::parse(
                                    $invoice->sales->date
                                )->format('F j, Y')

                                : '-'

                            }}

                        </td>



                        {{-- =================================================
                            INVOICE DETAILS
                        ================================================== --}}

                        <td>

                            {{-- Invoice Number --}}

                            <div class="mb-2">

                                <strong>
                                    Invoice No:
                                </strong>

                                {{ $invoice->sales->invoice ?? '-' }}

                            </div>



                            {{-- Product Details --}}

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

                                    @forelse($invoice->salesdelivery as $item)

                                        <tr>

                                            {{-- Product --}}

                                            <td>

                                                {{ $item->product->name ?? '-' }}

                                            </td>



                                            {{-- Product Qty --}}

                                            <td class="text-end">

                                                {{ number_format(
                                                    $item->qty ?? 0,
                                                    2
                                                ) }}

                                            </td>



                                            {{-- Delivery Qty --}}

                                            <td class="text-end">

                                                {{ number_format(
                                                    $item->delivery ?? 0,
                                                    2
                                                ) }}

                                            </td>



                                            {{-- Delivery Amount --}}

                                            <td class="text-end">

                                                {{ number_format(
                                                    $item->delivery_amount ?? 0,
                                                    2
                                                ) }}

                                            </td>

                                        </tr>

                                    @empty

                                        <tr>

                                            <td colspan="4"
                                                class="text-center">

                                                No Product Found

                                            </td>

                                        </tr>

                                    @endforelse

                                </tbody>

                            </table>

                        </td>



                        {{-- =================================================
                            INVOICE AMOUNT
                        ================================================== --}}

                        <td class="text-end align-top">

                            {{ number_format(
                                $invoice->total_paid ?? 0,
                                2
                            ) }}

                        </td>



                        {{-- =================================================
                            DELIVERY AMOUNT
                        ================================================== --}}

                        <td class="text-end align-top">

                            {{ number_format(
                                $invoice->total_delivery_amount ?? 0,
                                2
                            ) }}

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="6"
                            class="text-center text-muted">

                            No data found.

                        </td>

                    </tr>

                @endforelse

            </tbody>



            {{-- =================================================
                GRAND TOTAL
            ================================================== --}}

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

</div>
@endsection

{{-- =========================================================
JAVASCRIPT
========================================================= --}}

@push('js')

<script>

    $(document).ready(function () {


        /*
        |--------------------------------------------------------------------------
        | PRINT ONLY STATEMENT
        |--------------------------------------------------------------------------
        */

        $('#printBtn').on('click', function () {

            window.print();

        });



        /*
        |--------------------------------------------------------------------------
        | EXPORT TO EXCEL
        |--------------------------------------------------------------------------
        */

        $('#exportBtn').on('click', function () {

            let table = document.getElementById('dataTable');

            if (!table) {

                alert('Table not found.');

                return;

            }


            /*
            |--------------------------------------------------------------
            | Clone table
            |--------------------------------------------------------------
            */

            let clone = table.cloneNode(true);


            /*
            |--------------------------------------------------------------
            | Remove unnecessary classes
            |--------------------------------------------------------------
            */

            $(clone)
                .find('.text-end')
                .css('text-align', 'right');

            $(clone)
                .find('.text-center')
                .css('text-align', 'center');


            /*
            |--------------------------------------------------------------
            | Excel HTML
            |--------------------------------------------------------------
            */

            let html = `
                <html>
                <head>

                    <meta charset="UTF-8">

                    <style>

                        table {
                            border-collapse: collapse;
                            width: 100%;
                        }

                        table,
                        th,
                        td {
                            border: 1px solid #000;
                        }

                        th,
                        td {
                            border: 1px solid #000;
                            padding: 5px;
                        }

                        th {
                            font-weight: bold;
                            background: #eeeeee;
                        }

                        .text-end {
                            text-align: right;
                        }

                        .text-center {
                            text-align: center;
                        }

                    </style>
            </head>

            <body>

                ${clone.outerHTML}

            </body>

            </html>
        `;


        /*
        |--------------------------------------------------------------
        | Create Excel file
        |--------------------------------------------------------------
        */

        let blob = new Blob(
            [html],
            {
                type: 'application/vnd.ms-excel'
            }
        );


        let url = URL.createObjectURL(blob);


        let link = document.createElement('a');

        link.href = url;

        link.download = 'delivery-statement.xls';


        document.body.appendChild(link);

        link.click();

        document.body.removeChild(link);


        URL.revokeObjectURL(url);

    });

});

</script>

@endpush
