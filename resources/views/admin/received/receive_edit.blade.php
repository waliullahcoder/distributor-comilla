@extends('layouts.admin.index_app')

@section('content')

<form action="{{ route('admin.purchase.receive.update', ['vendorid' => $vendor->id]) }}"
      method="POST">

    @csrf
    @method('PUT')

    <div class="card">

        {{-- Header --}}
        <div class="card-header bg-primary text-white">

            <div class="d-flex justify-content-between align-items-center">

                <div>
                    <h5 class="mb-0">
                        Vendor Wise Receive Edit
                    </h5>

                    <small>
                        Vendor:
                        <b>{{ $vendor->name }}</b>
                    </small>
                </div>

                <div class="d-flex gap-2">

                    <a href="{{ route('admin.received.list') }}"
                       class="btn btn-danger">

                        <i class="far fa-house"></i>
                        Back

                    </a>

                    <button type="submit"
                            class="btn btn-success">

                        <i class="far fa-save"></i>
                        Save Received

                    </button>

                </div>

            </div>

        </div>


        {{-- Body --}}
        <div class="card-body">

            @if($purchases->count() > 0)

                @foreach($purchases as $purchase)

                    @php
                        /*
                         * Controller থেকে receives:
                         * groupBy('lifting_id')
                         *
                         * তাই purchase ID দিয়ে receive list নেওয়া হচ্ছে।
                         */
                        $purchaseReceives = $receives->get(
                            $purchase->id,
                            collect()
                        );
                    @endphp


                    {{-- Purchase Card --}}
                    <div class="card mb-4 border">

                        {{-- Purchase Header --}}
                        <div class="card-header bg-light">

                            <div class="row align-items-center">

                                <div class="col-md-4">

                                    <b>
                                        Vouchar:
                                        {{ $purchase->lifting_no }}
                                    </b>

                                </div>


                                <div class="col-md-4">

                                    <b>
                                        Vouchar Date:
                                        {{ $purchase->lifting_date
                                            ? date('d-m-Y', strtotime($purchase->lifting_date))
                                            : '-' }}
                                    </b>

                                </div>

                            </div>

                        </div>


                        {{-- Product Table --}}
                        <div class="card-body p-0">

                            <div class="table-responsive">

                                <table class="table table-bordered table-striped mb-0 delivery-table">

                                    <thead class="bg-primary text-white">

                                        <tr>

                                            <th width="60">
                                                SL
                                            </th>

                                            <th width="130">
                                                Product Code
                                            </th>

                                            <th>
                                                Product
                                            </th>

                                            <th width="130">
                                                Qty
                                            </th>

                                            <th width="150">
                                                Pending
                                            </th>

                                            <th width="160">
                                                Receive Qty
                                            </th>

                                            <th width="150">
                                                Receive Date
                                            </th>

                                        </tr>

                                    </thead>


                                    <tbody>

                                        @forelse($purchaseReceives as $key => $receive)

                                            @php

                                                $qty = (float) ($receive->qty ?? 0);

                                                $delivery = (float) ($receive->delivery ?? 0);

                                                $pending = $qty - $delivery;

                                                if ($pending < 0) {
                                                    $pending = 0;
                                                }

                                            @endphp


                                            <tr>

                                                {{-- SL --}}
                                                <td class="text-center">

                                                    <b>
                                                        {{ $key + 1 }}
                                                    </b>


                                                    {{-- Hidden Fields --}}

                                                    <input
                                                        type="hidden"
                                                        name="delivery_id[]"
                                                        value="{{ $receive->id }}">


                                                    <input
                                                        type="hidden"
                                                        name="product_id[]"
                                                        value="{{ $receive->product_id }}">


                                                    <input
                                                        type="hidden"
                                                        name="qty[]"
                                                        value="{{ $qty }}">


                                                    <input
                                                        type="hidden"
                                                        name="rate[]"
                                                        value="{{ $receive->lifting_price ?? 0 }}">


                                                    <input
                                                        type="hidden"
                                                        name="lifting_id[]"
                                                        value="{{ $purchase->id }}">

                                                </td>


                                                {{-- Product Code --}}
                                                <td>

                                                    {{ $receive->product_code ?? '-' }}

                                                </td>


                                                {{-- Product --}}
                                                <td>

                                                    <strong>
                                                        {{ $receive->product_name ?? '-' }}
                                                    </strong>

                                                </td>


                                                {{-- Total Qty --}}
                                                <td class="text-center">

                                                    <span class="qty">
                                                        {{ $qty }}
                                                    </span>

                                                </td>


                                                {{-- Pending --}}
                                                <td class="text-center">

                                                    <span class="badge bg-warning text-dark pending-qty">

                                                        {{ $pending }}

                                                    </span>

                                                </td>


                                                {{-- Delivery --}}
                                                <td>
                                                    <input type="hidden" name="prev_receive[]" value="{{$delivery}}">

                                                    <input
                                                        type="number"
                                                        step="any"
                                                        min="0"
                                                        max="{{ $pending }}"
                                                        name="delivery_qty[]"
                                                        class="form-control delivery-qty"
                                                        value=""
                                                        data-pending="{{ $pending }}">

                                                    <small class="text-danger delivery-error d-none">
                                                        Receive cannot be greater than Pending Quantity.
                                                    </small>

                                                </td>


                                                {{-- Delivery Date --}}
                                                <td>

                                                    <input
                                                        type="date"
                                                        name="delivery_date[]"
                                                        class="form-control"
                                                        value="{{ !empty($receive->updated_at)
                                                            ? date('Y-m-d', strtotime($receive->updated_at))
                                                            : date('Y-m-d') }}"
                                                        readonly>

                                                </td>

                                            </tr>


                                        @empty

                                            <tr>

                                                <td colspan="7"
                                                    class="text-center text-muted py-4">

                                                    <i class="far fa-box-open"></i>

                                                    No Pending Receive Found

                                                </td>

                                            </tr>

                                        @endforelse

                                    </tbody>

                                </table>

                            </div>

                        </div>

                    </div>

                @endforeach

            @else

                <div class="alert alert-warning text-center">

                    <i class="far fa-info-circle"></i>

                    No purchase found for this vendor.

                </div>

            @endif

        </div>

    </div>

