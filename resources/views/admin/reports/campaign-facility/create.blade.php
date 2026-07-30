@extends('layouts.admin.app')

@section('content')
    <div class="row g-3">
        <div class="col-12">
            <form action="{{ Route('admin.campaign-facility.store', request()->route('id')) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <div class="card">
                    <div class="card-header pe-2 py-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="h6 mb-0 text-uppercase">{{ @$title }}</h6>
                            <a href="{{ Route('admin.campaign-facility.index', request()->route('id')) }}"
                                class="btn btn-primary btn-sm">Go
                                Back</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="title" class="form-label"><b>Title</b></label>
                                <input type="text" name="title" id="title" class="form-control" placeholder="Title"
                                    value="{{ old('title') }}" required>
                            </div>
                            <div class="col-12">
                                <label for="description" class="form-label"><b>Description</b></label>
                                <textarea name="description" id="description" class="form-control" cols="30" rows="3"
                                    placeholder="Description" required>{{ old('description') }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-end pe-2 py-2">
                        <button type="submit" class="btn btn-primary btn-sm">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
