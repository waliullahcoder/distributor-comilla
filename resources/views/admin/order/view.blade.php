@extends('layouts.admin.app')

@section('content')
    <div class="row g-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header pe-2 py-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="h6 mb-0 text-uppercase py-5px"></h6>
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ Route('admin.online-order.index') }}" class="btn btn-primary btn-sm">Go
                                Back</a>
                        </div>
                    </div>
                </div>
                <div class="card-body" id="section-to-print">
                    <div class="invoice-area">
                        <div class="invoice-head">
                            <div class="row g-3 align-items-center">
                                <div class="col-4">
                                    <table class="table invoice-table table-borderless mb-0">
                                        <tbody>
                                            <tr>
                                                <td class="p-0">
                                                    <img src="{{ file_exists(@$setting->logo) ? asset($setting->logo) : asset('frontend/assets/images/logo/logo.png') }}"
                                                        height="60" alt="Logo">
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-8">
                                    <table class="table invoice-table table-borderless mb-0 text-end">
                                        <tbody>
                                            <tr>
                                                <td class="p-0">{{ !is_null($setting) ? $setting->address : '' }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="p-0"><a
                                                        href="mailto:{{ !is_null($setting) ? $setting->primary_email : '' }}"
                                                        target="_top">{{ !is_null($setting) ? $setting->primary_email : '' }}</a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="p-0"><a
                                                        href="tel:{{ !is_null($setting) ? $setting->primary_mobile : '' }}"
                                                        target="_top">{{ !is_null($setting) ? $setting->primary_mobile : '' }}</a>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="row g-3 grid-col">
                            <div class="col-md-6">
                                <div class="invoice-address">
                                    <h5>SHIPPING INFORMATION:</h5>
                                    <div class="">
                                        <label class="form-label"><b>Name : </b></label>
                                        <span>{{ $order->user_name }}</span>
                                    </div>
                                    <div class="">
                                        <label class="form-label"><b>Phone : </b></label>
                                        <span>{{ $order->user_phone }}
                                            {{ $order->user_phone_alt ? ', ' . $order->user_phone_alt : '' }}</span>
                                    </div>
                                    <div class="">
                                        <label class="form-label"><b>Area : </b></label>
                                        <span>{{ @$order->area->name }}</span>
                                    </div>
                                    <div class="">
                                        <label class="form-label"><b>Address : </b></label>
                                        <span>{{ @$order->address->address ?? @$order->shipping_address }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="invoice-address">
                                    <h5>ORDER INFORMATION</h5>
                                    <div class="">
                                        <label class="form-label"><b>Order Date : </b></label>
                                        <span>{{ $order->created_at->format('d M Y h:i A') }}</span>
                                    </div>
                                    <div class="">
                                        <label class="form-label"><b>Invoice Number : </b></label>
                                        <span>#{{ $order->invoice }}</span>
                                    </div>
                                    <form action="{{ Route('admin.order-dashboard.update', $order->id) }}" method="POST"
                                        id="order_status">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="prev_url"
                                            value="{{ @$previousUrl ?? Route('admin.dashboard') }}">
                                        @if ($order->status == 'Delivered' && !Auth::user()->hasRole('Store Keeper'))
                                            <div class="alert-items d-flex gap-3 align-items-center mb-2">
                                                <label class="form-label flex-shrink-0 mb-0"
                                                    style="width: 110px;"><b>Collection :</b></label>
                                                <select name="collected" class="form-select fs-14 px-2 py-1" id="collected"
                                                    style="width: 165px; min-height: auto;" required>
                                                    <option value="0">No</option>
                                                    <option value="1">Yes</option>
                                                </select>
                                                <button type="submit" class="btn btn-sm btn-primary"
                                                    id="collected__btn">Update Status</button>
                                            </div>
                                            <div class="alert-items d-flex gap-3 align-items-center mb-2">
                                                <label class="form-label flex-shrink-0 mb-0" style="width: 110px;"><b>Date
                                                        :</b></label>
                                                <input type="text" name="collected_at" id="collected_at"
                                                    style="width: 280px; padding-block: 3px; min-height: 32px;"
                                                    class="form-control date_picker" value="{{ date('d-m-Y') }}">
                                            </div>
                                        @endif
                                        <hr>
                                        <div class="alert-items d-flex gap-3 align-items-center mb-2">
                                            <label class="form-label flex-shrink-0 mb-0" style="width: 110px;"><b>Status
                                                    :
                                                </b></label>
                                            <select name="status" class="form-select fs-14 px-2 py-1" id="status"
                                                style="width: 165px; min-height: auto;" required>
                                                @if ($order->status == 'Pending')
                                                    <option value="Pending"
                                                        {{ $order->status == 'Pending' ? 'selected disabled' : '' }}>
                                                        Pending</option>
                                                    <option value="Forward">Forward</option>
                                                @endif
                                                @if ($order->status == 'Forward')
                                                    <option value="Forward"
                                                        {{ $order->status == 'Forward' ? 'selected disabled' : '' }}>
                                                        Forward</option>
                                                    <option value="On Route">On Route</option>
                                                    @if (
                                                        (Auth::user()->hasRole('Software Admin') && $order->status != 'Pending') ||
                                                            (Auth::user()->hasRole('System Admin') && $order->status != 'Pending'))
                                                        <option value="Pending">Pending</option>
                                                    @endif
                                                @endif
                                                @if (!in_array($order->status, ['Pending', 'Returned', 'On Route']))
                                                    <option value="Cancelled"
                                                        {{ $order->status == 'Cancelled' ? 'selected disabled' : '' }}>
                                                        {{ $order->status == 'Cancelled' ? 'Cancelled' : 'Cancel' }}
                                                    </option>
                                                    <option value="Pending">Pending</option>
                                                @endif
                                                @if ($order->status == 'On Route')
                                                    <option value="On Route">On Route</option>
                                                    <option value="Delivered">Delivered</option>
                                                    <option value="Cancelled"
                                                        {{ $order->status == 'Cancelled' ? 'selected disabled' : '' }}>
                                                        {{ $order->status == 'Cancelled' ? 'Cancelled' : 'Cancel' }}
                                                    </option>
                                                    @if (
                                                        (Auth::user()->hasRole('Software Admin') && $order->status != 'Pending') ||
                                                            (Auth::user()->hasRole('System Admin') && $order->status != 'Pending'))
                                                        <option value="Pending">Pending</option>
                                                    @endif
                                                @endif
                                            </select>
                                            <button type="submit" id="status_update_btn"
                                                class="btn btn-sm btn-primary">Update Status</button>
                                        </div>
                                        @if (in_array($order->status, ['Pending', 'Forward']) || Auth::user()->hasRole('Software Admin'))
                                            <div class="alert-items d-flex gap-3 align-items-center mb-2" id="store_area">
                                                <label class="form-label flex-shrink-0 mb-0" style="width: 110px;"><b>Store
                                                        :</b></label>
                                                <select name="store_id" class="form-select fs-14 px-2 py-1" id="store_id"
                                                    style="width: 280px; min-height: auto;">
                                                    <option value="">Select Store</option>
                                                    @foreach ($stores as $store)
                                                        <option value="{{ $store->id }}"
                                                            {{ $order->store_id == $store->id ? 'selected' : '' }}>
                                                            {{ $store->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @endif
                                        @if (in_array($order->status, ['Forward', 'On Route']))
                                            @php
                                                $deliveryAgents = \App\Models\DeliveryAgent::where('status', 1)
                                                    ->orderBy('name', 'asc')
                                                    ->get();
                                            @endphp
                                            <div class="alert-items {{ $order->status == 'Forward' ? 'd-none' : 'd-flex' }} gap-3 align-items-center mb-2"
                                                id="delivery_agent_area">
                                                <label class="form-label flex-shrink-0 mb-0"
                                                    style="width: 110px;"><b>Delivery Agent
                                                        :
                                                    </b></label>
                                                <select name="delivery_agent_id" class="form-select fs-14 px-2 py-1"
                                                    id="delivery_agent_id" style="width: 280px; min-height: auto;"
                                                    required>
                                                    <option value="">Select Delivery Agent</option>
                                                    @foreach ($deliveryAgents as $item)
                                                        <option value="{{ $item->id }}"
                                                            {{ $order->delivery_agent_id == $item->id ? 'selected' : '' }}>
                                                            {{ $item->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="alert-items {{ $order->status == 'Forward' ? 'd-none' : 'd-flex' }} gap-3 align-items-center mb-2"
                                                id="delivery_agent_area_type">
                                                <label class="form-label flex-shrink-0 mb-0" style="width: 110px;">
                                                    <b>Area Type:</b>
                                                </label>
                                                <select name="area_type" id="area_type"
                                                    class="form-select fs-14 px-2 py-1"
                                                    style="width: 280px; min-height: auto;" required>
                                                    <option value=""></option>
                                                    <option value="inside_dhaka"
                                                        {{ $order->area_type == 'inside_dhaka' ? 'selected' : '' }}>Inside
                                                        Dhaka</option>
                                                    <option value="subarea_dhaka"
                                                        {{ $order->area_type == 'subarea_dhaka' ? 'selected' : '' }}>
                                                        Subarea Dhaka</option>
                                                    <option value="inside_chittagong"
                                                        {{ $order->area_type == 'inside_chittagong' ? 'selected' : '' }}>
                                                        Inside Chittagong</option>
                                                    <option value="subarea_chittagong"
                                                        {{ $order->area_type == 'subarea_chittagong' ? 'selected' : '' }}>
                                                        Subarea Chittagong</option>
                                                    <option value="district_level"
                                                        {{ $order->area_type == 'district_level' ? 'selected' : '' }}>
                                                        District Level</option>
                                                </select>
                                            </div>
                                        @endif
                                    </form>
                                    @if ($order->status == 'Confirmed')
                                        <div class="">
                                            <label class="form-label"><b>Confirmed : </b></label>
                                            <span>{{ date('d M Y h:i A', strtotime($order->confirmed_at)) }}</span>
                                        </div>
                                    @elseif ($order->status == 'Processing')
                                        <div class="">
                                            <label class="form-label"><b>Processing : </b></label>
                                            <span>{{ date('d M Y h:i A', strtotime($order->processing_at)) }}</span>
                                        </div>
                                    @elseif ($order->status == 'Delivered')
                                        <div class="">
                                            <label class="form-label"><b>Delivered : </b></label>
                                            <span>{{ date('d M Y h:i A', strtotime($order->delivered_at)) }}</span>
                                        </div>
                                    @elseif ($order->status == 'Successed')
                                        <div class="">
                                            <label class="form-label"><b>Successed : </b></label>
                                            <span>{{ date('d M Y h:i A', strtotime($order->successed_at)) }}</span>
                                        </div>
                                    @elseif ($order->status == 'Canceled')
                                        <div class="">
                                            <label class="form-label"><b>Canceled : </b></label>
                                            <span>{{ date('d M Y h:i A', strtotime($order->canceled_at)) }}</span>
                                        </div>
                                    @endif
                                    <div class="">
                                        <label class="form-label"><b>Note : </b></label>
                                        <span>{{ @$order->order_note }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="invoice-table table-responsive mt-5">
                            <table class="table table-bordered table-hover align-middle table-striped">
                                <thead>
                                    <tr class="text-capitalize">
                                        <th class="text-center" width="40">#SL</th>
                                        <th>Product</th>
                                        <th>Regular Price</th>
                                        <th class="text-center">Discount</th>
                                        <th class="text-center">Qty</th>
                                        <th class="text-center">Sale Price</th>
                                        <th class="text-end">total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($order->products as $key => $product)
                                        <tr>
                                            <td class="text-center">{{ $key + 1 }}</td>
                                            <td>{{ $product->product->name }}</td>
                                            <td>{{ $product->regular_price }}</td>
                                            <td class="text-center">{{ $product->discount }}</td>
                                            <td class="text-center">{{ $product->quantity }}</td>
                                            <td class="text-center">{{ number_format($product->sale_price) }} TK.</td>
                                            <td class="text-end">
                                                {{ number_format($product->sale_price * $product->quantity) }} TK.</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="text-end">
                                    <tr>
                                        <td colspan="6">Sub total :</td>
                                        <td colspan="1">{{ number_format($order->sub_total) }} TK.</td>
                                    </tr>
                                    <tr>
                                        <td colspan="6">Shipping Charge :</td>
                                        <td colspan="1">{{ number_format($order->shipping_charge) }} TK.</td>
                                    </tr>
                                    <tr>
                                        <td colspan="6">Discount :</td>
                                        <td colspan="1">{{ number_format($order->discount) }} TK.</td>
                                    </tr>
                                    <tr>
                                        <td colspan="6">Advance :</td>
                                        <td colspan="1">{{ number_format($order->advance) }} TK.</td>
                                    </tr>
                                    <tr>
                                        <td colspan="6">Total :</td>
                                        <td colspan="1">
                                            {{ number_format($order->total - $order->discount - $order->advance) }} TK.
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="invoice-buttons text-end">
                        <a href="{{ route('admin.online-order.show', $order->id) }}" target="_blank"
                            class="invoice-btn">print
                            invoice</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script type="text/javascript">
        $(document).ready(function() {
            $(document).on('click', '#status_update_btn', function(e) {
                e.preventDefault();
                let delivery_agent_id = $('#delivery_agent_id').val();
                let store_id = $('#store_id').val();
                let status = $('#status').val();
                let area_type = $('#area_type').val();
                if (status == null && store_id == null) {
                    Swal.fire({
                        toast: true,
                        width: "20rem",
                        position: 'top-right',
                        text: "Please select a status!",
                        icon: "error",
                        showConfirmButton: false,
                        timer: 1500,
                        timerProgressBar: true
                    });
                    return;
                }

                if (store_id == '' && status != 'Cancelled') {
                    Swal.fire({
                        toast: true,
                        width: "20rem",
                        position: 'top-right',
                        text: "Please select a store!",
                        icon: "error",
                        showConfirmButton: false,
                        timer: 1500,
                        timerProgressBar: true
                    });
                    return;
                }

                if (delivery_agent_id == '' && status == 'On Route') {
                    Swal.fire({
                        toast: true,
                        width: "20rem",
                        position: 'top-right',
                        text: "Please select a delivery Agent!",
                        icon: "error",
                        showConfirmButton: false,
                        timer: 1500,
                        timerProgressBar: true
                    });
                    return;
                }

                if (area_type == '' && status == 'On Route') {
                    Swal.fire({
                        toast: true,
                        width: "20rem",
                        position: 'top-right',
                        text: "Please select area type!",
                        icon: "error",
                        showConfirmButton: false,
                        timer: 1500,
                        timerProgressBar: true
                    });
                    return;
                }

                Swal.fire({
                    width: "25rem",
                    title: "Are you sure?",
                    text: "You won't be able to revert this!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, Change it!",
                }).then((result) => {
                    if (result.value) {
                        $('#order_status').submit();
                    }
                });
            });

            // $(document).on('click', '#store_id', function(e) {
            //     let store_id = $('#store_id').val();
            //     $('#delivery_agent_id option').remove();
            //     $.ajax({
            //         url: "{{ Route(request()->route()->getName(), $order->id) }}",
            //         type: "POST",
            //         data: {
            //             _method: 'GET',
            //             store_id: store_id,
            //         },
            //         success: (response) => {
            //             if (response.status == 'success') {
            //                 $('#delivery_agent_id').append(
            //                     '<option value="">Select Delivery Agent</option');
            //                 $.each(response.delivery_men, function(key, item) {
            //                     $('#delivery_agent_id').append(
            //                         `<option value="${item.id}">${item.name}</option`
            //                     );
            //                 });
            //             }
            //         }
            //     });
            // });

            $(document).on('click', '#collected__btn', function(e) {
                e.preventDefault();
                Swal.fire({
                    width: "25rem",
                    title: "Are you sure?",
                    text: "You won't be able to revert this!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, Change it!",
                }).then((result) => {
                    if (result.value) {
                        $('#order_status').submit();
                    }
                });
            });

            $(document).on('change', '#status', function(e) {
                var status = $(this).val();
                if (status == 'Cancelled') {
                    $('#store_area').addClass('d-none').removeClass('d-flex');
                } else {
                    $('#store_area').addClass('d-flex').removeClass('d-none');
                }

                if (status == 'On Route' || status == '') {
                    $('#delivery_agent_area').addClass('d-flex').removeClass('d-none');
                    $('#delivery_agent_area_type').addClass('d-flex').removeClass('d-none');
                } else {
                    $('#delivery_agent_area').addClass('d-none').removeClass('d-flex');
                    $('#delivery_agent_area_type').addClass('d-none').removeClass('d-flex');
                }
            });
        });
    </script>
@endpush
