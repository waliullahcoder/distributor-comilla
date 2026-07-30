@extends('layouts.frontend.app')
@section('content')
    <div class="py-4 bg-white">
        <div class="container">
            <div class="row g-3 justify-content-center">
                @foreach ($pre_orders as $item)
                    <div class="col-md-4">
                        <div class="pre-order__item">
                            <a href="{{ Route('frontend.pre-order', $item->slug) }}">
                                <img src="{{ asset($item->image) }}" alt="">
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="pb-md-5 pb-4 pt-4">
        <div class="container">
            {{-- <div class="block-title">
                <h2 class="b-title h2">Trending</h2>
                <p class="title-desc mb-sm-3 mb-2">Recommended for You</p>
            </div> --}}
            {{-- <div class="carousel trending-carousel owl-carousel" data-margin="8" data-items="6" data-xl-items="5"
                data-lg-items="4" data-md-items="3" data-xs-items="2" data-arrows="true"> --}}
            <div class="row g-sm-3 g-2">
                @foreach ($trending_products as $product)
                    <div class="col-xl-custom col-lg-3 col-md-4 col-6">
                        <div class="product-card">
                            <div class="product-card__thumbnail">
                                @php
                                    $discount = 0;
                                    if ($product->product_type == 'Consumer' && $product->price->discount_tk > 0) {
                                        $price = $product->price->online_price;
                                        $discount_tk = $product->price->discount_tk;
                                        $discount = ceil(($discount_tk / $price) * 100);
                                    } elseif ($product->product_type == 'Fashion') {
                                        $sku = $product->sku->first();
                                        $price = $sku->price;
                                        $discount_tk = $sku->discount_tk;
                                        $discount = ceil(($discount_tk / $price) * 100);
                                    }
                                @endphp
                                @if ($discount > 0)
                                    <span class="discount">-{{ $discount }}%</span>
                                @endif
                                <div class="actions-secondary">
                                    <a href="{{ Auth::check() ? 'javascript:void(0)' : Route('customer.login') }}"
                                        class="action {{ Auth::check() ? 'add-to-wishlist' : '' }}" title="Add to Wishlist"
                                        data-id="{{ $product->id }}">
                                        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" height="16"
                                            viewBox="0 0 1024 1024">
                                            <g id="icomoon-ignore">
                                            </g>
                                            <path fill="currentColor"
                                                d="M934.176 168.48c-116.128-115.072-301.824-117.472-422.112-9.216-120.32-108.256-305.952-105.856-422.144 9.216-119.712 118.528-119.712 310.688 0 429.28 34.208 33.888 353.696 350.112 353.696 350.112 37.856 37.504 99.072 37.504 136.896 0 0 0 349.824-346.304 353.696-350.112 119.744-118.592 119.744-310.752-0.032-429.28zM888.576 552.576l-353.696 350.112c-12.576 12.512-33.088 12.512-45.6 0l-353.696-350.112c-94.4-93.44-94.4-245.472 0-338.912 91.008-90.080 237.312-93.248 333.088-7.104l43.392 39.040 43.36-39.040c95.808-86.144 242.112-83.008 333.12 7.104 94.4 93.408 94.4 245.44 0.032 338.912zM296.096 240.032c8.864 0 16 7.168 16 16s-7.168 16-16 16h-0.032c-57.408 0-103.968 46.56-103.968 103.968v0.032c0 8.832-7.168 16-16 16s-16-7.168-16-16v0c0-75.072 60.832-135.904 135.872-135.968 0.064 0 0.064-0.032 0.128-0.032z">
                                            </path>
                                        </svg>
                                    </a>
                                    <a class="action quickview-handler" title="Quick View" href="javascript:void(0)"
                                        data-id="{{ $product->id }}">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                                <a href="{{ Route('frontend.single-product', $product->slug) }}">
                                    <div class="ratio ratio-1x1">
                                        <img class="fit-cover"
                                            src="{{ file_exists($product->thumbnail) ? asset($product->thumbnail) : asset(@$setting->placeholder) }}"
                                            alt="{{ $product->name }}">
                                    </div>
                                </a>
                            </div>
                            <div class="product-card__details">
                                <a class="product-item-link"
                                    href="{{ Route('frontend.single-product', $product->slug) }}">{{ $product->name }}
                                </a>
                                <div class="price-box">
                                    @if ($product->product_type == 'Consumer')
                                        <div class="d-flex justify-content-between gap-2">
                                            <div class="flex-grow-1">
                                                <span class="special-price">
                                                    <span class="price">TK
                                                        {{ number_format($product->price->online_price - $product->price->discount_tk) }}</span>
                                                </span>
                                                @if (@$product->price->discount_tk > 0)
                                                    <span class="old-price">
                                                        <span class="price">TK
                                                            {{ number_format($product->price->online_price) }}</span>
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="flex-shrink-0">
                                                Per {{ @$product->attribute->name }}
                                            </div>
                                        </div>
                                    @else
                                        @php
                                            $sku = $product->sku->first();
                                        @endphp
                                        <span class="special-price">
                                            <span class="price">TK
                                                {{ number_format($sku->price - $sku->discount_tk) }}</span>
                                        </span>
                                        @if ($sku->discount_tk > 0)
                                            <span class="old-price">
                                                <span class="price">TK {{ number_format($sku->price) }}</span>
                                            </span>
                                        @endif
                                    @endif
                                </div>
                                <div class="product-view-wrap">
                                    <div class="actions-primary">
                                        <button type="button"
                                            class="btn action {{ count(@$product->sku) > 0 ? 'toModal' : 'toCart' }} btn-primary"
                                            title="Add to Cart" data-id="{{ $product->id }}">
                                            <span>
                                                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" height="16"
                                                    viewBox="0 0 512 448">
                                                    <g id="icomoon-ignore">
                                                    </g>
                                                    <path fill="currentColor"
                                                        d="M431.932 198.865c13.942 0 25.135 11.193 25.135 25.135s-11.193 25.135-25.135 25.135h-2.946l-22.582 129.996c-2.16 11.978-12.568 20.815-24.742 20.815h-251.352c-12.175 0-22.582-8.836-24.742-20.815l-22.582-129.996h-2.946c-13.942 0-25.135-11.193-25.135-25.135s11.193-25.135 25.135-25.135h351.892zM150.144 355.96c6.872-0.59 12.175-6.676 11.586-13.551l-6.284-81.689c-0.59-6.872-6.676-12.175-13.551-11.586s-12.175 6.676-11.586 13.551l6.284 81.689c0.59 6.48 6.088 11.586 12.568 11.586h0.982zM230.851 343.392v-81.689c0-6.872-5.694-12.568-12.568-12.568s-12.568 5.694-12.568 12.568v81.689c0 6.872 5.694 12.568 12.568 12.568s12.568-5.694 12.568-12.568zM306.257 343.392v-81.689c0-6.872-5.694-12.568-12.568-12.568s-12.568 5.694-12.568 12.568v81.689c0 6.872 5.694 12.568 12.568 12.568s12.568-5.694 12.568-12.568zM375.378 344.374l6.284-81.689c0.59-6.872-4.713-12.96-11.586-13.551s-12.96 4.713-13.551 11.586l-6.284 81.689c-0.59 6.872 4.713 12.96 11.586 13.551h0.982c6.48 0 11.978-5.106 12.568-11.586zM148.376 105.393l-18.262 80.904h-25.921l19.833-86.599c5.106-22.975 25.332-39.078 48.896-39.078h32.794c0-6.872 5.694-12.568 12.568-12.568h75.405c6.872 0 12.568 5.694 12.568 12.568h32.794c23.564 0 43.79 16.102 48.896 39.078l19.833 86.599h-25.921l-18.262-80.904c-2.749-11.586-12.764-19.636-24.546-19.636h-32.794c0 6.872-5.694 12.568-12.568 12.568h-75.405c-6.872 0-12.568-5.694-12.568-12.568h-32.794c-11.782 0-21.797 8.051-24.546 19.636z">
                                                    </path>
                                                </svg>
                                            </span>
                                            <span>Add to Cart</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <!-- End Trending Proudcts -->

    @php
        $sections = \App\Models\HomeSection::where('status', 1)->get();
    @endphp
    @foreach ($sections as $section)
        <div class="pb-md-5 pb-4">
            <div class="container">
                <div class="product-area__card">
                    <div class="row g-0 align-items-center title-section">
                        <div class="col-sm-4 col-6">
                            <div class="block-title mb-0">
                                <h2 class="b-title h3">{{ @$section->category->name }}</h2>
                            </div>
                        </div>
                        <div class="col-sm-8 tab-links-title col-6">
                            @if (count($section->sub_categories) > 0)
                                <a href="javascript:void(0)"
                                    class="mobile-toggle d-sm-none">{{ @$section->sub_categories->first()->category->name }}</a>
                            @endif
                            <ul class="nav nav-pills" id="pills-tab-1" role="tablist">
                                @foreach ($section->sub_categories as $key => $item)
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="pills-{{ 'cat' . $section->id . $item->id }}-tab"
                                            data-bs-toggle="pill"
                                            data-bs-target="#pills-{{ 'cat' . $section->id . $item->id }}" type="button"
                                            role="tab" aria-controls="pills-{{ 'cat' . $section->id . $item->id }}"
                                            aria-selected="false">{{ @$item->category->name }}</button>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div class="tab-content">
                        @if (count($section->sub_categories) == 0)
                            @php
                                $totalProduct = \App\Models\Product::where(
                                    'category_id',
                                    @$section->category_id,
                                )->count();
                                $limit = ceil($totalProduct / 2);
                                $lastLimit = $totalProduct - $limit;
                                if ($totalProduct >= 10) {
                                    $products_01 = \App\Models\Product::where('category_id', @$section->category_id)
                                        ->skip(0)
                                        ->take($limit)
                                        ->orderBy('id', 'desc')
                                        ->get();
                                    $products_02 = \App\Models\Product::where('category_id', @$section->category_id)
                                        ->skip($limit)
                                        ->take($limit)
                                        ->orderBy('id', 'desc')
                                        ->get();
                                } else {
                                    $products_01 = \App\Models\Product::where('category_id', @$section->category_id)
                                        ->skip(0)
                                        ->take(5)
                                        ->orderBy('id', 'desc')
                                        ->get();
                                    $products_02 = \App\Models\Product::where('category_id', @$section->category_id)
                                        ->skip(5)
                                        ->take(5)
                                        ->orderBy('id', 'desc')
                                        ->get();
                                }
                            @endphp
                            <div class="carousel owl-carousel" data-items="5" data-xl-items="4" data-lg-items="4"
                                data-md-items="2" data-xs-items="2">
                                @foreach ($products_01 as $product)
                                    <div class="item-group">
                                        <div class="product-card">
                                            <div class="product-card__thumbnail">
                                                @php
                                                    $discount = 0;
                                                    if (
                                                        $product->product_type == 'Consumer' &&
                                                        $product->price->discount_tk > 0
                                                    ) {
                                                        $price = $product->price->online_price;
                                                        $discount_tk = $product->price->discount_tk;
                                                        $discount = ceil(($discount_tk / $price) * 100);
                                                    } elseif ($product->product_type == 'Fashion') {
                                                        $sku = $product->sku->first();
                                                        $price = $sku->price;
                                                        $discount_tk = $sku->discount_tk;
                                                        $discount = ceil(($discount_tk / $price) * 100);
                                                    }
                                                @endphp
                                                @if ($discount > 0)
                                                    <span class="discount">-{{ $discount }}%</span>
                                                @endif
                                                <div class="actions-secondary">
                                                    <a href="{{ Auth::check() ? 'javascript:void(0)' : Route('customer.login') }}"
                                                        class="action {{ Auth::check() ? 'add-to-wishlist' : '' }}"
                                                        title="Add to Wishlist" data-id="{{ $product->id }}">
                                                        <svg version="1.1" xmlns="http://www.w3.org/2000/svg"
                                                            height="16" viewBox="0 0 1024 1024">
                                                            <g id="icomoon-ignore">
                                                            </g>
                                                            <path fill="currentColor"
                                                                d="M934.176 168.48c-116.128-115.072-301.824-117.472-422.112-9.216-120.32-108.256-305.952-105.856-422.144 9.216-119.712 118.528-119.712 310.688 0 429.28 34.208 33.888 353.696 350.112 353.696 350.112 37.856 37.504 99.072 37.504 136.896 0 0 0 349.824-346.304 353.696-350.112 119.744-118.592 119.744-310.752-0.032-429.28zM888.576 552.576l-353.696 350.112c-12.576 12.512-33.088 12.512-45.6 0l-353.696-350.112c-94.4-93.44-94.4-245.472 0-338.912 91.008-90.080 237.312-93.248 333.088-7.104l43.392 39.040 43.36-39.040c95.808-86.144 242.112-83.008 333.12 7.104 94.4 93.408 94.4 245.44 0.032 338.912zM296.096 240.032c8.864 0 16 7.168 16 16s-7.168 16-16 16h-0.032c-57.408 0-103.968 46.56-103.968 103.968v0.032c0 8.832-7.168 16-16 16s-16-7.168-16-16v0c0-75.072 60.832-135.904 135.872-135.968 0.064 0 0.064-0.032 0.128-0.032z">
                                                            </path>
                                                        </svg>
                                                    </a>
                                                    <a class="action quickview-handler" title="Quick View"
                                                        href="javascript:void(0)" data-id="{{ $product->id }}">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </div>
                                                <a href="{{ Route('frontend.single-product', $product->slug) }}">
                                                    <div class="ratio ratio-1x1">
                                                        <img class="fit-cover"
                                                            src="{{ file_exists($product->thumbnail) ? asset($product->thumbnail) : asset(@$setting->placeholder) }}"
                                                            alt="{{ $product->name }}">
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="product-card__details">
                                                <a class="product-item-link"
                                                    href="{{ Route('frontend.single-product', $product->slug) }}">{{ $product->name }}
                                                </a>
                                                <div class="price-box">
                                                    @if ($product->product_type == 'Consumer')
                                                        <span class="special-price">
                                                            <span class="price">TK
                                                                {{ number_format($product->price->online_price - $product->price->discount_tk) }}</span>
                                                        </span>
                                                        @if (@$product->price->discount_tk > 0)
                                                            <span class="old-price">
                                                                <span class="price">TK
                                                                    {{ number_format($product->price->online_price) }}</span>
                                                            </span>
                                                        @endif
                                                    @else
                                                        @php
                                                            $sku = $product->sku->first();
                                                        @endphp
                                                        <span class="special-price">
                                                            <span class="price">TK
                                                                {{ number_format($sku->price - $sku->discount_tk) }}</span>
                                                        </span>
                                                        @if ($sku->discount_tk > 0)
                                                            <span class="old-price">
                                                                <span class="price">TK
                                                                    {{ number_format($sku->price) }}</span>
                                                            </span>
                                                        @endif
                                                    @endif
                                                </div>
                                                <div class="product-view-wrap">
                                                    <div class="actions-primary">
                                                        <button type="button"
                                                            class="btn action {{ count(@$product->sku) > 0 ? 'toModal' : 'toCart' }} btn-primary"
                                                            title="Add to Cart" data-id="{{ $product->id }}">
                                                            <span>
                                                                <svg version="1.1" xmlns="http://www.w3.org/2000/svg"
                                                                    height="16" viewBox="0 0 512 448">
                                                                    <g id="icomoon-ignore">
                                                                    </g>
                                                                    <path fill="currentColor"
                                                                        d="M431.932 198.865c13.942 0 25.135 11.193 25.135 25.135s-11.193 25.135-25.135 25.135h-2.946l-22.582 129.996c-2.16 11.978-12.568 20.815-24.742 20.815h-251.352c-12.175 0-22.582-8.836-24.742-20.815l-22.582-129.996h-2.946c-13.942 0-25.135-11.193-25.135-25.135s11.193-25.135 25.135-25.135h351.892zM150.144 355.96c6.872-0.59 12.175-6.676 11.586-13.551l-6.284-81.689c-0.59-6.872-6.676-12.175-13.551-11.586s-12.175 6.676-11.586 13.551l6.284 81.689c0.59 6.48 6.088 11.586 12.568 11.586h0.982zM230.851 343.392v-81.689c0-6.872-5.694-12.568-12.568-12.568s-12.568 5.694-12.568 12.568v81.689c0 6.872 5.694 12.568 12.568 12.568s12.568-5.694 12.568-12.568zM306.257 343.392v-81.689c0-6.872-5.694-12.568-12.568-12.568s-12.568 5.694-12.568 12.568v81.689c0 6.872 5.694 12.568 12.568 12.568s12.568-5.694 12.568-12.568zM375.378 344.374l6.284-81.689c0.59-6.872-4.713-12.96-11.586-13.551s-12.96 4.713-13.551 11.586l-6.284 81.689c-0.59 6.872 4.713 12.96 11.586 13.551h0.982c6.48 0 11.978-5.106 12.568-11.586zM148.376 105.393l-18.262 80.904h-25.921l19.833-86.599c5.106-22.975 25.332-39.078 48.896-39.078h32.794c0-6.872 5.694-12.568 12.568-12.568h75.405c6.872 0 12.568 5.694 12.568 12.568h32.794c23.564 0 43.79 16.102 48.896 39.078l19.833 86.599h-25.921l-18.262-80.904c-2.749-11.586-12.764-19.636-24.546-19.636h-32.794c0 6.872-5.694 12.568-12.568 12.568h-75.405c-6.872 0-12.568-5.694-12.568-12.568h-32.794c-11.782 0-21.797 8.051-24.546 19.636z">
                                                                    </path>
                                                                </svg>
                                                            </span>
                                                            <span>Add to Cart</span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @if (@$products_02[$key])
                                            @php
                                                $product = @$products_02[$key];
                                            @endphp
                                            <div class="product-card">
                                                <div class="product-card__thumbnail">
                                                    @php
                                                        $discount = 0;
                                                        if (
                                                            $product->product_type == 'Consumer' &&
                                                            $product->price->discount_tk > 0
                                                        ) {
                                                            $price = $product->price->online_price;
                                                            $discount_tk = $product->price->discount_tk;
                                                            $discount = ceil(($discount_tk / $price) * 100);
                                                        } elseif ($product->product_type == 'Fashion') {
                                                            $sku = $product->sku->first();
                                                            $price = $sku->price;
                                                            $discount_tk = $sku->discount_tk;
                                                            $discount = ceil(($discount_tk / $price) * 100);
                                                        }
                                                    @endphp
                                                    @if ($discount > 0)
                                                        <span class="discount">-{{ $discount }}%</span>
                                                    @endif
                                                    <div class="actions-secondary">
                                                        <a href="{{ Auth::check() ? 'javascript:void(0)' : Route('customer.login') }}"
                                                            class="action {{ Auth::check() ? 'add-to-wishlist' : '' }}"
                                                            title="Add to Wishlist" data-id="{{ $product->id }}">
                                                            <svg version="1.1" xmlns="http://www.w3.org/2000/svg"
                                                                height="16" viewBox="0 0 1024 1024">
                                                                <g id="icomoon-ignore">
                                                                </g>
                                                                <path fill="currentColor"
                                                                    d="M934.176 168.48c-116.128-115.072-301.824-117.472-422.112-9.216-120.32-108.256-305.952-105.856-422.144 9.216-119.712 118.528-119.712 310.688 0 429.28 34.208 33.888 353.696 350.112 353.696 350.112 37.856 37.504 99.072 37.504 136.896 0 0 0 349.824-346.304 353.696-350.112 119.744-118.592 119.744-310.752-0.032-429.28zM888.576 552.576l-353.696 350.112c-12.576 12.512-33.088 12.512-45.6 0l-353.696-350.112c-94.4-93.44-94.4-245.472 0-338.912 91.008-90.080 237.312-93.248 333.088-7.104l43.392 39.040 43.36-39.040c95.808-86.144 242.112-83.008 333.12 7.104 94.4 93.408 94.4 245.44 0.032 338.912zM296.096 240.032c8.864 0 16 7.168 16 16s-7.168 16-16 16h-0.032c-57.408 0-103.968 46.56-103.968 103.968v0.032c0 8.832-7.168 16-16 16s-16-7.168-16-16v0c0-75.072 60.832-135.904 135.872-135.968 0.064 0 0.064-0.032 0.128-0.032z">
                                                                </path>
                                                            </svg>
                                                        </a>
                                                        <a class="action quickview-handler" title="Quick View"
                                                            href="javascript:void(0)" data-id="{{ $product->id }}">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    </div>
                                                    <a href="{{ Route('frontend.single-product', $product->slug) }}">
                                                        <div class="ratio ratio-1x1">
                                                            <img class="fit-cover"
                                                                src="{{ file_exists($product->thumbnail) ? asset($product->thumbnail) : asset(@$setting->placeholder) }}"
                                                                alt="{{ $product->name }}">
                                                        </div>
                                                    </a>
                                                </div>
                                                <div class="product-card__details">
                                                    <a class="product-item-link"
                                                        href="{{ Route('frontend.single-product', $product->slug) }}">{{ $product->name }}
                                                    </a>
                                                    <div class="price-box">
                                                        @if ($product->product_type == 'Consumer')
                                                            <span class="special-price">
                                                                <span class="price">TK
                                                                    {{ number_format($product->price->online_price - $product->price->discount_tk) }}</span>
                                                            </span>
                                                            @if (@$product->price->discount_tk > 0)
                                                                <span class="old-price">
                                                                    <span class="price">TK
                                                                        {{ number_format($product->price->online_price) }}</span>
                                                                </span>
                                                            @endif
                                                        @else
                                                            @php
                                                                $sku = $product->sku->first();
                                                            @endphp
                                                            <span class="special-price">
                                                                <span class="price">TK
                                                                    {{ number_format($sku->price - $sku->discount_tk) }}</span>
                                                            </span>
                                                            @if ($sku->discount_tk > 0)
                                                                <span class="old-price">
                                                                    <span class="price">TK
                                                                        {{ number_format($sku->price) }}</span>
                                                                </span>
                                                            @endif
                                                        @endif
                                                    </div>
                                                    <div class="product-view-wrap">
                                                        <div class="actions-primary">
                                                            <button type="button"
                                                                class="btn action {{ count(@$product->sku) > 0 ? 'toModal' : 'toCart' }} btn-primary"
                                                                title="Add to Cart" data-id="{{ $product->id }}">
                                                                <span>
                                                                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg"
                                                                        height="16" viewBox="0 0 512 448">
                                                                        <g id="icomoon-ignore">
                                                                        </g>
                                                                        <path fill="currentColor"
                                                                            d="M431.932 198.865c13.942 0 25.135 11.193 25.135 25.135s-11.193 25.135-25.135 25.135h-2.946l-22.582 129.996c-2.16 11.978-12.568 20.815-24.742 20.815h-251.352c-12.175 0-22.582-8.836-24.742-20.815l-22.582-129.996h-2.946c-13.942 0-25.135-11.193-25.135-25.135s11.193-25.135 25.135-25.135h351.892zM150.144 355.96c6.872-0.59 12.175-6.676 11.586-13.551l-6.284-81.689c-0.59-6.872-6.676-12.175-13.551-11.586s-12.175 6.676-11.586 13.551l6.284 81.689c0.59 6.48 6.088 11.586 12.568 11.586h0.982zM230.851 343.392v-81.689c0-6.872-5.694-12.568-12.568-12.568s-12.568 5.694-12.568 12.568v81.689c0 6.872 5.694 12.568 12.568 12.568s12.568-5.694 12.568-12.568zM306.257 343.392v-81.689c0-6.872-5.694-12.568-12.568-12.568s-12.568 5.694-12.568 12.568v81.689c0 6.872 5.694 12.568 12.568 12.568s12.568-5.694 12.568-12.568zM375.378 344.374l6.284-81.689c0.59-6.872-4.713-12.96-11.586-13.551s-12.96 4.713-13.551 11.586l-6.284 81.689c-0.59 6.872 4.713 12.96 11.586 13.551h0.982c6.48 0 11.978-5.106 12.568-11.586zM148.376 105.393l-18.262 80.904h-25.921l19.833-86.599c5.106-22.975 25.332-39.078 48.896-39.078h32.794c0-6.872 5.694-12.568 12.568-12.568h75.405c6.872 0 12.568 5.694 12.568 12.568h32.794c23.564 0 43.79 16.102 48.896 39.078l19.833 86.599h-25.921l-18.262-80.904c-2.749-11.586-12.764-19.636-24.546-19.636h-32.794c0 6.872-5.694 12.568-12.568 12.568h-75.405c-6.872 0-12.568-5.694-12.568-12.568h-32.794c-11.782 0-21.797 8.051-24.546 19.636z">
                                                                        </path>
                                                                    </svg>
                                                                </span>
                                                                <span>Add to Cart</span>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif
                        @if (count($section->sub_categories) > 0)
                            @php
                                $totalProduct = \App\Models\Product::where(
                                    'category_id',
                                    @$section->category_id,
                                )->count();
                                $limit = ceil($totalProduct / 2);
                                $lastLimit = $totalProduct - $limit;
                                if ($totalProduct >= 10) {
                                    $products_01 = \App\Models\Product::where('category_id', @$section->category_id)
                                        ->skip(0)
                                        ->take($limit)
                                        ->orderBy('id', 'desc')
                                        ->get();
                                    $products_02 = \App\Models\Product::where('category_id', @$section->category_id)
                                        ->skip($limit)
                                        ->take($limit)
                                        ->orderBy('id', 'desc')
                                        ->get();
                                } else {
                                    $products_01 = \App\Models\Product::where('category_id', @$section->category_id)
                                        ->skip(0)
                                        ->take(5)
                                        ->orderBy('id', 'desc')
                                        ->get();
                                    $products_02 = \App\Models\Product::where('category_id', @$section->category_id)
                                        ->skip(5)
                                        ->take(5)
                                        ->orderBy('id', 'desc')
                                        ->get();
                                }
                            @endphp
                            <div class="tab-pane fade show active"
                                id="pills-{{ 'cats' . $section->id . @$section->category_id }}" role="tabpanel"
                                aria-labelledby="pills-{{ 'cats' . $section->id . @$section->category_id }}-tab">
                                <div class="carousel owl-carousel" data-items="5" data-xl-items="4" data-lg-items="4"
                                    data-md-items="2" data-xs-items="2">
                                    @foreach ($products_01 as $key => $product)
                                        <div class="item-group">
                                            <div class="product-card">
                                                <div class="product-card__thumbnail">
                                                    @php
                                                        $discount = 0;
                                                        if (
                                                            $product->product_type == 'Consumer' &&
                                                            $product->price->discount_tk > 0
                                                        ) {
                                                            $price = $product->price->online_price;
                                                            $discount_tk = $product->price->discount_tk;
                                                            $discount = ceil(($discount_tk / $price) * 100);
                                                        } elseif ($product->product_type == 'Fashion') {
                                                            $sku = $product->sku->first();
                                                            $price = $sku->price;
                                                            $discount_tk = $sku->discount_tk;
                                                            $discount = ceil(($discount_tk / $price) * 100);
                                                        }
                                                    @endphp
                                                    @if ($discount > 0)
                                                        <span class="discount">-{{ $discount }}%</span>
                                                    @endif
                                                    <div class="actions-secondary">
                                                        <a href="{{ Auth::check() ? 'javascript:void(0)' : Route('customer.login') }}"
                                                            class="action {{ Auth::check() ? 'add-to-wishlist' : '' }}"
                                                            title="Add to Wishlist" data-id="{{ $product->id }}">
                                                            <svg version="1.1" xmlns="http://www.w3.org/2000/svg"
                                                                height="16" viewBox="0 0 1024 1024">
                                                                <g id="icomoon-ignore">
                                                                </g>
                                                                <path fill="currentColor"
                                                                    d="M934.176 168.48c-116.128-115.072-301.824-117.472-422.112-9.216-120.32-108.256-305.952-105.856-422.144 9.216-119.712 118.528-119.712 310.688 0 429.28 34.208 33.888 353.696 350.112 353.696 350.112 37.856 37.504 99.072 37.504 136.896 0 0 0 349.824-346.304 353.696-350.112 119.744-118.592 119.744-310.752-0.032-429.28zM888.576 552.576l-353.696 350.112c-12.576 12.512-33.088 12.512-45.6 0l-353.696-350.112c-94.4-93.44-94.4-245.472 0-338.912 91.008-90.080 237.312-93.248 333.088-7.104l43.392 39.040 43.36-39.040c95.808-86.144 242.112-83.008 333.12 7.104 94.4 93.408 94.4 245.44 0.032 338.912zM296.096 240.032c8.864 0 16 7.168 16 16s-7.168 16-16 16h-0.032c-57.408 0-103.968 46.56-103.968 103.968v0.032c0 8.832-7.168 16-16 16s-16-7.168-16-16v0c0-75.072 60.832-135.904 135.872-135.968 0.064 0 0.064-0.032 0.128-0.032z">
                                                                </path>
                                                            </svg>
                                                        </a>
                                                        <a class="action quickview-handler" title="Quick View"
                                                            href="javascript:void(0)" data-id="{{ $product->id }}">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    </div>
                                                    <a href="{{ Route('frontend.single-product', $product->slug) }}">
                                                        <div class="ratio ratio-1x1">
                                                            <img class="fit-cover"
                                                                src="{{ file_exists($product->thumbnail) ? asset($product->thumbnail) : asset(@$setting->placeholder) }}"
                                                                alt="{{ $product->name }}">
                                                        </div>
                                                    </a>
                                                </div>
                                                <div class="product-card__details">
                                                    <a class="product-item-link"
                                                        href="{{ Route('frontend.single-product', $product->slug) }}">{{ $product->name }}
                                                    </a>
                                                    <div class="price-box">
                                                        @if ($product->product_type == 'Consumer')
                                                            <span class="special-price">
                                                                <span class="price">TK
                                                                    {{ number_format($product->price->online_price - $product->price->discount_tk) }}</span>
                                                            </span>
                                                            @if (@$product->price->discount_tk > 0)
                                                                <span class="old-price">
                                                                    <span class="price">TK
                                                                        {{ number_format($product->price->online_price) }}</span>
                                                                </span>
                                                            @endif
                                                        @else
                                                            @php
                                                                $sku = $product->sku->first();
                                                            @endphp
                                                            <span class="special-price">
                                                                <span class="price">TK
                                                                    {{ number_format($sku->price - $sku->discount_tk) }}</span>
                                                            </span>
                                                            @if ($sku->discount_tk > 0)
                                                                <span class="old-price">
                                                                    <span class="price">TK
                                                                        {{ number_format($sku->price) }}</span>
                                                                </span>
                                                            @endif
                                                        @endif
                                                    </div>
                                                    <div class="product-view-wrap">
                                                        <div class="actions-primary">
                                                            <button type="button"
                                                                class="btn action {{ count(@$product->sku) > 0 ? 'toModal' : 'toCart' }} btn-primary"
                                                                title="Add to Cart" data-id="{{ $product->id }}">
                                                                <span>
                                                                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg"
                                                                        height="16" viewBox="0 0 512 448">
                                                                        <g id="icomoon-ignore">
                                                                        </g>
                                                                        <path fill="currentColor"
                                                                            d="M431.932 198.865c13.942 0 25.135 11.193 25.135 25.135s-11.193 25.135-25.135 25.135h-2.946l-22.582 129.996c-2.16 11.978-12.568 20.815-24.742 20.815h-251.352c-12.175 0-22.582-8.836-24.742-20.815l-22.582-129.996h-2.946c-13.942 0-25.135-11.193-25.135-25.135s11.193-25.135 25.135-25.135h351.892zM150.144 355.96c6.872-0.59 12.175-6.676 11.586-13.551l-6.284-81.689c-0.59-6.872-6.676-12.175-13.551-11.586s-12.175 6.676-11.586 13.551l6.284 81.689c0.59 6.48 6.088 11.586 12.568 11.586h0.982zM230.851 343.392v-81.689c0-6.872-5.694-12.568-12.568-12.568s-12.568 5.694-12.568 12.568v81.689c0 6.872 5.694 12.568 12.568 12.568s12.568-5.694 12.568-12.568zM306.257 343.392v-81.689c0-6.872-5.694-12.568-12.568-12.568s-12.568 5.694-12.568 12.568v81.689c0 6.872 5.694 12.568 12.568 12.568s12.568-5.694 12.568-12.568zM375.378 344.374l6.284-81.689c0.59-6.872-4.713-12.96-11.586-13.551s-12.96 4.713-13.551 11.586l-6.284 81.689c-0.59 6.872 4.713 12.96 11.586 13.551h0.982c6.48 0 11.978-5.106 12.568-11.586zM148.376 105.393l-18.262 80.904h-25.921l19.833-86.599c5.106-22.975 25.332-39.078 48.896-39.078h32.794c0-6.872 5.694-12.568 12.568-12.568h75.405c6.872 0 12.568 5.694 12.568 12.568h32.794c23.564 0 43.79 16.102 48.896 39.078l19.833 86.599h-25.921l-18.262-80.904c-2.749-11.586-12.764-19.636-24.546-19.636h-32.794c0 6.872-5.694 12.568-12.568 12.568h-75.405c-6.872 0-12.568-5.694-12.568-12.568h-32.794c-11.782 0-21.797 8.051-24.546 19.636z">
                                                                        </path>
                                                                    </svg>
                                                                </span>
                                                                <span>Add to Cart</span>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @if (@$products_02[$key])
                                                @php
                                                    $product = @$products_02[$key];
                                                @endphp
                                                <div class="product-card">
                                                    <div class="product-card__thumbnail">
                                                        @php
                                                            $discount = 0;
                                                            if (
                                                                $product->product_type == 'Consumer' &&
                                                                $product->price->discount_tk > 0
                                                            ) {
                                                                $price = $product->price->online_price;
                                                                $discount_tk = $product->price->discount_tk;
                                                                $discount = ceil(($discount_tk / $price) * 100);
                                                            } elseif ($product->product_type == 'Fashion') {
                                                                $sku = $product->sku->first();
                                                                $price = $sku->price;
                                                                $discount_tk = $sku->discount_tk;
                                                                $discount = ceil(($discount_tk / $price) * 100);
                                                            }
                                                        @endphp
                                                        @if ($discount > 0)
                                                            <span class="discount">-{{ $discount }}%</span>
                                                        @endif
                                                        <div class="actions-secondary">
                                                            <a href="{{ Auth::check() ? 'javascript:void(0)' : Route('customer.login') }}"
                                                                class="action {{ Auth::check() ? 'add-to-wishlist' : '' }}"
                                                                title="Add to Wishlist" data-id="{{ $product->id }}">
                                                                <svg version="1.1" xmlns="http://www.w3.org/2000/svg"
                                                                    height="16" viewBox="0 0 1024 1024">
                                                                    <g id="icomoon-ignore">
                                                                    </g>
                                                                    <path fill="currentColor"
                                                                        d="M934.176 168.48c-116.128-115.072-301.824-117.472-422.112-9.216-120.32-108.256-305.952-105.856-422.144 9.216-119.712 118.528-119.712 310.688 0 429.28 34.208 33.888 353.696 350.112 353.696 350.112 37.856 37.504 99.072 37.504 136.896 0 0 0 349.824-346.304 353.696-350.112 119.744-118.592 119.744-310.752-0.032-429.28zM888.576 552.576l-353.696 350.112c-12.576 12.512-33.088 12.512-45.6 0l-353.696-350.112c-94.4-93.44-94.4-245.472 0-338.912 91.008-90.080 237.312-93.248 333.088-7.104l43.392 39.040 43.36-39.040c95.808-86.144 242.112-83.008 333.12 7.104 94.4 93.408 94.4 245.44 0.032 338.912zM296.096 240.032c8.864 0 16 7.168 16 16s-7.168 16-16 16h-0.032c-57.408 0-103.968 46.56-103.968 103.968v0.032c0 8.832-7.168 16-16 16s-16-7.168-16-16v0c0-75.072 60.832-135.904 135.872-135.968 0.064 0 0.064-0.032 0.128-0.032z">
                                                                    </path>
                                                                </svg>
                                                            </a>
                                                            <a class="action quickview-handler" title="Quick View"
                                                                href="javascript:void(0)" data-id="{{ $product->id }}">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                        </div>
                                                        <a href="{{ Route('frontend.single-product', $product->slug) }}">
                                                            <div class="ratio ratio-1x1">
                                                                <img class="fit-cover"
                                                                    src="{{ file_exists($product->thumbnail) ? asset($product->thumbnail) : asset(@$setting->placeholder) }}"
                                                                    alt="{{ $product->name }}">
                                                            </div>
                                                        </a>
                                                    </div>
                                                    <div class="product-card__details">
                                                        <a class="product-item-link"
                                                            href="{{ Route('frontend.single-product', $product->slug) }}">{{ $product->name }}
                                                        </a>
                                                        <div class="price-box">
                                                            @if ($product->product_type == 'Consumer')
                                                                <span class="special-price">
                                                                    <span class="price">TK
                                                                        {{ number_format($product->price->online_price - $product->price->discount_tk) }}</span>
                                                                </span>
                                                                @if (@$product->price->discount_tk > 0)
                                                                    <span class="old-price">
                                                                        <span class="price">TK
                                                                            {{ number_format($product->price->online_price) }}</span>
                                                                    </span>
                                                                @endif
                                                            @else
                                                                @php
                                                                    $sku = $product->sku->first();
                                                                @endphp
                                                                <span class="special-price">
                                                                    <span class="price">TK
                                                                        {{ number_format($sku->price - $sku->discount_tk) }}</span>
                                                                </span>
                                                                @if ($sku->discount_tk > 0)
                                                                    <span class="old-price">
                                                                        <span class="price">TK
                                                                            {{ number_format($sku->price) }}</span>
                                                                    </span>
                                                                @endif
                                                            @endif
                                                        </div>
                                                        <div class="product-view-wrap">
                                                            <div class="actions-primary">
                                                                <button type="button"
                                                                    class="btn action {{ count(@$product->sku) > 0 ? 'toModal' : 'toCart' }} btn-primary"
                                                                    title="Add to Cart" data-id="{{ $product->id }}">
                                                                    <span>
                                                                        <svg version="1.1"
                                                                            xmlns="http://www.w3.org/2000/svg"
                                                                            height="16" viewBox="0 0 512 448">
                                                                            <g id="icomoon-ignore">
                                                                            </g>
                                                                            <path fill="currentColor"
                                                                                d="M431.932 198.865c13.942 0 25.135 11.193 25.135 25.135s-11.193 25.135-25.135 25.135h-2.946l-22.582 129.996c-2.16 11.978-12.568 20.815-24.742 20.815h-251.352c-12.175 0-22.582-8.836-24.742-20.815l-22.582-129.996h-2.946c-13.942 0-25.135-11.193-25.135-25.135s11.193-25.135 25.135-25.135h351.892zM150.144 355.96c6.872-0.59 12.175-6.676 11.586-13.551l-6.284-81.689c-0.59-6.872-6.676-12.175-13.551-11.586s-12.175 6.676-11.586 13.551l6.284 81.689c0.59 6.48 6.088 11.586 12.568 11.586h0.982zM230.851 343.392v-81.689c0-6.872-5.694-12.568-12.568-12.568s-12.568 5.694-12.568 12.568v81.689c0 6.872 5.694 12.568 12.568 12.568s12.568-5.694 12.568-12.568zM306.257 343.392v-81.689c0-6.872-5.694-12.568-12.568-12.568s-12.568 5.694-12.568 12.568v81.689c0 6.872 5.694 12.568 12.568 12.568s12.568-5.694 12.568-12.568zM375.378 344.374l6.284-81.689c0.59-6.872-4.713-12.96-11.586-13.551s-12.96 4.713-13.551 11.586l-6.284 81.689c-0.59 6.872 4.713 12.96 11.586 13.551h0.982c6.48 0 11.978-5.106 12.568-11.586zM148.376 105.393l-18.262 80.904h-25.921l19.833-86.599c5.106-22.975 25.332-39.078 48.896-39.078h32.794c0-6.872 5.694-12.568 12.568-12.568h75.405c6.872 0 12.568 5.694 12.568 12.568h32.794c23.564 0 43.79 16.102 48.896 39.078l19.833 86.599h-25.921l-18.262-80.904c-2.749-11.586-12.764-19.636-24.546-19.636h-32.794c0 6.872-5.694 12.568-12.568 12.568h-75.405c-6.872 0-12.568-5.694-12.568-12.568h-32.794c-11.782 0-21.797 8.051-24.546 19.636z">
                                                                            </path>
                                                                        </svg>
                                                                    </span>
                                                                    <span>Add to Cart</span>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        @foreach ($section->sub_categories as $key => $item)
                            <div class="tab-pane fade" id="pills-{{ 'cat' . $section->id . $item->id }}" role="tabpanel"
                                aria-labelledby="pills-{{ 'cat' . $section->id . $item->id }}-tab">
                                <div class="position-relative h-100">
                                    <div class="no-loaded">
                                        <div class="brand-loader double-bounce-spinner">
                                            <div class="double-bounce1"></div>
                                            <div class="double-bounce2"></div>
                                        </div>
                                    </div>
                                    <div class="carousel owl-carousel" data-items="5" data-xl-items="4"
                                        data-lg-items="4" data-md-items="2" data-xs-items="2">
                                        @php
                                            $totalProduct = \App\Models\Product::where(
                                                'category_id',
                                                $item->category_id,
                                            )->count();
                                            $limit = ceil($totalProduct / 2);
                                            $lastLimit = $totalProduct - $limit;
                                            $products_01 = \App\Models\Product::where('category_id', $item->category_id)
                                                ->skip(0)
                                                ->take($limit)
                                                ->orderBy('id', 'desc')
                                                ->get();
                                            $products_02 = \App\Models\Product::where('category_id', $item->category_id)
                                                ->skip($limit)
                                                ->take($limit)
                                                ->orderBy('id', 'desc')
                                                ->get();
                                        @endphp
                                        @foreach ($products_01 as $key => $product)
                                            <div class="item-group">
                                                <div class="product-card">
                                                    <div class="product-card__thumbnail">
                                                        @php
                                                            $discount = 0;
                                                            if (
                                                                $product->product_type == 'Consumer' &&
                                                                $product->price->discount_tk > 0
                                                            ) {
                                                                $price = $product->price->online_price;
                                                                $discount_tk = $product->price->discount_tk;
                                                                $discount = ceil(($discount_tk / $price) * 100);
                                                            } elseif ($product->product_type == 'Fashion') {
                                                                $sku = $product->sku->first();
                                                                $price = $sku->price;
                                                                $discount_tk = $sku->discount_tk;
                                                                $discount = ceil(($discount_tk / $price) * 100);
                                                            }
                                                        @endphp
                                                        @if ($discount > 0)
                                                            <span class="discount">-{{ $discount }}%</span>
                                                        @endif
                                                        <div class="actions-secondary">
                                                            <a href="{{ Auth::check() ? 'javascript:void(0)' : Route('customer.login') }}"
                                                                class="action {{ Auth::check() ? 'add-to-wishlist' : '' }}"
                                                                title="Add to Wishlist" data-id="{{ $product->id }}">
                                                                <svg version="1.1" xmlns="http://www.w3.org/2000/svg"
                                                                    height="16" viewBox="0 0 1024 1024">
                                                                    <g id="icomoon-ignore">
                                                                    </g>
                                                                    <path fill="currentColor"
                                                                        d="M934.176 168.48c-116.128-115.072-301.824-117.472-422.112-9.216-120.32-108.256-305.952-105.856-422.144 9.216-119.712 118.528-119.712 310.688 0 429.28 34.208 33.888 353.696 350.112 353.696 350.112 37.856 37.504 99.072 37.504 136.896 0 0 0 349.824-346.304 353.696-350.112 119.744-118.592 119.744-310.752-0.032-429.28zM888.576 552.576l-353.696 350.112c-12.576 12.512-33.088 12.512-45.6 0l-353.696-350.112c-94.4-93.44-94.4-245.472 0-338.912 91.008-90.080 237.312-93.248 333.088-7.104l43.392 39.040 43.36-39.040c95.808-86.144 242.112-83.008 333.12 7.104 94.4 93.408 94.4 245.44 0.032 338.912zM296.096 240.032c8.864 0 16 7.168 16 16s-7.168 16-16 16h-0.032c-57.408 0-103.968 46.56-103.968 103.968v0.032c0 8.832-7.168 16-16 16s-16-7.168-16-16v0c0-75.072 60.832-135.904 135.872-135.968 0.064 0 0.064-0.032 0.128-0.032z">
                                                                    </path>
                                                                </svg>
                                                            </a>
                                                            <a class="action quickview-handler" title="Quick View"
                                                                href="javascript:void(0)" data-id="{{ $product->id }}">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                        </div>
                                                        <a href="{{ Route('frontend.single-product', $product->slug) }}">
                                                            <div class="ratio ratio-1x1">
                                                                <img class="fit-cover"
                                                                    src="{{ file_exists($product->thumbnail) ? asset($product->thumbnail) : asset(@$setting->placeholder) }}"
                                                                    alt="{{ $product->name }}">
                                                            </div>
                                                        </a>
                                                    </div>
                                                    <div class="product-card__details">
                                                        <a class="product-item-link"
                                                            href="{{ Route('frontend.single-product', $product->slug) }}">{{ $product->name }}
                                                        </a>
                                                        <div class="price-box">
                                                            @if ($product->product_type == 'Consumer')
                                                                <span class="special-price">
                                                                    <span class="price">TK
                                                                        {{ number_format($product->price->online_price - $product->price->discount_tk) }}</span>
                                                                </span>
                                                                @if (@$product->price->discount_tk > 0)
                                                                    <span class="old-price">
                                                                        <span class="price">TK
                                                                            {{ number_format($product->price->online_price) }}</span>
                                                                    </span>
                                                                @endif
                                                            @else
                                                                @php
                                                                    $sku = $product->sku->first();
                                                                @endphp
                                                                <span class="special-price">
                                                                    <span class="price">TK
                                                                        {{ number_format($sku->price - $sku->discount_tk) }}</span>
                                                                </span>
                                                                @if ($sku->discount_tk > 0)
                                                                    <span class="old-price">
                                                                        <span class="price">TK
                                                                            {{ number_format($sku->price) }}</span>
                                                                    </span>
                                                                @endif
                                                            @endif
                                                        </div>
                                                        <div class="product-view-wrap">
                                                            <div class="actions-primary">
                                                                <button type="button"
                                                                    class="btn action {{ count(@$product->sku) > 0 ? 'toModal' : 'toCart' }} btn-primary"
                                                                    title="Add to Cart" data-id="{{ $product->id }}">
                                                                    <span>
                                                                        <svg version="1.1"
                                                                            xmlns="http://www.w3.org/2000/svg"
                                                                            height="16" viewBox="0 0 512 448">
                                                                            <g id="icomoon-ignore">
                                                                            </g>
                                                                            <path fill="currentColor"
                                                                                d="M431.932 198.865c13.942 0 25.135 11.193 25.135 25.135s-11.193 25.135-25.135 25.135h-2.946l-22.582 129.996c-2.16 11.978-12.568 20.815-24.742 20.815h-251.352c-12.175 0-22.582-8.836-24.742-20.815l-22.582-129.996h-2.946c-13.942 0-25.135-11.193-25.135-25.135s11.193-25.135 25.135-25.135h351.892zM150.144 355.96c6.872-0.59 12.175-6.676 11.586-13.551l-6.284-81.689c-0.59-6.872-6.676-12.175-13.551-11.586s-12.175 6.676-11.586 13.551l6.284 81.689c0.59 6.48 6.088 11.586 12.568 11.586h0.982zM230.851 343.392v-81.689c0-6.872-5.694-12.568-12.568-12.568s-12.568 5.694-12.568 12.568v81.689c0 6.872 5.694 12.568 12.568 12.568s12.568-5.694 12.568-12.568zM306.257 343.392v-81.689c0-6.872-5.694-12.568-12.568-12.568s-12.568 5.694-12.568 12.568v81.689c0 6.872 5.694 12.568 12.568 12.568s12.568-5.694 12.568-12.568zM375.378 344.374l6.284-81.689c0.59-6.872-4.713-12.96-11.586-13.551s-12.96 4.713-13.551 11.586l-6.284 81.689c-0.59 6.872 4.713 12.96 11.586 13.551h0.982c6.48 0 11.978-5.106 12.568-11.586zM148.376 105.393l-18.262 80.904h-25.921l19.833-86.599c5.106-22.975 25.332-39.078 48.896-39.078h32.794c0-6.872 5.694-12.568 12.568-12.568h75.405c6.872 0 12.568 5.694 12.568 12.568h32.794c23.564 0 43.79 16.102 48.896 39.078l19.833 86.599h-25.921l-18.262-80.904c-2.749-11.586-12.764-19.636-24.546-19.636h-32.794c0 6.872-5.694 12.568-12.568 12.568h-75.405c-6.872 0-12.568-5.694-12.568-12.568h-32.794c-11.782 0-21.797 8.051-24.546 19.636z">
                                                                            </path>
                                                                        </svg>
                                                                    </span>
                                                                    <span>Add to Cart</span>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @if (@$products_02[$key])
                                                    @php
                                                        $product = @$products_02[$key];
                                                    @endphp
                                                    <div class="product-card">
                                                        <div class="product-card__thumbnail">
                                                            @php
                                                                $discount = 0;
                                                                if (
                                                                    $product->product_type == 'Consumer' &&
                                                                    $product->price->discount_tk > 0
                                                                ) {
                                                                    $price = $product->price->online_price;
                                                                    $discount_tk = $product->price->discount_tk;
                                                                    $discount = ceil(($discount_tk / $price) * 100);
                                                                } elseif ($product->product_type == 'Fashion') {
                                                                    $sku = $product->sku->first();
                                                                    $price = $sku->price;
                                                                    $discount_tk = $sku->discount_tk;
                                                                    $discount = ceil(($discount_tk / $price) * 100);
                                                                }
                                                            @endphp
                                                            @if ($discount > 0)
                                                                <span class="discount">-{{ $discount }}%</span>
                                                            @endif
                                                            <div class="actions-secondary">
                                                                <a href="{{ Auth::check() ? 'javascript:void(0)' : Route('customer.login') }}"
                                                                    class="action {{ Auth::check() ? 'add-to-wishlist' : '' }}"
                                                                    title="Add to Wishlist"
                                                                    data-id="{{ $product->id }}">
                                                                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg"
                                                                        height="16" viewBox="0 0 1024 1024">
                                                                        <g id="icomoon-ignore">
                                                                        </g>
                                                                        <path fill="currentColor"
                                                                            d="M934.176 168.48c-116.128-115.072-301.824-117.472-422.112-9.216-120.32-108.256-305.952-105.856-422.144 9.216-119.712 118.528-119.712 310.688 0 429.28 34.208 33.888 353.696 350.112 353.696 350.112 37.856 37.504 99.072 37.504 136.896 0 0 0 349.824-346.304 353.696-350.112 119.744-118.592 119.744-310.752-0.032-429.28zM888.576 552.576l-353.696 350.112c-12.576 12.512-33.088 12.512-45.6 0l-353.696-350.112c-94.4-93.44-94.4-245.472 0-338.912 91.008-90.080 237.312-93.248 333.088-7.104l43.392 39.040 43.36-39.040c95.808-86.144 242.112-83.008 333.12 7.104 94.4 93.408 94.4 245.44 0.032 338.912zM296.096 240.032c8.864 0 16 7.168 16 16s-7.168 16-16 16h-0.032c-57.408 0-103.968 46.56-103.968 103.968v0.032c0 8.832-7.168 16-16 16s-16-7.168-16-16v0c0-75.072 60.832-135.904 135.872-135.968 0.064 0 0.064-0.032 0.128-0.032z">
                                                                        </path>
                                                                    </svg>
                                                                </a>
                                                                <a class="action quickview-handler" title="Quick View"
                                                                    href="javascript:void(0)"
                                                                    data-id="{{ $product->id }}">
                                                                    <i class="fas fa-eye"></i>
                                                                </a>
                                                            </div>
                                                            <a
                                                                href="{{ Route('frontend.single-product', $product->slug) }}">
                                                                <div class="ratio ratio-1x1">
                                                                    <img class="fit-cover"
                                                                        src="{{ file_exists($product->thumbnail) ? asset($product->thumbnail) : asset(@$setting->placeholder) }}"
                                                                        alt="{{ $product->name }}">
                                                                </div>
                                                            </a>
                                                        </div>
                                                        <div class="product-card__details">
                                                            <a class="product-item-link"
                                                                href="{{ Route('frontend.single-product', $product->slug) }}">{{ $product->name }}
                                                            </a>
                                                            <div class="price-box">
                                                                @if ($product->product_type == 'Consumer')
                                                                    <span class="special-price">
                                                                        <span class="price">TK
                                                                            {{ number_format($product->price->online_price - $product->price->discount_tk) }}</span>
                                                                    </span>
                                                                    @if (@$product->price->discount_tk > 0)
                                                                        <span class="old-price">
                                                                            <span class="price">TK
                                                                                {{ number_format($product->price->online_price) }}</span>
                                                                        </span>
                                                                    @endif
                                                                @else
                                                                    @php
                                                                        $sku = $product->sku->first();
                                                                    @endphp
                                                                    <span class="special-price">
                                                                        <span class="price">TK
                                                                            {{ number_format($sku->price - $sku->discount_tk) }}</span>
                                                                    </span>
                                                                    @if ($sku->discount_tk > 0)
                                                                        <span class="old-price">
                                                                            <span class="price">TK
                                                                                {{ number_format($sku->price) }}</span>
                                                                        </span>
                                                                    @endif
                                                                @endif
                                                            </div>
                                                            <div class="product-view-wrap">
                                                                <div class="actions-primary">
                                                                    <button type="button"
                                                                        class="btn action {{ count(@$product->sku) > 0 ? 'toModal' : 'toCart' }} btn-primary"
                                                                        title="Add to Cart"
                                                                        data-id="{{ $product->id }}">
                                                                        <span>
                                                                            <svg version="1.1"
                                                                                xmlns="http://www.w3.org/2000/svg"
                                                                                height="16" viewBox="0 0 512 448">
                                                                                <g id="icomoon-ignore">
                                                                                </g>
                                                                                <path fill="currentColor"
                                                                                    d="M431.932 198.865c13.942 0 25.135 11.193 25.135 25.135s-11.193 25.135-25.135 25.135h-2.946l-22.582 129.996c-2.16 11.978-12.568 20.815-24.742 20.815h-251.352c-12.175 0-22.582-8.836-24.742-20.815l-22.582-129.996h-2.946c-13.942 0-25.135-11.193-25.135-25.135s11.193-25.135 25.135-25.135h351.892zM150.144 355.96c6.872-0.59 12.175-6.676 11.586-13.551l-6.284-81.689c-0.59-6.872-6.676-12.175-13.551-11.586s-12.175 6.676-11.586 13.551l6.284 81.689c0.59 6.48 6.088 11.586 12.568 11.586h0.982zM230.851 343.392v-81.689c0-6.872-5.694-12.568-12.568-12.568s-12.568 5.694-12.568 12.568v81.689c0 6.872 5.694 12.568 12.568 12.568s12.568-5.694 12.568-12.568zM306.257 343.392v-81.689c0-6.872-5.694-12.568-12.568-12.568s-12.568 5.694-12.568 12.568v81.689c0 6.872 5.694 12.568 12.568 12.568s12.568-5.694 12.568-12.568zM375.378 344.374l6.284-81.689c0.59-6.872-4.713-12.96-11.586-13.551s-12.96 4.713-13.551 11.586l-6.284 81.689c-0.59 6.872 4.713 12.96 11.586 13.551h0.982c6.48 0 11.978-5.106 12.568-11.586zM148.376 105.393l-18.262 80.904h-25.921l19.833-86.599c5.106-22.975 25.332-39.078 48.896-39.078h32.794c0-6.872 5.694-12.568 12.568-12.568h75.405c6.872 0 12.568 5.694 12.568 12.568h32.794c23.564 0 43.79 16.102 48.896 39.078l19.833 86.599h-25.921l-18.262-80.904c-2.749-11.586-12.764-19.636-24.546-19.636h-32.794c0 6.872-5.694 12.568-12.568 12.568h-75.405c-6.872 0-12.568-5.694-12.568-12.568h-32.794c-11.782 0-21.797 8.051-24.546 19.636z">
                                                                                </path>
                                                                            </svg>
                                                                        </span>
                                                                        <span>Add to Cart</span>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <!-- End Category Proudcts -->
    @endforeach
@endsection

@push('js')
    <script type="text/javascript">
        $(document).ready(function() {
            $('button[data-bs-toggle="pill"]').on('shown.bs.tab', function(e) {
                var targeted_tab = $(this).data('bs-target');
                var text = $(this).text();
                $(this).closest('.tab-links-title').find('.mobile-toggle').text(text);
                var $loader = $(targeted_tab + ' .no-loaded');
                var $carousel = $(targeted_tab + ' .owl-carousel');
                $carousel.hide();
                $loader.show();
                $carousel.trigger('refresh.owl.carousel');
                $carousel.show();
                setTimeout(function() {
                    $loader.hide();
                }, 500);
            });
        });
    </script>
@endpush
