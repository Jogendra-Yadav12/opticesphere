@extends('layouts.app')

@section('content')

@include('header')
<!-- PAGE TITLE
        ================================================== -->
        <section class="page-title-section">
            <div class="container">

                <div class="breadcrumbs-info">
                    <ul class="ps-0">
                        <li><a href="{{ url('/') }}">Home</a></li>
                        <li><a href="{{ url('shop') }}">Shop</a></li>
                        @if(!empty($selectedCategory))
                            @if($selectedCategory->parent)
                            <li><a href="{{ url('shop?category_id='.$selectedCategory->parent->id) }}">{{ $selectedCategory->parent->name }}</a></li>
                            @endif
                            <li>{{ $selectedCategory->name }}</li>
                        @else
                            <li>Shop Product Grid</li>
                        @endif
                    </ul>
                </div>

            </div>
        </section>
        
        <!-- PRODUCT GRID
        ================================================== -->
        <section class="md">
            <div class="container">
                <div class="row">

                   
                    <div class="col-lg-3 col-12 side-bar order-2 order-lg-1">
                        <div class="widget">
                            <div class="widget-title">
                                <h5>Categories</h5>
                            </div>
                            <div id="accordion" class="accordion-style2">
                                @foreach($categories as $category)
                                @php
                                    $hasChildren = $category->children && $category->children->count() > 0;
                                    $isActive = !empty($selectedCategory) && $selectedCategory->id == $category->id;
                                    $childActive = !empty($selectedCategory) && $selectedCategory->parent && $selectedCategory->parent_id == $category->id;
                                    $showCollapse = $hasChildren && $childActive;
                                @endphp
                                <div class="card">
                                    <div class="card-header" id="heading{{ $category->id }}">
                                        <h5 class="mb-0">
                                            <div class="d-flex align-items-center justify-content-between category-row">
                                                <a href="{{ url('shop?category_id='.$category->id) }}" class="btn btn-link {{ $isActive ? 'active' : '' }}">{{ $category->name }}</a>
                                                @if($hasChildren)
                                                <button type="button" class="category-toggle {{ $showCollapse ? 'minus' : '' }}" data-bs-toggle="collapse" data-bs-target="#collapse{{ $category->id }}" aria-expanded="{{ $showCollapse ? 'true' : 'false' }}" aria-controls="collapse{{ $category->id }}"></button>
                                                @endif
                                            </div>
                                        </h5>
                                    </div>
                                    @if($hasChildren)
                                    <div id="collapse{{ $category->id }}" class="collapse {{ $showCollapse ? 'show' : '' }}" aria-labelledby="heading{{ $category->id }}" data-bs-parent="#accordion">
                                        <div class="card-body">
                                            <ul class="list-unstyled">
                                                @foreach($category->children as $child)
                                                <li><a href="{{ url('shop?category_id='.$child->id) }}" class="{{ !empty($selectedCategory) && $selectedCategory->id == $child->id ? 'active' : '' }}">{{ $child->name }}</a></li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="widget">
                            <div class="widget-title">
                                <h5>Price Range</h5>
                            </div>
                            <form method="GET" action="{{ url('shop') }}" id="priceFilterForm">
                                @if(request('category_id'))
                                <input type="hidden" name="category_id" value="{{ request('category_id') }}">
                                @endif
                                @if(request('q'))
                                <input type="hidden" name="q" value="{{ request('q') }}">
                                @endif
                                <input type="hidden" name="min_price" id="min_price" value="{{ request('min_price', $minPrice) }}">
                                <input type="hidden" name="max_price" id="max_price" value="{{ request('max_price', $maxPrice) }}">
                                <input type="text" class="price-range" name="my_range" value="">
                            </form>
                        </div>

                        <div class="widget">

                            <div class="widget-title">
                                <h5>Latest Products</h5>
                            </div>

                            @forelse($latestProducts as $latest)
                            <div class="d-flex {{ !$loop->last ? 'mb-4' : '' }}">
                                <div class="flex-shrink-0">
                                    <a href="{{ url('productDetails/'.$latest->id) }}">
                                        <img class="latest-product-thumb" src="{{ $latest->image ? asset('images/products/'.$latest->image) : default_image() }}" alt="{{ $latest->name }}">
                                    </a>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <a href="{{ url('productDetails/'.$latest->id) }}" class="mb-1 font-weight-600 text-extra-dark-gray">{{ $latest->name }}</a>
                                    <span class="d-block">₹{{ number_format($latest->special_price ?? $latest->price, 2) }}</span>
                                </div>
                            </div>
                            @empty
                            <p class="text-muted small mb-0">No products yet.</p>
                            @endforelse

                        </div>

                    </div>
                    <!-- end sidebar panel -->

                    <!-- right panel section -->
                    <div class="col-lg-9 col-12 ps-lg-1-9 order-1 order-lg-2 mb-1-9 mb-lg-0">

                        <div class="row g-0 align-items-center bg-light rounded p-3 mb-1-9">

                            <div class="col-12 col-md my-1 my-md-0 text-center text-md-start font-weight-600">
                                Showing {{ $products->firstItem() ?? 0 }}–{{ $products->lastItem() ?? 0 }} of {{ $products->total() }} results
                            </div>

                            <div class="col-12 col-md-auto">

                                <div class="row justify-content-center">

                                    <div class="col-auto my-1 my-md-0">
                                        <label class="m-0">Show:</label>
                                        <select class="w-auto d-inline-block form-select" onchange="location.href = this.value">
                                            @foreach([12, 24, 36, 48, 60] as $limitOption)
                                            <option value="{{ request()->fullUrlWithQuery(['limit' => $limitOption, 'page' => null]) }}" {{ request('limit', 12) == $limitOption ? 'selected' : '' }}>{{ $limitOption }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-auto my-1 my-md-0">
                                        <label class="m-0">Sort By:</label>
                                        <select class="w-auto d-inline-block form-select" onchange="location.href = this.value">
                                            @php $sortKey = request('sort', 'default'); $order = request('order', 'asc'); @endphp
                                            <option value="{{ request()->fullUrlWithQuery(['sort' => 'default', 'order' => null, 'page' => null]) }}" {{ $sortKey === 'default' ? 'selected' : '' }}>Default</option>
                                            <option value="{{ request()->fullUrlWithQuery(['sort' => 'name', 'order' => 'asc', 'page' => null]) }}" {{ $sortKey === 'name' && $order === 'asc' ? 'selected' : '' }}>Name (A - Z)</option>
                                            <option value="{{ request()->fullUrlWithQuery(['sort' => 'name', 'order' => 'desc', 'page' => null]) }}" {{ $sortKey === 'name' && $order === 'desc' ? 'selected' : '' }}>Name (Z - A)</option>
                                            <option value="{{ request()->fullUrlWithQuery(['sort' => 'price', 'order' => 'asc', 'page' => null]) }}" {{ $sortKey === 'price' && $order === 'asc' ? 'selected' : '' }}>Price (Low to High)</option>
                                            <option value="{{ request()->fullUrlWithQuery(['sort' => 'price', 'order' => 'desc', 'page' => null]) }}" {{ $sortKey === 'price' && $order === 'desc' ? 'selected' : '' }}>Price (High to Low)</option>
                                            <option value="{{ request()->fullUrlWithQuery(['sort' => 'newest', 'order' => null, 'page' => null]) }}" {{ $sortKey === 'newest' ? 'selected' : '' }}>Newest</option>
                                            <option value="{{ request()->fullUrlWithQuery(['sort' => 'oldest', 'order' => null, 'page' => null]) }}" {{ $sortKey === 'oldest' ? 'selected' : '' }}>Oldest</option>
                                        </select>
                                    </div>

                                </div>

                            </div>

                        </div>

                        <div class="row justify-content-center">
                            @forelse($products as $product)
                            <div class="col-11 col-sm-6 col-xl-4 mb-1-9">
                                <div class="product-grid">
                                    <div class="product-img">
                                        <a href="{{ url('productDetails/'.$product->id) }}">
                                            @if($product->special_price)
                                            <div class="label-offer bg-red">Sale</div>
                                            @endif
                                            <img src="{{ $product->image ? asset('images/products/'.$product->image) : default_image() }}" alt="{{ $product->name }}">
                                        </a>
                                    </div>
                                    <div class="product-description">
                                        <h3><a href="{{ url('productDetails/'.$product->id) }}">{{ $product->name }}</a></h3>
                                        <h4 class="price">
                                            @if($product->special_price)
                                            <span class="regular-price line-through">₹{{ number_format($product->price, 2) }}</span>
                                            <span class="offer-price">₹{{ number_format($product->special_price, 2) }}</span>
                                            @else
                                            <span class="offer-price">₹{{ number_format($product->price, 2) }}</span>
                                            @endif
                                        </h4>
                                    </div>
                                    <div class="product-buttons">
                                        <ul class="ps-0">
                                            @php $purchasable = !in_array($product->vendor_id, $nonPurchasableVendorIds ?? [], true); @endphp
                                            @php $inWishlist = $wishlistIds->contains($product->id); @endphp
                                            <li><a href="javascript:void(0)" onclick="toggleWishlist({{ $product->id }})" class="btn-link" title="{{ $inWishlist ? 'Remove From Wishlist' : 'Add To Wishlist' }}"><i class="{{ $inWishlist ? 'fas' : 'far' }} fa-heart" data-wishlist-product="{{ $product->id }}" style="{{ $inWishlist ? 'color:#e11d48;' : '' }}"></i></a></li>
                                            @if($purchasable)
                                            <li>
                                                <form action="{{ route('cart.add') }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                    <input type="hidden" name="quantity" value="1">
                                                    <button type="submit" class="butn-style2" title="Add to Cart">Add to Cart</button>
                                                </form>
                                            </li>
                                            @else
                                            <li><a href="{{ route('store', $product->vendor?->slug ?: $product->vendor_id) }}" class="butn-style2 not-for-sale" title="Visit Store for Purchase"><i class="fas fa-store me-1"></i> Visit Store for Purchase</a></li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="col-12 text-center py-5">
                                <h4>No products found in this category.</h4>
                            </div>
                            @endforelse
                        </div>

                        <!-- pagination -->
                        <div class="mt-4">
                            {{ $products->appends(request()->query())->links('pagination::bootstrap-4') }}
                        </div>
                        <!-- end pagination -->

                    </div>
                    <!-- end right panel section -->

                </div>
            </div>
        </section>

        <script>
            window.addEventListener('load', function () {
                var $range = $('.price-range');
                if ($range.length && $.fn.ionRangeSlider) {
                    var min = {{ $minPrice }};
                    var max = {{ $maxPrice }};
                    if (max <= min) { max = min + 1; }
                    var fromVal = parseInt($('#min_price').val(), 10);
                    var toVal = parseInt($('#max_price').val(), 10);
                    fromVal = (isNaN(fromVal) || fromVal < min) ? min : fromVal;
                    toVal = (isNaN(toVal) || toVal > max) ? max : toVal;
                    $range.ionRangeSlider({
                        type: 'double',
                        grid: true,
                        min: min,
                        max: max,
                        from: fromVal,
                        to: toVal,
                        prefix: '₹',
                        onFinish: function (data) {
                            $('#min_price').val(data.from);
                            $('#max_price').val(data.to);
                            $('#priceFilterForm').submit();
                        }
                    });
                }
            });
        </script>
        
@endsection('content')