/* SellMarket admin minimal JS (replaces missing admin theme scripts) */
(function () {
    'use strict';

    function ready(fn) {
        if (document.readyState !== 'loading') { fn(); } else { document.addEventListener('DOMContentLoaded', fn); }
    }

    ready(function () {
        // Current year
        document.querySelectorAll('.current-year').forEach(function (el) {
            el.textContent = new Date().getFullYear();
        });

        // Sidebar toggle (collapse/expand on small screens)
        function toggleSidebar() {
            var sidebar = document.querySelector('.page-sidebar');
            var content = document.querySelector('.page-content');
            if (!sidebar || !content) return;
            document.body.classList.toggle('sidebar-hidden');
            if (document.body.classList.contains('sidebar-hidden')) {
                sidebar.style.marginLeft = '-250px';
                content.style.marginLeft = '0';
            } else {
                sidebar.style.marginLeft = '0';
                content.style.marginLeft = '250px';
            }
        }

        ['sidebar-toggle-button', 'collapsed-sidebar-toggle-button'].forEach(function (id) {
            var btn = document.getElementById(id);
            if (btn) {
                btn.addEventListener('click', function (e) {
                    e.preventDefault();
                    toggleSidebar();
                });
            }
        });

        // Fullscreen toggle
        var fsBtn = document.getElementById('toggle-fullscreen');
        if (fsBtn) {
            fsBtn.addEventListener('click', function (e) {
                e.preventDefault();
                if (!document.fullscreenElement && !document.webkitFullscreenElement) {
                    var el = document.documentElement;
                    if (el.requestFullscreen) el.requestFullscreen();
                    else if (el.webkitRequestFullscreen) el.webkitRequestFullscreen();
                } else {
                    if (document.exitFullscreen) document.exitFullscreen();
                    else if (document.webkitExitFullscreen) document.webkitExitFullscreen();
                }
            });
        }

        // Scroll to top
        var toTop = document.querySelector('.scroll-to-top');
        if (toTop) {
            toTop.addEventListener('click', function (e) {
                e.preventDefault();
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        }

        // Highlight the current sidebar link
        var path = window.location.pathname;
        document.querySelectorAll('.accordion-menu a').forEach(function (a) {
            var href = a.getAttribute('href') || '';
            if (href !== '#' && path.indexOf(href) === 0) {
                a.classList.add('active');
            }
        });
    });
})();
