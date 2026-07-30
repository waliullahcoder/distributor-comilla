<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Thank You</title>
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
            font-size: 16px;
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

        .thank-heading {
            max-width: 500px;
            text-align: center;
            margin: 0 auto 2rem;
        }

        .thank-heading h1 {
            color: var(--bs-gray-900);
            font-weight: 700;
        }

        .thank-card {
            background-color: var(--bs-white);
            border-radius: 25px;
            box-shadow: 0px 12px 24px -12px rgba(0, 0, 0, 0.16);
            border: 2px solid #DEE0E3;
            padding: 40px;
            max-width: 720px;
            margin: 0 auto;
        }

        .thank-title {
            border: 2px dashed #7A9C59;
            text-align: center;
            color: var(--bs-gray-800);
            padding: 5px;
        }

        .thank-info {
            background-color: #F8FBFC;
            margin-top: 1rem;
            display: flex;
            flex-wrap: wrap;
            margin-bottom: 0.5rem;
        }

        .thank-info li {
            border-right: 1px dashed #ccc;
            padding: 0.5em 1em 0.5em 0;
            margin: 0.5em 0.5em 0.5em 0;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .thank-info li strong {
            color: var(--bs-gray-700);
        }

        .order-details {
            background-color: #F8FBFC;
            padding: 1rem;
        }

        .order-details__title {
            text-transform: uppercase;
            font-weight: 600;
        }

        .invoice_method {
            border: none !important;
        }

        @media (max-width: 767.98px) {

            .h2,
            h2 {
                font-size: calc(1rem + .9vw);
            }

            .card-header {
                padding: 15px 15px 10px;
            }

            .thank-card {
                border-radius: 15px;
                padding: 20px;
            }

            .thank-heading h5 {
                font-size: 1rem;
            }
        }
    </style>
</head>

<body>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-P28K7SWW" height="0" width="0"
            style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->

    <div class="py-md-5 py-4 bg-light">
        <div class="container">
            <div class="thank-heading">
                <h1>Order Confirmed</h1>
                <h5 class="text-muted">আপনার অর্ডারটি সম্পন্ন হয়েছে। আমরা দ্রুতই আপনাকে ফোন করে কনফার্ম করবো</h5>
            </div>
            <div class="thank-card">
                <h4 class="thank-title h5">Your order has been received</h4>
                <ul class="thank-info">
                    <li class="invoice_no">
                        Invoice No: <strong>{{ $data->invoice }}</strong>
                    </li>
                    <li class="invoice_date">
                        Date: <strong>{{ date('F d, Y', strtotime($data->date)) }}</strong>
                    </li>
                    <li class="invoice_total">
                        Total: <strong>
                            <span class="currencySymbol">৳&nbsp;</span>{{ number_format($data->due) }}
                        </strong>
                    </li>
                    <li class="invoice_method">
                        Payment method: <strong>Cash on delivery</strong>
                    </li>
                </ul>
                <p>Pay with cash upon delivery.</p>
                <div class="order-details">
                    <h5 class="order-details__title">Order details</h5>
                    <table class="table table--order-details shop_table order_details mb-0">
                        <thead>
                            <tr>
                                <th class="table__product-name product-name">Product</th>
                                <th class="table__product-table product-total text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="table__line-item order_item">
                                <td class="table__product-name product-name">
                                {{ @$data->package->description }}
                                </td>
                                <td class="table__product-total product-total text-end">
                                    <span class="Price-amount amount"><bdi><span
                                                class="Price-currencySymbol">৳&nbsp;</span>{{ number_format($data->sub_total) }}</bdi></span>
                                </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th scope="row">Subtotal:</th>
                                <td class="text-end"><span class="Price-amount amount"><span
                                            class="Price-currencySymbol">৳&nbsp;</span>{{ number_format($data->sub_total) }}</span>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">Shipping:</th>
                                <td class="text-end"><span class="Price-amount amount"><span
                                            class="Price-currencySymbol">৳&nbsp;</span>{{ $data->shipping_charge }}</span>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">Payment method:</th>
                                <td class="text-end text-nowrap">Cash on delivery</td>
                            </tr>
                            <tr>
                                <th scope="row">Total:</th>
                                <td class="text-end"><span class="Price-amount amount"><span
                                            class="Price-currencySymbol">৳&nbsp;</span>{{ number_format($data->due) }}</span>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
