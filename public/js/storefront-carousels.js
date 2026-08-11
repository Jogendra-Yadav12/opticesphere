/* Storefront carousels (categories + sellers)
   Must load AFTER owl.carousel.min.js and BEFORE main.js so these
   specific configs win over main.js's generic .owl-carousel init. */
!function (o) {
    "use strict";
    o(document).ready(function () {
        o(".category-carousel").owlCarousel({
            loop: true,
            nav: false,
            dots: false,
            autoplay: true,
            autoplayTimeout: 4e3,
            autoplayHoverPause: true,
            responsiveClass: true,
            margin: 30,
            smartSpeed: 800,
            responsive: {
                0: { items: 2, margin: 15 },
                576: { items: 3 },
                768: { items: 4 },
                992: { items: 5 },
                1200: { items: 6 }
            }
        });
        o(".sellers-carousel").owlCarousel({
            loop: true,
            nav: false,
            dots: true,
            autoplay: true,
            autoplayTimeout: 4e3,
            autoplayHoverPause: true,
            responsiveClass: true,
            margin: 25,
            smartSpeed: 800,
            responsive: {
                0: { items: 1 },
                576: { items: 2, margin: 15 },
                768: { items: 3 },
                992: { items: 4 },
                1200: { items: 5 }
            }
        });
    });
}(jQuery);
