@extends('layouts.admin.edit_app')

@section('content')
    <div class="row g-3">
        @if (Auth::user()->hasRole('Software Admin'))
            <div class="col-sm-6">
                <label for="company_id" class="form-label"><b>Company Name <span class="text-danger">*</span></b></label>
                <select name="company_id" id="company_id" class="select form-select" data-placeholder="Select Company" required>
                    <option value=""></option>
                    @foreach ($companies as $company)
                        <option value="{{ $company->id }}" {{ $data->company_id == $company->id ? 'selected' : '' }}>
                            {{ $company->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        @endif
        <div class="col-sm-6">
            <label for="reference_by" class="form-label"><b>Reference By</b></label>
            <select name="reference_by" id="reference_by" class="select form-select" data-placeholder="Select Reference">
                <option value=""></option>
                @foreach ($staffs as $staff)
                    <option value="{{ $staff->id }}" {{ $data->reference_by == $staff->id ? 'selected' : '' }}>
                        {{ $staff->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-sm-6">
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
       
        <div class="col-sm-6">
            <label for="client_category_id" class="form-label"><b>Client Type</b></label>
            <select name="client_category_id" id="client_category_id" class="select form-select"
                data-placeholder="Select Client Type">
                <option value=""></option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}"
                        {{ $data->client_category_id == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-sm-6">
            <label for="code" class="form-label"><b>Client Code <span class="text-danger">*</span></b></label>
            <input type="text" class="form-control" id="code" name="code" value="{{ $data->code }}" required
                placeholder="Client Code">
        </div>
        <div class="col-sm-6">
            <label for="name" class="form-label"><b>Client Name <span class="text-danger">*</span></b></label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $data->name }}"
                placeholder="Client Name" required>
        </div>
        <div class="col-sm-6">
            <label for="contact_person" class="form-label"><b>Contact Person</b></label>
            <input type="text" class="form-control" id="contact_person" name="contact_person"
                value="{{ $data->contact_person }}" placeholder="Contact Person">
        </div>
        <div class="col-sm-6">
            <label for="email" class="form-label"><b>Email</b></label>
            <input type="email" class="form-control" id="email" name="email" value="{{ $data->email }}"
                placeholder="Contact Email">
        </div>
        <div class="col-sm-6">
            <label for="phone" class="form-label"><b>Phone Number</b></label>
            <input type="text" class="form-control" id="phone" name="phone" value="{{ $data->phone }}"
                placeholder="Contact Number">
        </div>
        <div class="col-lg-3 col-sm-6">
            <label for="address" class="form-label"><b>Address</b></label>
            <input type="text" class="form-control" id="address" name="address" value="{{ $data->address }}"
                placeholder="Contact Address">
        </div>
        <div class="col-lg-3 col-sm-6">
            <label for="credit_limit" class="form-label"><b>Credit Limit</b></label>
            <input type="number" class="form-control" id="credit_limit" name="credit_limit"
                value="{{ $data->credit_limit }}" placeholder="Credit Limit">
        </div>
        <div class="col-lg-3 col-sm-6">
            <label for="bin_no" class="form-label"><b>BIN No.</b></label>
            <input type="number" class="form-control" id="bin_no" name="bin_no" value="{{ $data->bin_no }}"
                placeholder="BIN NO.">
        </div>
        <div class="col-lg-3 col-sm-6">
            <label for="discount" class="form-label"><b>Default Discount</b></label>
            <input type="number" class="form-control" id="discount" name="discount" placeholder="Default Discount"
                value="{{ $data->discount }}" max="100" step="0.5">
        </div>
        {{-- 
        <div class="col-md-3 col-sm-6">
            <label for="coa_setup_id" class="form-label"><b>COA Name</b></label>
            <select name="coa_setup_id" id="coa_setup_id" class="select form-select" data-placeholder="Select COA Name">
                <option value=""></option>
                @foreach ($coas as $coa)
                    <option value="{{ $coa->id }}" {{ $data->coa_setup_id == $coa->id ? 'selected' : '' }}>
                        {{ $coa->head_name }} - {{ $coa->head_code }}
                    </option>
                @endforeach
            </select>
        </div>
        --}}
        <div class="col-md-3 col-sm-6">
            <div class="row g-3 pt-2">
                <div class="col-6">
                    <label class="form-label text-white"> IS Chain</label>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="is_vat" name="is_vat"
                            {{ $data->is_vat == 1 ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_vat"><b>VAT Applicable</b></label>
                    </div>
                </div>
                <div class="col-6">
                    <label class="form-label text-white"> IS Chain</label>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="is_chain" name="is_chain"
                            {{ !is_null($data->chain_client_id) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_chain"><b>Is Chain Shop</b></label>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6" id="chain_client"
            style="display: {{ !is_null($data->chain_client_id) ? 'block' : 'none' }};">
            <label for="chain_client_id" class="form-label"><b>Mother Company</b></label>
            <select name="chain_client_id" id="chain_client_id" class="select form-select"
                data-placeholder="Select Mother Company">
                <option value=""></option>
                @foreach ($chain_clients as $chain_client)
                    <option value="{{ $chain_client->id }}"
                        {{ $data->chain_client_id == $chain_client->id ? 'selected' : '' }}>
                        {{ $chain_client->name }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
@endsection

@push('js')
    <script type="text/javascript">
        $(document).ready(function() {
            $(document).on('change', '#is_chain', function() {
                if ($(this).is(':checked')) {
                    $('#chain_client').show();
                } else {
                    $('#chain_client').hide();
                }
            });

            $(document).on('change', '#area_id', function(e) {
                let area_id = $(this).val();
                let url = "{{ Route('admin.client.edit', $data->id) }}";
                $.ajax({
                    url: url,
                    type: "POST",
                    data: {
                        _method: 'GET',
                        area_id: area_id
                    },
                    success: (response) => {
                        if (response.status == 'success') {
                            $('#territory_id option').remove();
                            $('#territory_id').append('<option value=""></option>');
                            $.each(response.territories, function(key, value) {
                                var option = '<option value="' + value.id + '">' + value
                                    .name + '</option>';
                                $('#territory_id').append(option);
                            });
                        }
                    }
                });
            });
        })
    </script>
@endpush
