@extends('layouts.admin.print_app')
@section('content')
    <table class="table table-bordered table-condensed table-striped align-middle">
        <thead>
            <tr class="text-nowrap">
                <th class="text-center" width="20">SL#</th>
                <th>Courier</th>
                <th>Update Date</th>
                <th>Invoice</th>
                <th>Name</th>
                <th>Phone</th>
                <th class="text-right">Amount</th>
                <th class="text-right">Collect</th>
                <th class="text-right">Delivery</th>
                <th class="text-right">Return</th>
                <th class="text-right">Payable</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $row)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ @$row->agent->name }}</td>
                    <td class="text-nowrap">{{ date('d-m-Y', strtotime($row->delivered_at)) }}</td>
                    <td>{{ $row->invoice }}</td>
                    <td>{{ $row->user_name }}</td>
                    <td>{{ $row->user_phone }}</td>
                    <td class="text-right">{{ number_format($row->due) }}</td>
                    <td class="text-right">{{ number_format($row->receive) }}</td>
                    <td class="text-right">{{ number_format($row->status == 'Returned' ? 0 : $row->delivery_cost) }}</td>
                    <td class="text-right">{{ number_format($row->status == 'Returned' ? $row->return_cost : 0) }}</td>
                    @php
                        $courier_cost = $row->delivery_cost;
                        if ($row->status == 'Returned') {
                            $courier_cost = $row->return_cost;
                        }
                        $payable = number_format($row->receive - $courier_cost);
                    @endphp
                    <td class="text-right">{{ $payable }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th class="text-right" colspan="6">Total</th>
                <th class="text-right">{{ number_format($data->sum('due')) }}</th>
                <th class="text-right">{{ number_format($data->sum('receive')) }}</th>
                <th class="text-right">{{ number_format($data->where('status', '!=', 'Returned')->sum('delivery_cost')) }}
                </th>
                <th class="text-right">{{ number_format($data->where('status', 'Returned')->sum('return_cost')) }}</th>
                @php
                    $deliveryCost = $data->where('status', '!=', 'Returned')->sum('delivery_cost');
                    $returnCost = $data->where('status', 'Returned')->sum('return_cost');
                    $totalPayable = $data->sum('receive') - $deliveryCost - $returnCost;
                @endphp
                <th class="text-right">{{ number_format($totalPayable) }}</th>
            </tr>
        </tfoot>
    </table>

    <div style="padding-top: 10px;">Print Date & Time : {{ date('d-m-Y h:i:s A') }}</div>
@endsection
