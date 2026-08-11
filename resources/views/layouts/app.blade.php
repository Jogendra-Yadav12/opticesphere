<!DOCTYPE html>
<html lang="en">


<!-- Mirrored from smartshop.websitelayout.net/home-shop-4.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 09 Jan 2024 11:18:41 GMT -->
<head>

    <!-- metas -->
    <meta charset="utf-8">
    <meta name="author" content="Chitrakoot Web" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="keywords" content="Multipurpose eCommerce Template + Admin" />
    <meta name="description" content="Smartshop - Multipurpose eCommerce Template + Admin" />

    <!-- store settings -->
    @php
        $storeName = \App\Models\Setting::where('key', 'store_name')->value('value') ?? 'Optic Sphere';
        $storeLogo = \App\Models\Setting::where('key', 'store_logo')->value('value');
        $contactEmail = \App\Models\Setting::where('key', 'contact_email')->value('value') ?? 'support@opticsphere.com';
        $supportPhone = \App\Models\Setting::where('key', 'support_phone')->value('value') ?? '+91 9794108026';
        $socialLinks = [
            'facebook' => \App\Models\Setting::where('key', 'social_facebook')->value('value'),
            'twitter' => \App\Models\Setting::where('key', 'social_twitter')->value('value'),
            'instagram' => \App\Models\Setting::where('key', 'social_instagram')->value('value'),
            'linkedin' => \App\Models\Setting::where('key', 'social_linkedin')->value('value'),
        ];
        $footerCreditText = \App\Models\Setting::where('key', 'footer_credit_text')->value('value') ?? 'Jogendra Yadav';
        $footerCreditUrl = \App\Models\Setting::where('key', 'footer_credit_url')->value('value') ?? 'https://jogendra-yadav.netlify.app/';
        $paymentMethods = array_values(array_filter(array_map('trim', explode(',', \App\Models\Setting::where('key', 'payment_methods')->value('value') ?? 'visa.png, mastercard.png, paypal.png, amex.png, discover.png'))));
        $favicon = $storeLogo ? asset('images/logos/'.$storeLogo) : asset('img/logos/logo.png');
    @endphp

    <!-- title  -->
    <title>{{ $storeName }}</title>

    <!-- favicon -->
    <link rel="shortcut icon" href="{{ $favicon }}">
    <link rel="apple-touch-icon" href="{{ $favicon }}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ $favicon }}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ $favicon }}">

    <!-- plugins -->
    <link rel="stylesheet" href="{{ asset('css/plugins.css') }}">

    <!-- revolution slider css -->
    <link rel="stylesheet" href="{{ asset('css/rev_slider/settings.css') }}">
    <link rel="stylesheet" href="{{ asset('css/rev_slider/layers.css') }}">
    <link rel="stylesheet" href="{{ asset('css/rev_slider/navigation.css') }}">

    <!-- theme core css -->
    <link href="{{ asset('css/styles-3.css') }}" rel="stylesheet">

</head>

