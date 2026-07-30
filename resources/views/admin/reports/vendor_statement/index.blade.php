@extends('layouts.admin.report_app')

@section('form')
    <div class="row g-3">
        <input type="hidden" name="filter" value="1">
        <div class="col-sm-6">
            <label for="vendor_id" class="form-label"><b>Vendor <span class="text-danger">*</span></b></label>
            <select name="vendor_id" id="vendor_id" class="form-select select" data-placeholder="Select Vendor" required>
                <option value=""></option>
                @foreach ($vendors as $vendor)
                    <option value="{{ $vendor->id }}" {{ $vendor_id == $vendor->id ? 'selected' : '' }}>
                        {{ $vendor->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-sm-6">
            <label for="date_range" class="form-label"><b>Date <span class="text-danger">*</span></b></label>
            <input type="text" class="form-control date-range" name="date_range" id="date_range"
                placeholder="{{ __('Select Date Range') }}" data-time-picker="true" data-format="DD-MM-Y"
                data-separator=" to " autocomplete="off"
                value="{{ !is_null($start_date) && !is_null($end_date) ? date('d-m-Y', strtotime($start_date)) . ' to ' . date('d-m-Y', strtotime($end_date)) : date('01-m-Y') . ' to ' . date('t-m-Y') }}"
                required>
        </div>
    </div>
@endsection

@section('content')
    <form action="{{ Route('admin.vendor-statement.index') }}" id="print-form" method="GET" target="_blank">
        <input type="hidden" name="print" value="true">
        <input type="hidden" name="vendor_id" class="vendor_id">
        <input type="hidden" name="date_range" class="date_range">
    </form>
    <div class="table-responsive">
        <table id="dataTable" name="paymentRecordTable" class="table table-bordered table-sm">
            <thead>
                <tr>
                    <th colspan="6" style="text-align: right; font-weight: bold;">Previous Balance</th>
                    <th style="text-align: right;">{{ isset($data['previousBalance']) ? $data['previousBalance'] : 0 }}</th>
                </tr>
                <tr>
                    <th class="text-center" width="20px">Sl#</th>
                    <th class="text-end" width="100px">Date</th>
                    <th>Particular</th>
                    <th class="text-end" width="100px">Purchase</th>
                    <th class="text-end" width="100px">Payment</th>
                    <th class="text-end" width="100px">Returns</th>
                    <th class="text-end" width="100px">Balance</th>
                </tr>
            </thead>

            <tbody>
                @if (count($data) > 0)
                    @foreach ($data['statements'] as $statement)
                        <tr>
                            <td class="text-center px-3">{{ $loop->iteration }}</td>
                            <td class="text-end px-3">{{ $statement['date'] }}</td>
                            <td class="px-3">{{ $statement['remarks'] }}</td>
                            <td class="text-end px-3">{{ $statement['lifting'] }}</td>
                            <td class="text-end px-3">{{ $statement['payment'] }}</td>
                            <td class="text-end px-3">{{ $statement['return'] }}</td>
                            <td class="text-end px-3">{{ $statement['balance'] }}</td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
@endsection

@push('js')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#dataTable').DataTable({
                "order": false,
                dom: 'Bfrtip',
                buttons: [
                    'excelHtml5',
                    {
                        'text': '<i class="fal fa-file-pdf"></i> Print',
                        'className': 'getPdf',
                    },
                ]
            });

            $(document).on('click', '.getPdf', function(e) {
                e.preventDefault();
                var vendor_id = $('#vendor_id').val();
                var date_range = $('#date_range').val();
                $('.vendor_id').val(vendor_id);
                $('.date_range').val(date_range);
                if (vendor_id == '') {
                    Swal.fire({
                        width: "22rem",
                        title: "Failed!",
                        text: "Please select a Vendor!",
                        icon: "error",
                        showConfirmButton: false,
                        timer: 1500
                    });
                    return;
                }
                $('#print-form').submit();
            });
        });
    </script>
@endpush
