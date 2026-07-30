@extends('layouts.admin.create_app')

@section('content')
    <div class="row g-3 align-items-center">
        <div class="col-lg-3 col-md-4 col-sm-6">
            <label for="barcode" class="form-label"><b>Scan Barcode</b></label>
            <input type="text" class="form-control" id="barcode" name="barcode" placeholder="Barcode">
        </div>
        <div class="col-lg-3 col-md-4 col-sm-6">
            <label for="store_id" class="form-label"><b>Store</b></label>
            <select name="store_id" id="store_id" class="select form-select" data-placeholder="Select Store" required>
                @foreach ($stores as $store)
                    <option value="{{ $store->id }}"
                        {{ old('store_id') && old('store_id') == $store->id ? 'selected' : '' }}>{{ $store->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-lg-3 col-md-4 col-sm-6">
            <label for="client_id" class="form-label"><b>Client Name</b></label>
            <select name="client_id" id="client_id" class="select form-select" data-placeholder="Select Client" required>
                @php
                    $cash_sales = $clients->where('name', 'Cash Sales')->first();
                @endphp
                @if (!is_null($cash_sales))
                    <option value="{{ $cash_sales->id }}"
                        {{ old('client_id') && old('client_id') == $cash_sales->id ? 'selected' : '' }}>
                        {{ $cash_sales->name }}
                @endif
                @foreach ($clients->whereNotIn('name', ['Cash Sales']) as $client)
                    <option value="{{ $client->id }}"
                        {{ old('client_id') && old('client_id') == $client->id ? 'selected' : '' }}>{{ $client->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-lg-3 col-md-4 col-sm-6">
            <label for="coa_setup_id" class="form-label"><b>Cash Account</b></label>
            <select name="coa_setup_id" id="coa_setup_id" class="select form-select" data-placeholder="Select Cash Account">
                @foreach ($cash_heads as $cash_head)
                    <option value="{{ $cash_head->id }}">{{ $cash_head->head_name . ' - ' . $cash_head->head_code }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-12">
            <table class="table table-bordered table-striped target-table align-middle mb-0">
                <thead class="bg-primary border-primary text-white">
                    <tr>
                        <th class="text-center" width="80">SL#</th>
                        <th class="text-nowrap text-center" width="40">Gift</th>
                        <th class="text-nowrap">Product Code</th>
                        <th class="text-nowrap">Product name</th>
                        <th>Unit</th>
                        <th>Rate</th>
                        <th>Qty</th>
                        <th width="250">Amount</th>
                        <th class="text-center" width="50"><i class="far fa-trash-alt"></i></th>
                    </tr>
                </thead>
                <tbody id="tbody">
                </tbody>
                <tfoot class="bg-primary text-white align-top border-primary">
                    <tr>
                        <td class="px-3" colspan="3">
                            <div class="form-check mb-2 pt-2">
                                <input class="form-check-input" type="radio" name="discount_type" id="fixed" checked
                                    value="fixed">
                                <label class="form-check-label" for="fixed">
                                    <b>Fix Discount</b>
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="discount_type" id="percentage"
                                    value="percentage">
                                <label class="form-check-label" for="percentage">
                                    <b>Discount (%)</b>
                                </label>
                            </div>
                            <input type="number" max="100" min="1" id="percentage_input"
                                class="form-control mt-2" placeholder="Discount Percentage"
                                style="display: none; max-width: 250px;">
                        </td>
                        <td colspan="2">
                        </td>
                        <td colspan="2">
                            <input type="hidden" name="total_price" id="total_price" value="0">
                            <div class="input-group align-items-center justify-content-end text-end mb-2"
                                style="height: 32px;">
                                <b style="width: 100px;">Total</b>
                            </div>
                            <div class="input-group align-items-center justify-content-end text-end mb-2"
                                style="height: 32px;">
                                <b style="width: 100px;">Discount</b>
                            </div>
                            <div class="input-group align-items-center justify-content-end text-end" style="height: 32px;">
                                <b style="width: 100px;">Net Payable</b>
                            </div>
                        </td>
                        <td colspan="2">
                            <input type="hidden" name="total_price" id="total_price" value="0">
                            <div class="input-group align-items-center mb-2">
                                <input type="number" id="total_amount" name="total_amount" class="form-control" readonly
                                    placeholder="Total Cost" value="0">
                                <b class="text-center" style="width: 40px;">TK.</b>
                            </div>
                            <div class="input-group align-items-center mb-2">
                                <input type="number" id="discount" name="discount" class="form-control"
                                    placeholder="Discount" value="0">
                                <b class="text-center" style="width: 40px;">TK.</b>
                            </div>
                            <div class="input-group align-items-center">
                                <input type="number" id="net_payable" name="net_payable" class="form-control" readonly
                                    placeholder="net Payable" value="0">
                                <b class="text-center" style="width: 40px;">TK.</b>
                            </div>
                        </td>
                    </tr>
                </tfoot>
            </table>
            <div class="text-end text-primary">
                <span id="limit_crosed" class="mt-4" style="display: none; font-size: 16px;">This bill has exceeded its
                    due
                    limit</span>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#barcode').focus();
            $(document).on('keypress', '#barcode', function(e) {
                if (e.which == 13) {
                    var formData = $('#store_form').serialize();
                    formData += '&_method=GET';
                    let store_id = $("#store_id").val();
                    let product_code = $('#barcode').val();
                    var existing_key = $("#tbody tr").length;
                    let url = "{{ Route('admin.pos-sales.create') }}";
                    $.ajax({
                        url: url,
                        type: "POST",
                        data: formData,
                        success: (response) => {
                            if (response.status == 'error') {
                                Swal.fire({
                                    width: "22rem",
                                    title: "Error!",
                                    text: response.data,
                                    icon: "error",
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                            }
                            if (response.status == 'increment') {
                                $('#qty_' + response.product_id).val(response.total_qty);
                                $('#amount_' + response.product_id).val(response.amount);
                                calculate();
                            }
                            if (response.status == 'success') {
                                var tr =
                                    `<tr>
                                        <td class="text-center" width="80">
                                            <b class="serial">${(existing_key+1)}</b>
                                            <input type="hidden" class="product_id" name="product_id[]" value="${response.product.id}">
                                        </td>
                                        <td>
                                            <div class="custom-control custom-checkbox mx-auto">
                                                <input type="checkbox" class="custom-control-input gift" id="gift_${response.product.id}" name="gift[${response.product.id}]" value="${response.product.id}">
                                                <label for="gift_${response.product.id}" class="custom-control-label"></label>
                                            </div>
                                        </td>
                                        <td>${response.product.code}</td>
                                        <td>${response.product.name}</td>
                                        <td><input type="text" class="form-control" placeholder="Unit" readonly value="${response.unit}"></td>
                                        <td><input type="number" class="form-control" placeholder="Rate" id="rate_${response.product.id}" name="rate[${response.product.id}]" readonly value="${response.price}"></td>
                                        <td><input type="number" class="form-control" placeholder="Quantity" id="qty_${response.product.id}" name="qty[${response.product.id}]" readonly  value="1"></td>
                                        <td><input type="number" class="form-control" placeholder="Amount" id="amount_${response.product.id}" name="amount[${response.product.id }]" readonly value="${response.price}"></td>
                                        <td class="text-center"><button type="button" class="btn btn-xs btn-outline-danger remove_item mnw-auto px-2"><i class="far fa-trash-alt"></i></button></td>
                                    </tr>`;
                                $('#tbody').append(tr);
                                calculate();
                            }
                        }
                    });
                    e.preventDefault();
                }
            });

            $(document).on('change', '.gift', function(e) {
                let product_id = $(this).val();
                if ($(this).is(':checked')) {
                    $('#amount_' + product_id).val(0);
                } else {
                    var rate = +$('#rate_' + product_id).val();
                    var qty = +$('#qty_' + product_id).val();
                    $('#amount_' + product_id).val(rate * qty);
                }
                calculate();
            });

            function calculate() {
                var total_amount = 0;
                $('.product_id').each(function(index, value) {
                    total_amount += +$('#amount_' + $(this).val())
                        .val();
                });
                $('#total_amount').val(total_amount);

                let discount_type = $('input[name="discount_type"]:checked')
                    .val();
                if (discount_type == 'percentage') {
                    var discount = parseFloat($('#percentage_input').val());
                    var fix_discount = parseFloat(total_amount) * (parseFloat(
                            discount) /
                        100);
                    $("#discount").val(Math.floor(fix_discount));
                    $("#net_payable").val(total_amount - Math.floor(
                        fix_discount));
                } else {
                    var discount = $('#discount').val();
                    var net_payable = total_amount - parseFloat(discount);
                    $('#total_amount').val(total_amount);
                    $('#net_payable').val(net_payable);
                }
            }

            $(document).on('change', '#store_id', function(e) {
                $('#tbody tr').remove();
                calculate();
            });

            $(document).on('click', '.remove_item', function(e) {
                $(this).closest('tr').remove();
                calculate();
            });

            $(document).on('change', 'input[name="discount_type"]', function(e) {
                if ($(this).val() == 'percentage') {
                    $('#percentage_input').show();
                } else {
                    $('#percentage_input').hide();
                }
            });

            $(document).on('wheel keyup change', '#percentage_input', function(event) {
                var discount = $(this).val();
                if (discount > 100) {
                    $(this).val(100);
                    var discount = 100;
                }

                var total = $("#total_amount").val();
                var fix_discount = parseFloat(total) * (parseFloat(discount) / 100);
                $("#discount").val(Math.floor(fix_discount));
                $("#net_payable").val(total - Math.floor(fix_discount));
            });

            $(document).on('wheel keyup change', '#discount', function(e) {
                var total_amount = parseFloat($('#total_amount').val());
                var discount = parseFloat($(this).val());
                $('#net_payable').val(total_amount - discount);
            });

            $(document).on('submit', '#store_form', function(e) {
                e.preventDefault();
                $('.btn-spiner').show();
                $('.submit_btn').attr('disabled', true);
                $('#store_form')[0].submit();
                @if (@$target_blank)
                    location.reload();
                @endif
            });
        });
    </script>
@endpush
