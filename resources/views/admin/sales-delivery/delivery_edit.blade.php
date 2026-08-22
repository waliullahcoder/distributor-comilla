@extends('layouts.admin.index_app')

@section('content')

<form action="{{ route('admin.sales.delivery.update', ['clientid' => $client->id]) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="card">
        <div class="card-header bg-primary text-white">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">
                        Client Wise Delivery Edit
                    </h5>

                    <small>
                        Client:
                        <b>{{ $client->name }}</b>
                    </small>
                </div>

                <a href="{{route('admin.delivery.list')}}" class="btn btn-danger">
                    <i class="far fa-house"></i>
                   Back
                </a>
                <button type="submit" class="btn btn-success">
                    <i class="far fa-save"></i>
                    Save Delivery
                </button>
            </div>
        </div>

        <div class="card-body">

            @foreach($sales as $sale)

                <div class="card mb-4 border">

                    <div class="card-header bg-light">
                        <div class="row align-items-center">

                            <div class="col-md-4">
                                <b>
                                    Invoice:
                                    {{ $sale->invoice }}
                                </b>
                            </div>

                            <div class="col-md-4">
                                <b>
                                    Date:
                                    {{ date('d-m-Y', strtotime($sale->date)) }}
                                </b>
                            </div>

                        </div>
                    </div>

                    <div class="card-body p-0">

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped mb-0 delivery-table">

                                <thead class="bg-primary text-white">

                                    <tr>
                                        <th width="50">SL</th>
                                        <th>Product Code</th>
                                        <th>Product</th>
                                        <th>Pending Qty</th>
                                        <th width="120">Delivery Qty</th>
                                        <th width="150">Delivery Date</th>
                                    </tr>

                                </thead>

                                <tbody
                                    id="delivery-body-{{ $sale->id }}"
                                    data-sale="{{ $sale->id }}">

                                    @php
                                        $saleDeliveries = $deliveries->get($sale->id, collect());
                                    @endphp

                                    @foreach($saleDeliveries as $key => $delivery)

                                        <tr>

                                            <td class="text-center">
                                                <b class="serial">
                                                    {{ $key + 1 }}
                                                </b>

                                                <input
                                                    type="hidden"
                                                    step="any"
                                                    min="0"
                                                    name="qty[]"
                                                    class="form-control"
                                                    value="{{ $delivery->qty }}">

                                                <input
                                                    type="hidden"
                                                    name="product_id[]"
                                                    value="{{ $delivery->product_id }}">

                                                <input
                                                    type="hidden"
                                                    name="delivery_id[]"
                                                    value="{{ $delivery->id }}">
                                                <input
                                                    type="hidden"
                                                    name="rate[]"
                                                    value="{{ $delivery->rate }}">

                                            </td>

                                            <td>
                                                {{ $delivery->product_code }}
                                            </td>

                                            <td>
                                                {{ $delivery->product_name }}
                                            </td>
                                            <td>
                                                <span class="qty">{{ $delivery->qty-$delivery->delivery }}</span>
                                            </td>

                                            <td>
                                                <input
                                                    type="hidden"
                                                    name="prev_delivery[]"
                                                    value="{{ $delivery->delivery }}">
                                                <input
                                                    type="number"
                                                    step="any"
                                                    min="0"
                                                    name="delivery_qty[]"
                                                    class="form-control delivery-qty"
                                                    value="">
                                            </td>

                                            <td>
                                                <input
                                                    type="date"
                                                    name="delivery_date[]"
                                                    class="form-control"
                                                    value="{{ date('Y-m-d', strtotime($delivery->updated_at)) }}"
                                                    readonly
                                                >
                                            </td>

                                        </tr>

                                    @endforeach

                                    @if($saleDeliveries->count() == 0)

                                        <tr class="no-delivery">
                                            <td colspan="7" class="text-center text-muted">
                                                No Pending found
                                            </td>
                                        </tr>

                                    @endif

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>

            @endforeach

        </div>

    </div>

</form>


{{-- Add Delivery Modal --}}
<div class="modal fade" id="deliveryModal" tabindex="-1">

    <div class="modal-dialog modal-lg">

        <div class="modal-content">

            <div class="modal-header bg-primary text-white">

                <h5 class="modal-title">
                    Add Delivery
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>

            </div>

            <div class="modal-body">

                <input type="hidden" id="modal_sale_id">

                <div class="row g-3">

                    <div class="col-md-8">

                        <label class="form-label">
                            Product
                        </label>

                        <select
                            id="modal_product_id"
                            class="form-select">

                            <option value="">
                                Select Product
                            </option>

                        </select>

                    </div>

                    <div class="col-md-4">

                        <label class="form-label">
                            Delivery Qty
                        </label>

                        <input
                            type="number"
                            id="modal_delivery_qty"
                            class="form-control"
                            step="any"
                            min="0">

                    </div>

                    <div class="col-md-6">

                        <label class="form-label">
                            Delivery Date
                        </label>

                        <input
                            type="date"
                            id="modal_delivery_date"
                            class="form-control"
                            value="{{ date('Y-m-d') }}">

                    </div>

                    <div class="col-md-6">

                        <label class="form-label">
                            Remarks
                        </label>

                        <input
                            type="text"
                            id="modal_remarks"
                            class="form-control">

                    </div>

                </div>

            </div>

            <div class="modal-footer">

                <button
                    type="button"
                    class="btn btn-secondary"
                    data-bs-dismiss="modal">
                    Close
                </button>

                <button
                    type="button"
                    class="btn btn-primary"
                    id="save-new-delivery">

                    Add Delivery

                </button>

            </div>

        </div>

    </div>