</form>

@endsection


@push('css')

<style>

    .delivery-table th,
    .delivery-table td {
        vertical-align: middle;
    }

    .delivery-table thead th {
        white-space: nowrap;
    }

    .delivery-qty {
        min-width: 120px;
    }

    .pending-qty {
        font-size: 13px;
        min-width: 60px;
        display: inline-block;
    }

    .delivery-error {
        font-size: 11px;
    }

    @media (max-width: 768px) {

        .card-header .d-flex {
            flex-direction: column;
            gap: 10px;
        }

    }

</style>

@endpush


@push('js')

<script>
$(document).ready(function () {

    $(document).on('input change', '.delivery-qty', function () {

        let input = $(this);

        let receiveQty = parseFloat(input.val()) || 0;

        // Original Pending Qty
        let originalPending = parseFloat(input.data('pending')) || 0;

        let error = input.closest('td').find('.delivery-error');

        // Pending badge
        let pendingBadge = input
            .closest('tr')
            .find('.pending-qty');


        /*
        |--------------------------------------------------------------------------
        | Receive Qty Pending এর বেশি হতে পারবে না
        |--------------------------------------------------------------------------
        */

        if (receiveQty > originalPending) {

            receiveQty = originalPending;

            input.val(originalPending);

            input.addClass('is-invalid');

            error.removeClass('d-none');

        } else {

            input.removeClass('is-invalid');

            error.addClass('d-none');
        }


        /*
        |--------------------------------------------------------------------------
        | Pending Calculation
        | Original Pending - Current Receive Input
        |--------------------------------------------------------------------------
        */

        let remainingPending = originalPending - receiveQty;

        if (remainingPending < 0) {
            remainingPending = 0;
        }

        pendingBadge.text(remainingPending);

    });


    /*
    |--------------------------------------------------------------------------
    | Form Submit Validation
    |--------------------------------------------------------------------------
    */

    $('form').on('submit', function (e) {

        let valid = true;

        $('.delivery-qty').each(function () {

            let input = $(this);

            let receiveQty = parseFloat(input.val()) || 0;

            let originalPending = parseFloat(input.data('pending')) || 0;

            if (receiveQty > originalPending) {

                valid = false;

                input.val(originalPending);

                input.addClass('is-invalid');

                input.closest('td')
                    .find('.delivery-error')
                    .removeClass('d-none');
            }

        });

        if (!valid) {

            e.preventDefault();

            alert('Receive Qty cannot be greater than Pending Quantity.');

            return false;
        }

    });

});
</script>

@endpush