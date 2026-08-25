<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{ !is_null($setting) ? $setting->title : '' }}</title>
    <link rel="shortcut icon"
        href="{{ !is_null($setting) && file_exists($setting->favicon) ? asset($setting->favicon) : asset('frontend/assets/images/logo/favicon.png') }}"
        type="image/x-icon">

    @include('layouts.frontend.partial.styles')
    <!-- End Css Links -->
    @include('layouts.admin.partial.alert')
</head>

<body>
    <div class="content-wrapper">
        @include('layouts.frontend.partial.header')
        @yield('content')
        @include('layouts.frontend.partial.footer')
    </div>
    <!-- End Contents -->

    @include('layouts.frontend.partial.components')
    @include('layouts.frontend.partial.scripts')
    @include('sweetalert::alert')
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>


    <script type="text/javascript">
        $(document).ready(function() {
            $('#search').on('keyup', function() {
                ajaxSearch();
            });

            $('#search').on('focus', function() {
                ajaxSearch();
            });

            function ajaxSearch() {
                var searchKey = $('#search').val();
                if (searchKey.length > 0) {
                    $('body').addClass("typed-search-box-shown");
                    $('.typed-search-box').removeClass('d-none');
                    $('.search-preloader').removeClass('d-none');

                    let url = "{{ Route('frontend.ajax.search') }}";
                    $.ajax({
                        url: url,
                        data: {
                            _method: 'GET',
                            search: searchKey,
                        },
                        success: (response) => {
                            if (response == '0') {
                                $('#search-content').html(null);
                                $('.typed-search-box .search-nothing').removeClass('d-none').html(
                                    'Sorry, nothing found for <strong>"' + searchKey + '"</strong>');
                                $('.search-preloader').addClass('d-none');

                            } else {
                                $('.typed-search-box .search-nothing').addClass('d-none').html(null);
                                $('#search-content').html(response);
                                $('.search-preloader').addClass('d-none');
                            }
                        }
                    });
                } else {
                    $('.typed-search-box').addClass('d-none');
                    $('body').removeClass("typed-search-box-shown");
                }
            }

            $(document).on('click', '.nav__image', function(e) {
                e.preventDefault();
                var img = $(this).data('image');
                $('.popup-image').attr('href', img);
                $('#zoomImg').attr('src', img);
                $('#big_img').attr('src', img);
            });

            $(document).on('click', '.toModal,.quickview-handler', function(e) {
                e.preventDefault();
                let product_id = $(this).data('id');
                let url = "{{ Route('frontend.view-product') }}";
                $.ajax({
                    type: 'POST',
                    url: url,
                    data: {
                        _method: 'GET',
                        id: product_id,
                    },
                    success: (response) => {
                        $('#modal_content').html(response);

                        var swiper = new Swiper("#image-gallery", {
                            effect: 'slide',
                            pagination: false,
                            slidesPerView: 'auto',
                            spaceBetween: 5,
                            autoHeight: true,
                            navigation: {
                                nextEl: ".button-next",
                                prevEl: ".button-prev",
                            },
                            // Responsive breakpoints
                            breakpoints: {
                                0: {
                                    direction: "horizontal",
                                    slideToClickedSlide: true,
                                    centeredSlides: true,
                                    centerInsufficientSlides: true,
                                    centeredSlidesBounds: true,
                                },
                                576: {
                                    direction: "horizontal",
                                }
                            }
                        });
                        $('#view_modal').modal('show');
                    }
                });
            });

            $(document).on('click', '.toCart', function(e) {
                e.preventDefault();
                let product_id = $(this).data('id');
                let variant_id = $(this).data('variant');
                let qty = $(this).closest('.qty_wrapper').find('.cart_qty').length ? $(this).closest(
                    '.qty_wrapper').find('.cart_qty') : 1;
                let url = "{{ Route('customer.add-cart') }}";
                $.ajax({
                    type: 'POST',
                    url: url,
                    data: {
                        id: product_id,
                        variant_id: variant_id,
                        quantity: qty,
                    },
                    success: (response) => {
                        if (response.status == 'success') {
                            $('.minicart-items').append(response.append);
                            $('.cart_count').html(response.total_cart_items);
                            $('.total_cart_price').html(response.total_cart_price);
                            Swal.fire({
                                width: "22rem",
                                text: 'Product Added into Cart Successfully!',
                                icon: "success",
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 1500
                            });
                        }
                        if (response.status == 'quantity_updated') {
                            $(response.selector).val(response.qty);
                            $('.total_cart_price').html(response.total_cart_price);
                            Swal.fire({
                                width: "22rem",
                                text: 'Quantity Updated!',
                                icon: "success",
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 1500
                            });
                        }
                        if (response.status == 'error') {
                            Swal.fire({
                                width: "22rem",
                                text: response.data,
                                icon: "error",
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 1500
                            });
                        }
                    }
                });
            });

            $(document).on('click', '.delete', function(e) {
                e.preventDefault();
                let id = $(this).data('id');
                let variant_id = $(this).data('variant');

                let url = "{{ Route('customer.remove-cart') }}";
                $.ajax({
                    url: url,
                    data: {
                        _method: 'GET',
                        id: id,
                        variant_id: variant_id,
                    },
                    success: (response) => {
                        if (response.status == 'success') {
                            Swal.fire({
                                width: "22rem",
                                text: 'Removed Successfully!',
                                icon: "success",
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            $(response.selector).remove();
                            $(response.selector + '_page').remove();
                            $('.cart_count').text(response.total_cart_items);
                            $('.total_cart_price').html((response.total_cart_price).toFixed(2));
                            $('.cart_total').html(response.total_cart_price);
                            if ($('#charge').length && response.total_cart_price > 0) {
                                var charge = parseFloat($('#charge').text());
                                $('.total_with_charge').html(response.total_cart_price +
                                    charge);
                            } else if ($('#charge').length) {
                                var charge = (+$('#charge').text()).toFixed(2);
                                $('.total_with_charge').text(charge);
                            }
                            if (response.total_cart_items == 0) {
                                $('#cart-footer-area').hide();
                                $('#cart_items').html('');
                                $('.cart_count').text('0');
                                $('#cart_items').append(
                                    '<li class="no-items" id="no_items">No products in the cart</li>'
                                );
                            }
                        }
                    }
                });
            });

            $(document).on('click', '.add-to-wishlist', function(e) {
                e.preventDefault();
                let id = $(this).data('id');

                let url = "{{ Route('customer.wishlist') }}";
                $.ajax({
                    url: url,
                    data: {
                        _method: 'GET',
                        id: id,
                    },
                    success: (response) => {
                        if (response.status == 'success') {
                            Swal.fire({
                                width: "22rem",
                                text: 'Added Successfully!',
                                icon: "success",
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 1500
                            });
                        }
                        if (response.status == 'error') {
                            Swal.fire({
                                width: "22rem",
                                text: 'Already Added this Product!',
                                icon: "error",
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 1500
                            });
                        }
                    }
                });
            });

            $('#option-choice-form select').on('change', function() {
                getVariantPrice();
            });

            $(document).on('click', '#add_cart_btn', function(e) {
                e.preventDefault();
                let url = "{{ Route('customer.add-cart') }}";
                $.ajax({
                    url: url,
                    type: "POST",
                    data: $('#option-choice-form').serializeArray(),
                    success: (response) => {
                        if (response.status == 'success') {
                            $('.minicart-items').append(response.append);
                            $('.cart_count').html(response.total_cart_items);
                            $('.total_cart_price').html(response.total_cart_price);
                            Swal.fire({
                                width: "22rem",
                                text: 'Product Added into Cart Successfully!',
                                icon: "success",
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 1500
                            });
                        }
                        if (response.status == 'quantity_updated') {
                            $(response.selector).val(response.qty);
                            $('.total_cart_price').html(response.total_cart_price);
                            Swal.fire({
                                width: "22rem",
                                text: 'Quantity Updated!',
                                icon: "success",
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 1500
                            });
                        }
                        if (response.status == 'error') {
                            Swal.fire({
                                width: "22rem",
                                text: response.data,
                                icon: "error",
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 1500
                            });
                        }
                    }
                });
                $('#view_modal').modal('hide');
            });

            $(document).on('click', '.plus-btn', function(e) {
                let id = $(this).data('id');
                let variant_id = $(this).data('variant');
                let qty = $(this).closest('.cart-quantity').find('.item-qty').val();
                qty++;
                $(this).closest('.cart-quantity').find('.item-qty').val(qty);
                updateCart(id, variant_id, qty);
            });

            $(document).on('click', '.minus-btn', function(e) {
                let id = $(this).data('id');
                let variant_id = $(this).data('variant');
                let qty = $(this).closest('.cart-quantity').find('.item-qty').val();
                qty--;
                if (qty == 0) {
                    return;
                }
                $(this).closest('.cart-quantity').find('.item-qty').val(qty);
                updateCart(id, variant_id, qty);
            });

            $(document).on('click', '.qty-plus', function(e) {
                let qty = $(this).closest('.input-group').find('.quantity_wanted').val();
                qty++;
                $(this).closest('.input-group').find('.quantity_wanted').val(qty);
            });

            $(document).on('click', '.qty-minus', function(e) {
                let qty = $(this).closest('.input-group').find('.quantity_wanted').val();
                qty--;
                if (qty == 0) {
                    return;
                }
                $(this).closest('.input-group').find('.quantity_wanted').val(qty);
            });
        });

        function updateCart(id, variant_id, qty) {
            let url = "{{ Route('customer.update-cart') }}";
            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    _method: 'POST',
                    product_id: id,
                    variant_id: variant_id,
                    quantity: qty,
                },
                success: (response) => {
                    if (response.status == 'success') {
                        $('.cart_count').text(response.total_cart_items);
                        $('.total_cart_price').html(response.total_cart_price);
                        $('.cart_total').html(response.total_cart_price);
                        $('#total_with_shipping').html(response.total_with_shipping);
                        $('#page-' + response.query).val(response.qty);
                        $('#header-' + response.query).val(response.qty);
                        $('#subtotal-' + response.query).text(response.subtotal);
                    }
                }
            });
        }

        function getVariantPrice() {
            if ($('#option-choice-form input[name=quantity]').val() > 0 && checkAddToCartValidity()) {
                $.ajax({
                    type: "POST",
                    url: "{{ route('frontend.product.variant-price') }}",
                    data: $('#option-choice-form').serializeArray(),
                    success: function(data) {
                        $('#quantity_wanted').val(data.quantity);
                        $('#quantity_wanted').attr('max', data.stock);
                        $('.special-price .price').text('TK' + data.regular_price);
                        if (data.sale_price) {
                            $('.old-price .price').text('TK' + data.sale_price);
                        } else {
                            $('.old-price .price').text('');
                        }
                        $('#available-quantity').html(data.stock + ' &nbsp;');

                        if (parseInt(data.stock) > 0) {
                            $('.out-of-stock').addClass('d-none');
                            $('.in-stock').removeClass('d-none');
                            $('.add_cart_btn').attr('disabled', false);
                        } else {
                            $('.add_cart_btn').attr('disabled', true);
                            $('.out-of-stock').removeClass('d-none');
                            $('.in-stock').addClass('d-none');
                        }
                        $('#product_sku').html(data.sku);
                        $('#variant_id').val(data.variant_id);
                        $('#add_cart_btn').attr('data-variant', data.variant_id);
                    }
                });
            }
        }

        function checkAddToCartValidity() {
            var names = {};
            $('#option-choice-form select').each(function() {
                // find unique names
                names[$(this).attr('name')] = true;
            });

            var count = 0;
            $.each(names, function() {
                // then count them
                count++;
            });

            if ($('#option-choice-form select option:selected').length == count) {
                return true;
            }

            return false;
        }
    </script>
    @stack('js')
</body>

</html>
