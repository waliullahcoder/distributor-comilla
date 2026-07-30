@extends('layouts.admin.print_app')
@section('content')
    <table class="table table-bordered table-condensed table-striped align-middle">
        <thead>
            <tr class="text-nowrap">
                <th class="text-center" width="30">SL#</th>
                <th>Name</th>
                <th class="text-center">Issue</th>
                <th class="text-center">On Route</th>
                <th class="text-center">Return</th>
                <th class="text-center">Delivered</th>
                <th class="text-center">Cancelled</th>
                <th class="text-center">Collected</th>
                <th class="text-center">Collected Amount</th>
            </tr>
        </thead>
        <tbody>
            @php
                $start_date = request('start_date') ? date('Y-m-d', strtotime(request('start_date'))) : date('Y-m-01');
                $end_date = request('end_date') ? date('Y-m-d', strtotime(request('end_date'))) : date('Y-m-t');
            @endphp
            @foreach ($data as $row)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $row->name }}</td>
                    <td class="text-center">
                        {{ $row->orders->where('date', '>=', $start_date)->where('date', '<=', $end_date)->count() }}</td>
                    <td class="text-center">
                        {{ $row->orders->where('date', '>=', $start_date)->where('date', '<=', $end_date)->where('status', 'On Route')->count() }}
                    </td>
                    <td class="text-center">
                        {{ $row->orders->where('date', '>=', $start_date)->where('date', '<=', $end_date)->where('status', 'Returned')->count() }}
                    </td>
                    <td class="text-center">
                        {{ $row->orders->where('date', '>=', $start_date)->where('date', '<=', $end_date)->where('status', 'Delivered')->count() }}
                    </td>
                    <td class="text-center">
                        {{ $row->orders->where('date', '>=', $start_date)->where('date', '<=', $end_date)->where('status', 'Cancelled')->count() }}
                    </td>
                    <td class="text-center">
                        {{ $row->orders->where('date', '>=', $start_date)->where('date', '<=', $end_date)->where('status', 'Collected')->count() }}
                    </td>
                    <td class="text-center">
                        @php
                            $qq = \App\Models\OrderProduct::whereHas('order', function ($query) use ($row) {
                                $start_date = request('start_date') ? date('Y-m-d', strtotime(request('start_date'))) : date('Y-m-01');
                                $end_date = request('end_date') ? date('Y-m-d', strtotime(request('end_date'))) : date('Y-m-t');
                                $query->where('date', '>=', $start_date)
                                        ->where('date', '<=', $end_date)
                                        ->where('collected', 1);
                                $query->where('delivery_agent_id', $row->id);
                            });
                            $amount = $qq->sum(DB::raw('subtotal - discount'));
                            $order_ids = $qq->groupBy('order_id')->pluck('order_id')->toArray();
                            $shipping_charge = \App\Models\Order::whereIn('id', $order_ids)->sum('shipping_charge');
                            $count = $amount + $shipping_charge;
                            echo $count;
                        @endphp
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="padding-top: 10px;">Print Date & Time : {{ date('d-m-Y h:i:s A') }}</div>
@endsection
