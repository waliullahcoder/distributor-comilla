@extends('layouts.admin.app')

@section('content')
    @php
        $currentRouteName = \Request::route()->getName();
        $link = Route($currentRouteName);
    @endphp
    <div class="row g-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header pe-2 py-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="h6 mb-0 text-uppercase">Order Management</h6>
                        <form action="#" class="flex-shrink-0 d-flex gap-2" method="GET" id="filter_form">
                            <input type="text" class="form-control date-range input-sm" name="date_range" id="date_range"
                                placeholder="Select Date Range" data-time-picker="true" data-format="DD-MM-Y"
                                data-separator=" to " autocomplete="off" required
                                value="{{ date('01-m-Y') . ' to ' . date('t-m-Y') }}">
                            <select name="status" id="status" class="form-select select-sm flex-shrink-0"
                                style="width: 120px;">
                                <option value="">Select Status</option>
                                <option value="Pending" {{ session('status') == 'Pending' ? 'selected' : '' }}>Pending
                                </option>
                                <option value="Forward" {{ session('status') == 'Forward' ? 'selected' : '' }}>Forward
                                </option>
                                <option value="On Route" {{ session('status') == 'On Route' ? 'selected' : '' }}>On Route
                                </option>
                                <option value="Delivered" {{ session('status') == 'Delivered' ? 'selected' : '' }}>Delivered
                                </option>
                                <option value="Collected" {{ session('status') == 'Collected' ? 'selected' : '' }}>Collected
                                </option>
                                <option value="Returned" {{ session('status') == 'Returned' ? 'selected' : '' }}>Returned
                                </option>
                                <option value="Cancelled" {{ session('status') == 'Cancelled' ? 'selected' : '' }}>Cancelled
                                </option>
                            </select>
                            <button type="submit" class="btn btn-sm btn-primary px-4">Filter</button>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <table class="dataTable table align-middle" style="width:100%">
                        <thead>
                            <tr class="text-nowrap">
                                <th>SL#</th>
                                <th>Invoice</th>
                                <th>Order Date</th>
                                <th>Customer Name</th>
                                <th>Customer Phone</th>
                                <th>Order Amount</th>
                                <th>Address</th>
                                <th>Order Status</th>
                                <th>Courier Assign</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot class="bg-primary text-white">
                            <tr>
                                <th colspan="4" class="text-end"></th>
                                <th class="text-white" id="total-value"></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script type="text/javascript">
        $(document).ready(function() {
            var table = $('.dataTable').dataTable({
                processing: true,
                serverSide: true,
                scrollX: true,
                ajax: {
                    url: "{{ $link }}",
                    type: "GET",
                    data: function(data) {
                        data.status = $("#status").val();
                        data.date_range = $("#date_range").val();
                    },
                    "dataSrc": function(json) {
                        $('#total-value').html(json.sumValue);
                        return json.data;
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: "DT_RowIndex",
                        orderable: false,
                        searchable: false,
                        width: '30',
                        className: 'text-center'
                    },
                    {
                        data: 'invoice',
                        name: 'invoice'
                    },
                    {
                        data: 'order_date',
                        name: 'order_date',
                        orderable: false,
                        searchable: false,
                        className: 'text-nowrap'
                    },
                    {
                        data: 'user_name',
                        name: 'user_name'
                    },
                    {
                        data: 'user_phone',
                        name: 'user_phone'
                    },
                    {
                        data: 'due',
                        name: 'due'
                    },
                    {
                        data: 'shipping_address',
                        name: 'shipping_address'
                    },
                    {
                        data: 'order_status',
                        name: 'order_status',
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: 'courier_assign',
                        name: 'courier_assign',
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false,
                        width: '100',
                        className: "text-end",
                    },
                ],
                "fnDrawCallback": function(oSettings) {
                    const tooltips = document.querySelectorAll('.tt');
                    tooltips.forEach(t => {
                        new bootstrap.Tooltip(t);
                    });
                }
            });

            $(document).on('submit', '#filter_form', function(e) {
                e.preventDefault();
                let status = $('#status').val();
                if (status == 'Processing') {
                    $('#export_form').show();
                } else {
                    $('#export_form').hide();
                }
                $('.dataTable').DataTable().draw();
            });

            $(document).on('click', '.assign_btn', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Assign to Courier!',
                    cancelButtonText: 'No, cancel!',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: url,
                            type: 'POST',
                            data: {
                                _method: 'GET',
                                assign_courier: true,
                            },
                            success: function(response) {
                                if (response.status == 'success') {
                                    Swal.fire({
                                        width: "22rem",
                                        title: "Assigned!",
                                        text: 'Successfully Assigned to Courier!',
                                        icon: "success",
                                        showConfirmButton: false,
                                        timer: 1500
                                    });
                                    $('.dataTable').DataTable().ajax.reload();
                                }
                                if (response.status == 'error') {
                                    Swal.fire({
                                        width: "22rem",
                                        title: "Failed!",
                                        text: "Something went wrong!",
                                        icon: "error",
                                        showConfirmButton: false,
                                        timer: 1500
                                    });
                                }
                            }
                        });
                    } else(
                        result.dismiss === Swal.DismissReason.cancel
                    )
                });

            });
        });
    </script>
@endpush
