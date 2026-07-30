@extends('layouts.admin.create_app')

@section('content')
    <div class="row g-3">
        <div class="col-md-3 col-sm-6">
            <label for="investor_id" class="form-label"><b>Investor <span class="text-danger">*</span></b></label>
            <select name="investor_id" id="investor_id" class="select form-select" data-placeholder="Select Investor" required>
                <option value=""></option>
                @foreach ($investors as $investor)
                    <option value="{{ $investor->id }}">{{ $investor->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 col-sm-6">
            <label for="date" class="form-label"><b>Date <span class="text-danger">*</span></b></label>
            <input type="text" class="form-control date_picker" id="date" name="date"
                value="{{ old('date') ? date('d-m-Y', strtotime(old('date'))) : date('d-m-Y') }}"
                placeholder="Sattlement Date" required>
        </div>
        <div class="col-md-3 col-sm-6">
            <label for="serial_no" class="form-label"><b>Serial No. <span class="text-danger">*</span></b></label>
            <input type="text" class="form-control" id="serial_no" name="serial_no" value="{{ $serial_no }}" readonly
                placeholder="Serial No." required>
        </div>
        <div class="col-md-3 col-sm-6">
            <label for="coa_setup_id" class="form-label"><b>Cash Account <span class="text-danger">*</span></b></label>
            <select name="coa_setup_id" id="coa_setup_id" class="select form-select" data-placeholder="Select Cash Account"
                required>
                <option value=""></option>
                @foreach ($cash_heads as $cash_head)
                    <option value="{{ $cash_head->id }}" {{ old('coa_setup_id') == $cash_head->id ? 'selected' : '' }}>
                        {{ $cash_head->head_name . ' - ' . $cash_head->head_code }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-12">
            <div class="table-responsive">
                <table class="table table-bordered table-striped target-table align-middle mb-0">
                    <thead class="bg-primary border-primary text-white align-middle text-nowrap">
                        <tr>
                            <th class="py-1 px-3">Invest No.</th>
                            <th class="py-1 px-3">Invest Date</th>
                            <th class="py-1 px-3">Invest Qty</th>
                            <th class="py-1 px-3">Invest Amount</th>
                            <th class="py-1 px-3" width="60">
                                <div class="custom-control custom-checkbox w-fit mx-auto">
                                    <input type="checkbox" class="custom-control-input" name="selectAll" id="checkAll">
                                    <label for="checkAll" class="custom-control-label"></label>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody id="tbody">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script type="text/javascript">
        $(document).ready(function() {
            $(".date_picker").datepicker({
                format: 'dd-mm-yyyy',
                changeMonth: true,
                changeYear: true,
            });

            $(document).on('change', '#investor_id', function(e) {
                $('#tbody tr').remove();
                let investor_id = $(this).val();
                let url = "{{ Route('admin.investor-sattlement.create') }}";
                $.ajax({
                    url: url,
                    type: "POST",
                    data: {
                        _method: 'GET',
                        investor_id: investor_id
                    },
                    success: (response) => {
                        if (response.status == 'success') {
                            if (response.data.length > 0) {
                                response.data.forEach(function(item, index) {
                                    var tr = `
                                        <tr>
                                            <td class="px-3">${item.invest_no}</td>
                                            <td class="px-3">${item.formattedDate}</td>
                                            <td class="px-3">${item.qty}</td>
                                            <td class="px-3">${item.amount}</td>
                                            <td class="px-3">
                                                <div class="custom-control custom-checkbox w-fit mx-auto">
                                                    <input type="checkbox" class="custom-control-input multi_checkbox" name="invest_id[]" id="invest_id${item.id}" value="${item.id}">
                                                    <label for="invest_id${item.id}" class="custom-control-label"></label>
                                                </div>
                                            </td>
                                        </tr>`;
                                    $('#tbody').append(tr);
                                });
                            }
                        }
                    }
                });
            });

            $(document).on('change', '#checkAll', function(e) {
                if ($(this).is(':checked')) {
                    $('.multi_checkbox').prop('checked', true);
                } else {
                    $('.multi_checkbox').prop('checked', false);
                }
            });
        });
    </script>
@endpush
