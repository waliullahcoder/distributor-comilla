@extends('layouts.admin.edit_app')

@section('content')
    <div class="row g-3">
        <div class="col-md-3 col-sm-6">
            <label for="investor_id" class="form-label"><b>Investor <span class="text-danger">*</span></b></label>
            <select name="investor_id" id="investor_id" class="form-select select" data-placeholder="Select Investor" required>
                <option value=""></option>
                @foreach ($additionalData['investors'] as $item)
                    <option value="{{ $item->id }}" {{ $data->investor_id == $item->id ? 'selected' : '' }}>
                        {{ $item->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 col-sm-6">
            <label for="date" class="form-label"><b>Date <span class="text-danger">*</span></b></label>
            <input type="text" class="form-control date_picker" id="date" name="date"
                value="{{ old('date') ? date('d-m-Y', strtotime(old('date'))) : date('d-m-Y', strtotime($data->date)) }}"
                placeholder="Payment Date" required>
        </div>
        <div class="col-md-3 col-sm-6">
            <label for="payment_no" class="form-label"><b>Payment No <span class="text-danger">*</span></b></label>
            <input type="text" class="form-control" id="payment_no" name="payment_no" placeholder="Payment No"
                value="{{ $data->payment_no }}" readonly>
        </div>
        <div class="col-md-3 col-sm-6">
            <label for="coa_setup_id" class="form-label"><b>Cash Account <span class="text-danger">*</span></b></label>
            <select name="coa_setup_id" id="coa_setup_id" class="select form-select" data-placeholder="Select Cash Account"
                required>
                <option value=""></option>
                @foreach ($additionalData['cash_heads'] as $cash_head)
                    <option value="{{ $cash_head->id }}"
                        {{ old('coa_setup_id', $data->coa_setup_id) == $cash_head->id ? 'selected' : '' }}>
                        {{ $cash_head->head_name . ' - ' . $cash_head->head_code }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-12">
            <div class="table-responsive" id="table">
                @include('admin.investor-payment.table', [
                    'data' => $data,
                    'list' => $additionalData['list'],
                ])
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script type="text/javascript">
        $(document).ready(function() {
            if ($('.list_id').length == $('.list_id:checked').length) {
                $('#checkAll').prop('checked', true);
            }

            $(document).on('change', '#investor_id', function(e) {
                var investor_id = $(this).val();
                $.ajax({
                    url: '{{ request()->fullUrl() }}',
                    type: 'POST',
                    data: {
                        _method: 'GET',
                        investor_id: investor_id
                    },
                    success: function(response) {
                        if (response.status == 'success') {
                            $('#table').html(response.data);
                            calculate();
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
                calculate();
            });

            $(document).on('change', '#checkAll', function(e) {
                if ($(this).is(':checked')) {
                    $('.checkbox').prop('checked', true);
                } else {
                    $('.checkbox').prop('checked', false);
                }
                calculate();
            });

            function calculate() {
                var total_amount = 0;
                $('.list_id:checked').each(function(index, value) {
                    var list_id = $(this).val();
                    var amount = +$('#profit_amount' + list_id).val();
                    total_amount += amount;
                });
                $('#total_amount').val(total_amount);
            }
        });
    </script>
@endpush
