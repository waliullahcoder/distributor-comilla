@extends('layouts.admin.create_app')

@section('content')
    <div class="row g-3">
        <div class="col-md-4 col-sm-6">
            <label for="name" class="form-label"><b>Agent Name <span class="text-danger">*</span></b></label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}"
                placeholder="Agent Name" required>
        </div>
        <div class="col-md-4 col-sm-6">
            <label for="code" class="form-label"><b>Code</b></label>
            <input type="text" class="form-control" id="code" name="code" value="{{ old('code') }}"
                placeholder="Code">
        </div>
        <div class="col-md-4 col-sm-6">
            <label for="phone" class="form-label"><b>Contact Number</b></label>
            <input type="number" class="form-control" id="phone" name="phone" value="{{ old('phone') }}"
                placeholder="Contact Number">
        </div>
    </div>
@endsection
