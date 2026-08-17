@extends('layouts.admin.invoice_app')

@push('css')

<style>

@page {
    size: a4;
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

</style>

@endpush


@section('content')

<div class="content-wrapper">


    {{-- =========================================================
        VENDOR INFORMATION
    ========================================================== --}}

    <table class="table mb-0 header-table"
           style="border: 2px solid black;">

        <tr>

            <td>

                <b class="d-inline-block"
                   style="min-width: 100px;">

                    Invoice No :

                </b>

                <span class="d-inline-block"
                      style="min-width: 200px;">

                    VENDOR-WISE HISTORY

                </span>

            </td>


            <td class="text-right">

                <b class="d-inline-block text-left">

                    Receive Date :

                </b>

                <span class="d-inline-block"
                      style="min-width: 130px;">

                    {{ !empty($data->updated_at)
                        ? date('d-m-Y', strtotime($data->updated_at))
                        : '-' }}

                </span>

            </td>

        </tr>


        {{-- Vendor Name --}}
        <tr>

            <td>

                <b class="d-inline-block"
                   style="min-width: 100px;">

                    Vendor Name :

                </b>

                <span class="d-inline-block"
                      style="min-width: 200px;">

                    {{ @$data->vendor->name ?? '-' }}

                </span>

            </td>


            <td class="text-right">

                <b class="d-inline-block text-left">

                    Contact Number :

                </b>

                <span class="d-inline-block"
                      style="min-width: 130px;">

                    {{ @$data->vendor->phone ?? '-' }}

                </span>

            </td>

        </tr>


        {{-- Vendor Address --}}
        <tr>

            <td>

                <b class="d-inline-block"
                   style="min-width: 100px;">

                    Address :

                </b>

                <span class="d-inline-block"
                      style="min-width: 200px;">

                    {{ @$data->vendor->address ?? '-' }}

                </span>

            </td>


            <td class="text-right">

                <b class="d-inline-block text-left">

                    Email :

                </b>

                <span class="d-inline-block"
                      style="min-width: 130px;">

                    {{ @$data->vendor->email ?? '-' }}

                </span>

            </td>

        </tr>


        {{-- Contact Person --}}
        <tr>

            <td>

                <b class="d-inline-block"
                   style="min-width: 100px;">

                    Contact Person :

                </b>

                <span class="d-inline-block"
                      style="min-width: 200px;">

                    {{ @$data->vendor->contact_person ?? '-' }}

                </span>

            </td>


            <td class="text-right">

                <b class="d-inline-block text-left">

                    Phone :

                </b>

                <span class="d-inline-block"
                      style="min-width: 130px;">

                    {{ @$data->vendor->phone ?? '-' }}

                </span>

            </td>

        </tr>


        {{-- Vendor Details --}}
        <tr>

            <td colspan="2">

                <b class="d-inline-block"
                   style="min-width: 100px;">

                    Vendor :

                </b>

                <span class="d-inline-block"
                      style="min-width: 200px;">

                    {{ @$data->vendor->name ?? '-' }}

                </span>

            </td>

        </tr>

    </table>



    {{-- =========================================================
        Receive DETAILS
    ========================================================== --}}

    <table class="table info-table align-middle"
           style="
                border: 2px solid black;
                margin-top: -2px;
                margin-bottom: 5px;
           ">

        <thead>

            <tr>

                <th class="text-center"
                    width="40px">

                    SL#

                </th>


                <th>

                    Product Details

                </th>


                @if($data->product_type != 'Consumer')

                    <th>

                        Variant

                    </th>

                @endif


                <th width="70">

                    Product Code

                </th>


                <th width="80">

                    Voucher No.

                </th>


                <th width="80">

                    Purchase Date

                </th>


                <th width="70">

                    Receive

                </th>


                <th width="70">

                    Rate

                </th>


                <th width="80"
                    class="text-right">

                    Amount

                </th>

            </tr>

        </thead>



        <tbody>
            @foreach ($data->list as $item)
            <?php
            
                            $rate= $item->product->price->lifting_price;
            ?>

                <tr>


                    {{-- SL --}}
                    <td class="text-center"
                        width="40px">

                        {{ $loop->iteration }}

                    </td>



                    {{-- PRODUCT DETAILS --}}
                    <td style="width:30%;">

                        <strong>

                            {{ @$item->product->name ?? '-' }}

                        </strong>


                        {{-- Trade Offer --}}
                        @if(@$item->product->type == 1)

                            <br>

                            <b>
                                Trade Offer:
                            </b>

                            {{ @$item->product->trade_offer }}


                            @php

                                $offerqty = 0;

                                $freeqty = 0;

                                $offer_subtotal = 0;

                                $doRatio =
                                    (int) (@$item->product->do_ratio ?? 0);


                                if ($doRatio > 0) {

                                    $freeqty = floor(
                                        ($item->qty ?? 0) / $doRatio
                                    );

                                }


                                $offerqty =
                                    ($item->qty ?? 0) - $freeqty;


                                $offer_subtotal =
                                    $offerqty * ($rate ?? 0);

                            @endphp


                            <br>

                            Total Order Quantity
                            ({{ @$item->qty ?? 0 }})


                            <br>

                            Total Offer Free Quantity
                            ({{ $freeqty }})


                            <br>

                            <b>
                                Do Ratio:
                            </b>

                            {{ @$item->product->do_ratio ?? 0 }}
                            CTN : 1 CTN

                        @endif

                    </td>



                    {{-- VARIANT --}}
                    @if($data->product_type != 'Consumer')

                        <td width="50">

                            {{ @$item->attribute_name ?? '-' }}

                        </td>

                    @endif



                    {{-- PRODUCT CODE --}}
                    <td width="50">

                        {{ @$item->product->code ?? '-' }}

                    </td>



                    {{-- INVOICE --}}
                    <td>

                        {{ @$item->lifting->voucher_no ?? '-' }}

                    </td>



                    {{-- INVOICE DATE --}}
                    <td>

                        {{ !empty($item->lifting->lifting_date)
                            ? date(
                                'd-m-Y',
                                strtotime($item->lifting->lifting_date)
                            )
                            : '-' }}

                    </td>



                    {{-- Receive --}}
                    <td width="50">

                        {{ $item->delivery ?? 0 }}

                    </td>



                    {{-- RATE --}}
                    <td width="50">

                        {{ number_format(
                            $rate ?? 0,
                            2,
                            '.',
                            ','
                        ) }}

                    </td>



                    {{-- AMOUNT --}}
                    <td class="text-right"
                        width="70">

                        {{ number_format(
                            ($rate ?? 0)
                            * ($item->delivery ?? 0),
                            2,
                            '.',
                            ','
                        ) }}

                    </td>

                </tr>

            @endforeach

        </tbody>



        {{-- =====================================================
            TOTAL SECTION
        ====================================================== --}}

        <tfoot>

            <tr>

                <td
                    colspan="{{ $data->product_type == 'Consumer' ? '2' : '3' }}"
                    rowspan="3">

                    <b>
                        In words :
                    </b>

                    {{
                        \App\HelperClass::convertNumber(
                            $data->total_delivery_amount
                            - $total_discount_amount
                        )
                    }}

                    Taka Only

                </td>



                <td class="text-right"
                    colspan="5">

                    <b>
                        Total Receive Amount :
                    </b>

                </td>



                <td class="text-right"
                    width="70">

                    {{ number_format(
                        $data->total_delivery_amount,
                        2,
                        '.',
                        ','
                    ) }}

                </td>

            </tr>



            {{-- DISCOUNT --}}
            @if ($total_discount_amount > 0)

                <tr>

                    <td class="text-right"
                        colspan="5">

                        <b>
                            Discount Amount :
                        </b>

                    </td>


                    <td class="text-right"
                        width="70">

                        {{ number_format(
                            $total_discount_amount,
                            2,
                            '.',
                            ','
                        ) }}

                    </td>

                </tr>

            @endif



            {{-- NET AMOUNT --}}
            <tr>

                <td class="text-right"
                    colspan="5">

                    <b>
                        Net Invoice Amount :
                    </b>

                </td>


                <td class="text-right"
                    width="70">

                    {{ number_format(
                        $data->total_delivery_amount
                        - $total_discount_amount,
                        2,
                        '.',
                        ','
                    ) }}

                </td>

            </tr>

        </tfoot>

    </table>



    {{-- =========================================================
        VENDOR Receive SUMMARY
    ========================================================== --}}

    <table class="table mb-0 info-table align-middle"
           style="border: 2px solid black;">

        <tbody>


            @php

                /*
                |--------------------------------------------------------------------------
                | Product-wise Receive Summary
                |--------------------------------------------------------------------------
                |
                | Same product একাধিক invoice-এ থাকলেও
                | Product একবার দেখাবে।
                |
                */

                $ReceiveSummary = $data->list
                    ->groupBy('product_id')
                    ->map(function ($items) {

                        return [

                            'product_name' =>
                                @$items->first()->product->name
                                ?? '-',

                            'product_code' =>
                                @$items->first()->product->code
                                ?? '-',

                            'Receive' =>
                                $items->sum('delivery'),

                            'pending' =>
                                $items->sum(function ($item) {

                                    return
                                        ($item->qty ?? 0)
                                        -
                                        ($item->delivery ?? 0);

                                }),

                        ];

                    });

            @endphp



            <tr>

                <td colspan="2"
                    rowspan="3">


                    {{-- =========================
                        TOTAL Receive
                    ========================== --}}

                    <b>
                        Total Receive :
                    </b>

                    <br>


                    @foreach ($ReceiveSummary as $item)

                        {{ $item['product_name'] }}
                        :

                        {{ $item['Receive'] }}
                        CTN

                        <br>

                    @endforeach



                    <br>


                    {{-- =========================
                        TOTAL PENDING
                    ========================== --}}

                    <b>
                        Total Pending :
                    </b>

                    <br>


                    @foreach ($ReceiveSummary as $item)

                        {{ $item['product_name'] }}
                        :

                        {{ $item['pending'] }}
                        CTN

                        <br>

                    @endforeach

                </td>



                {{-- TOTAL Receive AMOUNT --}}
                <td class="text-right"
                    colspan="3">

                    <b>
                        Total Receive Amount :
                    </b>

                </td>


                <td class="text-right"
                    width="70">

                    {{ number_format(
                        $vendor_total_delivery_amount,
                        2,
                        '.',
                        ','
                    ) }}

                </td>

            </tr>



            {{-- =================================================
                OPENING BALANCE
            ================================================== --}}

            <tr>

                <td class="text-right"
                    colspan="3">

                    <b>
                        Opening Balance :
                    </b>

                </td>


                <td class="text-right"
                    width="70">

                    {{ number_format(
                        $opening,
                        2,
                        '.',
                        ','
                    ) }}

                </td>

            </tr>



            {{-- =================================================
                UPDATE BALANCE
            ================================================== --}}

            <tr>

                <td class="text-right"
                    colspan="3">

                    <b>
                        Update Balance :
                    </b>

                </td>


                @php

                    $payable =
                        $opening
                        + $data->total_delivery_amount
                        - $total_discount_amount;

                @endphp


                <td class="text-right"
                    width="70">

                    {{ number_format(
                        $payable,
                        2,
                        '.',
                        ','
                    ) }}

                </td>

            </tr>

        </tbody>

    </table>

</div>



{{-- =============================================================
    FOOTER
============================================================= --}}

<footer class="print-footer">


    <div>

        <table class="table mb-0 border-0">

            <tbody>

                <tr>


                    {{-- PREPARED BY --}}
                    <td style="border: none;"
                        width="33%">

                        <div class="signature-item">

                            <i class="staff">

                                {{ auth()->user()->name }}

                            </i>

                            <span>

                                Prepared By

                            </span>

                        </div>

                    </td>



                    {{-- VENDOR / SALES BY --}}
                    <td style="border: none;">

                        <div class="signature-item">

                            <i class="staff">

                                {{ @$data->vendor->contact_person }}

                            </i>

                            <span>

                                Vendor

                            </span>

                        </div>

                    </td>



                    {{-- RECEIVE BY --}}
                    <td style="border: none;"
                        width="33%">

                        <div class="signature-item">

                            <span>

                                Receive By

                            </span>

                        </div>

                    </td>

                </tr>

            </tbody>

        </table>

    </div>



    {{-- HOTLINE --}}
    @isset($hotline)

        <h4 class="report-title mb-2"
            style="background-color: #ddd;">

            HOTLINE - {{ $hotline }}

        </h4>

    @endisset



    <div>

        Software Designed & Developed by
        Technopark Bangladesh
        (visit : wwww.technoparkbd.com)

    </div>

</footer>

@endsection