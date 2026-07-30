@extends('layouts.admin.edit_app')

@section('content')
    <div class="row g-3 align-items-center">
        <div class="col-lg-4 col-sm-6">
            <label for="delivery_agent_id" class="form-label"><b>Agent <span class="text-danger">*</span></b></label>
            <select name="delivery_agent_id" id="delivery_agent_id" class="form-select select"
                data-placeholder="Select Delivery Agent" required>
                <option value=""></option>
                @foreach ($agents as $item)
                    <option value="{{ $item->id }}"
                        {{ old('delivery_agent_id', $data->delivery_agent_id) == $item->id ? 'selected' : '' }}>
                        {{ $item->name }}</option>
                @endforeach
            </select>
        </div>
        {{-- <div class="col-lg-4 col-sm-6">
            <label for="name" class="form-label"><b>Package Name <span class="text-danger">*</span></b></label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $data->name) }}"
                placeholder="Package Name" required>
        </div> --}}
        <div class="col-lg-4 col-sm-6">
            <label for="base_rate" class="form-label"><b>Base Rate <span class="text-danger">*</span></b></label>
            <input type="number" class="form-control" id="base_rate" name="base_rate"
                value="{{ old('base_rate', $data->base_rate) }}" placeholder="Base Rate" required>
        </div>
        <div class="col-lg-4 col-sm-6">
            <label for="base_weight" class="form-label"><b>Base Weight (KG)<span class="text-danger">*</span></b></label>
            <input type="number" class="form-control" id="base_weight" name="base_weight"
                value="{{ old('base_weight', $data->base_weight) }}" placeholder="Base Weight" required>
        </div>
        <div class="col-lg-4 col-sm-6">
            <label for="additional_rate" class="form-label"><b>Additional Rate (Per KG)<span
                        class="text-danger">*</span></b></label>
            <input type="number" class="form-control" id="additional_rate" name="additional_rate"
                value="{{ old('additional_rate', $data->additional_rate) }}" placeholder="Additional Rate" required>
        </div>
        <div class="col-lg-4 col-sm-6">
            <label for="return_charge_type" class="form-label"><b>Return Charge Type<span
                        class="text-danger">*</span></b></label>
            <select name="return_charge_type" id="return_charge_type" class="form-select select"
                data-placeholder="Select Return Charge Type" required>
                <option value="Fixed Charge"
                    {{ old('return_charge_type', $data->return_charge_type) == 'Fixed Charge' ? 'selected' : '' }}>Fixed
                    Charge</option>
                <option value="Delivery Charge"
                    {{ old('return_charge_type', $data->return_charge_type) == 'Delivery Charge' ? 'selected' : '' }}>As
                    Like Delivery Charge</option>
            </select>
        </div>
        <div class="col-lg-4 col-sm-6">
            <label for="return_charge" class="form-label"><b>Return Charge<span class="text-danger">*</span></b></label>
            <input type="number" class="form-control" id="return_charge" name="return_charge"
                value="{{ old('return_charge', $data->return_charge) }}" placeholder="Return Charge"
                {{ old('return_charge_type', $data->return_charge_type) == 'Delivery Charge' ? 'readonly' : 'required' }}>
        </div>
        <div class="col-12">
            <div class="custom-control custom-checkbox d-inline-block me-lg-5 me-sm-4 me-3 mt-2">
                <input type="checkbox" class="custom-control-input multi_checkbox" id="inside_dhaka" name="inside_dhaka"
                    value="1" {{ old('inside_dhaka', $data->inside_dhaka) == '1' ? 'checked' : '' }}>
                <label for="inside_dhaka" class="custom-control-label ps-1"><b>Inside Dhaka</b></label>
            </div>
            <div class="custom-control custom-checkbox d-inline-block me-lg-5 me-sm-4 me-3 mt-2">
                <input type="checkbox" class="custom-control-input multi_checkbox" id="subarea_dhaka" name="subarea_dhaka"
                    value="1" {{ old('subarea_dhaka', $data->subarea_dhaka) == '1' ? 'checked' : '' }}>
                <label for="subarea_dhaka" class="custom-control-label ps-1"><b>Subarea Dhaka</b></label>
            </div>
            <div class="custom-control custom-checkbox d-inline-block me-lg-5 me-sm-4 me-3 mt-2">
                <input type="checkbox" class="custom-control-input multi_checkbox" id="inside_chittagong"
                    name="inside_chittagong" value="1"
                    {{ old('inside_chittagong', $data->inside_chittagong) == '1' ? 'checked' : '' }}>
                <label for="inside_chittagong" class="custom-control-label ps-1"><b>Inside Chittagong</b></label>
            </div>
            <div class="custom-control custom-checkbox d-inline-block me-lg-5 me-sm-4 me-3 mt-2">
                <input type="checkbox" class="custom-control-input multi_checkbox" id="subarea_chittagong"
                    name="subarea_chittagong" value="1"
                    {{ old('subarea_chittagong', $data->subarea_chittagong) == '1' ? 'checked' : '' }}>
                <label for="subarea_chittagong" class="custom-control-label ps-1"><b>Subarea Chittagong</b></label>
            </div>
            <div class="custom-control custom-checkbox d-inline-block me-lg-5 me-sm-4 me-3 mt-2">
                <input type="checkbox" class="custom-control-input multi_checkbox" id="district_level" name="district_level"
                    value="1" {{ old('district_level', $data->district_level) == '1' ? 'checked' : '' }}>
                <label for="district_level" class="custom-control-label ps-1"><b>District Level</b></label>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script type="text/javascript">
        $(document).ready(function() {
            $(document).on('change', '#return_charge_type', function() {
                var type = $(this).val();
                if (type == 'Delivery Charge') {
                    $('#return_charge').prop('readonly', true);
                    $('#return_charge').prop('required', false);
                    $('#return_charge').val('');
                } else {
                    $('#return_charge').prop('readonly', false);
                    $('#return_charge').prop('required', true);
                }
            });
        });
    </script>
@endpush
