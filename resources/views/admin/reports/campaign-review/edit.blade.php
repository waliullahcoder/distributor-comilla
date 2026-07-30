@extends('layouts.admin.app')

@section('content')
    <div class="row g-3">
        <div class="col-12">
            <form action="{{ Route('admin.campaign-review.update', $data->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card">
                    <div class="card-header pe-2 py-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="h6 mb-0 text-uppercase">{{ @$title }}</h6>
                            <a href="{{ Route('admin.campaign-review.index', $data->campaign_id) }}" class="btn btn-primary btn-sm">Go
                                Back</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <label for="name" class="form-label"><b>Name</b></label>
                                <input type="text" name="name" id="name" class="form-control"
                                    placeholder="Author Name" value="{{ $data->name }}">
                            </div>
                            <div class="col-sm-6">
                                <label for="image" class="form-label"><b>Image</b></label>
                                <input type="file" name="image" id="image" class="form-control" accept="image/*">
                                @if (file_exists($data->image))
                                    <img src="{{ asset($data->image) }}" alt="Review Image" height="50" class="mt-2">
                                @endif
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
