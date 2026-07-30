<table class="table mb-0">
    <thead class="bg-primary text-white">
        <tr>
            <th class="text-center" width="30">SL#</th>
            <th width="100">Invest No</th>
            <th width="100">Invest Date</th>
            <th>Investor</th>
            <th class="text-center" width="150">Qty</th>
            <th class="text-center" width="150">Amount</th>
            <th class="text-center px-3" width="50">
                <div class="custom-control custom-checkbox mx-auto">
                    <input type="checkbox" class="custom-control-input" id="checkAll">
                    <label for="checkAll" class="custom-control-label"></label>
                </div>
            </th>
        </tr>
    </thead>
    <tbody>
        @php
            $key = 1;
        @endphp
        @foreach ($invests as $row)
            @php
                $date = \Carbon\Carbon::parse(date('Y-m-d', strtotime($date)));
                $checked = null;
                if (isset($data)) {
                    if (
                        $data->month == $date->format('F') &&
                        $data->year == $date->year &&
                        in_array($row->id, $data->list->pluck('invest_id')->toArray())
                    ) {
                        $checked = true;
                    }
                }
            @endphp
            <tr>
                <td class="text-center" width="30">{{ $key++ }}</td>
                <td>{{ $row->invest_no }}</td>
                <td>{{ date('d-m-Y', strtotime($row->date)) }}</td>
                <td>{{ $row->investor->name }}</td>
                <td class="text-center">
                    <input type="number" class="form-control input-sm mx-auto text-center"
                        style="min-height: auto; width: 150px;" id="qty_{{ $row->id }}"
                        name="qty[{{ $row->id }}]" placeholder="Quantity" value="{{ $row->qty }}" readonly>
                </td>
                <td class="text-center">
                    <input type="text" class="form-control input-sm mx-auto text-center"
                        style="min-height: auto; width: 150px;" id="amount_{{ $row->id }}"
                        name="amount[{{ $row->id }}]" placeholder="Amount" value="{{ $row->amount }}" readonly>
                </td>
                <td class="text-center">
                    <div class="custom-control custom-checkbox mx-auto">
                        <input type="hidden" name="investor_id[{{ $row->id }}]" value="{{ $row->investor_id }}">
                        <input type="checkbox" class="custom-control-input checkbox invest_id"
                            id="check_{{ $row->id }}" name="invest_id[]" value="{{ $row->id }}"
                            {{ !is_null($checked) ? 'checked' : '' }}>
                        <label for="check_{{ $row->id }}" class="custom-control-label"></label>
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
    <tfoot class="bg-primary text-white">
        <tr>
            <th class="text-end" colspan="4">Total</th>
            <th class="text-center">
                <input type="text" class="form-control input-sm mx-auto text-center"
                    style="min-height: auto; width: 150px;" id="total_qty"
                    value="{{ isset($data) ? $data->list->sum('qty') : 0 }}" placeholder="Total Qty" readonly>
            </th>
            <th class="text-center">
                <input type="text" class="form-control input-sm mx-auto text-center"
                    style="min-height: auto; width: 150px;" id="total_amount"
                    value="{{ isset($data) ? $data->list->sum('amount') : 0 }}" placeholder="Total Amount" readonly>
            </th>
            <th></th>
        </tr>
    </tfoot>
</table>
