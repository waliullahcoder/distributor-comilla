@if (count($combinations[0]) > 0)
    <table class="table table-bordered mb-0 align-middle">
        <thead>
            <tr>
                <td class="text-center">
                    {{ __('Variant') }}
                </td>
                <td class="text-center">
                    {{ __('Variant Lifting Price') }}
                </td>
                <td class="text-center">
                    {{ __('Variant Sale Price') }}
                </td>
                <td class="text-center">
                    {{ __('Discount') }}
                </td>
                <td class="text-center">
                    {{ __('SKU') }}
                </td>
                <td class="text-center">
                    {{ __('Photo') }}
                </td>
            </tr>
        </thead>
        <tbody>
            @foreach ($combinations as $key => $combination)
                @php
                    $sku = '';
                    // foreach (explode(' ', $product_name) as $key => $value) {
                    //     $sku .= substr($value, 0, 1);
                    // }

                    $str = '';
                    foreach ($combination as $key => $item) {
                        if ($key > 0) {
                            $str .= '-' . str_replace(' ', '', $item);
                            $sku .= '-' . str_replace(' ', '', $item);
                        } else {
                            $str .= str_replace(' ', '', $item);
                            $sku .= '-' . str_replace(' ', '', $item);
                        }
                    }
                @endphp
                @if (strlen($str) > 0)
                    <tr class="variant">
                        <td>
                            <label for="" class="control-label">{{ $str }}</label>
                        </td>
                        <td>
                            <input type="number" name="lifting_price_{{ $str }}" min="0" value="0"
                                step="0.01" class="form-control" value="0" required>
                        </td>
                        <td>
                            <input type="number" name="price_{{ $str }}" min="0" value="0"
                                step="0.01" class="form-control" value="0" required>
                        </td>
                        <td>
                            <input type="number" name="discount_tk_{{ $str }}" min="0" value="0"
                                step="0.01" class="form-control" value="0" required>
                        </td>
                        <td>
                            <input type="text" name="sku_{{ $str }}" readonly value="{{ $str }}"
                                class="form-control">
                        </td>
                        <td>
                            <input type="file" name="img_{{ $str }}" class="form-control" accept="image/*">
                        </td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>
@endif
