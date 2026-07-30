@extends('layouts.admin.app')

@section('content')
    <div class="card">
        <div class="card-header pe-2 py-2">
            <form action="{{ @$filter_link }}" method="GET" class="filter_form">
                <input type="hidden" name="print" value="">
                <input type="hidden" name="filter" value="1">
                <div class="d-flex align-items-center justify-content-between gap-2">
                    <h6 class="h6 mb-0 text-uppercase py-1">{{ @$title }}</h6>
                    <div class="flex-shrink-0">
                        <a href="{{ Route('admin.moderator-orders.index') }}" class="btn btn-primary btn-sm">Go Back</a>
                        <input type="hidden" name="view_orders" value="true">
                        <input type="hidden" name="moderator_id" value="{{ request('moderator_id') }}">
                        <input type="hidden" name="status" value="{{ request('status') }}">
                    </div>
                </div>
            </form>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="dataTable" class="table table-bordered table-sm">
                    <thead class="text-nowrap">
                        <tr>
                            <th class="text-center" width="30">SL#</th>
                            <th>Date</th>
                            <th>Order No.</th>
                            <th>Customer Name</th>
                            <th>Customer Phone</th>
                            <th>Address</th>
                            <th>Product Details</th>
                            <th class="text-center">Collection Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $row)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td class="text-nowrap">{{ date('d-m-Y', strtotime($row->date)) }}</td>
                                <td>{{ $row->invoice }}</td>
                                <td>{{ $row->user_name }}</td>
                                <td>
                                    {{ $row->user_phone . (!is_null($row->user_phone_alt) ? ', ' . $row->user_phone_alt : '') }}
                                </td>
                                <td>{{ $row->shipping_address }}</td>
                                <td>
                                    @php
                                        $string = '';
                                        foreach ($row->products as $key => $item) {
                                            $string .=
                                                ($key > 0 ? ', ' : '') .
                                                @$item->product->name .
                                                ' - ' .
                                                $item->quantity .
                                                ' ' .
                                                @$item->product->attribute->name .
                                                ' - ' .
                                                $item->subtotal .
                                                'Taka ';
                                        }
                                    @endphp
                                    {{ $string }}
                                </td>
                                <td class="text-center">{{ number_format($row->due - $row->advance) }}</td>
                                <td>
                                    @php
                                        $bg = 'bg-primary';
                                        if ($row->status == 'On Route') {
                                            $bg = 'bg-route';
                                        } elseif ($row->status == 'Delivered') {
                                            $bg = 'bg-success';
                                        } elseif ($row->status == 'Collected') {
                                            $bg = 'bg-info';
                                        } elseif ($row->status == 'Returned') {
                                            $bg = 'bg-warning';
                                        } elseif ($row->status == 'Cancelled') {
                                            $bg = 'bg-danger';
                                        }
                                    @endphp
                                    <a class="btn btn-xs text-white px-2 {{ $bg }}" style="min-width: 80px;"
                                        href="{{ !Auth::user()->hasRole('Moderator') ? Route('admin.order-dashboard.edit', $row->id) : '' }}">{{ $row->status }}</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <th colspan="7" class="text-end">Total</th>
                        <th class="text-center">{{ $data->sum('due') - $data->sum('advance') }}</th>
                        <th></th>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#dataTable').DataTable({
                order: false,
                dom: "<'row g-2'<'col-sm-4'l><'col-sm-8 text-end'<'d-lg-flex justify-content-end'<'mb-2 mb-lg-0 me-1'f>B>>>t<'d-lg-flex align-items-center mt-2'<'me-auto mb-lg-0 mb-2'i><'mb-0'p>>",
                lengthMenu: [10, 20, 30, 40, 50],
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
                $('input[name="print"]').val('true');
                $('.filter_form')[0].setAttribute("target", "_blank");
                $('.filter_form').submit();
            });

            $(document).on('change', '#area_id', function(e) {
                e.preventDefault();
                $('input[name="print"]').val('');
                $('.filter_form')[0].setAttribute("target", "_self");
                $('.filter_form').submit();
            });
        });
    </script>
@endpush
