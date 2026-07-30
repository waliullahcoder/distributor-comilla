@extends('layouts.admin.create_app')

@section('content')
    <div class="row g-3">
        <div class="col-lg-3 col-sm-6">
            <label for="delivery_agent_id" class="form-label"><b>Delivery Agent <span class="text-danger">*</span></b></label>
            <select name="delivery_agent_id" id="delivery_agent_id" class="select form-select"
                data-placeholder="Select Delivery Agent" required>
                <option value=""></option>
                @foreach ($deliveryAgents as $item)
                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-lg-3 col-sm-6">
            <label for="area_type" class="form-label"><b>Area Type <span class="text-danger">*</span></b></label>
            <select name="area_type" id="area_type" class="select form-select" data-placeholder="Select Area Type" required>
                <option value=""></option>
                <option value="inside_dhaka">Inside Dhaka</option>
                <option value="subarea_dhaka">Subarea Dhaka</option>
                <option value="inside_chittagong">Inside Chittagong</option>
                <option value="subarea_chittagong">Subarea Chittagong</option>
                <option value="district_level">District Level</option>
            </select>
        </div>
        <div class="col-lg-3 col-sm-6">
            <label for="total_qty" class="form-label"><b>Total Orders <span class="text-danger">*</span></b></label>
            <input type="text" id="total_qty" class="form-control" value="0" readonly>
        </div>
        <div class="col-lg-3 col-sm-6">
            <label for="total_amount" class="form-label"><b>Total Amount <span class="text-danger">*</span></b></label>
            <input type="text" id="total_amount" class="form-control" value="0" readonly>
        </div>
        <div class="col-12">
            <div class="table-responsive">
                <table class="table table-bordered table-striped target-table align-middle mb-0">
                    <thead class="bg-primary border-primary text-white align-middle text-nowrap">
                        <tr>
                            <th class="py-1 text-center" width="30">SL.</th>
                            <th class="py-1">Date</th>
                            <th class="py-1">Invoice No</th>
                            <th class="py-1">Customer Name</th>
                            <th class="py-1">Phone</th>
                            <th class="py-1">Area</th>
                            <th class="py-1">Address</th>
                            <th class="py-1 text-center">Amount</th>
                            <th class="py-1" width="60">
                                <div class="custom-control custom-checkbox w-fit mx-auto">
                                    <input type="checkbox" class="custom-control-input" name="selectAll" id="checkAll">
                                    <label for="checkAll" class="custom-control-label"></label>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody id="tbody">
                        @foreach ($orders as $item)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td class="text-nowrap">{{ $item->formattedDate }}</td>
                                <td class="text-nowrap">{{ $item->invoice }}</td>
                                <td class="text-nowrap">{{ $item->user_name }}</td>
                                <td class="text-nowrap">{{ $item->user_phone }}</td>
                                <td class="text-nowrap">{{ @$item->area->name }}</td>
                                <td>{{ $item->shipping_address }}</td>
                                <td class="text-nowrap text-center">{{ $item->due }}</td>
                                <td>
                                    <input type="hidden" id="due_{{ $item->id }}" name="due[{{ $item->id }}]"
                                        value="{{ $item->due }}">
                                    <div class="custom-control custom-checkbox w-fit mx-auto">
                                        <input type="checkbox" class="custom-control-input order_id" name="order_id[]"
                                            value="{{ $item->id }}" id="order_id{{ $item->id }}">
                                        <label for="order_id{{ $item->id }}" class="custom-control-label"></label>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script type="text/javascript">
        $(document).ready(function() {
            $(document).on('change', '#checkAll', function(e) {
                if ($(this).is(':checked')) {
                    $('.order_id').prop('checked', true);
                } else {
                    $('.order_id').prop('checked', false);
                }
                calc();
            });

            $(document).on('change', '.order_id', function(e) {
                if ($('.order_id:checked').length == $('.order_id').length) {
                    $('#checkAll').prop('checked', true);
                } else {
                    $('#checkAll').prop('checked', false);
                }
                calc();
            });

            function calc() {
                var total_amount = 0;
                $('.order_id:checked').each(function(index, value) {
                    var order_id = $(this).val();
                    total_amount += +$('#due_' + order_id).val();
                });
                $('#total_amount').val(total_amount.toFixed(2));
                $('#total_qty').val($('.order_id:checked').length);
            }

            $(document).on('submit', '#store_form', function(e) {
                if ($('.order_id:checked').length == 0) {
                    e.preventDefault();
                    Swal.fire({
                        width: "22rem",
                        title: "Error!",
                        text: "Please select a Item",
                        icon: "error",
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            });
        });
    </script>
@endpush
