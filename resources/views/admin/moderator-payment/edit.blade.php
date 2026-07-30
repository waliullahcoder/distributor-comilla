@extends('layouts.admin.edit_app')

@section('content')
    <input type="hidden" name="generate" value="1">
    <div class="row g-3">
        <div class="col-lg-3 col-md-4 col-sm-6">
            <label for="date" class="form-label"><b>Date <span class="text-danger">*</span></b></label>
            <input type="text" class="form-control date_picker" id="date" name="date"
                value="{{ date('d-m-Y', strtotime(old('date', date('d-m-Y')))) }}" placeholder="Date" required>
        </div>
        <div class="col-lg-3 col-md-4 col-sm-6">
            <label for="month" class="form-label"><b>Month <span class="text-danger">*</span></b></label>
            <select class="form-select select" name="month" id="month" data-placeholder="Select Month." required>
                @php
                    $months = [
                        'January',
                        'February',
                        'March',
                        'April',
                        'May',
                        'June',
                        'July',
                        'August',
                        'September',
                        'October',
                        'November',
                        'December',
                    ];
                @endphp
                @foreach ($months as $item)
                    <option value="{{ $item }}" {{ $data->month == $item ? 'selected' : '' }}>
                        {{ $item }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-lg-3 col-md-4 col-sm-6">
            <label for="year" class="form-label"><b>Year <span class="text-danger">*</span></b></label>
            <select class="form-select select" name="year" id="year" data-placeholder="Select Year." required>
                @for ($i = 2015; $i <= 2055; $i++)
                    <option value="{{ $i }}" {{ $data->year == $i ? 'selected' : '' }}>
                        {{ $i }}</option>
                @endfor
            </select>
        </div>
        <div class="col-lg-3 col-md-4 col-sm-6">
            <label for="coa_setup_id" class="form-label"><b>Cash Head <span class="text-danger">*</span></b></label>
            <select name="coa_setup_id" id="coa_setup_id" class="select form-select" data-placeholder="Select Cash Head"
                required>
                <option value=""></option>
                @foreach ($cash_heads as $cash_head)
                    <option value="{{ $cash_head->id }}"
                        {{ old('coa_setup_id', $data->coa_setup_id) == $cash_head->id ? 'selected' : '' }}>
                        {{ $cash_head->head_name . ' - ' . $cash_head->head_code }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-12">
            <div id="response">
                @include('admin.moderator-payment.partial.edit-rows', [
                    'moderators' => $moderators,
                    'month' => $month,
                    'year' => $year,
                    'data'  => $data
                ])
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script type="text/javascript">
        $(document).ready(function() {
            $(document).on('change', '#month,#year', function(e) {
                var month = $('#month').val();
                var year = $('#year').val();
                $('#response').html('');
                $.ajax({
                    url: '{{ request()->fullUrl() }}',
                    type: 'POST',
                    data: {
                        _method: 'GET',
                        month: month,
                        year: year,
                    },
                    success: function(response) {
                        if (response.status == 'success') {
                            $('#response').html(response.data);
                        }
                    }
                });
            });
        });
    </script>
@endpush
