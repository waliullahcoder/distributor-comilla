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
                    <th>Image</th>
                    <th>Package Name</th>
                    <th>Amount</th>
                    <th>Discount</th>
                    <th>Shipping Charge</th>
                    <th>Net Amount</th>
                    <th>Status</th>
                    <th width="210" class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
            @if (Auth::user()->can($delete_link))
                <tfoot>
                    <tr>
                        <td colspan="1">
                            <div class="custom-control custom-checkbox">
                                <div id="regular_all_select">
                                    <input type="checkbox" class="custom-control-input" id="selectAll">
                                    <label class="custom-control-label" for="selectAll"></label>
                                </div>
                                <div id="trash_all_select" style="display: none;">
                                    <input type="checkbox" class="custom-control-input" id="trash_selectAll">
                                    <label class="custom-control-label" for="trash_selectAll"></label>
                                </div>
                            </div>
                        </td>
                        <td class="text-end d-table-cell" colspan="8">
                            <div class="text-end">
                                <button type="button" name="bulk_delete" data-url="{{ Route($delete_link, '0') }}"
                                    id="bulk_delete" class="btn btn btn-xs btn-danger">Delete</button>
                                <button type="button" name="bulk_delete" data-url="{{ Route($delete_link, '0') }}"
                                    style="display: none;" id="trash_bulk_delete"
                                    class="btn btn btn-xs btn-danger">Delete</button>
                            </div>
                        </td>
                    </tr>
                </tfoot>
            @endif
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
                        data: "checkbox",
                        name: "checkbox",
                        orderable: false,
                        searchable: false,
                        width: '30',
                    },
                    {
                        data: 'image',
                        name: 'image',
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: 'name',
                        name: 'name',
                    },
                    {
                        data: 'amount',
                        name: 'amount',
                    },
                    {
                        data: 'discount',
                        name: 'discount',
                    },
                    {
                        data: 'shipping_charge',
                        name: 'shipping_charge',
                    },
                    {
                        data: 'net_amount',
                        name: 'net_amount',
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
