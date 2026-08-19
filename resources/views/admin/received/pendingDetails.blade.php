@extends('layouts.admin.index_app')

@section('content')

    <div class="card">

        {{-- Header --}}
        <div class="card-header bg-primary text-white">

            <div class="d-flex justify-content-between align-items-center">

                <div>
                    <h5 class="mb-0">
                        Vendor Wise Received Pending List
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

                                            <th width="150">
                                                Receive Pending
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




                                                {{-- Pending --}}
                                                <td class="text-center">

                                                    <span class="badge bg-warning text-dark pending-qty">

                                                        {{ $pending }}

                                                    </span>

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


@endsection
