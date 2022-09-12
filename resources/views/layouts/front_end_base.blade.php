<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="language" content="en-uk, english">
    <meta name="description" content="">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{{ config('settings.SITE_NAME') }}</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/frontend.css') }}">
     @yield('css')
    <!-- Favicon icon -->
    <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}">
</head>


<body>
<!-- Header -->
<header class="header">
    <!-- Top Bar -->
    <div class="top_bar">
        <div class="container">
            <div class="row">
                <div class="col d-flex flex-row">
                    <div class="header-social-bookmark">
                        <ul class="social-bookmark">
                            @if (config('settings.SOCIAL_MEDIA_LINK_FACEBOOK'))
                                <li>
                                    <a class="facebook" href="{{ config('settings.SOCIAL_MEDIA_LINK_FACEBOOK') }}">
                                        <i class="fab fa-facebook-f"></i>
                                    </a>
                                </li>
                            @endif
                            <li>
                                <a class="youtube" href="#">
                                    <i class="fab fa-youtube"></i>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="top_bar_content ml-auto">
                        <div class="btn-group">
                            <button type="button" class="ml-2 btn  btn-sm dropdown-toggle pr-3" data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false"
                                    style="border-radius:100px;font-size:14px; background-color:#fafafa; color:black; border-color:grey">
                                    <span style="padding-right:5px">
                                        <img class="img-responsive pl-1 pr-2"
                                            style="height: 14px; padding-bottom:2px"
                                             src="{{ asset('images/country-flags/'.Session::get('currency').'.png') }}"
                                             alt="currency">
                                        {{ Session::get('currency') }}
                                    </span>
                                    <i class="fa fa-angle-down"></i>
                            </button>
                            <div class="dropdown-menu p-1" style="min-width:3rem;border-radius:6px;"
                                 id="price_currency">
                                @foreach (['BDT','INR','USD','GBP','SAR','EUR' ] as $currency)
                                    <a class="dropdown-item" href="javascript:void(0)" data-value="{{$currency}}"
                                       style="padding:.25rem 0.8rem;">
                                        <span>
                                            <img class="img-responsive pr-2"
                                                 src="{{asset('images/country-flags/'.$currency.'.png')}}"
                                                 alt="currency">
                                            {{ $currency }}
                                        </span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                        <div class="top_bar_user">

                            @if (Route::has('login'))
                                @auth
                                    <div class="user_icon">
                                        <img src="{{ asset('storage/sites/user1.png')}}" alt="">
                                    </div>
                                    <div class="">
                                        <a href="{{ route('members.account') }}" class="navgtn-bar-item-link">
                                            Account
                                        </a>
                                    </div>

                                    <div>
                                        <a href="{{ route('logout') }}" class="navgtn-bar-item-link"
                                           onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                            Sign out
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                              style="display: none;">
                                            @csrf
                                        </form>

                                    </div>

                                @else
                                    <div class="user_icon"><img
                                            src="{{ asset('storage/sites/user.svg')}}"
                                            alt=""></div>
                                    <div><a class="" href="{{ url('/register') }}">Register</a></div>
                                    <div class="user_icon"><img src="{{ asset('storage/sites/lock1.png')}}" alt=""></div>
                                    <div> <a class="" href="{{ url('/login') }}">Login</a></div>
                                @endauth
                            @endif


                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div> <!-- Header Main -->
    <div class="header_main">
        <div class="container">
            <div class="row">
                <!-- Logo -->
                <div class="col-lg-3 col-sm-3 col-3 order-1">
                    <div class="logo_container">
                        <div class="logo">
                            <a class="" href="{{ url('/') }}">
                                <img src="{{ asset('themes/innolytic/image/mob_price_logo.png') }}"
                                     alt="Mobile price Logo">
                            </a>
                        </div>
                    </div>
                </div> <!-- Search -->
                <div class="col-lg-5 col-12 order-lg-2 order-3 text-lg-left text-right">
                    <div class="header_search">
                        <div class="header_search_content typeahead__container">
                            <div class="header_search_form_container typeahead__query">
                                <input id="search-input" type="text" class="typeahead header_search_input"
                                       placeholder="Search Mobiles...">
                                <button type="submit" class="header_search_button trans_300 btn-search"
                                        value="Submit"><img
                                        src="{{ asset('storage/sites/search.png')}}"
                                        alt=""></button>

                            </div>
                        </div>
                    </div>
                </div> <!-- Wishlist -->
                <div class="col-lg-4 col-9 order-lg-3 order-2 text-lg-left text-right">

                    <div class="wishlist_cart d-flex flex-row align-items-center justify-content-end">
                        <div class="wishlist d-flex flex-row align-items-center justify-content-end">

                            <!-- App Store button -->
                            <a href="#" target="_blank" class="home-market-btn apple-btn" role="button">
                                <span class="market-button-subtitle">Download on the</span>
                                <span class="market-button-title">App Store</span>
                            </a>

                        </div> <!-- Cart -->
                        <div class="cart">
                            <div class="cart_container d-flex flex-row align-items-center justify-content-end">
                                <!-- Google Play button -->
                                <a href="https://play.google.com/store/apps/details?id=com.ratebd" target="_blank"
                                   class="home-market-btn google-btn" role="button">
                                    <span class="market-button-subtitle">GET IT ON</span>
                                    <span class="market-button-title">Google Play</span>
                                </a>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- Main Navigation -->
    <nav class="main_nav scrolling-navbar">
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="main_nav_content d-flex flex-row">
                        <!-- Categories Menu -->
                        <!-- Main Nav Menu -->
                        <div class="main_nav_menu">
                            <ul class="standard_dropdown main_nav_dropdown">
                                <li class="{{ Request::is('/') ? 'active' : '' }}">
                                    <a class="" href="{{ route('home') }}">Home<i
                                            class="fas fa-chevron-down"></i></a>
                                </li>

                                <li class=" {{ Request::routeIs('newmobile') ||
                                                   Request::routeIs('mobiledetail') ? 'active' : '' }}">
                                    <a class="" href="{{route('newmobile')}}">Phone Finder</a>
                                </li>
                                <li class=" {{ Request::routeIs('all.brands') ? 'active' : '' }}">
                                    <a class="" href="{{route('all.brands')}}">Brands</a>
                                </li>
                                <li class=" {{ Request::routeIs('news.*') ? 'active' : '' }}">
                                    <a class="" href="{{route('news.all')}}">News</a>
                                </li>
                                <li class=" {{ Request::is('compare') ? 'active' : '' }}">
                                    <a class="" href="{{route('compare')}}">Compare</a>
                                </li>
                            </ul>
                        </div> <!-- Menu Trigger -->
                        <div class="menu_trigger_container ml-auto">
                            <div class="menu_trigger d-flex flex-row align-items-center justify-content-end">
                                <div class="menu_burger">
                                    <div class="menu_trigger_text">menu</div>
                                    <div class="cat_burger menu_burger_inner">
                                        <span></span><span></span><span></span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav> <!-- Menu -->
    <div class="page_menu">
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="page_menu_content">
                        <ul class="page_menu_nav">

                            <li class="page_menu_item {{ Request::is('/') ? 'active' : '' }}">
                                <a class="" href="{{ route('home') }}">Home<i class="fa fa fa-angle-down"></i></a>
                            </li>

                            <li class="page_menu_item  {{ Request::routeIs('newmobile') ||
                                Request::routeIs('mobiledetail') ? 'active' : '' }}">
                                <a class="" href="{{route('newmobile')}}">Phone Finder</a>
                            </li>
                            <li class="page_menu_item  {{ Request::routeIs('all.brands') ? 'active' : '' }}">
                                <a class="" href="{{route('all.brands')}}">Brand</a>
                            </li>
                            <li class="page_menu_item  {{ Request::routeIs('news.*') ? 'active' : '' }}">
                                <a class="" href="{{route('news.all')}}">News</a>
                            </li>
                            <li class="page_menu_item  {{ Request::is('compare') ? 'active' : '' }}">
                                <a class="" href="{{route('compare')}}">Compare</a>
                            </li>
                        </ul>

                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

