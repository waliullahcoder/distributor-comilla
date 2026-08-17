@extends('layouts.admin.edit_app')

@section('content')
    <div class="row g-3 align-items-center">
        <!-- <div class="col-lg-3 col-md-4 col-sm-6">
            <label for="sales_type" class="form-label"><b>Sales Type <span class="text-danger">*</span></b></label>
            <select name="sales_type" id="sales_type" class="select form-select" data-placeholder="Sales Type" required>
                <option value="credit" {{ $data->sales_type == 'credit' ? 'selected' : '' }}>Credit</option>
                <option value="cash" {{ $data->sales_type == 'cash' ? 'selected' : '' }}>Cash</option>
            </select>
        </div> -->
         <input type="hidden" name="sales_type" value="credit">
        <input type="hidden" name="coa_setup_id" value="26">
        <!-- <div class="col-lg-3 col-md-4 col-sm-6" id="accounts_area"
            style="display: {{ $data->sales_type == 'cash' ? 'block' : 'none' }};">
            <label for="coa_setup_id" class="form-label"><b>Cash Account <span class="text-danger">*</span></b></label>
            <select name="coa_setup_id" id="coa_setup_id" class="select form-select" data-placeholder="Select Cash Account"
                {{ $data->sales_type == 'cash' ? 'required' : '' }}>
                <option value="">Select Cash Account</option>
                @foreach ($cash_heads as $cash_head)
                    <option value="{{ $cash_head->id }}">{{ $cash_head->head_name . ' - ' . $cash_head->head_code }}
                    </option>
                @endforeach
            </select>
        </div> -->
        <div class="col-lg-3 col-md-4 col-sm-6">
            <label for="order_type" class="form-label"><b>Order Type</b></label>
            <select name="order_type" id="order_type" class="select form-select" data-placeholder="Order Type">
                <option value="post_order">Post Order</option>
                <option value="pre_order">Pre Order</option>
            </select>
        </div>
        <div class="col-lg-3 col-md-4 col-sm-6">
            <label for="invoice" class="form-label"><b>Invoice No. <span class="text-danger">*</span></b></label>
            <input type="text" class="form-control" id="invoice" name="invoice" value="{{ $data->invoice }}" readonly
                placeholder="Invoice No." required>
        </div>
        <div class="col-lg-3 col-md-4 col-sm-6">
            <label for="date" class="form-label"><b>Invoice Date <span class="text-danger">*</span></b></label>
            <input type="text" class="form-control date_picker" id="date" name="date"
                value="{{ date('d-m-Y', strtotime($data->date)) }}" placeholder="Invoice Date" required>
        </div>
        <div class="col-lg-3 col-md-4 col-sm-6">
            <label for="store_id" class="form-label"><b>Store <span class="text-danger">*</span></b></label>
            <select name="store_id" id="store_id" class="select form-select" data-placeholder="Select Store" required>
                <!-- <option value=""></option> -->
                @foreach ($stores as $store)
                    <option value="{{ $store->id }}" {{ $data->store_id == $store->id ? 'selected' : '' }}>
                        {{ $store->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-lg-3 col-md-4 col-sm-6">
            <label for="client_id" class="form-label"><b>Client Name <span class="text-danger">*</span></b></label>
            <select name="client_id" id="client_id" class="select form-select" data-placeholder="Select Client" required>
                <!-- <option value=""></option> -->
                @foreach ($clients as $client)
                    <option value="{{ $client->id }}" {{ $data->client_id == $client->id ? 'selected' : '' }}>
                        {{ $client->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-lg-3 col-md-4 col-sm-6" id="order_area" style="display: none;">
            <label for="order_id" class="form-label"><b>Orders</b></label>
            <select name="order_id" id="order_id" class="select form-select" data-placeholder="Select Order">
                <option value=""></option>
            </select>
        </div>
        <div class="col-lg-3 col-md-4 col-sm-6" id="vendor_area">
            <label for="vendor_id" class="form-label"><b>Vendor</b></label>
            <select name="vendor_id" id="vendor_id" class="select form-select" data-placeholder="Select Vendor">
                <!-- <option value=""></option> -->
                @foreach ($vendors as $vendor)
                    <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-lg-3 col-md-4 col-sm-6">
            <label for="staff_id" class="form-label"><b>Staff <span class="text-danger">*</span></b></label>
            <select name="staff_id" id="staff_id" class="select form-select" data-placeholder="Select Staff" required>
                <!-- <option value=""></option> -->
                @foreach ($staffs as $staff)
                    <option value="{{ $staff->id }}" {{ $data->staff_id == $staff->id ? 'selected' : '' }}>
                        {{ $staff->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4 col-sm-6" id="post_product">
            <label for="product_id" class="form-label"><b>Products</b></label>
            <select id="product_id" class="select form-select" data-placeholder="Select Product">
                <option value="">Select Product</option>
                @foreach ($products as $product)
                    <option value="{{ $product->id }}">{{ $product->name }} - {{ $product->code }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4 col-sm-6" id="pre_product" style="display: none;">
            <label for="order_product_id" class="form-label"><b>Order Products</b></label>
            <select name="order_product_id" id="order_product_id" class="select form-select"
                data-placeholder="Select Product">
                <option value="">Select Product</option>
            </select>
        </div>
        <div class="col-md-2 col-sm-6">
            <label for="quantity" class="form-label"><b>Quantity</b></label>
            <input type="number" class="form-control" id="quantity" name="quantity" step="any"
                placeholder="Quantity">
        </div>
        <div class="col-md-2 col-sm-6">
            <label for="stock" class="form-label"><b>Stock</b></label>
            <input type="number" class="form-control" id="stock" name="stock" step="any"
                placeholder="Stock" readonly value="0">
        </div>
        <div class="col-md-2 col-sm-6">
            <label for="credit_limit" class="form-label"><b>Credit Limit</b></label>
            <input type="number" class="form-control" id="credit_limit" name="credit_limit" placeholder="Credit Limit"
                readonly value="{{ $balance }}">
        </div>
        <div class="col-md-2 col-sm-6">
            <label class="form-label text-white"><b>Add</b></label>
            <button type="button" class="btn btn-xs btn-primary w-100 px-2 py-2" id="add_item">Add Product</button>
        </div>
        <div class="col-12">
            <div class="table-responsive">
                <table class="table table-bordered table-striped target-table align-middle mb-0">
                    <thead class="bg-primary border-primary text-white">
                        <tr>
                            <th class="text-center" width="30">SL#</th>
                            <th class="text-nowrap">Gift</th>
                            <th class="text-nowrap">Vendor Name</th>
                            <th class="text-nowrap">Product Code</th>
                            <th class="text-nowrap">Product name</th>
                            <th>Rate</th>
                            <th class="text-nowrap">Order Qty</th>
                            <th>Unit</th>
                            <th>Amount</th>
                            <th class="text-center" width="50"><i class="far fa-trash-alt"></i></th>
                        </tr>
                    </thead>
                    <tbody id="tbody">
                        @foreach ($data->list as $key => $item)
                            <tr>
                                <td class="text-center" width="30"><b class="serial">{{ $key + 1 }}</b>
                                    <input type="hidden" name="product_id[]" class="product_id{{ $item->product_id }}"
                                        value="{{ $item->product_id }}">
                                    <input type="hidden" name="order_product_id[]"
                                        value="{{ $item->order_product_id }}">
                                </td>
                                <td>
                                    @php
                                        $product = App\Models\Product::find($item->product_id);
                                        $client_price = App\Models\ClientPrice::where('client_id', $data->client_id)
                                            ->where('product_id', $item->product_id)
                                            ->first();
                                    @endphp
                                    <div class="custom-control mx-auto custom-checkbox">
                                        <input type="checkbox" class="custom-control-input gift_item" name="gift[]"
                                            id="{{ $item->product_id }}"
                                            value="{{ @$client_price->client_price ?? $product->price->sale_price }}"
                                            {{ $item->rate == 0 ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="{{ $item->product_id }}"></label>
                                    </div>
                                </td>
                                <td>
                                    @foreach($item->product->vendors as $single)
                                        {{ @$single->vendor->name }},
                                    @endforeach
                                </td>
                                <td>{{ $item->product->code }}</td>
                                <td>{{ $item->product->name }}</td>
                                <td>
                                    <input type="number" style="min-width: 100px;" class="form-control rate"
                                        placeholder="Rate" name="rate[]" value="{{ $item->rate }}">
                                </td>
                                <td>
                                    <input type="number" style="min-width: 100px;" class="form-control qty"
                                        placeholder="Quantity" name="qty[]" step="any" value="{{ $item->qty }}">
                                </td>
                               
                                <td>
                                    <input type="text" style="min-width: 100px;" class="form-control unit"
                                        placeholder="Unit" readonly value="{{ $item->product->attribute->name }}">
                                </td>
                                <td>
                                    <input type="number" style="min-width: 100px;" class="form-control amount"
                                        placeholder="Amount" name="amount[]" readonly value="{{ $item->amount }}">
                                </td>
                                <td class="text-center"><button type="button"
                                        class="btn btn-xs btn-outline-danger remove_item mnw-auto px-2"><i
                                            class="far fa-trash-alt"></i></button></td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-primary text-white align-top border-primary">
                        <tr>
                            <td class="px-3" colspan="3">
                                <div class="form-check mb-2 pt-2">
                                    <input class="form-check-input" type="radio" name="discount_type" id="fixed"
                                        checked value="fixed">
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
                            <td colspan="3">
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
                                <div class="input-group align-items-center justify-content-end text-end"
                                    style="height: 32px;">
                                    <b style="width: 100px;">Net Payable</b>
                                </div>
                            </td>
                            <td colspan="2">
                                <input type="hidden" name="total_price" id="total_price" value="0">
                                <div class="input-group align-items-center mb-2">
                                    <input type="number" id="total_amount" name="total_amount" class="form-control"
                                        readonly placeholder="Total Cost" value="{{ $data->total_amount }}">
                                    <b class="text-center" style="width: 40px;">TK.</b>
                                </div>
                                <div class="input-group align-items-center mb-2">
                                    <input type="number" id="discount" name="discount" class="form-control"
                                        placeholder="Discount" value="{{ $data->discount }}">
                                    <b class="text-center" style="width: 40px;">TK.</b>
                                </div>
                                <div class="input-group align-items-center">
                                    <input type="number" id="net_payable" name="net_payable" class="form-control"
                                        readonly placeholder="net Payable"
                                        value="{{ $data->total_amount - $data->discount }}">
                                    <b class="text-center" style="width: 40px;">TK.</b>
                                </div>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
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
            $(".date_picker").datepicker({
                format: 'dd-mm-yyyy',
                changeMonth: true,
                changeYear: true,
            });

            $(document).on('change', '#sales_type', function(e) {
                var balance = $('#credit_limit').val();
                var payable = $('#net_payable').val();
                var sales_type = $('#sales_type').val();
                if (sales_type == 'credit' && parseFloat(payable) > parseFloat(balance)) {
                    $('#limit_crosed').show();
                    $(":submit").attr('disabled', true);
                } else {
                    $(":submit").attr('disabled', false);
                    $('#limit_crosed').hide();
                }
                if (sales_type == 'cash') {
                    $('#accounts_area').show();
                    $('#coa_setup_id').attr('required', true);
                    $('#order_type_area').addClass('col-lg-2').removeClass('col-lg-3');
                    $('#invoice_area').addClass('col-lg-2').removeClass('col-lg-3');
                    $('#date_area').addClass('col-lg-2').removeClass('col-lg-3');
                } else {
                    $('#accounts_area').hide();
                    $('#coa_setup_id').attr('required', false);
                    $('#order_type_area').addClass('col-lg-3').removeClass('col-lg-2');
                    $('#invoice_area').addClass('col-lg-3').removeClass('col-lg-2');
                    $('#date_area').addClass('col-lg-3').removeClass('col-lg-2');
                }
                $('.select').select2({
                    allowClear: true,
                });
            });

            $(document).on('change', '#store_id', function(e) {
                $('#tbody tr').remove();
            });

            $(document).on('change', '#vendor_id', function(e) {
                let vendor_id = $(this).val();
                var selected_product_ids = [];
                $('input[name="product_id[]"]').each(function(index, value) {
                    selected_product_ids.push($(value).val());
                });
                let url = "{{ Route('admin.sales.edit', $data->id) }}";
                $.ajax({
                    url: url,
                    type: "POST",
                    data: {
                        _method: 'GET',
                        get_products: 'true',
                        vendor_id: vendor_id,
                        selected_product_ids: selected_product_ids,
                    },
                    success: (response) => {
                        if (response.status == 'success') {
                            $('#product_id option').remove();
                            $('#product_id').append(
                                '<option value="">Select Product</option>');
                            $.each(response.products, function(key, value) {
                                var option =
                                    `<option value="${value.id}">${value.name} (${value.code})</option>`;
                                $('#product_id').append(option);
                            });
                        }
                    }
                });
            });

            $(document).on('change', '#product_id', function(e) {
                var store_id = $("#store_id").val();
                var product_id = $(this).val();
                $('#order_product_id').val('');
                if (product_id == '') {
                    $('#stock').val(0);
                    return;
                }

                if (store_id == '') {
                    Swal.fire({
                        width: "22rem",
                        title: "Error!",
                        text: "Please select a Store",
                        icon: "error",
                        showConfirmButton: false,
                        timer: 1500
                    });
                    $('#stock').val(0);
                    $('#product_id').val('');
                    return;
                }

                let url = "{{ Route('admin.sales.edit', $data->id) }}";
                $.ajax({
                    url: url,
                    type: "POST",
                    data: {
                        _method: 'GET',
                        get_product: 'true',
                        store_id: store_id,
                        product_id: product_id,
                    },
                    success: (response) => {
                        if (response.status == 'success') {
                            $('#quantity').val(response.quantity);
                            $('#stock').val((response.stock).toFixed(2));
                        }
                    }
                });
            });

            $(document).on('change', '#order_type', function(e) {
                var order_type = $('#order_type').val();
                if (order_type == 'pre_order') {
                    $('#vendor_area').hide();
                    $('#order_area').show();
                    $('#pre_product').show();
                    $('#post_product').hide();
                    $('#product_id').val('');
                    $('#product_id').select2();
                } else {
                    $('#vendor_area').show();
                    $('#order_area').hide();
                    $('#pre_product').hide();
                    $('#post_product').show();
                    $('#order_product_id').val('');
                    $('#order_product_id').select2();
                }

                let vendor_id = $('#vendor_id').val();
                var selected_product_ids = [];
                $('input[name="product_id[]"]').each(function(index, value) {
                    selected_product_ids.push($(value).val());
                });
                if (vendor_id != '') {
                    let url = "{{ Route('admin.sales.edit', $data->id) }}";
                    $.ajax({
                        url: url,
                        type: "POST",
                        data: {
                            _method: 'GET',
                            get_products: 'true',
                            vendor_id: vendor_id,
                            selected_product_ids: selected_product_ids,
                        },
                        success: (response) => {
                            if (response.status == 'success') {
                                $('#product_id option').remove();
                                $('#product_id').append(
                                    '<option value="">Select Product</option>');
                                $.each(response.products, function(key, value) {
                                    var option =
                                        `<option value="${value.id}">${value.name} (${value.code})</option>`;
                                    $('#product_id').append(option);
                                });
                            }
                        }
                    });
                }
            });

            $(document).on('change', '#client_id', function(e) {
                var client_id = $(this).val();
                $('#tbody tr.pre_order').remove();
                var total_amount = 0;
                $('.serial').each(function(index, value) {
                    $(value).text(index + 1);
                    var amount = $('input[name="amount[]"]')[index];
                    var amount_val = $(amount).val();
                    total_amount += parseFloat(amount_val);
                });
                $('#total_amount').val(total_amount);
                var discount = $('#discount').val();
                var net_payable = total_amount - parseFloat(discount);
                $('#total_amount').val(total_amount);
                $('#net_payable').val(net_payable);

                let url = "{{ Route('admin.sales.edit', $data->id) }}";
                $.ajax({
                    url: url,
                    type: "POST",
                    data: {
                        _method: 'GET',
                        client_orders: 'true',
                        client_id: client_id,
                    },
                    success: (response) => {
                        if (response.status == 'success') {
                            $('#credit_limit').val(response.balance);
                            var balance = $('#credit_limit').val();
                            var payable = $('#net_payable').val();
                            var sales_type = $('#sales_type').val();
                            if (sales_type == 'credit' && parseFloat(payable) > parseFloat(
                                    balance)) {
                                $('#limit_crosed').show();
                                $(":submit").attr('disabled', true);
                            } else {
                                $(":submit").attr('disabled', false);
                                $('#limit_crosed').hide();
                            }
                            var order_type = $('#order_type').val();
                            if (order_type == 'pre_order') {
                                $('#vendor_area').hide();
                                $('#order_id').empty();
                                $('#order_id').append('<option value=""></option>');
                                $.each(response.orders, function(key, value) {
                                    $('#order_id').append('<option value="' +
                                        value.id + '">' + value.invoice +
                                        '</option>');
                                });
                                $('#order_area').show();
                            }
                        }
                    }
                });
            });

            $(document).on('change', '#order_id', function(e) {
                var order_id = $(this).val();
                var selected_product_ids = [];
                $('input[name="product_id[]"]').each(function(index, value) {
                    selected_product_ids.push($(value).val());
                });
                let url = "{{ Route('admin.sales.edit', $data->id) }}";
                $.ajax({
                    url: url,
                    type: "POST",
                    data: {
                        _method: 'GET',
                        order_products: 'true',
                        order_id: order_id,
                        selected_product_ids: selected_product_ids,
                    },
                    success: (response) => {
                        if (response.status == 'success') {
                            $('#order_product_id').empty();
                            $('#order_product_id').append(
                                '<option value="">Select Product</option>');
                            $.each(response.products, function(key, value) {
                                $('#order_product_id').append('<option value="' +
                                    value.id + '">' + value.product.name +
                                    '</option>');
                            });
                        }
                    }
                });
            });

            $(document).on('change', '#order_product_id', function(e) {
                $('#product_id').val('');
                var store_id = $("#store_id").val();
                if (store_id == '') {
                    Swal.fire({
                        width: "22rem",
                        title: "Error!",
                        text: "Please select a Store",
                        icon: "error",
                        showConfirmButton: false,
                        timer: 1500
                    });
                    $('#order_product_id').val('');
                    return;
                }

                var order_product_id = $(this).val();
                let url = "{{ Route('admin.sales.edit', $data->id) }}";
                $.ajax({
                    url: url,
                    type: "POST",
                    data: {
                        _method: 'GET',
                        get_product: 'true',
                        store_id: store_id,
                        order_product_id: order_product_id,
                    },
                    success: (response) => {
                        if (response.status == 'success') {
                            $('#quantity').val(response.quantity);
                            $('#stock').val((response.stock).toFixed(2));
                        }
                    }
                });
            });

            $(document).on('click', '#add_item', function(e) {
                var product_id = $("#product_id").val();
                var order_product_id = $("#order_product_id").val();
                var store_id = $("#store_id").val();
                var quantity = $("#quantity").val();
                var client_id = $("#client_id").val();
                var existing_key = $("#tbody tr").length;

                if ($('.product_id' + product_id).length) {
                    Swal.fire({
                        width: "22rem",
                        position: 'top-right',
                        toast: true,
                        text: "Product Already Added!",
                        icon: "error",
                        showConfirmButton: false,
                        timer: 1500
                    });
                    return;
                }

                if (product_id == '' && order_product_id == '') {
                    Swal.fire({
                        width: "22rem",
                        position: 'top-right',
                        toast: true,
                        text: "Please select a Product",
                        icon: "error",
                        showConfirmButton: false,
                        timer: 1500
                    });
                    return;
                }
                if (quantity == '' || quantity == '0') {
                    Swal.fire({
                        width: "22rem",
                        position: 'top-right',
                        toast: true,
                        text: "Please take Quantity",
                        icon: "error",
                        showConfirmButton: false,
                        timer: 1500
                    });
                    return;
                }
                if (store_id == '') {
                    Swal.fire({
                        width: "22rem",
                        position: 'top-right',
                        toast: true,
                        text: "Please select a Store",
                        icon: "error",
                        showConfirmButton: false,
                        timer: 1500
                    });
                    return;
                }
                if (client_id == '') {
                    Swal.fire({
                        width: "22rem",
                        position: 'top-right',
                        toast: true,
                        text: "Please select a Client",
                        icon: "error",
                        showConfirmButton: false,
                        timer: 1500
                    });
                    return;
                }

                let qty = $('#quantity').val();
                let url = "{{ Route('admin.sales.edit', $data->id) }}";
                $.ajax({
                    url: url,
                    type: "POST",
                    data: {
                        _method: 'GET',
                        get_stock: 'true',
                        order_product_id: order_product_id,
                        product_id: product_id,
                        store_id: store_id,
                        quantity: quantity,
                        client_id: client_id,
                    },
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
                        if (response.status == 'success') {
                            var tr =
                                `<tr class ="${(response.pre_order_product == 'true' ?
                                    "pre_order" : "") }">
                                    <td class="text-center" width="80">
                                        <b class="serial">${ ( existing_key + 1 )}</b>
                                        <input type="hidden" class="product_id${ response.product.id }" name="product_id[]" value="${ response.product.id }">
                                        <input type="hidden" name="order_product_id[]" value="${ (response.order_product_id ? response.order_product_id : '') }">
                                    </td>
                                    <td>
                                        <div class="custom-control mx-auto custom-checkbox">
                                            <input type="checkbox" class="custom-control-input gift_item" name="gift[]" id="${ response.product.id }" value="${ response.price }">
                                            <label class="custom-control-label" for="${ response.product.id }"></label>
                                        </div>
                                    </td>
                                    <td>${ response.vendor }</td>
                                    <td>${ response.product.code }</td>
                                    <td>${ response.product.name }</td>
                                    <td><input type="number" style="min-width: 100px;" class="form-control rate" placeholder="Rate" name="rate[]" value="${ response.price }"></td>
                                    <td><input type="number" style="min-width: 100px;" class="form-control qty" placeholder="Quantity" step="any" name="qty[]" value="${ response.quantity }"></td>
                                   
                                    <td><input type="text" style="min-width: 100px;" class="form-control unit" placeholder="Unit" readonly value="${ response.unit }"></td>
                                    <td><input type="number" style="min-width: 100px;" class="form-control amount" placeholder="Amount" name="amount[]" readonly value="${ response.amount }"></td>
                                    <td class="text-center"><button type="button" class="btn btn-xs btn-outline-danger remove_item mnw-auto px-2"><i class="far fa-trash-alt"></i></button></td>
                                </tr>`;
                            $('#tbody').append(tr);
                            if (response.pre_order_product == 'true') {
                                $('#order_product_id option[value=' + order_product_id + ']')
                                    .remove();
                                $('#order_product_id').val('');
                            } else {
                                $('#product_id option[value=' + product_id + ']').remove();
                                $('#product_id').val('');
                            }
                            calculate();
                        }
                    }
                });
            });

            function calculate() {
                var total_amount = 0;
                $('.serial').each(function(index, value) {
                    $(value).text(index + 1);
                    var amount = $('input[name="amount[]"]')[index];
                    var amount_val = $(amount).val();
                    total_amount += parseFloat(amount_val);
                });
                $('#total_amount').val(total_amount);
                var discount = $('#discount').val();
                var net_payable = total_amount - parseFloat(discount);
                $('#total_amount').val(total_amount);
                $('#net_payable').val(net_payable);
                var balance = $('#credit_limit').val();
                var payable = $('#net_payable').val();
                var sales_type = $('#sales_type').val();
                if (sales_type == 'credit' && parseFloat(payable) > parseFloat(balance)) {
                    $('#limit_crosed').show();
                    $(":submit").attr('disabled', true);
                } else {
                    $(":submit").attr('disabled', false);
                    $('#limit_crosed').hide();
                }
            }

            $(document).on('click', '.remove_item', function(e) {
                $(this).closest('tr').remove();
                calculate();

                let vendor_id = $('#vendor_id').val();
                var selected_product_ids = [];
                $('input[name="product_id[]"]').each(function(index, value) {
                    selected_product_ids.push($(value).val());
                });
                let url = "{{ Route('admin.sales.edit', $data->id) }}";
                $.ajax({
                    url: url,
                    type: "POST",
                    data: {
                        _method: 'GET',
                        get_products: 'true',
                        vendor_id: vendor_id,
                        selected_product_ids: selected_product_ids,
                    },
                    success: (response) => {
                        if (response.status == 'success') {
                            $('#product_id option').remove();
                            $('#product_id').append(
                                '<option value="">Select Product</option>');
                            $.each(response.products, function(key, value) {
                                var option =
                                    `<option value="${value.id}">${value.name} (${value.code})</option>`;
                                $('#product_id').append(option);
                            });
                        }
                    }
                });
            });

            $(document).on('change', 'input[name="discount_type"]', function(e) {
                if ($(this).val() == 'percentage') {
                    $('#percentage_input').show();
                } else {
                    $('#percentage_input').hide();
                }
                var balance = $('#credit_limit').val();
                var payable = $('#net_payable').val();
                var sales_type = $('#sales_type').val();
                if (sales_type == 'credit' && parseFloat(payable) > parseFloat(balance)) {
                    $('#limit_crosed').show();
                    $(":submit").attr('disabled', true);
                } else {
                    $(":submit").attr('disabled', false);
                    $('#limit_crosed').hide();
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

                var balance = $('#credit_limit').val();
                var payable = $('#net_payable').val();
                var sales_type = $('#sales_type').val();
                if (sales_type == 'credit' && parseFloat(payable) > parseFloat(balance)) {
                    $('#limit_crosed').show();
                    $(":submit").attr('disabled', true);
                } else {
                    $(":submit").attr('disabled', false);
                    $('#limit_crosed').hide();
                }
            });

            $(document).on('wheel keyup change', '#discount', function(e) {
                var total_amount = parseFloat($('#total_amount').val());
                var discount = parseFloat($(this).val());
                $('#net_payable').val(total_amount - discount);
                var balance = $('#credit_limit').val();
                var payable = $('#net_payable').val();
                var sales_type = $('#sales_type').val();
                if (sales_type == 'credit' && parseFloat(payable) > parseFloat(balance)) {
                    $('#limit_crosed').show();
                    $(":submit").attr('disabled', true);
                } else {
                    $(":submit").attr('disabled', false);
                    $('#limit_crosed').hide();
                }
            });

            $(document).on('change', '.gift_item', function(e) {
                if ($(this).is(':checked')) {
                    $(this).closest('tr').find('.rate').val(0);
                    $(this).closest('tr').find('.amount').val(0);
                } else {
                    var rate = +$(this).val();
                    var qty = +$(this).closest('tr').find('.qty').val();
                    $(this).closest('tr').find('.rate').val(rate);
                    $(this).closest('tr').find('.amount').val(rate * qty);
                }
                calculate();
            });           
            
            $(document).on('wheel keyup change', '.rate,.qty', function(e) {
                var rate = +$(this).closest('tr').find('.rate').val();
                var qty = +$(this).closest('tr').find('.qty').val();
                $(this).closest('tr').find('.amount').val(rate * qty);
                calculate();
            });
        });

        //Delivery input validation
        $(document).on('input', '.delivery', function () {

            let delivery = parseFloat($(this).val()) || 0;
            let qty = parseFloat($(this).closest('tr').find('.qty').val()) || 0;

            if (delivery > qty) {
                alert('Delivery Qty cannot be greater than Quantity.');
                $(this).val(qty);
            }
        });


    </script>
@endpush
