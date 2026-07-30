@if (!is_null($package))
    <input type="hidden" id="package_active" value="1">
    <table class="table table-bordered table-striped target-table align-middle mb-0 text-nowrap">
        <thead class="bg-primary border-primary text-white">
            <tr>
                <th>Product Name</th>
                <th>Code</th>
                <th width="150" class="text-center">Rate</th>
                <th width="150" class="text-center">Quantity</th>
                <th width="150" class="text-center">Amount</th>
                <th class="text-center" width="50"><i class="far fa-trash-alt"></i></th>
            </tr>
        </thead>
        <tbody id="tbody">
            @foreach ($package->list as $key => $item)
                <tr>
                    <td>
                        <input type="hidden" class="product_id{{ $item->product_id }}"
                            name="product_id[{{ $key }}]" value="{{ $item->product_id }}">
                        <span>{{ @$item->product->name }}</span>
                    </td>
                    <td>{{ @$item->product->code }}</td>
                    <td><input style="min-width: 80px;" class="form-control input-sm text-center rate" type="number"
                            name="price[{{ $key }}]" step="any" value="{{ $item->rate }}" required></td>
                    <td><input style="min-width: 80px;" class="form-control input-sm text-center qty" type="number"
                            name="quantity[{{ $key }}]" step="any" value="{{ $item->qty }}" required>
                    </td>
                    <td><input style="min-width: 80px;" class="form-control input-sm text-center amount" type="number"
                            name="amount[{{ $key }}]" step="any" value="{{ $item->amount }}" required>
                    </td>
                    <td class="text-center"><button type="button"
                            class="btn btn-xs btn-outline-danger remove_item mnw-auto px-2"><i
                                class="far fa-trash-alt"></i></button></td>
                </tr>
            @endforeach
        </tbody>
        <tfoot class="bg-primary text-white align-top border-primary">
            <tr>
                <td colspan="2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="discount_type" id="fixed" checked
                            value="fixed">
                        <label class="form-check-label" for="fixed">
                            Fix Discount
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="discount_type" id="percentage"
                            value="percentage">
                        <label class="form-check-label" for="percentage">
                            Discount (%)
                        </label>
                    </div>
                    <input type="number" max="100" min="1" id="percentage_input" step="any"
                        class="form-control mt-2" placeholder="Discount Percentage"
                        style="display: none; max-width: 250px;">
                </td>
                <td colspan="4">
                    <div class="input-group align-items-center mb-1 flex-nowrap justify-content-end">
                        <span style="min-width: 120px;">Total</span>
                        <input type="number" id="total_amount" name="total_amount" readonly class="form-control"
                            style="min-width: 100px; max-width: 150px;" placeholder="Total Cost" value="{{ $package->amount }}">
                        <span class="text-center" style="width: 40px;">TK.</span>
                    </div>
                    <div class="input-group align-items-center mb-1 flex-nowrap justify-content-end">
                        <span style="min-width: 120px;">Shipping Charge</span>
                        <input type="number" id="shipping_charge" name="shipping_charge" class="form-control"
                            style="min-width: 100px; max-width: 150px;" value="{{ $package->shipping_charge }}">
                        <span class="text-center" style="width: 40px;">TK.</span>
                    </div>
                    <div class="input-group align-items-center mb-1 flex-nowrap justify-content-end">
                        <span style="min-width: 120px;">Discount</span>
                        <input type="number" id="discount" name="discount" class="form-control"
                            style="min-width: 100px; max-width: 150px;" placeholder="Discount" value="{{ $package->discount }}">
                        <span class="text-center" style="width: 40px;">TK.</span>
                    </div>
                    <div class="input-group align-items-center mb-1 flex-nowrap justify-content-end">
                        <span style="min-width: 120px;">Advance</span>
                        <input type="number" id="advance" name="advance" class="form-control"
                            style="min-width: 100px; max-width: 150px;" placeholder="Advance" value="0">
                        <span class="text-center" style="width: 40px;">TK.</span>
                    </div>
                    <div class="input-group align-items-center flex-nowrap justify-content-end">
                        <span style="min-width: 120px;">Net Payable</span>
                        <input type="number" id="net_payable" name="net_payable" readonly class="form-control"
                            style="min-width: 100px; max-width: 150px;" placeholder="net Payable" value="{{ $package->net_amount }}">
                        <span class="text-center" style="width: 40px;">TK.</span>
                    </div>
                </td>
            </tr>
        </tfoot>
    </table>
@else
    <table class="table table-bordered table-striped target-table align-middle mb-0 text-nowrap">
        <thead class="bg-primary border-primary text-white">
            <tr>
                <th>Product Name</th>
                <th>Code</th>
                <th width="150" class="text-center">Rate</th>
                <th width="150" class="text-center">Quantity</th>
                <th width="150" class="text-center">Amount</th>
                <th class="text-center" width="50"><i class="far fa-trash-alt"></i></th>
            </tr>
        </thead>
        <tbody id="tbody">
        </tbody>
        <tfoot class="bg-primary text-white align-top border-primary">
            <tr>
                <td colspan="2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="discount_type" id="fixed" checked
                            value="fixed">
                        <label class="form-check-label" for="fixed">
                            Fix Discount
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="discount_type" id="percentage"
                            value="percentage">
                        <label class="form-check-label" for="percentage">
                            Discount (%)
                        </label>
                    </div>
                    <input type="number" max="100" min="1" id="percentage_input" step="any"
                        class="form-control mt-2" placeholder="Discount Percentage"
                        style="display: none; max-width: 250px;">
                </td>
                <td colspan="4">
                    <div class="input-group align-items-center mb-1 flex-nowrap justify-content-end">
                        <span style="min-width: 120px;">Total</span>
                        <input type="number" id="total_amount" name="total_amount" readonly class="form-control"
                            style="min-width: 100px; max-width: 150px;" placeholder="Total Cost" value="0">
                        <span class="text-center" style="width: 40px;">TK.</span>
                    </div>
                    <div class="input-group align-items-center mb-1 flex-nowrap justify-content-end">
                        <span style="min-width: 120px;">Shipping Charge</span>
                        <input type="number" id="shipping_charge" name="shipping_charge" class="form-control"
                            style="min-width: 100px; max-width: 150px;" value="">
                        <span class="text-center" style="width: 40px;">TK.</span>
                    </div>
                    <div class="input-group align-items-center mb-1 flex-nowrap justify-content-end">
                        <span style="min-width: 120px;">Discount</span>
                        <input type="number" id="discount" name="discount" class="form-control"
                            style="min-width: 100px; max-width: 150px;" placeholder="Discount" value="0">
                        <span class="text-center" style="width: 40px;">TK.</span>
                    </div>
                    <div class="input-group align-items-center mb-1 flex-nowrap justify-content-end">
                        <span style="min-width: 120px;">Advance</span>
                        <input type="number" id="advance" name="advance" class="form-control"
                            style="min-width: 100px; max-width: 150px;" placeholder="Advance" value="0">
                        <span class="text-center" style="width: 40px;">TK.</span>
                    </div>
                    <div class="input-group align-items-center flex-nowrap justify-content-end">
                        <span style="min-width: 120px;">Net Payable</span>
                        <input type="number" id="net_payable" name="net_payable" readonly class="form-control"
                            style="min-width: 100px; max-width: 150px;" placeholder="net Payable" value="0">
                        <span class="text-center" style="width: 40px;">TK.</span>
                    </div>
                </td>
            </tr>
        </tfoot>
    </table>
@endif
