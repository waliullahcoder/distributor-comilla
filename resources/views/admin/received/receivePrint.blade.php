@extends('layouts.admin.invoice_app')

@push('css')
<style>
@page {
    size: A4;
}

.info-table thead {
    background-color: #fff;
    color: #333;
}

.info-table thead th {
    border-bottom: 2px solid #000 !important;
    border-right: 2px dotted #888;
}

.info-table tfoot td,
.info-table tbody td {
    border-top: 2px dotted #888;
    border-right: 2px dotted #888;
}

.staff {
    position: absolute;
    top: -25px;
    left: 50%;
    transform: translateX(-50%);
    white-space: nowrap;
}

.info-table th,
.info-table td,
.header-table td {
    line-height: 1;
    padding: 3px 12px 1px !important;
    font-family: 'PT Serif', serif;
    font-weight: normal;
}

.info-table th,
.info-table td {
    padding: 0px 12px 5px !important;
}

.d-inline-block {
    display: inline-block;
}

.overflow-hidden {
    overflow: hidden;
}

@media print {

    @page {
        size: A4;
        margin: 10mm 8mm 25mm 8mm;
    }

    .print-footer {
        position: relative !important;
        bottom: auto !important;
        left: auto !important;
        width: 100%;
        display: block;
        clear: both;
        margin-top: 30px;

        break-inside: avoid;
        page-break-inside: avoid;
    }

    .print-footer table {
        break-inside: avoid;
        page-break-inside: avoid;
    }

    .signature-item {
        break-inside: avoid;
        page-break-inside: avoid;
    }

    .content-wrapper {
        position: relative !important;
        min-height: auto !important;
        padding-bottom: 0 !important;
    }
}
</style>
@endpush

@section('content')

