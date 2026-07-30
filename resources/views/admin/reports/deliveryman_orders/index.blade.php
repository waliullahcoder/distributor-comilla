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
            <label for="store_id" class="form-label"><b>Store</b></label>
            <select name="store_id" id="store_id" class="form-select select" data-placeholder="Select Store">
                <option value=""></option>
                @foreach ($stores as $item)
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
            $(".date_picker").datepicker({
                format: 'dd-mm-yyyy',
                changeMonth: true,
                changeYear: true,
            });

            const table = $('#dataTable');
            table.on('preXhr.dt', function(e, settings, data) {
                data.store_id = $('#store_id').val();
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

            $(document).on('change', '#store_id', function() {
                let store_id = $(this).val();
                $.ajax({
                    url: "{{ Route(request()->route()->getName()) }}",
                    type: "POST",
                    data: {
                        _method: 'GET',
                        store_id: store_id,
                        get_delivery_men: true,
                    },
                    success: function(response) {
                        if (response.status == 'success') {
                            $('#delivery_agent_id option').remove();
                            $('#delivery_agent_id').append('<option value=""></option>');
                            $.each(response.delivery_men, function(key, value) {
                                var html =
                                    `<option value="${value.id}">${value.name}</option>`;
                                $('#delivery_agent_id').append(html);
                            });
                        }
                    }
                });
            });
        });
    </script>
@endpush
