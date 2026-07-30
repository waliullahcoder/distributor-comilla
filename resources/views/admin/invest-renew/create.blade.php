@extends('layouts.admin.create_app')

@section('content')
    <div class="row g-3">
        <div class="col-md-4 col-sm-6">
            <label for="date" class="form-label"><b>Date <span class="text-danger">*</span></b></label>
            <input type="text" class="form-control date_picker" id="date" name="date"
                value="{{ old('date') ? date('d-m-Y', strtotime(old('date'))) : date('d-m-Y') }}" placeholder="Renew Date"
                required>
        </div>
        <div class="col-md-4 col-sm-6">
            <label for="year" class="form-label"><b>Year <span class="text-danger">*</span></b></label>
            <input type="text" class="form-control" id="year" name="year" placeholder="Year"
                value="{{ date('Y') }}" readonly>
        </div>
        <div class="col-md-4 col-sm-6">
            <label for="month" class="form-label"><b>Month <span class="text-danger">*</span></b></label>
            <input type="text" class="form-control" id="month" name="month" placeholder="Month"
                value="{{ date('F') }}" readonly>
        </div>
        <div class="col-12">
            <div class="table-responsive" id="table">
                @include('admin.invest-renew.table', [
                    'invests' => $invests,
                    'date' => $date
                ])
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script type="text/javascript">
        $(document).ready(function() {
            $(document).on('change', '#date', function(e) {
                var date = $('#date').val();
                $.ajax({
                    url: '{{ request()->fullUrl() }}',
                    type: 'POST',
                    data: {
                        _method: 'GET',
                        date: date,
                    },
                    success: function(response) {
                        if (response.status == 'success') {
                            $('#table').html(response.data);
                            $('#year').val(response.year);
                            $('#month').val(response.month);
                            calc();
                        }
                    }
                });
            });

            $(document).on('change', '.checkbox', function(e) {
                if ($('.checkbox:checked').length == $('.checkbox').length) {
                    $('#checkAll').prop('checked', true);
                } else {
                    $('#checkAll').prop('checked', false);
                }
                calc();
            });

            $(document).on('change', '#checkAll', function(e) {
                if ($(this).is(':checked')) {
                    $('.checkbox').prop('checked', true);
                } else {
                    $('.checkbox').prop('checked', false);
                }
                calc();
            });

            function calc() {
                var total_qty = 0;
                var total_amount = 0;
                $('.invest_id:checked').each(function(index, value) {
                    var invest_id = $(this).val();
                    var qty = +$('#qty_' + invest_id).val();
                    var amount = +$('#amount_' + invest_id).val();
                    total_qty += qty;
                    total_amount += amount;
                });
                $('#total_qty').val(total_qty);
                $('#total_amount').val(total_amount);
            }
        });
    </script>
@endpush
