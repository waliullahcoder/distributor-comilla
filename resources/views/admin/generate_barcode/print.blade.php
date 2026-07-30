@extends('layouts.admin.print_app')
@push('css')
    <style>
        .table th,
        .table td {
            padding: 10px !important;
            border: none !important;
        }
    </style>
@endpush
@php
    error_reporting(0);
    use Milon\Barcode\DNS1D;
@endphp
@section('content')
    @foreach ($quantity as $key => $qty)
        @php
            $ending = $qty % 6;
            $total = $qty - $ending;
            $chunks = $total / 6;
        @endphp
        <div>
            <table class="table border-0">
                @for ($i = 0; $i < $chunks; $i++)
                    <tr>
                        @for ($j = 0; $j < 6; $j++)
                            <td class="text-center">
                                @php
                                    $getresult = substr($product_code[$key], 1);
                                    $barcode = new DNS1D();
                                @endphp
                                <img src="data:image/png;base64,{{ $barcode->getBarcodePNG($product_code[$key], 'C128', 1, 60, [1, 1, 1], true) }}"
                                    alt="barcode" />
                            </td>
                        @endfor
                    </tr>
                @endfor
                <tr>
                    @for ($k = 0; $k < $ending; $k++)
                        <td class="text-center">
                            @php
                                $getresult = substr($product_code[$key], 1);
                                $barcode = new DNS1D();
                            @endphp
                            <img src="data:image/png;base64,{{ $barcode->getBarcodePNG($product_code[$key], 'C128', 1, 60, [1, 1, 1], true) }}"
                                alt="barcode" />
                        </td>
                    @endfor
                </tr>
            </table>
        </div>
    @endforeach
@endsection
