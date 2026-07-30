<table class="table mb-0">
    <thead class="bg-primary text-white">
        <tr>
            <th class="text-center" width="30">SL#</th>
            <th>Year</th>
            <th>Month</th>
            <th class="text-center" width="150">Invest Qty</th>
            <th class="text-center" width="150">Invest Amount</th>
            <th class="text-center" width="150">Profit Amount</th>
            <th class="text-center px-3" width="50">
                <div class="custom-control custom-checkbox mx-auto">
                    <input type="checkbox" class="custom-control-input" id="checkAll">
                    <label for="checkAll" class="custom-control-label"></label>
                </div>
            </th>
        </tr>
    </thead>
    <tbody>
        @foreach ($list as $row)
            <tr>
                <td class="text-center" width="30">{{ $loop->iteration }}</td>
                <td>{{ $row->year }}</td>
                <td>{{ $row->month }}</td>
                <td class="text-center">
                    <input type="number" class="form-control input-sm mx-auto text-center"
                        style="min-height: auto; width: 150px;" placeholder="Invest Qty" value="{{ $row->invest_qty }}"
                        readonly>
                </td>
                <td class="text-center">
                    <input type="number" class="form-control input-sm mx-auto text-center"
                        style="min-height: auto; width: 150px;" placeholder="Invest Amount"
                        value="{{ $row->invest_amount }}" readonly>
                </td>
                <td class="text-center">
                    <input type="number" class="form-control input-sm mx-auto text-center"
                        style="min-height: auto; width: 150px;" id="profit_amount{{ $row->id }}"
                        placeholder="Profit Amount" value="{{ $row->profit_amount }}" readonly>
                </td>
                <td class="text-center">
                    <div class="custom-control custom-checkbox mx-auto">
                        <input type="checkbox" class="custom-control-input checkbox list_id"
                            id="check_{{ $row->id }}" name="profit_distribution_list_id[]" value="{{ $row->id }}"
                            {{ isset($data) && in_array($row->id, $data->list->pluck('profit_distribution_list_id')->toArray()) ? 'checked' : '' }}>
                        <label for="check_{{ $row->id }}" class="custom-control-label"></label>
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
    <tfoot class="bg-primary text-white">
        <tr>
            <th class="text-end" colspan="5">Total</th>
            <th class="text-center">
                <input type="text" class="form-control input-sm mx-auto text-center"
                    style="min-height: auto; width: 150px;" id="total_amount" name="total_amount"
                    value="{{ isset($data) ? $data->list->sum('profit_amount') : 0 }}" placeholder="Total Amount"
                    readonly>
            </th>
            <th></th>
        </tr>
    </tfoot>
</table>
