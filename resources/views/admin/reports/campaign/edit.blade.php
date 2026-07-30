@extends('layouts.admin.edit_app')

@section('content')
    <div class="row g-3">
        <div class="col-md-4 col-sm-6">
            <label for="name" class="form-label"><b>Name</b></label>
            <input type="text" name="name" id="name" class="form-control" placeholder="Campaign Name"
                value="{{ $data->name }}" required>
        </div>
        <div class="col-md-4 col-sm-6">
            <label for="phone" class="form-label"><b>Hepline</b></label>
            <input type="text" name="phone" id="phone" class="form-control" placeholder="Hepline"
                value="{{ $data->phone }}" required>
        </div>
        <div class="col-md-4 col-sm-6">
            <label for="order_package_id" class="form-label"><b>Packages</b></label>
            <select name="order_package_id[]" id="order_package_id" class="select form-select"
                data-placeholder="Select Packages" multiple>
                <option value=""></option>
                @foreach ($packages as $item)
                    <option value="{{ $item->id }}"
                        {{ in_array($item->id, $data->packages->pluck('order_package_id')->toArray()) ? 'selected' : '' }}>
                        {{ $item->name }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
@endsection
