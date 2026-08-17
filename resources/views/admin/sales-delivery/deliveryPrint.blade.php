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

    {{-- =========================
        CLIENT INFORMATION
    ========================== --}}
    <table class="table mb-0 header-table" style="border: 2px solid black;">
        <tr>
            <td>
                <b class="d-inline-block" style="min-width: 100px;">
                    Invoice No :
                </b>

                <span class="d-inline-block" style="min-width: 200px;">
                    CLIENT-WISE HISTORY
                </span>
            </td>

            <td class="text-right">
                <b class="d-inline-block text-left">
                    Delivery Date :
                </b>

                <span class="d-inline-block" style="min-width: 130px;">
                    {{ date('d-m-Y', strtotime($data->updated_at)) }}
                </span>
            </td>
        </tr>

        <tr>
            <td>
                <b class="d-inline-block" style="min-width: 100px;">
                    Client Name :
                </b>

                <span class="d-inline-block" style="min-width: 200px;">
                    {{ @$data->client->name }}
                </span>
            </td>

            <td class="text-right">
                <b class="d-inline-block text-left">
                    Contact Number :
                </b>

                <span class="d-inline-block" style="min-width: 130px;">
                    {{ @$data->client->phone }}
                </span>
            </td>
        </tr>

        <tr>
            <td>
                <b class="d-inline-block" style="min-width: 100px;">
                    Address :
                </b>

                <span class="d-inline-block" style="min-width: 200px;">
                    {{ @$data->client->address }}
                </span>
            </td>

            <td class="text-right">
                <b class="d-inline-block text-left">
                    Car Number :
                </b>

                <span class="d-inline-block" style="min-width: 130px;">
                    {{ @$data->staff->car_number }}
                </span>
            </td>
        </tr>

        <tr>
            <td>
                <b class="d-inline-block" style="min-width: 100px;">
                    Driver :
                </b>

                <span class="d-inline-block" style="min-width: 200px;">
                    {{ @$data->staff->name }}
                </span>
            </td>

            <td class="text-right">
                <b class="d-inline-block text-left">
                    Driver's Phone :
                </b>

                <span class="d-inline-block" style="min-width: 130px;">
                    {{ @$data->staff->phone }}
                </span>
            </td>
        </tr>

        <tr>
            <td colspan="2">
                <b class="d-inline-block" style="min-width: 100px;">
                    Region :
                </b>

                <span class="d-inline-block" style="min-width: 200px;">
                    {{ @$data->client->area
                        ? $data->client->area->region->name
                        : '-' }},
                        {{ @$data->client->area
                        ? $data->client->area->region->incharge_name
                        : '-' }},
                    {{ @$data->client->area
                        ? $data->client->area->region->phone
                        : '-' }}
                        
                </span>
            </td>

            <!-- <td class="text-right">
                <b class="d-inline-block text-left">
                    Invoice Date :
                </b>

                <span class="d-inline-block" style="min-width: 130px;">
                    {{ date('d-m-Y', strtotime($data->date)) }}
                </span>
            </td> -->
        </tr>
    </table>


    {{-- =========================
        DELIVERY DETAILS
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

                @if($data->product_type != 'Consumer')
                    <th>
                        Variant
                    </th>
                @endif

                <th width="70">
                    Product Code
                </th>
                <th width="70">
                   Invoice No.
                </th>
                <th width="70">
                    Invoice Date
                </th>

                <th width="70">
                    Delivery
                </th>

                <th width="70">
                    Rate
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
                    <td style="width:30%">

                        {{-- Invoice Number --}}
                        <!-- <small>
                            Invoice No :
                            <b>
                                {{ @$item->sales->invoice }} , Invoice Date: {{ @$item->sales->date }}
                            </b>
                        </small>

                        <br> -->

                        {{ @$item->product->name }}

                        <br>


                        {{-- Trade Offer --}}
                        @if(@$item->product->type == 1)

                            <b>Trade Offer:</b>
                            {{ @$item->product->trade_offer }}

                            <br>

                            @php

                                $offerqty = 0;
                                $freeqty = 0;
                                $offer_subtotal = 0;

                                $doRatio = (int) @$item->product->do_ratio;

                                if ($doRatio > 0) {

                                    $freeqty = floor(
                                        @$item->qty / $doRatio
                                    );

                                }

                                $offerqty =
                                    $item->qty - $freeqty;

                                $offer_subtotal =
                                    $offerqty * $item->rate;

                            @endphp


                            Total Order Quantity
                            ({{ @$item->qty }})

                            <br>


                            Total Offer free Quantity
                            ({{ $freeqty }})

                            <br>


                            <b>Do Ratio:</b>
                            {{ @$item->product->do_ratio }}
                            CTN : 1 CTN

                            <br>

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

                        {{ @$item->product->code }}

                    </td>
                    <td>{{ @$item->sales->invoice }}</td>
                    <td>{{ @$item->sales->date }}</td>


                  


                    {{-- DELIVERY --}}
                    <td width="50">

                        {{ @$item->delivery }}

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


                    {{-- AMOUNT --}}
                    <td class="text-right" width="70">

                        {{ number_format(
                            $item->rate * $item->delivery,
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

                <td
                    colspan="{{ $data->product_type == 'Consumer' ? '2' : '3' }}"
                    rowspan="3">

                    <b>In words :</b>

                    {{
                        \App\HelperClass::convertNumber(
                            $data->total_delivery_amount
                            - $total_discount_amount
                        )
                    }}

                    Taka Only

                </td>


                <td class="text-right" colspan="5">

                    <b>
                        Total Delivery Amount :
                    </b>

                </td>


                <td class="text-right" width="70">

                    {{ number_format(
                        $data->total_delivery_amount,
                        2,
                        '.',
                        ','
                    ) }}

                </td>

            </tr>


            @if ($total_discount_amount > 0)

                <tr>

                    <td class="text-right" colspan="5">

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

                <td class="text-right" colspan="5">

                    <b>
                        Net Invoice Amount :
                    </b>

                </td>

                <td class="text-right" width="70">

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


    {{-- =========================
        CLIENT DELIVERY SUMMARY
    ========================== --}}
    <table class="table mb-0 info-table align-middle"
           style="border: 2px solid black;">

        <tbody>

            <tr>

                <td colspan="2" rowspan="3">

                    {{-- TOTAL DELIVERY --}}

                    @php
                        $deliverySummary = $data->list->groupBy('product_id')->map(function ($items) {
                            return [
                                'product_name' => @$items->first()->product->name,
                                'delivery' => $items->sum('delivery'),
                                'pending' => $items->sum(function ($item) {
                                    return $item->qty - $item->delivery;
                                }),
                            ];
                        });
                    @endphp

                    <b>Total Delivery :</b><br>

                    @foreach ($deliverySummary as $item)
                        {{ $item['product_name'] }} : {{ $item['delivery'] }} CTN
                        <br>
                    @endforeach


                    {{-- TOTAL PENDING --}}
                    <b>Total Pending :</b><br>

                    @foreach ($deliverySummary as $item)
                        {{ $item['product_name'] }} : {{ $item['pending'] }} CTN
                        <br>
                    @endforeach

                </td>


                {{-- DELIVERY AMOUNT --}}
                <td class="text-right" colspan="3">

                    <b>
                        Total Delivery Amount :
                    </b>

                </td>

                <td class="text-right" width="70">

                    {{ number_format(
                        $client_total_delivery_amount,
                        2,
                        '.',
                        ','
                    ) }}

                </td>

            </tr>


            {{-- OPENING --}}
            <tr>

                <td class="text-right" colspan="3">

                    <b>
                        Opening Balance :
                    </b>

                </td>

                <td class="text-right" width="70">

                    {{ number_format(
                        $opening,
                        2,
                        '.',
                        ','
                    ) }}

                </td>

            </tr>


            {{-- UPDATE BALANCE --}}
            <tr>

                <td class="text-right" colspan="3">

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

                <td class="text-right" width="70">

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


{{-- =========================
    FOOTER
========================== --}}
<footer class="print-footer">

    <div>

        <table class="table mb-0 border-0">

            <tbody>

                <tr>

                    <td style="border: none;" width="33%">

                        <div class="signature-item">

                            <i class="staff">
                                {{ auth()->user()->name }}
                            </i>

                            <span>
                                Prepared By
                            </span>

                        </div>

                    </td>


                    <td style="border: none;">

                        <div class="signature-item">

                            <i class="staff">
                                {{ @$data->staff->name }}
                            </i>

                            <span>
                                Sales By
                            </span>

                        </div>

                    </td>


                    <td style="border: none;" width="33%">

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