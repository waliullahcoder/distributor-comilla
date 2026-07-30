@extends('layouts.admin.edit_app')

@section('content')
    <div class="row g-3">
        <div class="col-md-3 col-sm-6">
            <label for="name" class="form-label"><b>Name</b></label>
            <input type="text" name="name" id="name" class="form-control" placeholder="Package Name"
                value="{{ $data->name }}" required>
        </div>
        <div class="col-md-3 col-sm-6">
            <label for="image" class="form-label"><b>Image</b></label>
            <input type="file" name="image" id="image" class="form-control" accept="image/*">
        </div>
        <div class="col-md-3 col-sm-6">
            <label for="shipping_charge" class="form-label"><b>Shipping Charge</b></label>
            <input type="text" name="shipping_charge" id="shipping_charge" class="form-control"
                placeholder="Shipping Charge" value="{{ $data->shipping_charge }}">
        </div>
        <div class="col-md-3 col-sm-6">
            <label for="discount" class="form-label"><b>Discount</b></label>
            <input type="text" name="discount" id="discount" class="form-control" placeholder="Discount"
                value="{{ $data->discount }}">
        </div>
        <div class="col-12">
            <label for="description" class="form-label"><b>Description</b></label>
            <textarea class="form-control" name="description" id="description" placeholder="Description" rows="2" required>{{ $data->description }}</textarea>
        </div>
        <div class="col-md-4 col-sm-6">
            <label for="net_amount" class="form-label"><b>Net Amount</b></label>
            <input type="text" name="net_amount" id="net_amount" class="form-control" placeholder="Net Amount"
                value="{{ $data->net_amount }}" readonly>
        </div>
        <div class="col-md-4 col-sm-6">
            <label for="products" class="form-label"><b>Products</b></label>
            <select name="products" id="products" class="select form-select" data-placeholder="Select Product">
                <option value=""></option>
                @foreach ($products as $item)
                    <option value="{{ $item->id }}" data-price="{{ @$item->price->online_price }}">{{ $item->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4 col-sm-6">
            <label for="" class="form-label text-white d-md-block d-none"><b>Add</b></label>
            <button type="button" class="btn btn-sm btn-primary w-100" id="add_btn" style="height: 36px">Add
                Product</button>
        </div>
        <div class="col-12">
            <div class="table-responsive">
                <table class="table">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th class="text-center" width="40">SL#</th>
                            <th>Product</th>
                            <th>Rate</th>
                            <th>Qty</th>
                            <th>Amount</th>
                            <th class="text-end" width="40"></th>
                        </tr>
                    </thead>
                    <tbody id="tbody">
                        @foreach ($data->list as $item)
                            <tr>
                                <td class="text-center px-1">{{ $loop->iteration }}</td>
                                <td class="px-1"><input type="text" class="form-control"
                                        style="min-height: auto; padding: 3px 10px;" value="{{ @$item->product->name }}"
                                        readonly required></td>
                                <td class="px-1"><input type="text" class="form-control rate"
                                        id="rate{{ $item->product_id }}" style="min-height: auto; padding: 3px 10px;"
                                        name="rate[{{ $item->product_id }}]" value="{{ $item->rate }}" required></td>
                                <td class="px-1"><input type="text" class="form-control qty"
                                        id="qty{{ $item->product_id }}" style="min-height: auto; padding: 3px 10px;"
                                        name="qty[{{ $item->product_id }}]" value="{{ $item->qty }}" required></td>
                                <td class="px-1"><input type="text" class="form-control"
                                        id="amount{{ $item->product_id }}" style="min-height: auto; padding: 3px 10px;"
                                        name="amount[{{ $item->product_id }}]" value="{{ $item->amount }}" readonly
                                        required></td>
                                <td class="text-end px-1">
                                    <input type="hidden" name="product_id[]" class="product_id"
                                        id="product_id{{ $item->product_id }}" value="{{ $item->product_id }}">
                                    <button type="button" class="btn btn-xs btn-outline-danger remove mnw-auto px-2"><i
                                            class="far fa-trash-alt"></i></button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-primary text-white">
                        <tr>
                            <th class="text-end" colspan="4">Total Amount</th>
                            <th><input type="text" class="form-control" id="total_amount"
                                    style="min-height: auto; padding: 3px 10px;" name="total_amount"
                                    value="{{ $data->amount }}" readonly required></th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script type="text/javascript">
        $(document).ready(function() {
            $(document).on('click', '#add_btn', function(e) {
                var serial = $('#tbody tr').length + 1;
                var id = $('#products option:selected').val();
                if (id != '' && $('#product_id' + id).length == 0) {
                    var name = $('#products option:selected').text();
                    var price = $('#products option:selected').data('price');
                    var tr = `<tr>
                                <td class="text-center px-1">${serial}</td>
                                <td class="px-1"><input type="text" class="form-control" style="min-height: auto; padding: 3px 10px;" value="${name}" readonly required></td>
                                <td class="px-1"><input type="text" class="form-control rate" id="rate${id}" style="min-height: auto; padding: 3px 10px;" name="rate[${id}]" value="${price}" required></td>
                                <td class="px-1"><input type="text" class="form-control qty" id="qty${id}" style="min-height: auto; padding: 3px 10px;" name="qty[${id}]" value="1" required></td>
                                <td class="px-1"><input type="text" class="form-control" id="amount${id}" style="min-height: auto; padding: 3px 10px;" name="amount[${id}]" value="${price}" readonly required></td>
                                <td class="text-end px-1">
                                    <input type="hidden" name="product_id[]" class="product_id" id="product_id${id}" value="${id}">
                                    <button type="button" class="btn btn-xs btn-outline-danger remove mnw-auto px-2"><i class="far fa-trash-alt"></i></button>
                                </td>
                            </tr>`;
                    $('#tbody').append(tr);
                    calculate();
                }
            });

            $(document).on('click', '.remove', function(e) {
                $(this).closest('tr').remove();
                calculate();
            });

            $(document).on('keyup', '.rate,.qty,#shipping_charge,#discount', function() {
                var id = $(this).closest('tr').find('.product_id').val();
                var rate = +$('#rate' + id).val();
                var qty = +$('#qty' + id).val();
                $('#amount' + id).val(rate * qty);
                calculate();
            });

            function calculate() {
                var total_amount = 0;
                $('.product_id').each(function(index, value) {
                    var id = $(this).val();
                    rate = +$('#rate' + id).val();
                    qty = +$('#qty' + id).val();
                    total_amount += rate * qty;
                });
                var shipping_charge = +$('#shipping_charge').val();
                var discount = +$('#discount').val();
                $('#total_amount').val(total_amount);
                $('#net_amount').val(total_amount + shipping_charge - discount);
            }
        });
    </script>
@endpush