<body>

    <!-- PAGE LOADING
    ================================================== -->
    <div id="preloader"></div>

    @yield('content')
    

        <!-- FOOTER
        ================================================== -->
        <footer class="classic-footer bordered">

            <div class="container">
                <div class="row">

                    <div class="col-lg-4 col-md-6 mb-2-3 mb-lg-0">
                        <h3>Contact Us</h3>
                        <ul class="list-style mb-2 ps-0">
                            <li>
                                <strong>Phone: </strong><span class="ps-1">{{ $supportPhone }}</span>
                            </li>
                            <li>
                                <strong>Email: </strong><span class="ps-1">{{ $contactEmail }}</span>
                            </li>
                        </ul>

                        <ul class="list-style-two mb-0 ps-0">
                            @if($socialLinks['facebook'])
                            <li><a href="{{ $socialLinks['facebook'] }}" target="_blank" rel="noopener"><i class="fab fa-facebook-f"></i></a></li>
                            @endif
                            @if($socialLinks['twitter'])
                            <li><a href="{{ $socialLinks['twitter'] }}" target="_blank" rel="noopener"><i class="fab fa-twitter"></i></a></li>
                            @endif
                            @if($socialLinks['instagram'])
                            <li><a href="{{ $socialLinks['instagram'] }}" target="_blank" rel="noopener"><i class="fab fa-instagram"></i></a></li>
                            @endif
                            @if($socialLinks['linkedin'])
                            <li><a href="{{ $socialLinks['linkedin'] }}" target="_blank" rel="noopener"><i class="fab fa-linkedin-in"></i></a></li>
                            @endif
                        </ul>
                    </div>

                    <div class="col-lg-4 col-md-6 mb-2-3 mb-lg-0">

                        <div class="row">
                            @php
                                $footerPages = \App\Models\Page::where('status', 'published')->get()->keyBy('slug');
                                $quickSlugs = ['news', 'history', 'our-shop', 'secure-shopping', 'privacy-policy'];
                            @endphp
                            <div class="col-md-6 col-6 pe-lg-0">
                                <h3>Quick Links</h3>
                                <ul class="list-style ps-0">
                                    <li><a href="{{ url('shop') }}">Shop</a></li>
                                    <li><a href="{{ url('aboutus') }}">About Us</a></li>
                                    @foreach($quickSlugs as $quickSlug)
                                    @if(isset($footerPages[$quickSlug]))
                                    <li><a href="{{ url('page/'.$quickSlug) }}">{{ $footerPages[$quickSlug]->title }}</a></li>
                                    @endif
                                    @endforeach
                                    <li><a href="{{ url('contact') }}">Contact Us</a></li>
                                </ul>
                            </div>
                            <div class="col-md-6 col-6 pe-lg-0">
                                <h3>My Account</h3>
                                <ul class="list-style ps-0">
                                    @auth
                                    <li><a href="{{ route('profile') }}">My Account</a></li>
                                    <li><a href="{{ route('orders') }}">Order History</a></li>
                                    <li><a href="{{ route('address') }}">My Addresses</a></li>
                                    <li><a href="{{ route('cart') }}">Shopping Cart</a></li>
                                    @else
                                    <li><a href="{{ route('login') }}">My Account</a></li>
                                    <li><a href="{{ route('register') }}">Create Account</a></li>
                                    <li><a href="{{ route('seller.register') }}">Become a Seller</a></li>
                                    <li><a href="{{ route('cart') }}">Shopping Cart</a></li>
                                    @endauth
                                    <li><a href="{{ url('shop') }}">Returns</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-12">
                        <div class="ps-lg-1-9">

                            <div class="row">
                                <div class="col-lg-12 col-md-6 mb-1-9">
                                    <h3>News Letter</h3>
                                    <form method="POST" action="{{ route('newsletter.subscribe') }}" id="footerSubscribeForm">
                                        @csrf
                                        <div class="form-group footer-subscribe">
                                            <input type="email" name="email" placeholder="Subscribe with us" id="email" class="form-control" required />
                                            <button type="submit" class="butn-style2"><i class="fas fa-paper-plane display-27 mt-1"></i></button>
                                        </div>
                                    </form>
                                    @if(session('success'))
                                    <p class="text-success small mt-2 mb-0">{{ session('success') }}</p>
                                    @endif
                                    @if($errors->any())
                                    <p class="text-danger small mt-2 mb-0">{{ $errors->first() }}</p>
                                    @endif
                                </div>
                                <div class="col-lg-12 col-md-6">
                                    <h3>Download Our Mobile Apps</h3>
                                    <div class="text-start">
                                        <a href="/" class="btn bordered text-start ms-1 ms-md-0 mt-1 mt-md-0 mb-0 mb-lg-2 mb-xl-0">
                                            <span class="media align-items-center">
                                        <span class="fab fa-apple display-26 pe-3"></span>
                                            <span class="media-body">
                                            <span class="d-block display-32">Download on the</span>
                                            <strong>App Store</strong>
                                            </span>
                                            </span>
                                        </a>

                                        <a href="/" class="btn bordered text-start ms-1 ms-lg-0 ms-xl-1 mt-1 mt-md-0">
                                            <span class="media align-items-center">
                                        <span class="fab fa-google-play display-26 pe-3"></span>
                                            <span class="media-body">
                                            <span class="d-block display-32">Get it on</span>
                                            <strong>Google Play</strong>
                                            </span>
                                            </span>
                                        </a>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>

            <div class="footer-bottom py-1-9 mt-6 mt-lg-8">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <div class="text-center text-md-start">
                                <p class="mb-0">&copy; <span class="current-year"></span> {{ $storeName }} | Design by @if($footerCreditUrl)<a href="{{ $footerCreditUrl }}" target="_blank" class="text-purple">@endif{{ $footerCreditText }}@if($footerCreditUrl)</a>@endif</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <ul class="list-style-17 text-center text-md-end">
                                @foreach($paymentMethods as $paymentMethod)
                                <li><img src="{{ asset('img/content/payment-options/'.$paymentMethod) }}" alt="{{ $paymentMethod }}"/></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </footer>

    </div>

    <!-- SCROLL TO TOP
    ================================================== -->
    <a href="#" class="scroll-to-top"><i class="fas fa-angle-up" aria-hidden="true"></i></a>

    <!-- all js include start -->

    <!-- jQuery -->
    <script src="{{ asset('js/jquery.min.js') }}"></script>

    <!-- popper js -->
    <script src="{{ asset('js/popper.min.js') }}"></script>

    <!-- bootstrap -->
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>

    <!-- core.min.js -->
    <script src="{{ asset('js/core.min.js') }}"></script>

    <!-- owl carousel -->
    <script src="{{ asset('js/owl.carousel.min.js') }}"></script>

    <!-- ion range slider -->
    <script src="{{ asset('_admin/plugins/rangeslider/ion.rangeSlider.min.js') }}"></script>

    <!-- storefront carousels (must be before main.js) -->
    <script src="{{ asset('js/storefront-carousels.js') }}"></script>

    <!-- xzoom -->
    <script src="{{ asset('js/xzoom.js') }}"></script>
    <script src="{{ asset('js/setup.js') }}"></script>

    <!-- custom scripts -->
    <script src="{{ asset('js/main.js') }}"></script>

    <!-- storefront helpers (nav + cart dropdown fallback) -->
    <script src="{{ asset('js/app.js') }}"></script>

    <script>
        function toggleWishlist(productId) {
            fetch("{{ route('wishlist.toggle') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ product_id: productId })
            }).then(function (response) {
                if (!response.ok) {
                    if (response.redirected || (response.headers.get('content-type') || '').indexOf('application/json') === -1) {
                        window.location = "{{ route('login') }}";
                        return null;
                    }
                    return null;
                }
                return response.json();
            }).then(function (data) {
                if (!data) return;
                document.querySelectorAll('[data-wishlist-product="' + productId + '"]').forEach(function (icon) {
                    icon.className = data.added ? 'fas fa-heart' : 'far fa-heart';
                    icon.style.color = data.added ? '#e11d48' : '';
                });
                var badge = document.getElementById('wishlistCount');
                if (badge) {
                    var n = parseInt(badge.textContent, 10) || 0;
                    badge.textContent = data.added ? n + 1 : Math.max(n - 1, 0);
                    badge.style.display = (parseInt(badge.textContent, 10) || 0) > 0 ? '' : 'none';
                }
            }).catch(function () {
                alert('Something went wrong. Please try again.');
            });
        }
    </script>
	
	<!-- all js include end -->

</body>


<!-- Mirrored from smartshop.websitelayout.net/home-shop-4.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 09 Jan 2024 11:18:49 GMT -->
</html>