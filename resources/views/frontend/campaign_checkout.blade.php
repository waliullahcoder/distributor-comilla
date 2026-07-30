<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $data->name }}</title>
    <link rel="shortcut icon"
        href="{{ !is_null($setting) && file_exists($setting->favicon) ? asset($setting->favicon) : asset('frontend/assets/images/logo/favicon.png') }}"
        type="image/x-icon">

    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/plugin.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/owl.theme.default.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/swiper.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/responsive.css') }}">

    <!-- Google Tag Manager -->
    <script>
        (function(w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({
                'gtm.start': new Date().getTime(),
                event: 'gtm.js'
            });
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s),
                dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src =
                'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', 'GTM-P28K7SWW');
    </script>
    <!-- End Google Tag Manager -->

    @include('layouts.admin.partial.alert')
    <style>
        .hind-siliguri-light {
            font-family: "Hind Siliguri", sans-serif;
            font-weight: 300;
            font-style: normal;
        }

        body,
        .hind-siliguri-regular {
            font-family: "Hind Siliguri", sans-serif;
            font-weight: 400;
            font-style: normal;
        }

        .hind-siliguri-medium {
            font-family: "Hind Siliguri", sans-serif;
            font-weight: 500;
            font-style: normal;
        }

        .hind-siliguri-semibold {
            font-family: "Hind Siliguri", sans-serif;
            font-weight: 600;
            font-style: normal;
        }

        .hind-siliguri-bold {
            font-family: "Hind Siliguri", sans-serif;
            font-weight: 700;
            font-style: normal;
        }

        .widget-video .wrapper {
            aspect-ratio: 1.8;
            max-width: 800px;
            margin: 0 auto;
        }

        .container {
            max-width: 1100px;
        }

        .wrapper iframe {
            height: 100%;
            width: 100%;
            display: flex;
            border: none;
            background-color: #000;
        }

        :root {
            --divider-pattern-url: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' preserveAspectRatio='none' overflow='visible' height='100%' viewBox='0 0 24 24' fill='none' stroke='black' stroke-width='2.4' stroke-linecap='square' stroke-miterlimit='10'%3E%3Cpath d='M0,6c6,0,0.9,11.1,6.9,11.1S18,6,24,6'/%3E%3C/svg%3E");
        }

        .bg-light {
            background-color: var(--bs-gray-100) !important;
        }

        .title-border {
            width: 100%;
            min-height: 20px;
            -webkit-mask-size: 18px 100%;
            mask-size: 18px 100%;
            -webkit-mask-repeat: repeat-x;
            mask-repeat: repeat-x;
            background-color: #777;
            -webkit-mask-image: var(--divider-pattern-url);
            mask-image: var(--divider-pattern-url);
            max-width: 800px;
        }

        .btn-primary {
            background-color: #44C40C;
            border-color: #44C40C;
            padding: 12px 30px 10px;
            font-size: 16px;
        }

        .btn-primary:focus,
        .btn-primary:hover {
            background-color: #f54;
            border-color: #f54;
        }

        .feature__list {
            font-size: 16px;
            font-family: "Hind Siliguri", sans-serif;
            font-weight: 600;
            font-style: normal;
        }

        .feature__list li {
            margin-bottom: 8px;
            color: #15A126;
        }

        .author {
            border-top: 1px solid var(--bs-gray);
            text-align: center;
            padding: 5px 0 6px;
            background-color: var(--bs-gray-200);
        }

        .helpline {
            background-color: #FF0000;
            padding: 13px 13px 13px 13px;
            color: var(--bs-white);
            font-family: "Hind Siliguri", sans-serif;
            font-weight: 500;
            font-style: normal;
            font-size: 20px;
            max-width: 300px;
            margin: 0 auto;
            border-radius: 10px;
        }

        .card {
            border-radius: 14px 14px 0 0 !important;
            overflow: hidden;
        }

        .card-header {
            background-color: transparent;
            background-image: linear-gradient(158deg, #02569B 0%, #0264b5 100%);
            padding: 20px;
        }

        .package-card {
            border-radius: 8px !important;
            border: 2px solid var(--bs-gray-300);
            padding: 20px;
            padding-left: 50px;
        }

        .package__selector {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
        }

        .order-package__label {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            z-index: 9;
            cursor: pointer;
        }

        .order-package_description {}

        .order-package__info {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .order-package__qty {
            max-width: 100px;
            position: relative;
            z-index: 20;
        }

        .order-package__qty input {
            max-width: 100%;
            text-align: center;
            border: none;
        }

        .order-package__price {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .qty_wrapper {
            border: 1px solid var(--bs-gray-500);
            display: flex;
            align-items: center;
            border-radius: 5px;
        }

        .qty-btn {
            width: 24px;
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            flex-shrink: 0;
            align-self: stretch;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        .checkout_btn:hover,
        .checkout_btn:focus,
        .checkout_btn {
            background-color: #008E0B;
            color: var(--bs-white);
            display: block;
            width: 100%;
            padding: 13px;
            font-size: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .form-input {
            padding: 12px 18px;
            font-size: 16px;
            border-width: 2px;
        }

        .table th,
        .table td {
            border: none;
            padding: 12px 0;
        }

        .table tbody,
        .table tfoot,
        .table thead {
            border: none !important;
        }

        .table tr {
            border-top: 1px dashed var(--bs-gray);
        }

        .table thead tr {
            border-top: none;
        }

        .payment_methods {
            margin: 1em 0 0;
            background-color: #f7f7f7;
            padding: 15px;
            color: #333;
            font-size: 16px;
        }

        .payment_box {
            position: relative;
            box-sizing: border-box;
            width: 100%;
            padding: 1em;
            margin: 1em 0;
            font-size: .92em;
            border-radius: 2px;
            line-height: 1.5;
            background-color: #dcd7e3;
            color: #515151;
            background-color: #eaeaea;
            color: #515151;
            font-family: inherit;
            font-weight: inherit;
            margin-bottom: 0.5em;
        }

        .payment_box::before {
            content: "";
            display: block;
            border: 1em solid;
            border-right-color: transparent;
            border-left-color: transparent;
            border-top-color: transparent;
            border-bottom-color: #eaeaea;
            position: absolute;
            top: -0.75em;
            left: 0;
            margin: -1em 0 0 2em;
        }

        .accordion-flush .accordion-item {
            margin-top: 10px;
        }

        .accordion-flush .accordion-item {
            margin-top: 10px;
            border: 2px solid #e9d6bc !important;
            border-radius: 10px;
            overflow: hidden;
        }

        .accordion-button:focus {
            box-shadow: inset 0 -1px 0 rgba(0, 0, 0, .125);
        }

        .accordion-button:not(.collapsed) {
            color: #444;
            background-color: var(--bs-white);
        }

        .icon-card {
            background-color: #bce1ff;
            padding: 25px;
            border-radius: 10px;
            color: #02569B;
        }

        .icon-card__title {
            font-weight: 600;
            color: #02569B;
        }

        .icon_desc_list {}

        .icon_desc_item {
            display: flex;
            align-items: center;
            gap: 16px;
            color: var(--bs-white);
            font-size: 22px;
            text-align: left;
            margin-top: 20px;
        }

        .icon {
            width: 80px;
            height: 80px;
            background-color: var(--bs-white);
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .desc {
            flex-grow: 1;
        }

        .icon_desc_list.reverse .icon_desc_item {
            text-align: right;
        }

        .icon_desc_list.reverse .icon {
            order: 3;
        }

        .bg-primary {
            background-color: #02569B !important;
        }

        @media (max-width: 991.98px) {
            .icon_desc_list.reverse .icon_desc_item {
                text-align: left;
            }

            .icon_desc_list.reverse .icon {
                order: 0;
            }
        }

        @media (max-width: 767.98px) {

            .h2,
            h2 {
                font-size: calc(1rem + .9vw);
            }

            .card-header {
                padding: 15px 15px 10px;
            }
        }

        .package__card {
            background-color: var(--bs-white);
            padding: 25px;
            border-radius: 6px;
            text-align: center;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .package__image {
            padding: 15px 35px 10px;
            flex-grow: 1;
        }

        .package__text {
            font-size: 12px;
            color: var(--bs-dark);
        }

        .package__link {
            text-align: center;
            padding-top: 1rem;
        }

        .package__link a {
            background-color: #00A651;
            color: var(--bs-white);
            padding: 13px 40px;
            display: inline-block;
            font-size: 16px;
            font-weight: 600;
            border-radius: 4px;
        }

        .package__info {
            flex-shrink: 0;
        }
    </style>
</head>

<body>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-P28K7SWW" height="0" width="0"
            style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->

    <div class="py-md-5 py-4 bg-light" id="checkout">
        <div class="container">
            <div class="card">
                <div class="card-header">
                    <h1 class="h2 mb-0 text-center hind-siliguri-bold text-white">অর্ডার করার জন্য নিচের ফর্মটি পূরণ
                        করুন-</h1>
                </div>
                <div class="card-body p-sm-4">
                    <form action="{{ Route('frontend.package-order.store') }}" method="POST">
                        <input type="hidden" name="order_package_id" value="{{ $data->id }}">
                        <input type="hidden" name="qty" value="1">
                        @csrf
                        <div class="pt-4">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <h4 class="h5 mb-3">Billing details</h4>
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <label for="user_name" class="form-label"><b>আপনার নাম <span
                                                        class="text-danger">*</span></b></label>
                                            <input type="text" name="user_name" id="user_name"
                                                class="form-control form-input" value="{{ old('user_name') }}"
                                                placeholder="আপনার নাম" required>
                                        </div>
                                        <div class="col-12">
                                            <label for="shipping_address" class="form-label"><b>সম্পূর্ণ ঠিকানা <span
                                                        class="text-danger">*</span></b></label>
                                            <input type="text" name="shipping_address" id="shipping_address"
                                                class="form-control form-input" value="{{ old('shipping_address') }}"
                                                placeholder="সম্পূর্ণ ঠিকানা" required>
                                        </div>
                                        <div class="col-12">
                                            <label for="user_phone" class="form-label"><b>মোবাইল নাম্বার <span
                                                        class="text-danger">*</span></b></label>
                                            <input type="number" name="user_phone" id="user_phone"
                                                class="form-control form-input" value="{{ old('user_phone') }}"
                                                placeholder="মোবাইল নাম্বার" required>
                                        </div>
                                        <div class="col-12">
                                            <label for="remarks" class="form-label"><b>অর্ডার নোট (যদি
                                                    থাকে)</b></label>
                                            <textarea name="remarks" id="remarks" cols="30" rows="1" class="form-control form-input"
                                                placeholder="অর্ডার নোট (যদি থাকে)">{{ old('remarks') }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h4 class="h5 mb-3">Your order</h4>
                                    <table class="shop_table table">
                                        <thead>
                                            <tr>
                                                <th class="product-name">Product</th>
                                                <th class="product-total text-end">Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="cart_item">
                                                <td class="product-name">
                                                    <h5 id="item_name">{{ @$data->name }} <strong class="product-quantity ms-2">&nbsp;×&nbsp;1</strong></h5>
                                                    <div class="package__text">
                                                        {{ @$data->description }}
                                                    </div>
                                                </td>
                                                <td class="product-total text-end subtotal">{{ @$data->amount }}</td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr class="cart-subtotal">
                                                <th>Subtotal</th>
                                                <td class="text-end subtotal">{{ @$data->amount }}</td>
                                            </tr>
                                            <tr class="woocommerce-shipping-totals shipping">
                                                <th>Shipping Charge</th>
                                                <td class="text-end shipping_charge">{{ @$data->shipping_charge }}
                                                </td>
                                            </tr>
                                            <tr class="order-total">
                                                <th>Total</th>
                                                <td class="text-end net_amount">{{ @$data->net_amount }}</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                    <ul class="payment_methods">
                                        <li class="payment_method_cod">
                                            <label>
                                                ক্যাশ অন হোম ডেলিভারী </label>
                                            <div class="payment_box">
                                                <p class="mb-0">পন্য হাতে পেয়ে মুল্য পরিশোধ করুন</p>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn checkout_btn mt-3 mb-0">অর্ডার কনফার্ম করুন
                                        <span class="net_amount">{{ @$data->net_amount }}</span> ৳ </button>
                                </div>
                            </div>
                            @if ($data->phone)
                                <div class="text-center">
                                    <div class="helpline"><a href="tel:{{ $data->phone }}">প্রয়োজনে কল
                                            করুন: {{ $data->phone }}</a></div>
                                </div>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="{{ asset('frontend/assets/js/plugin.js') }}"></script>
    <script type="text/javascript" src="{{ asset('frontend/assets/js/owl.carousel.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('frontend/assets/js/script.js') }}"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @include('sweetalert::alert')

    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function() {
            $(document).on('click', '.qty-btn', function() {
                var type = $(this).data('type');
                var id = $(this).data('id');
                var qty = +$('#qty_' + id).val();

                if (type == 'minus') {
                    qty--;
                    if (qty == 0) return;
                } else {
                    qty++;
                }
                $('#qty_' + id).val(qty);
                var order_package_id = $('input[name="order_package_id"]:checked').val();
                if (id == order_package_id) {
                    calculate(order_package_id, qty);
                }
            });

            $(document).on('change keyup', '.qty-input', function() {
                var checked_order_package_id = $('input[name="order_package_id"]:checked').val();
                var order_package_id = $(this).data('id');
                var qty = $(this).val();
                if (checked_order_package_id == order_package_id) {
                    calculate(order_package_id, qty);
                }
            });

            $(document).on('change', 'input[name="order_package_id"]', function() {
                var order_package_id = $('input[name="order_package_id"]:checked').val();
                var qty = $('#qty_' + order_package_id).val();
                calculate(order_package_id, qty);
            });

            function calculate(order_package_id, qty) {
                $.ajax({
                    url: '{{ url()->current() }}',
                    type: 'POST',
                    data: {
                        _method: 'GET',
                        order_package_id: order_package_id,
                        qty: qty
                    },
                    success: function(response) {
                        if (response.status == 'success') {
                            $('#item_name').text(response.data.name);
                            $('.product-quantity').text(' × ' + qty);
                            $('.subtotal').text(response.subtotal);
                            $('.net_amount').text(response.net_amount);
                            $('.shipping_charge').text(response.data.shipping_charge);
                        }
                    }
                });
            }
        });
    </script>
</body>

</html>
