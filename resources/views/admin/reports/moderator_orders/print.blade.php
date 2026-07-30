@extends('layouts.admin.print_app')
@section('content')
    <table class="table table-bordered table-condensed table-striped align-middle">
        <thead>
            <tr class="text-nowrap">
                <th class="text-center" width="20">SL#</th>
                <th>Moderator</th>
                <th class="text-center">Receive</th>
                <th class="text-center">Pending</th>
                <th class="text-center">Forward</th>
                <th class="text-center">On Route</th>
                <th class="text-center">Return</th>
                <th class="text-center">Delivered</th>
                <th class="text-center">Cancelled</th>
                <th class="text-center">Collected</th>
                <th class="text-center">Collected Amount</th>
                <th class="text-center">Moderator Earned</th>
            </tr>
        </thead>
        <tbody>
            @php
                $cost = \App\Models\AdditionalCost::first();
                $start_date = request('start_date') ? date('Y-m-d', strtotime(request('start_date'))) : date('Y-m-01');
                $end_date = request('end_date') ? date('Y-m-d', strtotime(request('end_date'))) : date('Y-m-t');
                $treceive = 0;
                $tpending = 0;
                $tforward = 0;
                $tonRoute = 0;
                $treturn = 0;
                $tdelivered = 0;
                $tcancelled = 0;
                $tcollected = 0;
                $tcollectedAmount = 0;
                $tmoderatorCommission = 0;
            @endphp
            @foreach ($data as $row)
                @php
                    $receive = $row->moderatorOrders->where('date', '>=', $start_date)->where('date', '<=', $end_date)->count();
                    $pending = $row->moderatorOrders->where('date', '>=', $start_date)->where('date', '<=', $end_date)->where('status', 'Pending')->count();
                    $forward = $row->moderatorOrders->where('date', '>=', $start_date)->where('date', '<=', $end_date)->where('status', 'Forward')->count();
                    $onRoute = $row->moderatorOrders->where('date', '>=', $start_date)->where('date', '<=', $end_date)->where('status', 'On Route')->count();
                    $return = $row->moderatorOrders->where('date', '>=', $start_date)->where('date', '<=', $end_date)->where('status', 'Returned')->count();
                    $delivered = $row->moderatorOrders->where('date', '>=', $start_date)->where('date', '<=', $end_date)->where('status', 'Delivered')->count();
                    $cancelled = $row->moderatorOrders->where('date', '>=', $start_date)->where('date', '<=', $end_date)->where('status', 'Cancelled')->count();
                    $collected = $row->moderatorOrders->where('date', '>=', $start_date)->where('date', '<=', $end_date)->where('status', 'Collected')->count();
                    $collectedAmount = $row->moderatorOrders->where('date', '>=', $start_date)->where('date', '<=', $end_date)->where('collected', true)->sum(function ($order) {
                        return $order->sub_total - $order->discount;
                    });

                    $orders = $row->moderatorOrders->where('date', '>=', $start_date)->where('date', '<=', $end_date)->whereIn('status', ['Delivered', 'Collected'])->where('commission', false);
                    
                    $qty = $orders->count();
                    $qtyCommission = $qty * $cost->moderator_cost;
                    $amount = $orders->sum(function ($order) {
                        return $order->sub_total - $order->discount;
                    });
                    $amountCommission = ($cost->moderator_cost_percentage / 100) * $amount;
                    $moderatorCommission = round($qtyCommission + $amountCommission, 2);
                    
                    $treceive += $receive;
                    $tpending += $pending;
                    $tforward += $forward;
                    $tonRoute += $onRoute;
                    $treturn += $return;
                    $tdelivered += $delivered;
                    $tcancelled += $cancelled;
                    $tcollected += $collected;
                    $tcollectedAmount += $collectedAmount;
                    $tmoderatorCommission += $moderatorCommission;
                @endphp
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $row->name }}</td>
                    <td class="text-center">
                        {{ $receive }}
                    </td>
                    <td class="text-center">
                        {{ $pending }}
                    </td>
                    <td class="text-center">
                        {{ $forward }}
                    </td>
                    <td class="text-center">
                        {{ $onRoute }}
                    </td>
                    <td class="text-center">
                        {{ $return }}
                    </td>
                    <td class="text-center">
                        {{ $delivered }}
                    </td>
                    <td class="text-center">
                        {{ $cancelled }}
                    </td>
                    <td class="text-center">
                        {{ $collected }}
                    </td>
                    <td class="text-center">
                        {{ $collectedAmount }}
                    </td>
                    <td class="text-center">
                        {{ $moderatorCommission }}
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th class="text-right" colspan="2">Total</th>
                <th class="text-center">{{ $treceive }}</th>
                <th class="text-center">{{ $tpending }}</th>
                <th class="text-center">{{ $tforward }}</th>
                <th class="text-center">{{ $tonRoute }}</th>
                <th class="text-center">{{ $treturn }}</th>
                <th class="text-center">{{ $tdelivered }}</th>
                <th class="text-center">{{ $tcancelled }}</th>
                <th class="text-center">{{ $tcollected }}</th>
                <th class="text-center">{{ $tcollectedAmount }}</th>
                <th class="text-center">{{ $tmoderatorCommission }}</th>
            </tr>
        </tfoot>
    </table>

    <div style="padding-top: 10px;">Print Date & Time : {{ date('d-m-Y h:i:s A') }}</div>
@endsection