</div>

@endsection

@push('js')
<script>

$(document).ready(function () {

    /*
    |--------------------------------------------------------------------------
    | Add Delivery Button
    |--------------------------------------------------------------------------
    */

    $('.add-delivery').on('click', function () {

        let saleId = $(this).data('sale');

        $('#modal_sale_id').val(saleId);

        $('#modal_product_id').html(`
            <option value="">Select Product</option>
        `);

        /*
        |--------------------------------------------------------------------------
        | এখান থেকে invoice-এর products AJAX দিয়ে load করবেন
        |--------------------------------------------------------------------------
        */

        $.ajax({

            url: "{{ url('/sales/products') }}/" + saleId,

            type: "GET",

            success: function (response) {

                $.each(response, function (index, product) {

                    $('#modal_product_id').append(`
                        <option value="${product.id}">
                            ${product.name} - ${product.code}
                        </option>
                    `);

                });

            }

        });

        $('#deliveryModal').modal('show');

    });


    /*
    |--------------------------------------------------------------------------
    | Add New Delivery Row
    |--------------------------------------------------------------------------
    */

    $('#save-new-delivery').on('click', function () {

        let saleId = $('#modal_sale_id').val();
        let productId = $('#modal_product_id').val();
        let productText = $('#modal_product_id option:selected').text();

        let qty = $('#modal_delivery_qty').val();
        let date = $('#modal_delivery_date').val();
        let remarks = $('#modal_remarks').val();

        if (!productId) {
            alert('Please select product.');
            return;
        }

        if (!qty || qty <= 0) {
            alert('Please enter delivery quantity.');
            return;
        }

        let tbody = $('#delivery-body-' + saleId);

        tbody.find('.no-delivery').remove();

        let row = `
            <tr>

                <td class="text-center">
                    <b class="serial"></b>

                    <input
                        type="hidden"
                        name="new_sale_id[]"
                        value="${saleId}">

                    <input
                        type="hidden"
                        name="new_product_id[]"
                        value="${productId}">
                </td>

                <td>
                    ${productText.split(' - ')[1] ?? ''}
                </td>

                <td>
                    ${productText.split(' - ')[0]}
                </td>

                <td>
                    <input
                        type="number"
                        step="any"
                        min="0"
                        name="new_delivery_qty[]"
                        class="form-control"
                        value="${qty}">
                </td>

                <td>
                    <input
                        type="date"
                        name="new_delivery_date[]"
                        class="form-control"
                        value="${date}">
                </td>

                <td>
                    <input
                        type="text"
                        name="new_remarks[]"
                        class="form-control"
                        value="${remarks}">
                </td>

                <td class="text-center">

                    <button
                        type="button"
                        class="btn btn-sm btn-outline-danger remove-delivery">

                        <i class="far fa-trash-alt"></i>

                    </button>

                </td>

            </tr>
        `;

        tbody.append(row);

        updateSerial(tbody);

        $('#deliveryModal').modal('hide');

        $('#modal_product_id').val('');
        $('#modal_delivery_qty').val('');
        $('#modal_remarks').val('');

    });


    /*
    |--------------------------------------------------------------------------
    | Remove Delivery
    |--------------------------------------------------------------------------
    */

    $(document).on('click', '.remove-delivery', function () {

        let tbody = $(this).closest('tbody');

        $(this).closest('tr').remove();

        updateSerial(tbody);

    });


    /*
    |--------------------------------------------------------------------------
    | Serial Number
    |--------------------------------------------------------------------------
    */

    function updateSerial(tbody) {

        tbody.find('tr').each(function (index) {

            $(this)
                .find('.serial')
                .text(index + 1);

        });

    }

});

       // Delivery Qty Validation
            $(document).on('input change', '.delivery-qty', function () {
            let deliveryInput = $(this);

            let delivery = parseFloat(deliveryInput.val()) || 0;

            let qtyText = deliveryInput
                .closest('tr')
                .find('.qty')
                .text()
                .trim();

            let qty = parseFloat(qtyText) || 0;

            if (delivery > qty) {
                alert('Delivery Qty cannot be greater than Pending Quantity.');
                deliveryInput.val(qty);
            }
        });
</script>
@endpush