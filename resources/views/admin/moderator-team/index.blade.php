@extends('layouts.admin.index_app')
@section('content')
    <div class="card-body">
        <table class="dataTable table align-middle" style="width:100%">
            <thead>
                <tr class="text-nowrap">
                    <th>SL#</th>
                    <th>Team Name</th>
                    <th>Team Leader</th>
                    <th>Team Members</th>
                    <th>Status</th>
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
                responsive: true,
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
                        className: 'text-center',
                        width: '20',
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'leader.name',
                        name: 'leader.name',
                        orderable: false,
                        searchable: false,
                        defaultContent: ''
                    },
                    {
                        data: 'team_members',
                        name: 'team_members',
                        orderable: false,
                        searchable: false,
                        defaultContent: ''
                    },
                    {
                        data: 'status',
                        name: 'status',
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false,
                        className: "text-end",
                        width: '90',
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
