@extends('layouts.admin.app')

@section('content')
    <div class="row g-3">
        <div class="col-12">
            <form action="{{ Route('admin.campaign-section.store', request()->route('id')) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <div class="card">
                    <div class="card-header pe-2 py-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="h6 mb-0 text-uppercase">{{ @$title }}</h6>
                            <a href="{{ Route('admin.campaign-section.index', request()->route('id')) }}"
                                class="btn btn-primary btn-sm">Go
                                Back</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4 col-sm-6">
                                <label for="type" class="form-label"><b>Type <span
                                            class="text-danger">*</span></b></label>
                                <select name="type" id="type" class="form-select" required>
                                    <option value="list" {{ old('type') == 'list' ? 'selected' : '' }}>List</option>
                                    <option value="list_image" {{ old('type') == 'list_image' ? 'selected' : '' }}>Image &
                                        List</option>
                                    <option value="image_description"
                                        {{ old('type') == 'image_description' ? 'selected' : '' }}>Image & Description
                                    </option>
                                    <option value="description_image"
                                        {{ old('type') == 'description_image' ? 'selected' : '' }}>Description & Image
                                    </option>
                                    <option value="video_description"
                                        {{ old('type') == 'video_description' ? 'selected' : '' }}>Video & Description
                                    </option>
                                    <option value="description" {{ old('type') == 'description' ? 'selected' : '' }}>
                                        Description</option>
                                    <option value="facilities" {{ old('type') == 'facilities' ? 'selected' : '' }}>
                                        Facilities</option>
                                    <option value="faqs" {{ old('type') == 'faqs' ? 'selected' : '' }}>
                                        Faqs</option>
                                    <option value="reviews" {{ old('type') == 'reviews' ? 'selected' : '' }}>
                                        Reviews</option>
                                </select>
                            </div>
                            <div class="col-md-4 col-sm-6">
                                <label for="title" class="form-label"><b>Title</b></label>
                                <input type="text" name="title" id="title" class="form-control"
                                    placeholder="Section Title" value="{{ old('title') }}">
                            </div>
                            <div class="col-md-4 col-sm-6">
                                <label for="order" class="form-label"><b>Order <span
                                            class="text-danger">*</span></b></label>
                                <input type="number" name="order" id="order" class="form-control"
                                    placeholder="Section Order" value="{{ old('order') ?? @$order }}" required>
                            </div>
                            <div class="col-12" id="type_collumns">
                                @include('admin.campaign-section.partial.collumns', [
                                    'type' => old('type'),
                                ])
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

@push('js')
    <script type="text/javascript">
        $(document).ready(function() {
            var input = document.querySelector('#list');
            var tagify = new Tagify(input, {
                delimiters: "`",
        });

        $(document).on('change', '#type', function() {
            var type = $(this).val();
            $.ajax({
                url: '{{ url()->current() }}',
                data: {
                    type: type
                },
                success: function(response) {
                    $('#type_collumns').html(response.data);

                    $('.description').summernote('destroy');
                    $('.description').summernote({
                        placeholder: 'Write here..',
                        height: 300,
                        styleTags: [
                            'p',
                            {
                                title: 'Blockquote',
                                tag: 'blockquote',
                                className: 'blockquote',
                                value: 'blockquote'
                            },
                            'h1', 'h2', 'h3', 'h4', 'h5', 'h6'
                        ],
                        prettifyHtml: true,
                        toolbar: [
                            ['style', ['style']],
                            ['font', ['bold', 'italic', 'underline',
                                'add-text-tags', 'highlight', 'clear'
                            ]],
                            ['font', ['strikethrough', 'superscript',
                                'subscript'
                            ]],
                            ['fontsize', ['fontsize']],
                            ['color', ['color']],
                            ['para', ['ul', 'ol', 'paragraph']],
                            ['table', ['table']],
                            ['insert', ['link', 'picture', 'videoAttributes']],
                            ['view', ['fullscreen', 'codeview', 'help']],
                        ],
                        imageAttributes: {
                            icon: '<i class="note-icon-pencil"/>',
                            figureClass: 'figureClass',
                            figcaptionClass: 'captionClass',
                            captionText: 'Caption Goes Here.',
                            manageAspectRatio: true // true = Lock the Image Width/Height, Default to true
                        },
                        lang: 'en-US',
                        popover: {
                            image: [
                                ['imagesize', ['imageSize100', 'imageSize50',
                                    'imageSize25'
                                ]],
                                ['float', ['floatLeft', 'floatRight',
                                    'floatNone'
                                ]],
                                ['remove', ['removeMedia']],
                                ['custom', ['imageAttributes']],
                            ],
                        },
                    });

                    var input = document.querySelector('#list');
                    var tagify = new Tagify(input, {
                        delimiters: "`",
                        });
                    }
                });
            });
        });
    </script>
@endpush
