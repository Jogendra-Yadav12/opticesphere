/* SellMarket storefront minimal JS (replaces missing theme scripts) */
(function () {
    'use strict';

    function ready(fn) {
        if (document.readyState !== 'loading') { fn(); } else { document.addEventListener('DOMContentLoaded', fn); }
    }

    ready(function () {
        // Show the nav menu (template hides it via inline style until JS runs)
        document.querySelectorAll('ul.navbar-nav').forEach(function (nav) {
            nav.style.removeProperty('display');
        });

        // Search toggle
        var topSearch = document.querySelector('.top-search');
        var searchBtn = document.querySelector('.attr-nav .search a');
        if (topSearch && searchBtn) {
            searchBtn.addEventListener('click', function (e) {
                e.preventDefault();
                topSearch.classList.toggle('open');
                var input = topSearch.querySelector('input');
                if (input && topSearch.classList.contains('open')) { input.focus(); }
            });
            var closeBtn = topSearch.querySelector('.close-search');
            if (closeBtn) {
                closeBtn.addEventListener('click', function () { topSearch.classList.remove('open'); });
            }
        }

        // Year in footer
        document.querySelectorAll('.current-year').forEach(function (el) {
            el.textContent = new Date().getFullYear();
        });

        // Scroll to top
        var toTop = document.querySelector('.scroll-to-top');
        if (toTop) {
            toTop.addEventListener('click', function (e) {
                e.preventDefault();
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        }

        // Simple equal-height product images
        var images = document.querySelectorAll('.product-img');
        if (images.length > 0) {
            var max = 0;
            images.forEach(function (img) { if (img.offsetHeight > max) { max = img.offsetHeight; } });
            if (max > 0) { images.forEach(function (img) { img.style.minHeight = max + 'px'; }); }
        }
    });
})();