<div class="content-wrapper">

    {{-- =========================
        VENDOR INFORMATION
    ========================== --}}
    <table class="table mb-0 header-table" style="border: 2px solid black;">

        <tr>

            <td>

                <b class="d-inline-block" style="min-width: 100px;">
                    Voucher No. :
                </b>

                <span class="d-inline-block" style="min-width: 200px;">
                    DELIVERY HISTORY
                </span>

            </td>

            <td class="text-right">

                <b class="d-inline-block text-left">
                    Receive Date :
                </b>

                <span class="d-inline-block" style="min-width: 130px;">
                    {{ date('d-m-Y', strtotime($data->receive_date)) }}
                </span>

            </td>

        </tr>


        <tr>

            <td>

                <b class="d-inline-block" style="min-width: 100px;">
                    Vendor Name :
                </b>

                <span class="d-inline-block" style="min-width: 200px;">
                    {{ @$data->vendor->name }}
                </span>

            </td>

            <td class="text-right">

                <b class="d-inline-block text-left">
                    Contact Number :
                </b>

                <span class="d-inline-block" style="min-width: 130px;">
                    {{ @$data->vendor->phone }}
                </span>

            </td>

        </tr>


        <tr>

            <td>

                <b class="d-inline-block" style="min-width: 100px;">
                    Address :
                </b>

                <span class="d-inline-block" style="min-width: 200px;">
                    {{ @$data->vendor->address }}
                </span>

            </td>

            <td class="text-right">

                <b class="d-inline-block text-left">
                    Email :
                </b>

                <span class="d-inline-block" style="min-width: 130px;">
                    {{ @$data->vendor->email }}
                </span>

            </td>

        </tr>


        <tr>

            <td>

                <b class="d-inline-block" style="min-width: 100px;">
                    Contact Person :
                </b>

                <span class="d-inline-block" style="min-width: 200px;">
                    {{ @$data->vendor->contact_person }}
                </span>

            </td>

            <td class="text-right">

                <b class="d-inline-block text-left">
                    Phone :
                </b>

                <span class="d-inline-block" style="min-width: 130px;">
                    {{ @$data->vendor->phone }}
                </span>

            </td>

        </tr>

    </table>


    {{-- =========================
        RECEIVE DETAILS
    ========================== --}}
    <table class="table info-table align-middle"
           style="border: 2px solid black; margin-top: -2px; margin-bottom: 5px;">

        <thead>

            <tr>

                <th class="text-center" width="40px">
                    SL#
                </th>

                <th>
                    Product Details
                </th>

                <th width="70">
                    Product Code
                </th>

                <th width="70">
                    Voucher No..
                </th>

                <th width="70">
                    Voucher Date
                </th>

                <th width="70">
                    Rate
                </th>

                <th width="70">
                    Receive
                </th>

                <th width="70">
                    Discount
                </th>

                <th width="70" class="text-right">
                    Amount
                </th>

            </tr>

        </thead>


        <tbody>

        @foreach ($data->list as $item)

            <tr>

                {{-- SL --}}
                <td class="text-center" width="40px">
                    {{ $loop->iteration }}
                </td>


                {{-- PRODUCT DETAILS --}}
                <td style="width:30%;">

                    {{ @$item->product->name }}

                    @if(@$item->product->type == 1)

                        <br>

                        <b>Trade Offer:</b>
                        {{ @$item->product->trade_offer }}

                        <br>

                        @php

                            $freeqty = 0;

                            $doRatio = (int) @$item->do_ratio;

                            if ($doRatio > 0) {

                                $freeqty = floor(
                                    $item->qty / $doRatio
                                );

                            }

                            $receiveQty = (float) $item->receive;

                        @endphp

                        Total Order Quantity
                        ({{ $item->qty }})

                        <br>

                        Total Offer Free Quantity
                        ({{ $freeqty }})

                        <br>

                        <b>Do Ratio:</b>
                        {{ @$item->do_ratio }}

                        CTN : 1 CTN

                    @endif

                </td>


                {{-- PRODUCT CODE --}}
                <td width="50">
                    {{ @$item->product->code }}
                </td>


                {{-- Voucher No. --}}
                <td>
                    {{ @$item->liftingreceives->lifting? $item->liftingreceives->lifting->lifting_no : "-"}}
                </td>


                {{-- INVOICE DATE --}}
                <td>
                     {{ @$item->liftingreceives->lifting? $item->liftingreceives->lifting->lifting_date: "-"}}
                </td>


                {{-- RATE --}}
                <td width="50">

                    {{ number_format(
                        $item->rate,
                        2,
                        '.',
                        ','
                    ) }}

                </td>


                {{-- RECEIVE --}}
                <td width="50">

                    {{ number_format(
                        $item->receive,
                        2,
                        '.',
                        ','
                    ) }}

                </td>


                {{-- DISCOUNT --}}
                <td width="50">

                    {{ number_format(
                        $item->trade_discount ?? 0,
                        2,
                        '.',
                        ','
                    ) }}

                </td>


                {{-- AMOUNT --}}
                <td class="text-right" width="70">

                    {{ number_format(
                        $item->receive * $item->rate,
                        2,
                        '.',
                        ','
                    ) }}

                </td>

            </tr>

        @endforeach

        </tbody>


        {{-- =========================
            TOTAL SECTION
        ========================== --}}
        <tfoot>

            <tr>

                <td colspan="4" rowspan="3">

                    <b>In words :</b>

                    {{
                        \App\HelperClass::convertNumber(
                            $vendor_total_receive_amount
                        )
                    }}

                    Taka Only

                </td>


                <td class="text-right" colspan="4">

                    <b>
                        Total Receive Amount :
                    </b>

                </td>


                <td class="text-right" width="70">

                    {{ number_format(
                        $data->total_receive_amount,
                        2,
                        '.',
                        ','
                    ) }}

                </td>

            </tr>


            @if ($total_discount_amount > 0)

                <tr>

                    <td class="text-right" colspan="4">

                        <b>
                            Discount Amount :
                        </b>

                    </td>

                    <td class="text-right" width="70">

                        {{ number_format(
                            $total_discount_amount,
                            2,
                            '.',
                            ','
                        ) }}

                    </td>

                </tr>

            @endif


            <tr>

                <td class="text-right" colspan="4">

                    <b>
                        Net Receive Amount :
                    </b>

                </td>

                <td class="text-right" width="70">

                    {{ number_format(
                        $vendor_total_receive_amount,
                        2,
                        '.',
                        ','
                    ) }}

                </td>

            </tr>

        </tfoot>

    </table>


    {{-- =========================
        VENDOR RECEIVE SUMMARY
    ========================== --}}
    <table class="table mb-0 info-table align-middle"
           style="border: 2px solid black;">

        <tbody>

            <tr>

                <td colspan="2" rowspan="3">

                    {{-- =========================
                        RECEIVE SUMMARY
                    ========================== --}}

                    @php

                        $receiveSummary = $data->list
                            ->groupBy('product_id')
                            ->map(function ($items) {

                                return [
                                    'product_name' =>
                                        @$items->first()->product->name,

                                    'receive' =>
                                        $items->sum('receive'),

                                    'pending' =>
                                        $items->sum(function ($item) {

                                            return
                                                $item->qty -
                                                $item->receive;

                                        }),
                                ];

                            });

                    @endphp


                    <b>Total Receive :</b>
                    <br>

                    @foreach ($receiveSummary as $item)

                        {{ $item['product_name'] }}
                        :
                        {{ number_format($item['receive'], 2) }}
                        CTN

                        <br>

                    @endforeach


                    <b>Total Pending :</b>
                    <br>

                    @foreach ($receiveSummary as $item)

                        {{ $item['product_name'] }}
                        :
                        {{ number_format($item['pending'], 2) }}
                        CTN

                        <br>

                    @endforeach

                </td>


                {{-- NET RECEIVE --}}
                <td class="text-right" colspan="3">

                    <b>
                        Total Receive Amount :
                    </b>

                </td>

                <td class="text-right" width="70">

                    {{ number_format(
                        $vendor_total_receive_amount,
                        2,
                        '.',
                        ','
                    ) }}

                </td>

            </tr>


            {{-- OPENING BALANCE --}}
            <tr>

                <td class="text-right" colspan="3">

                    <b>
                        Opening Balance :
                    </b>

                </td>

                <td class="text-right" width="70">

                    {{ number_format(
                        $openingBalance,
                        2,
                        '.',
                        ','
                    ) }}

                </td>

            </tr>


            {{-- CLOSING BALANCE --}}
            <tr>

                <td class="text-right" colspan="3">

                    <b>
                        Closing Balance :
                    </b>

                </td>

                <td class="text-right" width="70">

                    {{ number_format(
                        $closingBalance,
                        2,
                        '.',
                        ','
                    ) }}

                </td>

            </tr>

        </tbody>

    </table>

</div>


{{-- =========================
    FOOTER
========================== --}}
<footer class="print-footer">

    <div>

        <table class="table mb-0 info-table align-middle">

            <tbody>

                <tr>

                    <td style="border: none;" width="33%">

                        <div class="signature-item">

                            <span>
                                Prepared By
                            </span>

                        </div>

                    </td>


                    <td style="border: none;">

                        <div class="signature-item">

                            <span>
                                Receive By
                            </span>

                        </div>

                    </td>


                    <td style="border: none;" width="33%">

                        <div class="signature-item">

                            <span>
                                Authorized By
                            </span>

                        </div>

                    </td>

                </tr>

            </tbody>

        </table>

    </div>


    @isset($hotline)

        <h4 class="report-title mb-2"
            style="background-color: #ddd;">

            HOTLINE - {{ $hotline }}

        </h4>

    @endisset


    <div>
        Software Designed & Developed by
        Technopark Bangladesh
        (visit : www.technoparkbd.com)
    </div>

</footer>

@endsection