@yield('content')

<!-- Footer Section Start -->

<!-- Footer Section Start -->
<footer id="footer" class="footer-area section-padding">
    <div class="container">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 col-mb-12">
                    <div class="widget">
                        <h5 class="footer-logo">
                            <a class="" href="{{ url('/') }}">
                                <img src="{{ asset('themes/innolytic/image/mob_price_logo.png') }}"
                                     alt="Mobile price Logo">
                            </a>
                        </h5>
                        <div class="text-widget">
                            <p>Let's Find a mobile for you</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                    <h3 class="footer-title">Support</h3>
                    <ul class="footer-link">
                        <li><a href="{{route('about') }}">About Us</a></li>
                        <li><a href="{{route('contact') }}">Contact Us</a></li>
                        <li><a href="{{route('terms') }}">Terms & Condition</a></li>
                        <li><a href="{{route('privacy') }}">Privacy Policy</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
                    <h3 class="footer-title">Have a Questions?</h3>
                    <ul class="address">
                        <li>
                            <a href="#">
                                <i class="fas fa-map-marker-alt"></i> {!! config('settings.SITE_ADDRESS') !!}
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <i class="fas fa-phone"></i> {{ config('settings.SITE_PHONE') }}
                            </a>
                        </li>
                        <li>
                            <div style="display: flex;flex-direction:row">
                                <div style="padding-top:6px;padding-right:4px">
                                    <i class="fa fa-envelope" style="font-size:20px;color:dimgrey;padding-right:9px;padding-left:6px"></i>
                                </div>
                                <div>
                                    <a href="{{ 'mailto:' . config('settings.SITE_EMAIL') }}">
                                        {{ config('settings.SITE_EMAIL') }}
                                    </a>
                                </div>

                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div id="copyright">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="copyright-content" style="text-align:center">
                        <p>Copyright © {{ date('Y') }} {{ config('settings.SITE_NAME') }} All Right Reserved</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

