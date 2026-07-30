@extends('layouts.admin.report_app')

@section('form')
    <input type="hidden" name="print" value="">
    <div class="row g-3">
        <div class="col-md-4">
            <label for="start_date" class="form-label"><b>Start Date</b></label>
            <input type="text" class="form-control date_picker" id="start_date" name="start_date"
                value="{{ request('start_date') ? date('d-m-Y', strtotime(request('start_date'))) : date('01-m-Y') }}"
                placeholder="Start Date" required>
        </div>
        <div class="col-md-4">
            <label for="end_date" class="form-label"><b>End Date</b></label>
            <input type="text" class="form-control date_picker" id="end_date" name="end_date"
                value="{{ request('end_date') ? date('d-m-Y', strtotime(request('end_date'))) : date('t-m-Y') }}"
                placeholder="End Date" required>
        </div>
        <div class="col-md-4">
            <label for="moderator_id" class="form-label"><b>Moderators</b></label>
            <select name="moderator_id[]" id="moderator_id" class="form-select select" data-placeholder="Select Moderators"
                multiple>
                <option value=""></option>
                @foreach ($moderators as $item)
                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
@endsection

@section('content')
    {!! $dataTable->table(['class' => 'dataTable table align-middle table-bordered'], true) !!}
@endsection

@push('js')
    <script type="text/javascript" src="{{ asset('vendor/datatables/buttons.server-side.js') }}"></script>
    {!! $dataTable->scripts() !!}

    <script type="text/javascript">
        $(document).ready(function() {
            const table = $('#dataTable');
            table.on('preXhr.dt', function(e, settings, data) {
                data.moderator_id = $('#moderator_id').val();
                data.start_date = $('#start_date').val();
                data.end_date = $('#end_date').val();
            });

            $(document).on('click', '.getPdf', function(e) {
                $('input[name="print"]').val('true');
                $('.filter_form').attr('target', '_blank');
                $('.filter_form')[0].submit();
            });

            $(document).on('click', '#filter_btn', function(e) {
                $('input[name="print"]').val('');
                $('.filter_form').attr('target', '_self');
            });
        });
    </script>
@endpush
