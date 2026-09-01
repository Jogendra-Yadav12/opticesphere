<!-- MAIN WRAPPER
    ================================================== -->
    <div class="main-wrapper mp-pusher" id="mp-pusher">
<!-- HEADER
        ================================================== -->
        <header class="menu_area-light header-light-nav header-02" style="background:#ffffff;border-bottom:1px solid #e5e7eb;">

            <div class="navbar-default">

                <!-- top search -->
                <div class="top-search bg-primary">
                    <div class="container-fluid">
                        <form class="search-form" action="#" method="GET">
                            <div class="input-group">
                                <span class="input-group-addon cursor-pointer">
                                    <button class="search-form_submit fas fa-search display-27 text-white" type="submit"></button>
                                </span>
                                <input type="text" class="search-form_input form-control" name="s" autocomplete="off" placeholder="Type & hit enter...">
                                <span class="input-group-addon close-search"><i class="fas fa-times display-27 mt-2"></i></span>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- end top search -->

                <div class="container">
                    <div class="row align-items-center g-0">
                        <div class="col-12 col-lg-12">
                            <div class="menu_area alt-font">
                                <nav class="navbar navbar-expand-lg navbar-light p-0">

                                    <div class="navbar-header navbar-header-custom">
                                        <!-- logo -->
                                        @php
                                            $storeName = \App\Models\Setting::where('key', 'store_name')->value('value') ?? 'Optic Sphere';
                                            $storeLogo = \App\Models\Setting::where('key', 'store_logo')->value('value');
                                        @endphp
                                        <a href="{{ url('/') }}" class="navbar-brand">
                                            @if($storeLogo)
                                                <img src="{{ Storage::url('images/logos/'.$storeLogo) }}" alt="logo" style="max-height: 50px;">
                                            @else
                                                <h4>{{ $storeName }}</h4>
                                            @endif
                                        </a>
                                        <!-- end logo -->
                                    </div>

                                    <!-- menu toggler -->
                                    <div class="navbar-toggler"></div>
                                    <!-- end menu toggler -->

                                    <!-- menu area -->
                                    <ul class="navbar-nav mx-auto" style="display: none;color:black">
                                    <li>
                                        <a href="{{url('/')}}">Home</a>
                                    </li>
                                    <li>
                                        <a href="{{url('/shop')}}">Shop</a>
                                    </li>
                                    <li>
                                        <a href="{{url('/contact')}}">Contacts</a>
                                    </li>
                                    <li>
                                        <a href="{{url('/aboutus')}}">About Us</a>
                                    </li>
                                    @auth
                                    @php $authVendor = \App\Models\Vendor::where('user_id', auth()->id())->first(); @endphp
                                    @if($authVendor && $authVendor->status === 'approved' && $authVendor->banner)
                                    <li>
                                        <a href="{{ route('store', $authVendor->slug ?: $authVendor->id) }}">My Store</a>
                                    </li>
                                    @endif
                                    <li>
                                        <a href="{{url('/profile')}}">Account</a>
                                    </li>
                                    @endauth
                                    @guest
                                    <li>
                                        <a href="{{ route('login') }}">Login</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('register') }}">Register</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('seller.register') }}">Become a Seller</a>
                                    </li>
                                    @endguest
                                    </ul>
                                    <!-- end menu area -->

                                    <!-- attribute navigation -->
                                    <div class="attr-nav">
                                        <ul>
                                            @auth
                                            <li class="me-2 me-lg-0">
                                                <a href="{{ route('wishlist') }}" title="Wishlist">
                                                    <i class="ti-heart"></i>
                                                    @php $wishlistCount = auth()->user()->wishlists()->count(); @endphp
                                                    @if($wishlistCount > 0)
                                                    <span class="badge bg-primary" id="wishlistCount">{{ $wishlistCount }}</span>
                                                    @else
                                                    <span class="badge bg-primary" id="wishlistCount" style="display:none;">0</span>
                                                    @endif
                                                </a>
                                            </li>
                                            @endauth
                                            <li class="dropdown me-3 me-lg-0">
                                                <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown">
                                                    <i class="ti-bag"></i>
                                                    @php
                                                        $cartCount = auth()->check() && auth()->user()->cart ? auth()->user()->cart->items->sum('quantity') : 0;
                                                        $cartItems = auth()->check() && auth()->user()->cart ? auth()->user()->cart->items()->with('product.images', 'product.attributeValues.attribute', 'variant.attributeValues.attribute')->get() : collect();
                                                        $cartTotal = 0;
                                                    @endphp
                                                    <span class="badge bg-primary">{{ $cartCount }}</span>
                                                </a>
                                                <ul class="dropdown-menu cart-list">
                                                    @forelse($cartItems as $item)
                                                    @php
                                                        $cartItemPrice = $item->unit_price ?? ($item->product->special_price ?? $item->product->price);
                                                        $cartTotal += $cartItemPrice * $item->quantity;
                                                    @endphp
                                                    <li>
                                                        <a href="{{ url('productDetails/'.$item->product_id) }}" class="photo">
                                                            <img src="{{ $item->product->image ? Storage::url('images/products/'.$item->product->image) : default_image() }}" class="cart-thumb" alt="..." />
                                                        </a>
                                                        <h6><a href="{{ url('productDetails/'.$item->product_id) }}">{{ $item->product->name }}</a></h6>
                                                        @if(count($item->selected_options) > 0)
                                                            <p class="small text-muted">
                                                                @foreach($item->selected_options as $option)
                                                                    <span class="me-1">{{ $option['name'] }}: {{ $option['value'] }}</span>
                                                                @endforeach
                                                            </p>
                                                        @endif
                                                        <p>{{ $item->quantity }}x - <span class="price">₹{{ number_format($cartItemPrice, 2) }}</span></p>
                                                    </li>
                                                    @empty
                                                    <li>
                                                        <p class="text-center mt-3">Your cart is empty.</p>
                                                    </li>
                                                    @endforelse
                                                    
                                                    @if($cartCount > 0)
                                                    <li class="total bg-primary">
                                                        <span class="float-start"><strong>Total</strong>: ₹{{ number_format($cartTotal, 2) }}</span>
                                                        <a href="{{ url('cart') }}" class="butn-style2 small white float-end w-auto"><span>View Cart</span></a>
                                                    </li>
                                                    @endif
                                                </ul>
                                            </li>
                                            <li class="search"><a href="#"><i class="ti-search"></i></a></li>
                                        </ul>
                                    </div>
                                    <!-- end attribute navigation -->

                                </nav>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </header>