<!-- Go to Top Link -->
<a href="#" id="backToTop" class="back-to-top-default" style="display:none;">
    <i class="fas fa-long-arrow-alt-up">
        <title>Back to top</title>

    </i>
</a>
<!-- Preloader -->
<div id="preloader">
    <div class="loader" id="loader-1"></div>
</div>

<!-- End Preloader -->

<!-- Modal Filter -->
<div class="modal fade right-full-height size-lg" tabindex="-1" role="dialog" aria-labelledby="searchResults"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="searchResults">Search Results</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body article-list"></div>
        </div>
    </div>
</div>

<!--script-->
<script>
    let dateFormat = '{{ \App\Helpers\CommonHelper::getJsDisplayDateFormat() }}';
    let maxUploadSize = parseInt('{{ config('settings.MAXIMUM_FILE_SIZE') }}');
    let maxUploadSizeInByte = maxUploadSize * 1024 * 1024;
    let mediaUploadRoute = '{{ route('media.summerNoteUpload') }}';
    let defaultDashboardTime = parseInt('{{ config('settings.DEFAULT_DASHBOARD_TIME') }}');
    let loaderImageHtml = '<img src="{{ asset('images/loader-64.gif') }}" class="ajax-loader">';
</script>
<script src="{{ asset('js/frontend.js') }}"></script>
@yield('javascript')
<script>
    $(window).on('load', function () {
        // Activate page loader
        $('#preloader').fadeOut();
    });

    $(document).ready(function () {
        "use strict";

        var menuActive = false;
        var header = $('.header');
        setHeader();
        // initCustomDropdown();
        initPageMenu();

        function setHeader() {

            if (window.innerWidth > 991 && menuActive) {
                closeMenu();
            }
        }

        function initCustomDropdown() {
            if ($('.custom_dropdown_placeholder').length && $('.custom_list').length) {
                var placeholder = $('.custom_dropdown_placeholder');
                var list = $('.custom_list');
            }

            placeholder.on('click', function (ev) {
                if (list.hasClass('active')) {
                    list.removeClass('active');
                } else {
                    list.addClass('active');
                }

                $(document).one('click', function closeForm(e) {
                    if ($(e.target).hasClass('clc')) {
                        $(document).one('click', closeForm);
                    } else {
                        list.removeClass('active');
                    }
                });

            });

            $('.custom_list a').on('click', function (ev) {
                ev.preventDefault();
                var index = $(this).parent().index();

                placeholder.text($(this).text()).css('opacity', '1');

                if (list.hasClass('active')) {
                    list.removeClass('active');
                } else {
                    list.addClass('active');
                }
            });


            $('select').on('change', function (e) {
                placeholder.text(this.value);

                $(this).animate({width: placeholder.width() + 'px'});
            });
        }

        /*

        4. Init Page Menu

        */

        function initPageMenu() {
            if ($('.page_menu').length && $('.page_menu_content').length) {
                var menu = $('.page_menu');
                var menuContent = $('.page_menu_content');
                var menuTrigger = $('.menu_trigger');

                //Open / close page menu
                menuTrigger.on('click', function () {
                    if (!menuActive) {
                        openMenu();
                    } else {
                        closeMenu();
                    }
                });

                //Handle page menu
                if ($('.page_menu_item').length) {
                    var items = $('.page_menu_item');
                    items.each(function () {
                        var item = $(this);
                        if (item.hasClass("has-children")) {
                            item.on('click', function (evt) {
                                evt.preventDefault();
                                evt.stopPropagation();
                                var subItem = item.find('> ul');
                                if (subItem.hasClass('active')) {
                                    subItem.toggleClass('active');
                                    TweenMax.to(subItem, 0.3, {height: 0});
                                } else {
                                    subItem.toggleClass('active');
                                    TweenMax.set(subItem, {height: "auto"});
                                    TweenMax.from(subItem, 0.3, {height: 0});
                                }
                            });
                        }
                    });
                }
            }
        }

        function openMenu() {
            var menu = $('.page_menu');
            var menuContent = $('.page_menu_content');
            TweenMax.set(menuContent, {height: "auto"});
            TweenMax.from(menuContent, 0.3, {height: 0});
            menuActive = true;
        }

        function closeMenu() {
            var menu = $('.page_menu');
            var menuContent = $('.page_menu_content');
            TweenMax.to(menuContent, 0.3, {height: 0});
            menuActive = false;
        }


    });
