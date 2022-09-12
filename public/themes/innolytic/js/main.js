(function ($) {

    "use strict";

    // Mixitup gallery
    function mixitupGallery() {
        let mixItem = $(".project-gallery");
        if (mixItem.length) {
            mixItem.mixItUp()
        }
    }

    $(window).on('load', function () {

        // STICKY MAIN MENU
        $("#main-menu-area").sticky({
            topSpacing: 0
        });

        // Activate page loader
        $('#preloader').fadeOut();

        if ($(document).scrollTop() > 50) {
            $('.scrolling-navbar').addClass('top-nav-collapse');
        }

        // Sticky Nav
        $(window).on('scroll', function () {
            if ($(window).scrollTop() > 50) {
                $('.scrolling-navbar').addClass('top-nav-collapse');
            } else {
                $('.scrolling-navbar').removeClass('top-nav-collapse');
            }
        });

        // one page navigation
        $('.navbar-nav').onePageNav({
            currentClass: 'active'
        });

        /* Auto Close Responsive Navbar on Click
        ========================================================*/
        function close_toggle() {
            if ($(window).width() <= 768) {
                $('.navbar-collapse a').on('click', function () {
                    $('.navbar-collapse').collapse('hide');
                });
            } else {
                $('.navbar .navbar-inverse a').off('click');
            }
        }

        close_toggle();
        $(window).resize(close_toggle);

        // WOW Scroll Spy
        let wow = new WOW({
            //disabled for mobile
            mobile: false
        });

        wow.init();

        // Testimonials Carousel
        let owl = $("#testimonials-owl");
        owl.owlCarousel({
            loop: true,
            nav: false,
            dots: true,
            center: true,
            margin: 15,
            slideSpeed: 1000,
            stopOnHover: true,
            autoPlay: true,
            responsiveClass: true,
            responsiveRefreshRate: true,
            responsive: {
                0: {
                    items: 1
                },
                768: {
                    items: 2
                },
                960: {
                    items: 3
                },
                1200: {
                    items: 3
                },
                1920: {
                    items: 3
                }
            }
        });

        // demo stores Carousel
        let demo_stores_owl = $("#demostores-owl");
        demo_stores_owl.owlCarousel({
            loop: true,
            nav: true,
            navText: ['<i class="fa fa-angle-left" aria-hidden="true"></i>', '<i class="fa fa-angle-right" aria-hidden="true"></i>'],
            dots: false,
            center: false,
            margin: 15,
            smartSpeed: 1000,
            autoplayTimeout: 5000,
            autoplayHoverPause: true,
            autoplay: true,
            responsiveClass: true,
            responsiveRefreshRate: true,
            responsive: {
                0: {
                    items: 1
                },
                500: {
                    items: 2
                },
                768: {
                    items: 2
                },
                960: {
                    items: 3
                },
                1200: {
                    items: 3
                },
                1920: {
                    items: 3
                }
            }
        });

        // Back Top Link active
        let offset = 200;
        $(window).scroll(function () {
            if ($(this).scrollTop() > offset) {
                $('.back-to-top').fadeIn(400);
            } else {
                $('.back-to-top').fadeOut(400);
            }
        });

        $('.back-to-top').on('click', function (event) {
            event.preventDefault();
            $('html, body').animate({
                scrollTop: 0
            }, 600);
            return false;
        });

        mixitupGallery();

        $(".toggole-boxs").accordion();

    });

}(jQuery));
