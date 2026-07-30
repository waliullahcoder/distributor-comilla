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
            padding: 15px 15px 10px;
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
    </style>
</head>

<body>    
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-P28K7SWW" height="0" width="0"
            style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    
    @foreach ($data->list as $item)
        <div
            class="py-4 {{ $loop->iteration % 2 == 1 ? 'bg-light' : 'bg-white' }} {{ in_array($item->type, ['list_image', 'image_description', 'description_image']) ? 'text-center' : '' }} {{ $item->type == 'reviews' || $item->type == 'list_image' ? 'bg-primary' : '' }}">
            <div class="container">
                @if ($item->type == 'video_description')
                    @if (!is_null($item->title))
                        <h2 class="h2 title mb-1 text-center hind-siliguri-bold">{{ $item->title }}</h2>
                        <div class="title-border mb-3 mx-auto"></div>
                    @endif
                    <div class="widget-video">
                        <div class="wrapper">
                            <iframe width="560" height="315"
                                src="https://www.youtube.com/embed/{{ $item->video }}" title="YouTube video player"
                                frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                        </div>
                    </div>
                    @if ($item->description)
                        <div class="mt-sm-4 mt-3">
                            {!! $item->description !!}
                        </div>
                    @endif
                    <div class="text-center mt-sm-4 mt-3">
                        <a class="btn btn-primary rounded-pill hind-siliguri-medium" href="#checkout">
                            <span>অর্ডার করুন </span>
                        </a>
                    </div>
                @elseif ($item->type == 'list')
                    @php
                        $list = explode('|', @$item->list);
                    @endphp
                    @if (!is_null($item->title))
                        <h2 class="h2 title mb-4 hind-siliguri-bold">{{ $item->title }}</h2>
                        <div class="title-border mb-3"></div>
                    @endif
                    <ul class="feature__list">
                        @foreach ($list as $single)
                            <li><span class="me-2">✦</span>{{ $single }}</li>
                        @endforeach
                    </ul>
                @elseif ($item->type == 'list_image')
                    <div class="py-3">
                        @if (!is_null($item->title))
                            <h2 class="h2 title mb-4 text-center text-white hind-siliguri-bold">
                                {{ $item->title }}
                            </h2>
                        @endif
                        <div class="row g-4">
                            <div class="col-lg-4">
                                <div class="icon_desc_list reverse">
                                    <div class="icon_desc_item">
                                        <div class="icon">
                                            <img src="{{ asset('frontend/assets/images/icons/icon1.png') }}"
                                                height="60" alt="">
                                        </div>
                                        <div class="desc">ফাস্ট ডেলিভারি</div>
                                    </div>
                                    <div class="icon_desc_item">
                                        <div class="icon">
                                            <img src="{{ asset('frontend/assets/images/icons/icon2.png') }}"
                                                height="60" alt="">
                                        </div>
                                        <div class="desc">১০০% হালাল</div>
                                    </div>
                                    <div class="icon_desc_item">
                                        <div class="icon">
                                            <img src="{{ asset('frontend/assets/images/icons/icon3.png') }}"
                                                height="60" alt="">
                                        </div>
                                        <div class="desc">ফ্রেশ এবং টেস্টি</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 text-lg-center text-start">
                                <img src="{{ asset($item->image) }}" alt="">
                            </div>
                            <div class="col-lg-4">
                                <div class="icon_desc_list">
                                    <div class="icon_desc_item">
                                        <div class="icon">
                                            <img src="{{ asset('frontend/assets/images/icons/icon5.png') }}"
                                                height="60" alt="">
                                        </div>
                                        <div class="desc">জিরো টেস্টিং সল্ট</div>
                                    </div>
                                    <div class="icon_desc_item">
                                        <div class="icon">
                                            <img src="{{ asset('frontend/assets/images/icons/icon6.png') }}"
                                                height="60" alt="">
                                        </div>
                                        <div class="desc">হোমমেড</div>
                                    </div>
                                    <div class="icon_desc_item">
                                        <div class="icon">
                                            <img src="{{ asset('frontend/assets/images/icons/icon4.png') }}"
                                                height="60" alt="">
                                        </div>
                                        <div class="desc">১০০% হাইজেনিক</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-center mt-sm-4 mt-3">
                            <a class="btn btn-primary rounded-pill hind-siliguri-medium" href="#checkout">
                                <span>অর্ডার করতে চাই</span>
                            </a>
                        </div>
                    </div>
                @elseif ($item->type == 'image_description')
                    <div class="row g-4">
                        <div class="col-12">
                            <img src="{{ asset($item->image) }}" alt="">
                        </div>
                        <div class="col-12">
                            @if (!is_null($item->title))
                                <h2 class="h2 title mb-1 text-center hind-siliguri-bold">{{ $item->title }}
                                </h2>
                                {{-- <div class="title-border mx-auto mb-3"></div> --}}
                            @endif
                            <div>
                                {!! $item->description !!}
                            </div>
                        </div>
                    </div>
                    <div class="text-center">
                        <a class="btn btn-primary rounded-pill hind-siliguri-medium" href="#checkout">
                            <span>অর্ডার করতে চাই </span>
                        </a>
                    </div>
                @elseif ($item->type == 'description_image')
                    <div class="row g-4">
                        <div class="col-12">
                            @if (!is_null($item->title))
                                <h2 class="h2 title mb-1 text-center hind-siliguri-bold">{{ $item->title }}
                                </h2>
                                {{-- <div class="title-border mx-auto mb-3"></div> --}}
                            @endif
                            <div>
                                {!! $item->description !!}
                            </div>
                            <img src="{{ asset($item->image) }}" alt="">
                        </div>
                    </div>
                    <div class="text-center mt-3">
                        <a class="btn btn-primary rounded-pill hind-siliguri-medium" href="#checkout">
                            <span>অর্ডার করতে চাই </span>
                        </a>
                    </div>
                @elseif ($item->type == 'description')
                    @if (!is_null($item->title))
                        <h2 class="h2 title mb-4 text-center hind-siliguri-bold">{{ $item->title }}</h2>
                        {{-- <div class="title-border mx-auto mb-3"></div> --}}
                    @endif
                    <div>
                        {!! $item->description !!}
                    </div>
                @elseif ($item->type == 'facilities')
                    @if (!is_null($item->title))
                        <h2 class="h2 title mb-4 text-center hind-siliguri-bold">{{ $item->title }}</h2>
                        {{-- <div class="title-border mx-auto mb-3"></div> --}}
                        <div class="row g-3 justify-content-center">
                            @foreach ($data->facilities as $facility)
                                <div class="col-md-6">
                                    <div class="icon-card">
                                        <h3 class="icon-card__title h4 mb-2 sm-mb-1">{{ $facility->title }}</h3>
                                        <p class="text mb-0">{{ $facility->description }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                @elseif ($item->type == 'faqs')
                    @if (!is_null($item->title))
                        <h2 class="h2 title mb-4 text-center hind-siliguri-bold">{{ $item->title }}</h2>
                        {{-- <div class="title-border mx-auto mb-3"></div> --}}
                    @endif
                    <div class="accordion accordion-flush" id="accordionFlushExample">
                        @foreach ($data->faqs as $faq)
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="flush-heading{{ $loop->iteration }}">
                                    <button class="accordion-button collapsed" type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#flush-collapse{{ $loop->iteration }}" aria-expanded="false"
                                        aria-controls="flush-collapse{{ $loop->iteration }}">
                                        {{ $faq->title }}
                                    </button>
                                </h2>
                                <div id="flush-collapse{{ $loop->iteration }}" class="accordion-collapse collapse"
                                    aria-labelledby="flush-heading{{ $loop->iteration }}"
                                    data-bs-parent="#accordionFlushExample">
                                    <div class="accordion-body">{{ $faq->description }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @elseif ($item->type == 'reviews')
                    @if (!is_null($item->title))
                        <h2 class="h2 title mb-2 text-center hind-siliguri-bold text-white">{{ $item->title }}</h2>
                        {{-- <div class="title-border mx-auto mb-3"></div> --}}
                    @endif
                    <div>
                        <div class="carousel owl-carousel" data-autoplay="true" data-items="3" data-xl-items="3"
                            data-lg-items="3" data-md-items="3" data-sm-items="2" data-xs-items="2"
                            data-loop="true">
                            @foreach ($data->reviews as $single)
                                <div class="review__wrapper">
                                    <img src="{{ asset($single->image) }}" alt="{{ $single->name }}">
                                    @if ($single->name)
                                        <h6 class="h6 mb-0 author">{{ $single->name }}</h6>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @endforeach

    <div class="py-md-5 py-4 bg-primary" id="checkout">
        <div class="container">
            <div class="row g-4 justify-content-center">
                @foreach ($data->packages as $item)
                    <div class="col-lg-5 col-md-6">
                        <div class="package__card">
                            <div class="package__image">
                                <img src="{{ asset(file_exists(@$item->package->image) ? @$item->package->image : 'frontend/assets/images/package/1.jpg') }}"
                                    alt="Package Image">
                            </div>
                            <div class="package__info">
                                <h2 class="package__title h3 mb-1">{{ @$item->package->name }}</h2>
                                @if (@$item->package->discount == 0)
                                    <h3 class="h3 mb-2 fw-bold">{{ @$item->package->amount }}/-</h3>
                                @else
                                    <h3 class="h3 mb-2 text-danger">
                                        <del>{{ @$item->package->amount + @$item->package->discount }}/-
                                        </del>
                                    </h3>
                                    <h3 class="h3 mb-2 fw-bold">
                                        {{ @$item->package->amount }}/-
                                    </h3>
                                @endif
                                <div class="package__text">
                                    {{ @$item->package->description }}
                                </div>
                                <div class="package__link">
                                    <a href="{{ Route('frontend.package-checkout', @$item->package->slug) }}">অর্ডার
                                        করুন</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                <div class="col-lg-5 col-md-6">
                    <div class="package__card">
                        <div class="package__image">
                            <img src="{{ asset('frontend/assets/images/package/2.jpg') }}" alt="Package Image">
                        </div>
                        <div class="package__info">
                            <h2 class="h4 package__title">কাস্টমাইজ করতে চাইলে আমাদের সাথে যোগাযোগ করুন</h2>
                            <a href="tel: {{ $data->phone }}">
                                <h3 class="h3 mb-0 fw-bold text-white bg-danger py-2 rounded"
                                    style="letter-spacing: 2px;">
                                    {{ $data->phone }}</h3>
                            </a>
                            <div class="package__link">
                                <a href="https://www.facebook.com/FrozenFoodibd?mibextid=ZbWKwL" target="_blank">মেসেজ করুন</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="py-md-5 py-4 bg-dark text-center" id="helpline_section">
        <div class="container">
            <h4 class="text-white">কথা বলুন আমাদের প্রতিনিধির সাথে</h4>
            <h4 class="h1 text-warning mb-0"><a href="tel: {{ $data->phone }}">{{ $data->phone }}</a></h4>
        </div>
    </div>

    <script type="text/javascript" src="{{ asset('frontend/assets/js/plugin.js') }}"></script>
    <script type="text/javascript" src="{{ asset('frontend/assets/js/owl.carousel.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('frontend/assets/js/script.js') }}"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    @include('sweetalert::alert')
</body>

</html>
