<div class="row g-3">
    <div class="col-12">
        <div class="table-responsive">
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
                    @php
                        $tqty = 0;
                        $tqtyCommission = 0;
                        $tamount = 0;
                        $tamountCommission = 0;
                        $tleaderQty = 0;
                        $tleaderQtyCommission = 0;
                        $tleaderAmount = 0;
                        $tleaderAmountCommission = 0;
                        $start_date = date('Y-m-01', strtotime($month . '-' . $year));
                        $end_date = date('Y-m-t', strtotime($month . '-' . $year));
                        $cost = \App\Models\AdditionalCost::first();
                    @endphp
                    @foreach ($moderators as $row)
                        @php
                            $orders = \App\Models\Order::where('created_by', $row->id)
                                ->where(function ($query) use ($start_date, $end_date) {
                                    $query
                                        ->whereBetween('delivered_at', [$start_date, $end_date])
                                        ->orWhere('delivered_at', '<', $start_date);
                                })
                                ->whereIn('status', ['Delivered', 'Collected'])
                                ->where('commission', false)
                                ->get();
                            $qty = $orders->count();
                            $qtyCommission = $qty * $cost->moderator_cost;
                            $amount = $orders->sum(function ($order) {
                                return $order->sub_total - $order->discount;
                            });
                            $amountCommission = ($cost->moderator_cost_percentage / 100) * $amount;

                            if ($row->leader) {
                                $moderator_ids = $row->leader->members->pluck('user_id')->toArray();
                                $leaderOrders = \App\Models\Order::whereIn('created_by', $moderator_ids)
                                    ->whereNot('created_by', $row->id)
                                    ->where(function ($query) use ($start_date, $end_date) {
                                        $query
                                            ->whereBetween('delivered_at', [$start_date, $end_date])
                                            ->orWhere('delivered_at', '<', $start_date);
                                    })
                                    ->whereIn('status', ['Delivered', 'Collected'])
                                    ->where('commission', false)
                                    ->get();
                                $leaderQty = $leaderOrders->count();
                                
                                $leaderQtyCommission = $leaderQty * $cost->team_leader_cost;
                                $leaderAmount = $leaderOrders->sum(function ($order) {
                                    return $order->sub_total - $order->discount;
                                });
                                $leaderAmountCommission = ($cost->team_leader_percentage / 100) * $leaderAmount;

                                // Calculate Total
                                $tleaderQty += $leaderQty;
                                $tleaderQtyCommission += $leaderQtyCommission;
                                $tleaderAmount += $leaderAmount;
                                $tleaderAmountCommission += $leaderAmountCommission;
                            }

                            // Calculate Total
                            $tqty += $qty;
                            $tqtyCommission += $qtyCommission;
                            $tamount += $amount;
                            $tamountCommission += $amountCommission;
                        @endphp
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>
                                {{ $row->name }}
                                <input type="hidden" name="user_id[]" value="{{ $row->id }}">
                                <input type="hidden" name="order_ids[{{ $row->id }}]"
                                    value="{{ json_encode($orders->pluck('id')->toArray()) }}">
                            </td>
                            <td class="px-1">
                                <input type="text" class="form-control input-sm text-center"
                                    value="{{ $qty }}" readonly>
                            </td>
                            <td class="px-1">
                                <input type="text" class="form-control input-sm text-center"
                                    value="{{ $qtyCommission }}" readonly>
                            </td>
                            <td class="px-1">
                                <input type="text" class="form-control input-sm text-center"
                                    value="{{ $amount }}" readonly>
                            </td>
                            <td class="px-1">
                                <input type="text" class="form-control input-sm text-center"
                                    value="{{ $amountCommission }}" readonly>
                            </td>
                            <td class="px-1">
                                <input type="text" class="form-control input-sm text-center"
                                    value="{{ $leaderQty ?? 0 }}" readonly>
                            </td>
                            <td class="px-1">
                                <input type="text" class="form-control input-sm text-center"
                                    value="{{ $leaderQtyCommission ?? 0 }}" readonly>
                            </td>
                            <td class="px-1">
                                <input type="text" class="form-control input-sm text-center"
                                    value="{{ $leaderAmount ?? 0 }}" readonly>
                            </td>
                            <td class="px-1">
                                <input type="text" class="form-control input-sm text-center"
                                    value="{{ $leaderAmountCommission ?? 0 }}" readonly>
                            </td>
                            <td class="px-1">
                                <input type="text" class="form-control input-sm text-center"
                                    value="{{ $qtyCommission + $amountCommission + ($leaderQtyCommission ?? 0) + ($leaderAmountCommission ?? 0) }}"
                                    readonly>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-primary text-white">
                    <tr>
                        <th class="text-end" colspan="2">Total</th>
                        <th class="px-1">
                            <input type="number" name="member_order_qty" class="form-control input-sm text-center"
                                step="any" value="{{ $tqty }}" readonly>
                        </th>
                        <th class="px-1">
                            <input type="number" name="member_qty_commission" class="form-control input-sm text-center"
                                step="any" value="{{ $tqtyCommission }}" readonly>
                        </th>
                        <th class="px-1">
                            <input type="number" name="member_order_amout" class="form-control input-sm text-center"
                                step="any" value="{{ $tamount }}" readonly>
                        </th>
                        <th class="px-1">
                            <input type="number" name="member_amount_commission"
                                class="form-control input-sm text-center" step="any"
                                value="{{ $tamountCommission }}" readonly>
                        </th>
                        <th class="px-1">
                            <input type="number" name="leader_order_qty" class="form-control input-sm text-center"
                                step="any" value="{{ $tleaderQty }}" readonly>
                        </th>
                        <th class="px-1">
                            <input type="number" name="leader_qty_commission"
                                class="form-control input-sm text-center" step="any"
                                value="{{ $tleaderQtyCommission }}" readonly>
                        </th>
                        <th class="px-1">
                            <input type="number" name="leader_order_amout" class="form-control input-sm text-center"
                                step="any" value="{{ $tleaderAmount }}" readonly>
                        </th>
                        <th class="px-1">
                            <input type="number" name="leader_amount_commission"
                                class="form-control input-sm text-center" step="any"
                                value="{{ $tleaderAmountCommission }}" readonly>
                        </th>
                        <th class="px-1">
                            <input type="number" name="total_commission" class="form-control input-sm text-center"
                                step="any"
                                value="{{ $tqtyCommission + $tamountCommission + $tleaderQtyCommission + $tleaderAmountCommission }}"
                                readonly>
                        </th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
