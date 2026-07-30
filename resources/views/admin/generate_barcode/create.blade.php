@extends('layouts.admin.create_app')

@section('content')
    <div class="row g-3">
        <div class="col-md-4 col-sm-6">
            <label for="product_code" class="form-label"><b>Products</b></label>
            <select id="product_code" class="select form-select" data-placeholder="Select Product">
                <option value="">Select Product</option>
                @foreach ($products as $product)
                    <option value="{{ $product->code }}" data-name="{{ $product->name }}">{{ $product->name }} -
                        {{ $product->code }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4 col-sm-6">
            <label for="quantity" class="form-label"><b>Quantity</b></label>
            <input type="number" class="form-control" id="quantity" name="quantity" step="1" placeholder="Quantity">
        </div>
        <div class="col-md-4 col-sm-6">
            <label class="form-label text-white"><b>Add</b></label>
            <button type="button" class="btn btn-xs btn-primary w-100 px-2 py-2" id="add_item">Add Item</button>
        </div>
        <div class="col-12">
            <div class="col-12">
                <table class="table table-bordered table-striped target-table align-middle mb-0">
                    <thead class="bg-primary border-primary text-white">
                        <tr>
                            <th class="text-center" width="80">SL#</th>
                            <th class="text-nowrap">Product Name</th>
                            <th class="text-nowrap">Product Code</th>
                            <th>Quantity</th>
                            <th class="text-center" width="50"><i class="far fa-trash-alt"></i></th>
                        </tr>
                    </thead>
                    <tbody id="tbody">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script type="text/javascript">
        $(document).ready(function() {
            $(document).on('click', '#add_item', function(e) {
                let product_code = $('#product_code').val();
                let quantity = $('#quantity').val();
                if (product_code == '') {
                    Swal.fire({
                        width: "22rem",
                        position: 'top-right',
                        toast: true,
                        text: "Please Select a product First!",
                        icon: "error",
                        showConfirmButton: false,
                        timer: 1500
                    });
                    return false;
                }
                if (quantity == '' || quantity == 0) {
                    Swal.fire({
                        width: "22rem",
                        position: 'top-right',
                        toast: true,
                        text: "Please take some quantity!",
                        icon: "error",
                        showConfirmButton: false,
                        timer: 1500
                    });
                    return false;
                }

                let product_name = $('#product_code option[value=' + product_code + ']').data('name');
                var existing_key = $("#tbody tr").length;
                $('#product_code option[value=' + product_code + ']').remove();
                var tr =
                    `<tr id="${product_code}">
                        <td class="text-center" width="80">
                            <b class="serial">${(existing_key+1)}</b>
                        </td>
                        <td><input type="text" class="form-control" placeholder="Product Name" name="product_name[]" readonly value="${product_name}"></td>
                        <td><input type="text" class="form-control" placeholder="Product Code" name="product_code[]" readonly value="${product_code}"></td>
                        <td><input type="number" class="form-control" placeholder="Quantity" name="quantity[]" value="${quantity}"></td>
                        <td class="text-center"><button type="button" class="btn btn-xs btn-outline-danger remove_item mnw-auto px-2" data-name="${product_name}" data-code="${product_code}"><i class="far fa-trash-alt"></i></button></td>
                    </tr>`;
                $('#tbody').append(tr);
            });

            $(document).on('click', '.remove_item', function(e) {
                let product_code = $(this).data('code');
                let product_name = $(this).data('name');
                var option =
                    `<option value="${product_code}" data-name="${product_name}">${product_name} - ${product_code}</option>`;
                $('#product_code').append(option);
                $('#' + product_code).remove();
            });
        });
    </script>
@endpush