</script>
<script>
    $(document).ready(function () {
        let searchInputObj = $('#search-input');
        let loaderImageHtml = '<img src="{{ asset('images/loader-64.gif') }}" class="ajax-loader">';
        searchInputObj.typeahead({
            minLength: 1,
            maxItem: 0,
            dynamic: true,
            hint: true,
            template: function (query, item) {
                return `{!! view('front_end.mobile_list_item1') !!}`;
            },
            emptyTemplate: "No results found for <b>@{{query}}</b>",
            source: {
                mobiles: {
                    display: ["title"],
                    href: "@{{href}}",
                    templateValue: "@{{title}}",
                    ajax: function (query) {
                        return {
                            type: "GET",
                            url: '{{ route('mobiles.searchList') }}',
                            path: "data.mobiles",
                            data: {
                                q: query,
                                'homeSearchBox': 'yes'
                            },
                            callback: {
                                done: function (data, textStatus, jqXHR) {
                                    // Perform operations on received data...
                                    // IMPORTANT: data has to be returned if this callback is used
                                    return data;
                                },
                                fail: function (jqXHR, textStatus, errorThrown) {
                                },
                                always: function (data, textStatus, jqXHR) {
                                },
                                then: function (jqXHR, textStatus) {
                                }
                            }
                        }
                    }
                }
            }
        });
        $('.btn-search').click(function () {
            if (searchInputObj.val()) {
                $.ajax({
                    type: "GET",
                    url: "{{ route('mobiles.searchList') }}",
                    data: {
                        q: searchInputObj.val(),
                        isHtml: true,
                        'homeSearchBox': 'yes'
                    },
                    dataType: "html",
                    beforeSend: function () {
                        $('.right-full-height .modal-title').html('Search Results');
                        $('.right-full-height').modal('show');
                        if (loaderImageHtml) {
                            $('.article-list').html(loaderImageHtml).fadeIn(50);
                        }
                    },
                    success: function (data) {
                        $('.article-list').html(data);
                    }
                });
            } else {
                alertify.error('Please write somethings');
            }
        });

        function initializedSlider() {
            for (let i = 1; i < 5; i++) {
                let viewedSlider = $('.bbb_viewed_slider-' + i);
                viewedSlider.owlCarousel({
                    margin: 15,
                    autoplay: false,
                    autoplayTimeout: 6000,
                    nav: false,
                    dots: false,
                    responsiveClass: true,
                    responsive:
                        {
                            0: {items: 1},
                            554: {items: 2},
                            868: {items: 3},
                            1024: {items: 4},
                            1200: {items: 5}
                        }
                });
            }
        }

        initializedSlider();
        if ($('.bbb_viewed_prev').length) {
            var prev = $('.bbb_viewed_prev');
            prev.on('click', function () {
                itemId = $(this).data('prev');
                // initializedSlider(itemId);
                $('.bbb_viewed_slider-' + itemId).trigger('prev.owl.carousel');
            });

        }
        if ($('.bbb_viewed_next').length) {
            var next = $('.bbb_viewed_next');
            next.on('click', function () {
                itemId = $(this).data('next');
                // initializedSlider(itemId);
                $('.bbb_viewed_slider-' + itemId).trigger('next.owl.carousel');
            });
        }

    });

    $(document).ready(function () {
        const backToTop = $('#backToTop')
        const amountScrolled = 200

        $(window).scroll(() => {
            $(window).scrollTop() >= amountScrolled
                ? backToTop.fadeIn('fast')
                : backToTop.fadeOut('fast')
        })

        backToTop.click(() => {
            $('body, html').animate({
                scrollTop: 0
            }, 600)
            return false
        })
        $('#price_currency a').click(function () {
            location.href = '{{ route('changecurrency') }}?currency=' + $(this).data('value');
        });
    })

</script>

</body>
</html>

