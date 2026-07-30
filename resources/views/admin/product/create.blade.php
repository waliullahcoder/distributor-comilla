@extends('layouts.admin.create_app')

@section('content')
    <div class="row g-4">
        <div class="col-lg-6">
            <div class="row g-3">
                <div class="col-sm-6">
                    <label for="name" class="form-label"><b>Product Name <span class="text-danger">*</span></b></label>
                    <input type="text" id="name" class="form-control" name="name" required
                        placeholder="Product Name." value="{{ old('name') }}">
                </div>
                <div class="col-sm-6">
                    <label for="thumbnail" class="form-label"><b>Product thumbnail <span
                                class="text-danger">(600x600)</span> <span class="text-danger">*</span></b></label>
                    <input type="file" name="thumbnail" id="thumbnail" class="form-control" required accept="image/*">
                    <div id="showThamb-wrapper" style="display: none;">
                        <img class="mt-2" src="" alt="" id="showThamb" height="60">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="row g-3">
                <div class="col-sm-6">
                    <label class="form-label" for="parent_category"><b>Category <span
                                class="text-danger">*</span></b></label>
                    <select name="category_id" id="parent_category" class="select form-select" required
                        data-placeholder="Select Category..">
                        <option value=""></option>
                        @foreach ($categories as $key => $category)
                            <option value="{{ $category->id }}"
                                {{ old('category_id') && old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-6">
                    <label class="form-label" for="code"><b>Code</b></label>
                    <input type="text" name="code" id="code" class="form-control" placeholder="Code"
                        value="{{ old('code') }}">
                </div>
            </div>
        </div>
        <div class="col-12" id="vendors_area">
            <label class="form-label" for="vendors"><b>Select Vendor <span
                        class="text-danger">*</span></b></label>
            <select name="vendor_id[]" id="vendors" class="select form-select" data-placeholder="Select Vendor.."
                multiple required>
                <option value=""></option>
                @foreach ($vendors as $vendor)
                    <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                @endforeach
            </select>
            <div id="vendor_price_area">
                <table class="table table-bordered align-middle mb-0 mt-4" id="vendor_price_table">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th>Purchase Price</th>
                            <th>Dealer Price</th>
                            <th>Retail Price</th>
                            <th>Retail Discount</th>
                            <th>CTN Size</th>
                        </tr>
                    </thead>
                    <tbody id="vendor_prices">
                        <tr>
                            <td>
                                <input type="number" class="form-control" name="lifting_price" min="0" value="0" step="any" required>
                            </td>
                            <td>
                                <input type="number" class="form-control" name="sale_price" min="0" value="0" step="any" required>
                            </td>
                            <td>
                                <input type="number" class="form-control" name="online_price" min="0" value="0" step="any" required>
                            </td>
                            <td>
                                <input type="number" class="form-control" name="discount_tk" min="0" value="0" step="any" required>
                            </td>
                            <td>
                                <input type="number" class="form-control" name="ctn_size" min="0" value="0" step="any" required>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-12" id="attribute_area">
            <label class="form-label" for="attribute_id"><b>Measurement Unit <span
                        class="text-danger">*</span></b></label>
            <select name="attribute_id" id="attribute_id" class="form-control select" required
                data-placeholder="Choose Attributes">
                <option value=""></option>
                @foreach ($attributes->where('type', 'Consumer') as $key => $attribute)
                    <option value="{{ $attribute->id }}">{{ $attribute->name }}</option>
                @endforeach
            </select>
            <div class="pt-3 customer_choice_options" id="customer_choice_options" style="display: none;">
            </div>
            <div class="pt-3">
                <div class="sku_combination" id="sku_combination"></div>
            </div>
        </div>

        <div class="col-12">
            <label for="short_description" class="form-label"><b>Short Description</b></label>
            <textarea name="short_description" id="short_description" class="short_description" cols="30" rows="10">{!! old('short_description') !!}</textarea>
        </div>
        <div class="col-12">
            <label for="description" class="form-label"><b>Long Description</b></label>
            <textarea name="description" id="description" class="description" cols="30" rows="10">{!! old('description') !!}</textarea>
        </div>
    </div>

    <div id="fashion_attributes" class="d-none">
        @foreach ($attributes->where('type', 'Fashion') as $key => $attribute)
            <option value="{{ $attribute->id }}">{{ $attribute->name }}</option>
        @endforeach
    </div>
    <div id="consumer_attributes" class="d-none">
        @foreach ($attributes->where('type', 'Consumer') as $key => $attribute)
            <option value="{{ $attribute->id }}">{{ $attribute->name }}</option>
        @endforeach
    </div>
@endsection

@push('js')
    <script type="text/javascript">
        $(document).ready(function() {
            // Thumbail Image Preview
            function readFile(input) {
                let reader = new FileReader();
                reader.onload = (e) => {
                    $('#showThamb').attr('src', e.target.result);
                    $('#showThamb-wrapper').show();
                }
                reader.readAsDataURL(input.files[0]);
            }
            $(document).on('change', '#thumbnail', function(event) {
                readFile(this);
            });

            // Manage Product Prices
            $('#regular_price').on('wheel keyup change', function(event) {
                var regular_price = $("#regular_price").val();
                var discount = $("#discount").val();
                var sale_price = regular_price - (regular_price * (discount / 100));
                $("#sale_price").val(sale_price);
            });
            $('#discount').on('wheel keyup change', function(event) {
                var regular_price = $("#regular_price").val();
                var discount = $("#discount").val();
                var dis_price = regular_price * (discount / 100);
                var sale_price = regular_price - (regular_price * (discount / 100));
                $("#discount_tk").val(Math.ceil(dis_price));
                $("#sale_price").val(Math.ceil(sale_price));
            });
            $('#discount_tk').on('wheel keyup change', function(event) {
                var regular_price = $("#regular_price").val();
                var discount_tk = $("#discount_tk").val();
                var sale_price = regular_price - discount_tk;
                var discount = (discount_tk * 100) / regular_price;
                $("#discount").val(Math.ceil(discount));
                $("#sale_price").val(sale_price);
            });
            $('#sale_price').on('wheel keyup change', function(event) {
                var regular_price = $("#regular_price").val();
                var sale_price = $("#sale_price").val();
                var discount_tk = $("#discount_tk").val();
                var diff = regular_price - sale_price;
                var discount = (diff / regular_price) * 100;
                if (regular_price == '') {
                    discount = 0;
                    diff = 0;
                    // regular_price = sale_price;
                }
                $("#discount").val(Math.ceil(discount));
                $("#discount_tk").val(diff);
                // $("#regular_price").val(regular_price);
            });

            // More Images Preview
            $(document).on('change', '#more_images', function() {
                $('#preview_image').html('');
                if (window.File && window.FileReader && window.FileList && window.Blob) {
                    var data = $(this)[0].files;
                    $.each(data, function(index, file) {
                        if (/(\.|\/)(gif|jpe?g|png)$/i.test(file.type)) {
                            var fRead = new FileReader();
                            fRead.onload = (function(file) {
                                return function(e) {
                                    var img = $('<img/>').addClass('thumb').attr('src',
                                        e.target.result).height(50);
                                    $('#preview_image').append(img);
                                };
                            })(file);
                            fRead.readAsDataURL(file);
                        }
                    });
                    $('#preview_image').show();
                } else {
                    alert("Your browser doesn't support File API!");
                }
            });

            // Append Child Categories Ajax
            $(document).on('change', '#parent_category', function(event) {
                let id = $(this).val();
                let url = "{{ Route('admin.product.create') }}";
                $.ajax({
                    url: url,
                    type: "POST",
                    data: {
                        _method: 'GET',
                        id: id
                    },
                    success: (response) => {
                        if (response.status == 'success') {
                            $('#subchild_category').empty();
                            $('#child_category').empty();
                            $('#child_category').append(
                                '<option value="" selected disabled> Choose.. </option>');
                            $.each(response.child_categories, function(key, value) {
                                $('#child_category').append('<option value="' + value
                                    .id + '">' + value.name + '</option>');
                            });
                            var product_type = $('#product_type').val();
                            if (product_type == 'Consumer') {
                                $('#vendors').empty();
                                $('#vendors').append(
                                    '<option value=""> Choose Vendor.. </option>');
                                $('#vendors').append(response.html);
                                $('#vendor_price_table').show();
                            }
                        }
                    }
                });
            });

            // Append SubChild Categories Ajax
            $(document).on('change', '#child_category', function(event) {
                event.preventDefault();
                let id = $(this).val();
                let url = "{{ Route('admin.product.create') }}";
                $.ajax({
                    url: url,
                    type: "POST",
                    data: {
                        _method: 'GET',
                        id: id
                    },
                    success: (response) => {
                        if (response.status == 'success') {
                            $('#subchild_category').empty();
                            $('#subchild_category').append(
                                '<option value="" disabled selected> Choose.. </option>');
                            $.each(response.child_categories, function(key, value) {
                                $('#subchild_category').append('<option value="' + value
                                    .id + '">' + value.name + '</option>');
                            });
                        }
                    }
                });
            });

            $(document).on('change', '#vendors', function(e) {
                var vendors = $(this).val();
                $('#vendor_prices').show();
            });

            $(document).on('click', '.vendor-remove', function(e) {
                e.preventDefault();
                let vendor_id = $(this).data('id');
                let vendor_name = $(this).data('name');
                $('#vendors').append('<option value="' + vendor_id + '"> ' + vendor_name + ' </option>');
                $(this).closest('tr').remove();
            });

            $(document).on('change', '#product_type', function(e) {
                e.preventDefault();
                let type = $(this).val();
                var fashion_attr = $('#fashion_attributes').html();
                var consumer_attr = $('#consumer_attributes').html();
                if (type == 'Fashion') {
                    $('#attribute_id').html('');
                    $('#attribute_id').append(fashion_attr);
                    $('#attribute_id').attr('multiple', true);
                    $('#vendor_price_area').hide();
                    $('#vendor_prices').html('');
                    $('#vendors_area').hide();
                    calculateAttr();
                    update_sku();
                } else {
                    $('#attribute_id').html('');
                    $('#vendors_area').show();
                    $('#attribute_id').append(consumer_attr);
                    $('#attribute_id').attr('multiple', false);
                }
                $('.select').select2({
                    allowClear: true,
                });
            });

            $(document).on('change', '#attribute_id', function() {
                var product_type = $('#product_type').val();
                if (product_type == 'Fashion') {
                    calculateAttr();
                    update_sku();
                }
            });

            function calculateAttr() {
                var elements = '';
                $.each($("#attribute_id option:selected"), function(j, attribute) {
                    if (j == 0) {
                        elements += '.attribute_parent';
                    }
                    elements += ':not(.attribute_parent_' + $(attribute).val() + ')';
                    flag = false;
                    $('input[name="choice_no[]"]').each(function(i, choice_no) {
                        if ($(attribute).val() == $(choice_no).val()) {
                            flag = true;
                        }
                    });
                    if (!flag) {
                        add_more_customer_choice_option($(attribute).val(), $(attribute).text());
                    }
                });
                if (elements == '') {
                    $('.attribute_parent').remove();
                }
                $(elements).remove();
            };

            function add_more_customer_choice_option(i, name) {
                let url = "{{ Route('admin.product.create') }}";
                $.ajax({
                    type: "POST",
                    url: url,
                    data: {
                        _method: 'GET',
                        choices: true,
                        attribute_id: i,
                    },
                    success: function(data) {
                        var obj = JSON.parse(data);
                        $('#customer_choice_options').append(
                            `<div class="mb-3 row g-3 attribute_parent attribute_parent_${i}">
                                <div class="col-md-3">
                                    <input type="hidden" name="choice_no[]" value="${i}">
                                    <input type="text" class="form-control" name="choice[]" value="${name}" placeholder="Choice Title" readonly>
                                </div>
                                <div class="col-md-9">
                                    <select class="form-control select attribute_choice" data-placeholder="Select ${name}" name="choice_options_${i}[]" multiple>
                                        ${obj}
                                    </select>
                                </div>
                            </div>`);
                        $('#customer_choice_options').show();
                        $('.select').select2({
                            allowClear: true,
                        });
                    }
                });
            }

            $(document).on("change", ".attribute_choice", function() {
                update_sku();
            });

            function update_sku() {
                let url = "{{ Route('admin.product.create') }}";
                var formData = $('#store_form').serializeArray();
                // Push a new key-value pair to the serialized data
                formData.push({
                    name: '_method',
                    value: 'GET',
                }, {
                    name: 'get_sku',
                    value: true,
                }, );
                var serializedData = $.param(formData);
                $.ajax({
                    type: "POST",
                    url: url,
                    data: serializedData,
                    success: function(data) {
                        $('#sku_combination').html(data);
                    }
                });
            }
        });
    </script>
@endpush
