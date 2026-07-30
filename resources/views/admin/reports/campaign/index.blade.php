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
                    <th></th>
                    <th>Campaign Name</th>
                    <th>Link</th>
                    <th>Status</th>
                    <th width="210" class="text-end">Actions</th>
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
                responsive: true,
                ajax: {
                    url: "{{ $link }}",
                    type: "GET"
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
                        data: 'name',
                        name: 'name',
                    },
                    {
                        data: 'link',
                        name: 'link',
                    },
                    {
                        data: 'status',
                        name: 'status',
                        orderable: false,
                        searchable: false,
                        width: '100',
                        className: "text-center",
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false,
                        className: "text-end",
                        width: '110',
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
