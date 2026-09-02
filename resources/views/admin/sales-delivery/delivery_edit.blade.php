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
        value="{{ $delivery->delivery }}"
    >

    <input
        type="number"
        step="any"
        min="0"
        max="{{ $delivery->qty - $delivery->delivery }}"
        name="delivery_qty[]"
        class="form-control delivery-qty"
        value=""
    >

    <small class="text-danger delivery-error" style="display:none;"></small>
</td>

                                            <td>
                                                <input
                                                    type="date"
                                                    name="delivery_date[]"
                                                    class="form-control"
                                                    value="{{ date('Y-m-d') }}"
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

<script>
document.addEventListener('DOMContentLoaded', function () {

    // Delivery Qty input validation
    document.querySelectorAll('.delivery-qty').forEach(function (input) {

        input.addEventListener('input', function () {

            let row = this.closest('tr');

            let pendingElement = row.querySelector('.qty');
            let errorElement = row.querySelector('.delivery-error');

            let pendingQty = parseFloat(pendingElement.innerText.trim()) || 0;
            let deliveryQty = parseFloat(this.value) || 0;

            // Clear previous error
            errorElement.style.display = 'none';
            errorElement.innerText = '';
            this.classList.remove('is-invalid');

            // Delivery বেশি হলে
            if (deliveryQty > pendingQty) {

                this.classList.add('is-invalid');

                errorElement.innerText =
                    'Delivery Qty cannot be greater than Pending Qty (' +
                    pendingQty +
                    ')';

                errorElement.style.display = 'block';

            }

        });

    });


    // Form submit validation
    let form = document.querySelector('form');

    form.addEventListener('submit', function (e) {

        let hasError = false;

        document.querySelectorAll('.delivery-qty').forEach(function (input) {

            let row = input.closest('tr');

            let pendingElement = row.querySelector('.qty');
            let errorElement = row.querySelector('.delivery-error');

            let pendingQty = parseFloat(pendingElement.innerText.trim()) || 0;
            let deliveryQty = parseFloat(input.value) || 0;

            if (deliveryQty > pendingQty) {

                hasError = true;

                input.classList.add('is-invalid');

                errorElement.innerText =
                    'Delivery Qty cannot be greater than Pending Qty (' +
                    pendingQty +
                    ')';

                errorElement.style.display = 'block';
            }

        });

        if (hasError) {

            e.preventDefault();

            alert('Delivery Qty cannot be greater than Pending Qty.');

            let firstError = document.querySelector('.is-invalid');

            if (firstError) {
                firstError.focus();
                firstError.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
            }

            return false;
        }

    });

});
</script>
@endsection
