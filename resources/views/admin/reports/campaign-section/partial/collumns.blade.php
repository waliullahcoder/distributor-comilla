@if (@$type == 'list_image')
    <div class="row g-3">
        <div class="col-sm-6">
            <label for="list" class="form-label"><b>List <span class="text-danger">*</span></b></label>
            <input type="text" class="form-control" id="list" name="list[]"
                value="{{ old('list') ?? implode('`', explode('|', @$data->list)) }}" placeholder="List" required>
        </div>
        <div class="col-sm-6">
            <label for="image" class="form-label"><b>Image {!! !file_exists(@$data->image) ? '<span class="text-danger">*</span>' : '' !!}</b></label>
            <input type="file" class="form-control" id="image" name="image" accept="image/*"
                {{ !file_exists(@$data->image) ? 'required' : '' }}>
            @if (file_exists(@$data->image))
                <img src="{{ asset(@$data->image) }}" alt="" class="mt-2" height="50">
            @endif
        </div>
    </div>
@elseif (@$type == 'image_description' || @$type == 'description_image')
    <div class="row g-3">
        <div class="col-12">
            <label for="image" class="form-label"><b>Image {!! !file_exists(@$data->image) ? '<span class="text-danger">*</span>' : '' !!}</b></label>
            <input type="file" class="form-control" id="image" name="image" accept="image/*"
                {{ !file_exists(@$data->image) ? 'required' : '' }}>
            @if (file_exists(@$data->image))
                <img src="{{ asset(@$data->image) }}" alt="" class="mt-2" height="50">
            @endif
        </div>
        <div class="col-12">
            <label for="description" class="form-label"><b>Description</b></label>
            <textarea name="description" id="description" cols="30" rows="10" class="form-control description"
                placeholder="Description">{!! old('description') ?? @$data->description !!}</textarea>
        </div>
    </div>
@elseif (@$type == 'video_description')
    <div class="row g-3">
        <div class="col-12">
            <label for="video" class="form-label"><b>Video <span class="text-danger">*</span></b></label>
            <input type="text" class="form-control" id="video" name="video" placeholder="Youtute Video ID"
                value="{{ old('video') ?? @$data->video }}" required>
        </div>
        <div class="col-12">
            <label for="description" class="form-label"><b>Description</b></label>
            <textarea name="description" id="description" cols="30" rows="10" class="form-control description"
                placeholder="Description">{!! old('description') ?? @$data->description !!}</textarea>
        </div>
    </div>
@elseif (@$type == 'description')
    <div class="col-12">
        <label for="description" class="form-label"><b>Description <span class="text-danger">*</span></b></label>
        <textarea name="description" id="description" cols="30" rows="10" class="form-control description"
            placeholder="Description" required>{!! old('description') ?? @$data->description !!}</textarea>
    </div>
@elseif (@$type == 'list')
    <div class="row g-3">
        <div class="col-12">
            <label for="list" class="form-label"><b>List <span class="text-danger">*</span></b></label>
            <input type="text" class="form-control" id="list" name="list[]"
                value="{{ old('list') ?? implode('`', explode('|', @$data->list)) }}" placeholder="List" required>
        </div>
    </div>
@endif
