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
                        <li><a href="#">{{ $vendor->store_name }}</a></li>
                    </ul>
                </div>

            </div>
        </section>

        <!-- STORE BANNER (full image, aspect ratio fit)
        ================================================== -->
        <section class="store-banner">
            <img class="store-banner-img" src="{{ $vendor->banner ? asset('images/banners/'.$vendor->banner) : default_image() }}" alt="{{ $vendor->store_name }}">

            <div class="store-banner-overlay">
                <div class="container">
                    <div class="d-flex align-items-center">
                        <img class="store-logo me-4" src="{{ $vendor->logo ? asset('images/logos/'.$vendor->logo) : default_image() }}" alt="{{ $vendor->store_name }}">
                        <div>
                            <h2 class="mb-1">{{ $vendor->store_name }}</h2>
                            <div class="mb-2">
                                <span class="seller-rating me-1"><i class="fas fa-star"></i> {{ number_format($avgRating, 1) }}</span>
                                <span class="text-light opacity-75 small">({{ $vendor->reviews->count() }} reviews)</span>
                                @if($vendor->isOpenNow())
                                <span class="badge bg-success ms-2"><i class="fas fa-circle me-1" style="font-size:8px;vertical-align:middle;"></i>Open Now</span>
                                @else
                                <span class="badge bg-danger ms-2"><i class="fas fa-circle me-1" style="font-size:8px;vertical-align:middle;"></i>Closed</span>
                                @endif
                            </div>
                            @if($vendor->description)
                            <p class="mb-0 store-overlay-desc">{{ $vendor->description }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- STORE DETAILS (left) + PRODUCTS (right)
        ================================================== -->
        <section class="md pt-0">
            <div class="container">
                <div class="row">

                    <div class="col-lg-4 col-12 order-2 order-lg-1">

                        <div class="store-details">
                            <div class="info-box">
                                <h5>Store Info</h5>
                                <ul class="mb-0 list-unstyled">
                                    @if($vendor->address)
                                    <li class="mb-4">
                                        <div class="d-flex align-top">
                                            <div class="info-icon"><i class="ti-location-pin"></i></div>
                                            <div class="ps-4">
                                                <h6 class="info-label">Address</h6>
                                                <span>{{ $vendor->address }}{{ $vendor->city ? ', '.$vendor->city : '' }}{{ $vendor->state ? ', '.$vendor->state : '' }}{{ $vendor->country ? ', '.$vendor->country : '' }}</span>
                                            </div>
                                        </div>
                                    </li>
                                    @endif
                                    @if($vendor->phone)
                                    <li class="mb-4">
                                        <div class="d-flex align-top">
                                            <div class="info-icon"><i class="ti-mobile"></i></div>
                                            <div class="ps-4">
                                                <h6 class="info-label">Phone</h6>
                                                <span>{{ $vendor->phone }}</span>
                                            </div>
                                        </div>
                                    </li>
                                    @endif
                                    @if($vendor->user?->email)
                                    <li class="mb-4">
                                        <div class="d-flex align-top">
                                            <div class="info-icon"><i class="ti-email"></i></div>
                                            <div class="ps-4">
                                                <h6 class="info-label">Email</h6>
                                                <span>{{ $vendor->user->email }}</span>
                                            </div>
                                        </div>
                                    </li>
                                    @endif
                                    <li class="mb-4">
                                        <div class="d-flex align-top">
                                            <div class="info-icon"><i class="ti-medall"></i></div>
                                            <div class="ps-4">
                                                <h6 class="info-label">Total Sales</h6>
                                                <span>{{ number_format((float) $vendor->total_sales, 0) }}</span>
                                            </div>
                                        </div>
                                    </li>
                                </ul>

                                @php $today = (int) date('w'); $dayNames = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday']; $storeHours = $vendor->store_hours; @endphp
                                <h6 class="info-label">Store Hours</h6>
                                <ul class="mb-0 list-unstyled small">
                                    @foreach($dayNames as $idx => $dayName)
                                    @php
                                        $dh = $storeHours[$idx] ?? [];
                                        $closed = !empty($dh['is_closed']);
                                        $open = $dh['open'] ?? null;
                                        $close = $dh['close'] ?? null;
                                        $label = (!$closed && $open && $close) ? \Carbon\Carbon::createFromFormat('H:i', $open)->format('g:i A').' - '.\Carbon\Carbon::createFromFormat('H:i', $close)->format('g:i A') : 'Closed';
                                    @endphp
                                    <li class="d-flex justify-content-between {{ $idx === $today ? 'fw-bold text-primary' : '' }}">
                                        <span>{{ $dayName }}</span>
                                        <span>{{ $label }}</span>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>

                        @auth
                        <div class="text-center mt-1-9">
                            <button type="button" class="butn-style2" data-bs-toggle="modal" data-bs-target="#storeReviewModal"><span>Write a Review</span></button>
                        </div>
                        @else
                        <div class="border rounded p-4 mt-1-9">
                            <p class="mb-3 text-muted">Please <a href="{{ route('login') }}">login</a> or <a href="{{ route('register') }}">register</a> to write a review.</p>
                        </div>
                        @endauth

                    </div>

                    <div class="col-lg-8 col-12 order-1 order-lg-2">
                        <div class="text-center mb-1-9 mb-lg-2-3">
                            <h2 class="mb-0">Products by {{ $vendor->store_name }}</h2>
                        </div>
                        <div class="row mt-n1-9">
                            @forelse($products as $product)
                            <div class="col-sm-6 col-lg-4 mt-1-9">
                                <div class="product-grid-four">
                                    <div class="product-img">
                                        <a href="{{ url('productDetails/'.$product->id) }}">
                                            <img src="{{ $product->image ? asset('images/products/'.$product->image) : default_image() }}" alt="{{ $product->name }}">
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
                                    <span class="font-weight-600 display-29 text-muted">₹{{ number_format($product->special_price ?? $product->price, 2) }}</span>
                                    @if(!$purchasable)
                                    <div><a href="{{ route('store', $product->vendor?->slug ?: $product->vendor_id) }}" class="badge bg-secondary text-decoration-none"><i class="fas fa-store me-1"></i> Visit Store for Purchase</a></div>
                                    @endif
                                </div>
                            </div>
                            @empty
                            <div class="col-12 mt-1-9"><p class="text-center">No products found for this seller.</p></div>
                            @endforelse
                        </div>

                        <div class="mt-4">
                            {{ $products->links('pagination::bootstrap-4') }}
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <!-- STORE REVIEWS
        ================================================== -->
        <section class="md pt-0">
            <div class="container">
                <div class="text-center mb-1-9 mb-lg-2-3">
                    <h2 class="mb-0">Seller Reviews</h2>
                </div>

                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        @forelse($vendor->reviews as $review)
                        <div class="border rounded p-3 mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div>
                                    <strong>{{ $review->user->name ?? 'Customer' }}</strong>
                                    <span class="text-muted small ms-2">{{ $review->created_at->format('d M Y') }}</span>
                                </div>
                                <span class="seller-rating">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fa {{ $i <= $review->rating ? 'fas' : 'far' }} fa-star"></i>
                                    @endfor
                                </span>
                            </div>
                            @if($review->title)
                            <h6 class="mb-1">{{ $review->title }}</h6>
                            @endif
                            <p class="mb-0">{{ $review->body }}</p>
                            @if($review->replies->count())
                            @foreach($review->replies as $reply)
                            <div class="mt-3 ps-3 border-start border-3">
                                <strong class="small">{{ $reply->replier->name ?? $vendor->store_name }}</strong>
                                <p class="mb-0 small text-muted">{{ $reply->body }}</p>
                            </div>
                            @endforeach
                            @endif
                        </div>
                        @empty
                        <div class="border rounded p-4 text-center text-muted">No reviews yet for this seller.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </section>

        <!-- WRITE REVIEW MODAL
        ================================================== -->
        <div class="modal fade" id="storeReviewModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Write a Review for {{ $vendor->store_name }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('store.review', $vendor->slug ?: $vendor->id) }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label d-block">Rating <span class="text-danger">*</span></label>
                                <div class="star-rating" id="store-star-rating">
                                    @for($i = 1; $i <= 5; $i++)
                                    <i class="far fa-star" data-value="{{ $i }}" title="{{ $i }} star{{ $i > 1 ? 's' : '' }}"></i>
                                    @endfor
                                </div>
                                <input type="hidden" name="rating" id="store-rating-input" value="{{ old('rating') }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Title</label>
                                <input type="text" name="title" class="form-control" value="{{ old('title') }}" placeholder="Short summary">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Your Review <span class="text-danger">*</span></label>
                                <textarea name="body" rows="4" class="form-control" required placeholder="Share your experience with this seller">{{ old('body') }}</textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="butn-style2"><span>Submit Review</span></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @if($errors->any())
        <script>
            window.addEventListener('DOMContentLoaded', function() {
                $('#storeReviewModal').modal('show');
            });
        </script>
        @endif

        <script>
            window.addEventListener('DOMContentLoaded', function() {
                function paintStars() {
                    var val = parseInt($('#store-rating-input').val(), 10) || 0;
                    $('#store-star-rating i').each(function() {
                        $(this).attr('class', $(this).data('value') <= val ? 'fas fa-star' : 'far fa-star');
                    });
                }
                $('#store-star-rating i').on('click', function() {
                    $('#store-rating-input').val($(this).data('value'));
                    paintStars();
                });
                paintStars();
            });
        </script>

@endsection
