@extends('layouts.admin.index_app')

@section('content')
    <div class="card-body">
        <table class="dataTable table align-middle" style="width:100%">
            <thead>
                <tr class="text-nowrap">
                    <th>SL#</th>
                    <th>Date</th>
                    <th>Serial No</th>
                    <th>Year</th>
                    <th>Month</th>
                    <th>Moderators</th>
                    <th>Qty Comm.</th>
                    <th>Value Comm.</th>
                    <th>L. Qty Comm.</th>
                    <th>L. Value Comm.</th>
                    <th>Total Comm.</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
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
                    url: "{{ request()->fullUrl() }}",
                    type: "GET",
                    data: function(data) {
                        data.type = $('#filter').val();
                    }
                },
                columns: [{
                        data: "DT_RowIndex",
                        name: "DT_RowIndex",
                        orderable: false,
                        searchable: false,
                        width: '30',
                        className: "text-center",
                    },
                    {
                        data: 'date',
                        name: 'date',
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: 'serial_no',
                        name: 'serial_no',
                    },
                    {
                        data: 'year',
                        name: 'year'
                    },
                    {
                        data: 'month',
                        name: 'month'
                    },
                    {
                        data: 'moderators',
                        name: 'moderators',
                        orderable: false,
                        searchable: false,
                        defaultContent: ''
                    },
                    {
                        data: 'member_qty_commission',
                        name: 'member_qty_commission'
                    },
                    {
                        data: 'member_amount_commission',
                        name: 'member_amount_commission'
                    },
                    {
                        data: 'leader_qty_commission',
                        name: 'leader_qty_commission'
                    },
                    {
                        data: 'leader_amount_commission',
                        name: 'leader_amount_commission'
                    },
                    {
                        data: 'total_commission',
                        name: 'total_commission'
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false,
                        className: 'text-end',
                        width: '60',
                    },
                ],
                "fnDrawCallback": function(oSettings) {
                    const tooltips = document.querySelectorAll('.tt');
                    tooltips.forEach(t => {
                        new bootstrap.Tooltip(t);
                    });
                }
            });
        });
    </script>
@endpush
