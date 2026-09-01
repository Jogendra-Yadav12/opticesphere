@extends('layouts.app')

@section('content')

        @include('header')

        <!-- BANNER
        ================================================== -->
        <section class="full-screen p-0 top-position">
            <div class="slider-fade2 owl-carousel owl-theme w-100">
                @forelse($banners as $banner)
                <div class="item bg-img h-100 w-100 cover-background" data-overlay-dark="0" data-background="{{ Storage::url('images/slider/'.$banner->image_path) }}">
                    <div class="container h-100 d-table">
                        <div class="row d-table-cell align-middle h-100">
                            <div class="col-lg-5">
                                @if($banner->subtitle)
                                <h3 class="alt-font mb-2 h6 text-uppercase">{{ $banner->subtitle }}</h3>
                                @endif
                                <h1 class="display-16 display-sm-8 display-md-5 display-lg-3 mb-1-6 mb-lg-2-9">{{ $banner->title }}</h1>
                                <a href="{{ $banner->link_url ?? url('shop') }}" class="butn-style4">Shop Now</a>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="item bg-img h-100 w-100 cover-background" data-overlay-dark="0" data-background="{{ default_image() }}">
                    <div class="container h-100 d-table">
                        <div class="row d-table-cell align-middle h-100">
                            <div class="col-lg-5">
                                <h3 class="alt-font mb-2 h6 text-uppercase">New Arrivals</h3>
                                <h1 class="display-16 display-sm-8 display-md-5 display-lg-3 mb-1-6 mb-lg-2-9">Decor Inspiration</h1>
                                <a href="{{ url('shop') }}" class="butn-style4">Shop Now</a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforelse
            </div>
        </section>

        <!-- CATEGORY
        ================================================== -->
        <section>
            <div class="container">
                <div class="owl-carousel owl-theme category-carousel">
                    @forelse($categories as $category)
                    <div class="item">
                        <a href="{{ url('shop?category_id='.$category->id) }}" class="categoty-style2">
                            <div class="category-icon mb-4">
                                <img src="{{ $category->img && $category->img !== 'default.png' ? Storage::url('images/'.$category->img) : default_image() }}" alt="{{ $category->name }}">
                            </div>
                            <h3 class="font-weight-500 mb-0">{{ $category->name }}</h3>
                        </a>
                    </div>
                    @empty
                    <div class="col-12"><p>No categories found.</p></div>
                    @endforelse
                </div>
            </div>
        </section>

        <!-- FEATURED PRODUCTS
        ================================================== -->
        <section>
            <div class="container">
                <div class="text-center mb-1-9 mb-lg-2-3">
                    <h2 class="mb-0">Featured Products</h2>
                </div>
                <div class="row mt-n1-9">
                    @forelse($featuredProducts as $product)
                    <div class="col-sm-6 col-lg-3 mt-1-9">
                        <div class="product-grid-four">
                            <div class="product-img">
                                <a href="{{ url('productDetails/'.$product->id) }}">
                                    <img src="{{ $product->image ? Storage::url('images/products/'.$product->image) : default_image() }}" alt="{{ $product->name }}">
                                </a>
                                <div class="action-butn">
                                    @php $purchasable = !in_array($product->vendor_id, $nonPurchasableVendorIds ?? [], true); @endphp
                                    @if($purchasable)
                                    <a href="javascript:void(0)" onclick="event.preventDefault(); document.getElementById('add-to-cart-{{ $product->id }}').submit();" title="Add to Cart"><i class="ti-shopping-cart"></i></a>
                                    @endif
                                    <a href="{{ url('productDetails/'.$product->id) }}"><i class="ti-eye"></i></a>
                                    @php $inWishlist = $wishlistIds->contains($product->id); @endphp
                                    <a href="javascript:void(0)" onclick="toggleWishlist({{ $product->id }})" title="{{ $inWishlist ? 'Remove From Wishlist' : 'Add to Wishlist' }}"><i class="{{ $inWishlist ? 'fas' : 'far' }} fa-heart" data-wishlist-product="{{ $product->id }}" style="{{ $inWishlist ? 'color:#e11d48;' : '' }}"></i></a>
                                </div>
                                @if($purchasable)
                                <form id="add-to-cart-{{ $product->id }}" action="{{ route('cart.add') }}" method="POST" style="display:none;">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <input type="hidden" name="quantity" value="1">
                                </form>
                                @endif
                            </div>
                            <h3 class="h6"><a href="{{ url('productDetails/'.$product->id) }}">{{ $product->name }}</a></h3>
                            <span class="font-weight-600 display-29 text-muted">₹{{ number_format($product->price, 2) }}</span>
                            @if(!$purchasable)
                            <div><a href="{{ route('store', $product->vendor?->slug ?: $product->vendor_id) }}" class="badge bg-secondary text-decoration-none"><i class="fas fa-store me-1"></i> Visit Store for Purchase</a></div>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="col-12 mt-1-9"><p class="text-center">No featured products found.</p></div>
                    @endforelse
                </div>
            </div>
        </section>

        <!-- SELLERS
        ================================================== -->
        <section class="pt-0">
            <div class="container">
                <div class="text-center mb-1-9 mb-lg-2-3">
                    <h2 class="mb-0">Our Sellers</h2>
                </div>
                <div class="owl-carousel owl-theme sellers-carousel">
                    @forelse($vendors as $vendor)
                    <div class="item">
                        <a href="{{ route('store', $vendor->slug ?: $vendor->id) }}" class="d-block text-decoration-none">
                        <div class="categoty-style2 text-center">
                            <div class="category-icon mb-4">
                                <img src="{{ $vendor->logo ? Storage::url('images/logos/'.$vendor->logo) : default_image() }}" alt="{{ $vendor->store_name }}">
                            </div>
                            <h3 class="font-weight-500 mb-1">{{ $vendor->store_name }}</h3>
                            <div class="seller-rating"><i class="fas fa-star"></i> {{ number_format((float) $vendor->rating_avg, 1) }}</div>
                        </div>
                        </a>
                    </div>
                    @empty
                    <p>No sellers found.</p>
                    @endforelse
                </div>
            </div>
        </section>

        <!-- SERVICES
        ================================================== -->
        <section class="p-0">
            <div class="container">
                <div class="row mt-n4">
                    <div class="col-sm-6 col-lg-3 mt-4">
                        <div class="text-center">
                            <img src="{{ asset('img/icons/icon-10.png') }}" class="mb-3" alt="...">
                            <h3 class="h5">Free Shipping</h3>
                            <p class="mb-0">Free shipping over ₹100</p>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-3 mt-4">
                        <div class="text-center">
                            <img src="{{ asset('img/icons/icon-11.png') }}" class="mb-3" alt="...">
                            <h3 class="h5">Money Return</h3>
                            <p class="mb-0">Guarantee under 7 days</p>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-3 mt-4">
                        <div class="text-center">
                            <img src="{{ asset('img/icons/icon-12.png') }}" class="mb-3" alt="...">
                            <h3 class="h5">Gift Voucher</h3>
                            <p class="mb-0">Get ₹15 off your order</p>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-3 mt-4">
                        <div class="text-center">
                            <img src="{{ asset('img/icons/icon-13.png') }}" class="mb-3" alt="...">
                            <h3 class="h5">Support 24 / 7</h3>
                            <p class="mb-0">Support online 24 hours a day</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

@endsection('content')