@extends('layouts.admin.index_app')

@section('content')
    @php
        $currentRouteName = \Request::route()->getName();
        $link = Route($currentRouteName);
        $delete_link = str_replace('index', 'destroy', $currentRouteName);
    @endphp
    <div class="card-body">
        <table class="dataTable table align-middle" style="width:100%">
            <thead>
                <tr class="text-nowrap">
                    <th>SL#</th>
                    <th>Payment No</th>
                    <th>Date</th>
                    <th>Investor</th>
                    <th>Amount</th>
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
                    url: "{{ $link }}",
                    type: "GET",
                    data: function(data) {
                        data.type = $('#filter').val();
                    },
                },
                columns: [{
                        data: "DT_RowIndex",
                        name: "DT_RowIndex",
                        orderable: false,
                        searchable: false,
                        className: "text-center",
                        width: '20'
                    },
                    {
                        data: 'payment_no',
                        name: 'payment_no',
                        width: '150'
                    },
                    {
                        data: 'date',
                        name: 'date',
                        orderable: false,
                        searchable: false,
                        className: "text-nowrap",
                        width: '150'
                    },
                    {
                        data: 'investor.name',
                        name: 'investor.name',
                        defaultContent: ''
                    },
                    {
                        data: 'amount',
                        name: 'amount',
                        className: "text-end",
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false,
                        className: "text-end",
                        width: '100'
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
