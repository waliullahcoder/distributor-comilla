@extends('layouts.admin.app')

@section('content')
    <div class="card">
        <div class="card-header pe-2 py-2">
            <div class="d-flex flex-wrap justify-content-between gap-2 align-items-center">
                <h6 class="h6 mb-0 text-uppercase text-nowrap flex-grow-1">
                    {{ @$title ?? 'Please Set Title' }}</h6>
                <a href="{{ Route(str_replace('show', 'index', \Request::route()->getName())) }}"
                    class="btn btn-primary btn-sm">Go Back</a>
            </div>
        </div>
        <div class="card-body px-3">
            <div class="table-responsive-sm">
                <table class="table table-borderless table-striped mb-0">
                    <tbody class="text-nowrap">
                        <tr>
                            <th width="200">Date</th>
                            <th width="10">:</th>
                            <td>{{ date('d-m-Y', strtotime($data->date)) }}</td>
                        </tr>
                        <tr>
                            <th width="200">Month</th>
                            <th width="10">:</th>
                            <td>{{ $data->month }}</td>
                        </tr>
                        <tr>
                            <th width="200">Year</th>
                            <th width="10">:</th>
                            <td>{{ $data->year }}</td>
                        </tr>
                        <tr>
                            <th width="200">Moderator Qty Commission</th>
                            <th width="10">:</th>
                            <td>{{ $data->member_qty_commission }}</td>
                        </tr>
                        <tr>
                            <th width="200">Moderator Value Commission</th>
                            <th width="10">:</th>
                            <td>{{ $data->member_amount_commission }}</td>
                        </tr>
                        <tr>
                            <th width="200">Leader Qty Commission</th>
                            <th width="10">:</th>
                            <td>{{ $data->leader_qty_commission }}</td>
                        </tr>
                        <tr>
                            <th width="200">Leader Value Commission</th>
                            <th width="10">:</th>
                            <td>{{ $data->leader_amount_commission }}</td>
                        </tr>
                        <tr>
                            <th width="200">Total Commission</th>
                            <th width="10">:</th>
                            <td>{{ $data->total_commission }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="table-responsive-sm mt-4">
                <table class="table mb-0">
                    <thead class="bg-primary text-white text-nowrap">
                        <tr>
                            <th class="text-center" width="30">SL#</th>
                            <th>Moderator</th>
                            <th class="text-center px-1" width="120">O. Qty</th>
                            <th class="text-center px-1" width="120">O. Commission</th>
                            <th class="text-center px-1" width="120">O. Value</th>
                            <th class="text-center px-1" width="120">O. Value Comm.</th>
                            <th class="text-center px-1" width="120">L. O. Qty</th>
                            <th class="text-center px-1" width="120">L. O. Commission</th>
                            <th class="text-center px-1" width="120">L. Value</th>
                            <th class="text-center px-1" width="120">L. Value Comm.</th>
                            <th class="text-center px-1" width="120">Total Comm.</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data->list as $row)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>
                                    {{ $row->user->name ?? '' }}
                                </td>
                                <td class="px-1">
                                    <input type="text" class="form-control input-sm text-center"
                                        value="{{ $row->order_qty }}" readonly>
                                </td>
                                <td class="px-1">
                                    <input type="text" class="form-control input-sm text-center"
                                        value="{{ $row->qty_commission }}" readonly>
                                </td>
                                <td class="px-1">
                                    <input type="text" class="form-control input-sm text-center"
                                        value="{{ $row->order_amount }}" readonly>
                                </td>
                                <td class="px-1">
                                    <input type="text" class="form-control input-sm text-center"
                                        value="{{ $row->amount_commission }}" readonly>
                                </td>
                                <td class="px-1">
                                    <input type="text" class="form-control input-sm text-center"
                                        value="{{ $row->leader_qty }}" readonly>
                                </td>
                                <td class="px-1">
                                    <input type="text" class="form-control input-sm text-center"
                                        value="{{ $row->leader_qty_commission }}" readonly>
                                </td>
                                <td class="px-1">
                                    <input type="text" class="form-control input-sm text-center"
                                        value="{{ $row->leader_amount }}" readonly>
                                </td>
                                <td class="px-1">
                                    <input type="text" class="form-control input-sm text-center"
                                        value="{{ $row->leader_amount_commission }}" readonly>
                                </td>
                                <td class="px-1">
                                    <input type="text" class="form-control input-sm text-center"
                                        value="{{ $row->total_commission }}" readonly>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-primary text-white">
                        <tr>
                            <th class="text-end" colspan="2">Total</th>
                            <th class="px-1">
                                <input type="number" class="form-control input-sm text-center" step="any"
                                    value="{{ $data->list->sum('order_qty') }}" readonly>
                            </th>
                            <th class="px-1">
                                <input type="number" class="form-control input-sm text-center" step="any"
                                    value="{{ $data->list->sum('qty_commission') }}" readonly>
                            </th>
                            <th class="px-1">
                                <input type="number" class="form-control input-sm text-center" step="any"
                                    value="{{ $data->list->sum('order_amount') }}" readonly>
                            </th>
                            <th class="px-1">
                                <input type="number" class="form-control input-sm text-center" step="any"
                                    value="{{ $data->list->sum('amount_commission') }}" readonly>
                            </th>
                            <th class="px-1">
                                <input type="number" class="form-control input-sm text-center" step="any"
                                    value="{{ $data->list->sum('leader_qty') }}" readonly>
                            </th>
                            <th class="px-1">
                                <input type="number" class="form-control input-sm text-center" step="any"
                                    value="{{ $data->list->sum('leader_qty_commission') }}" readonly>
                            </th>
                            <th class="px-1">
                                <input type="number" class="form-control input-sm text-center" step="any"
                                    value="{{ $data->list->sum('leader_amount') }}" readonly>
                            </th>
                            <th class="px-1">
                                <input type="number" class="form-control input-sm text-center" step="any"
                                    value="{{ $data->list->sum('leader_amount_commission') }}" readonly>
                            </th>
                            <th class="px-1">
                                <input type="number" class="form-control input-sm text-center" step="any"
                                    value="{{ $data->list->sum('total_commission') }}" readonly>
                            </th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection
