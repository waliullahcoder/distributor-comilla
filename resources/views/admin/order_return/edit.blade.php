@extends('layouts.admin.edit_app')

@section('content')
    <div class="row g-3">
        <div class="col-lg-3 col-md-4 col-sm-6">
            <label for="order_no" class="form-label"><b>Order No.</b></label>
            <input type="text" class="form-control" id="order_no" name="order_no" value="{{ $data->invoice }}" readonly
                placeholder="Order No.">
        </div>
        <div class="col-lg-3 col-md-4 col-sm-6">
            <label for="user_name" class="form-label"><b>Customer Name</b></label>
            <input type="text" class="form-control" id="user_name" name="user_name" placeholder="Customer Name"
                value="{{ $data->user_name }}">
        </div>
        <div class="col-lg-3 col-md-4 col-sm-6">
            <label for="user_phone" class="form-label"><b>Customer Phone</b></label>
            <input type="number" class="form-control" id="user_phone" name="user_phone" placeholder="Customer Phone"
                value="{{ $data->user_phone }}">
        </div>
        <div class="col-lg-3 col-md-4 col-sm-6">
            <label for="date" class="form-label"><b>Date</b></label>
            <input type="text" class="form-control date_picker" id="date" name="date"
                value="{{ date('d-m-Y', strtotime($data->date)) }}" placeholder="Date">
        </div>
        <div
            class="{{ Auth::user()->hasRole('System Admin') || Auth::user()->hasRole('Software Admin') ? 'col-md-6 col-sm-6' : 'col-md-9 col-sm-6' }}">
            <label for="shipping_address" class="form-label"><b>Shipping Address <span
                        class="text-danger">*</span></b></label>
            <input type="text" class="form-control" id="shipping_address" name="shipping_address"
                value="{{ $data->shipping_address }}" placeholder="Shipping Address">
        </div>
        <div class="col-md-3 col-sm-6">
            <label for="area_id" class="form-label"><b>Area</b></label>
            <select name="area_id" id="area_id" class="select form-select" data-placeholder="Select Area">
                <option value=""></option>
                @foreach ($areas as $area)
                    <option value="{{ $area->id }}" {{ $data->area_id == $area->id ? 'selected' : '' }}>
                        {{ $area->name }}
                    </option>
                @endforeach
            </select>
        </div>
        {{-- @if (Auth::user()->hasRole('System Admin') || Auth::user()->hasRole('Software Admin'))
            <div class="col-md-3 col-sm-6">
                <label for="created_by" class="form-label"><b>Staff</b></label>
                <select name="created_by" id="created_by" class="select form-select" data-placeholder="Select Staff">
                    @php
                        $users = \App\Models\User::where('role', 1)
                            ->whereHas('roles', function ($q) {
                                $q->where('name', 'Moderator');
                            })
                            ->orderBy('name', 'asc')
                            ->get();
                    @endphp
                    <option value=""></option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}" {{ $data->created_by == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            @endif --}}
        <div class="col-md-3 col-sm-6">
            <label for="return_cost" class="form-label"><b>Return Cost</b></label>
            <input type="text" class="form-control" value="{{ $data->return_cost }}" readonly>
        </div>
        <div class="col-12">
            <div class="table-responsive">
                <table class="table table-bordered table-striped target-table align-middle mb-0">
                    <thead class="bg-primary border-primary text-white">
                        <tr>
                            <th class="px-3">Product Name</th>
                            <th class="px-3">Code</th>
                            <th class="px-3 text-center" width="120">Rate</th>
                            <th class="px-3 text-center" width="120">Quantity</th>
                            <th class="px-3 text-center" width="120">Return Qty</th>
                            <th class="px-3 text-center" width="120">Damaged Qty</th>
                            <th class="px-3 text-center" width="120">Amount</th>
                            <th class="px-3 text-center" width="120">Return Amount</th>
                            <th class="px-3 text-center d-none" width="30">
                                <div class="custom-control custom-checkbox w-fit mx-auto">
                                    <input type="checkbox" class="custom-control-input" name="selectAll" id="checkAll">
                                    <label for="checkAll" class="custom-control-label"></label>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody id="tbody">
                        @foreach ($data->products as $item)
                            <tr>
                                <td class="px-3">
                                    <span>{{ @$item->product->name }}</span>
                                </td>
                                <td class="px-3">{{ @$item->product->code }}</td>
                                <td><input type="number" class="text-center rate" id="rate{{ $item->id }}"
                                        step="any" value="{{ $item->sale_price - $item->discount / $item->quantity }}"
                                        placeholder="0.00" readonly required></td>
                                <td><input type="number" class="text-center qty" step="any"
                                        value="{{ $item->quantity }}" placeholder="0.00" readonly></td>
                                <td><input type="number" class="text-center return_qty"
                                        name="return_quantity[{{ $item->id }}]"
                                        id="return_quantity{{ $item->id }}" step="any"
                                        max="{{ $item->quantity }}" data-id="{{ $item->id }}"
                                        value="{{ $item->quantity }}" placeholder="0.00" readonly>
                                </td>
                                <td>
                                    <input type="number" class="text-center damaged_qty"
                                        id="damaged_qty{{ $item->id }}" name="damaged_quantity[{{ $item->id }}]"
                                        max="{{ $item->quantity }}" data-id="{{ $item->id }}" step="any"
                                        value="{{ $item->damaged_quantity }}" placeholder="0.00">
                                </td>
                                <td>
                                    <input type="number" class="text-center amount" name="amount[{{ $item->id }}]"
                                        step="any" value="{{ $item->subtotal - $item->discount }}"
                                        placeholder="0.00" readonly>
                                <td>
                                    <input type="number" class="text-center return_amount"
                                        id="return_amount{{ $item->id }}" name="return_amount[{{ $item->id }}]"
                                        step="any" max="{{ $item->subtotal - $item->discount }}"
                                        value="{{ $item->subtotal - $item->discount }}" placeholder="0.00" readonly>
                                </td>
                                <td class="d-none">
                                    <div class="custom-control custom-checkbox w-fit mx-auto">
                                        <input type="checkbox" class="custom-control-input multi_checkbox orderProductId"
                                            name="order_product_id[]" value="{{ $item->id }}"
                                            id="{{ $item->id }}" data-qty="{{ $item->quantity }}"
                                            data-amount="{{ $item->subtotal }}" checked {{-- {{ $item->return_amount > 0 ? 'checked' : '' }} --}}>
                                        <label for="{{ $item->id }}" class="custom-control-label"></label>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-primary text-white align-top border-primary">
                        <tr>
                            <td colspan="7" class="text-end">Total Return</td>
                            <td>
                                <div class="input-group align-items-center">
                                    <input type="number" id="total_return" name="total_return" class="text-center"
                                        step="any" placeholder="Total Return"
                                        value="{{ $data->products->sum('subtotal') - $data->products->sum('discount') }}"
                                        readonly>
                                </div>
                            </td>
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
            if ($('.multi_checkbox:checked').length == $('.multi_checkbox').length) {
                $('#checkAll').prop('checked', true);
            } else {
                $('#checkAll').prop('checked', false);
            }

            $(document).on('wheel keyup change', '.return_qty', function(e) {
                var id = $(this).data('id');
                var rate = +$('#rate' + id).val();
                var return_qty = +$(this).val();
                var max = +$(this).attr('max');
                if (return_qty > max) {
                    return_qty = max;
                    $(this).val(max);
                }
                var damaged_qty = +$('#damaged_qty' + id).val();
                if (damaged_qty > return_qty) {
                    $('#damaged_qty' + id).val(return_qty);
                }
                if (return_qty > 0) {
                    $('#' + id).prop('checked', true);
                } else {
                    $('#' + id).prop('checked', false);
                }
                $('#return_amount' + id).val(rate * return_qty);
                calculate();
            });

            $(document).on('wheel keyup change', '.damaged_qty', function(e) {
                var id = $(this).data('id');
                var return_qty = +$('#return_quantity' + id).val();
                var damaged_qty = +$(this).val();
                var max = +$(this).attr('max');
                if (damaged_qty > max) {
                    $(this).val(max);
                }
                if (damaged_qty > return_qty) {
                    $(this).val(return_qty);
                }
            });

            $(document).on('change', '#checkAll', function(e) {
                if ($(this).is(':checked')) {
                    $('.multi_checkbox').prop('checked', true);
                    $('.multi_checkbox').each(function(index, value) {
                        var id = $(this).val();
                        var qty = +$(this).data('qty');
                        var amount = +$(this).data('amount');
                        $('#return_quantity' + id).val(qty);
                        $('#return_amount' + id).val(amount);
                    });
                } else {
                    $('.multi_checkbox').prop('checked', false);
                    $('.return_qty').val('');
                    $('.return_amount').val('');
                }
                calculate();
            });

            $(document).on('click', '.orderProductId', function() {
                var id = $(this).val();
                if ($(this).is(':checked')) {
                    var qty = +$(this).data('qty');
                    var amount = +$(this).data('amount');
                    $('#return_quantity' + id).val(qty);
                    $('#return_amount' + id).val(amount);
                } else {
                    $('#return_quantity' + id).val(0);
                    $('#return_amount' + id).val(0);
                }
                calculate();
            });

            function calculate() {
                var total_return = 0;
                $('.return_amount').each(function(index, value) {
                    total_return += +$(this).val();
                });
                $('#total_return').val(total_return);
                if ($('.multi_checkbox:checked').length == $('.multi_checkbox').length) {
                    $('#checkAll').prop('checked', true);
                } else {
                    $('#checkAll').prop('checked', false);
                }
            }
        });
    </script>
@endpush
