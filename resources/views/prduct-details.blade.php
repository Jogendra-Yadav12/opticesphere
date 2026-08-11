@extends('layouts.app')

@section('content')

@include('header')

@php
    $mainImage = $product->images->firstWhere('is_primary', true) ?? $product->images->first();
    $mainUrl = $mainImage ? asset(($mainImage->is_primary ? 'images/products/' : 'images/products/gallery/').$mainImage->path) : default_image();
    $fullStars = (int) floor($avgRating);
    $halfStar = ($avgRating - $fullStars) >= 0.5;
    $reviews = $product->reviews;
    $shareUrl = urlencode(url()->current());
@endphp

 <!-- PAGE TITLE
        ================================================== -->
        <section class="page-title-section">
            <div class="container">

                <div class="breadcrumbs-info">
                    <ul class="ps-0">
                        <li><a href="{{ url('/') }}">Home</a></li>
                        <li><a href="{{ url('shop') }}">Shop</a></li>
                        @if(!empty($category))
                        <li><a href="{{ url('shop?category_id='.$category->id) }}">{{ $category->name }}</a></li>
                        @endif
                        <li>{{ $product->name }}</li>
                    </ul>
                </div>

            </div>
        </section>
        
        <!-- PRODUCT DETAILS
        ================================================== -->
        <section class="md">
            <div class="container">

                <!-- product section -->
                <div class="row mb-6 mb-md-7 mb-lg-9">
                    <div class="col-lg-5 text-center text-lg-start mb-1-9 mb-lg-0">

                        <!-- product left start -->
                        <div class="xzoom-container">
                            <img class="xzoom5 mb-1-9" id="xzoom-magnific" src="{{ $mainUrl }}" xoriginal="{{ $mainUrl }}" alt="{{ $product->name }}" />
                            @if($product->images->count() > 1)
                            <div class="xzoom-thumbs no-margin">
                                @foreach($product->images as $img)
                                <a href="{{ asset(($img->is_primary ? 'images/products/' : 'images/products/gallery/').$img->path) }}">
                                    <img class="xzoom-gallery5" width="80" src="{{ asset(($img->is_primary ? 'images/products/' : 'images/products/gallery/').$img->path) }}" title="{{ $img->alt_text ?: $product->name }}">
                                </a>
                                @endforeach
                            </div>
                            @endif
                        </div>
                        <!-- product left end -->

                    </div>
                    <div class="col-lg-7 ps-lg-2-3">
                        <div class="product-detail">
                            <h2 class="mb-1">{{ $product->name }} 
                                @if($product->special_price)
                                <span class="label-sale bg-primary text-white text-uppercase display-31">Sale</span>
                                @endif
                            </h2>
                            <div class="bg-primary separator-line-horrizontal-full mb-4"></div>
                            <p class="rating-text"><span>SKU:</span> <span class="font-500 theme-color">{{ $product->sku ?? 'N/A' }}</span></p>
                            <p>{!! nl2br(e(strip_tags($product->short_description ?? 'No description available.'))) !!}</p>

                            <div class="mb-4">
                                <div class="d-inline-block me-3 pe-3 borders-end border-color-extra-medium-gray">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $fullStars)
                                        <i class="fas fa-star text-primary"></i>
                                        @elseif($halfStar && $i == $fullStars + 1)
                                        <i class="fas fa-star-half-alt text-primary"></i>
                                        @else
                                        <i class="far fa-star text-primary"></i>
                                        @endif
                                    @endfor
                                    <span class="ms-1">{{ number_format($avgRating, 1) }} ({{ $reviews->count() }} reviews)</span>
                                </div>

                                <div class="d-inline-block">
                                    @auth
                                    <a class="text-primary" href="#" data-bs-toggle="modal" data-bs-target="#productReviewModal">Write a review</a>
                                    @else
                                    <a class="text-primary" href="{{ route('login') }}">Write a review</a>
                                    @endauth
                                </div>
                            </div>

                            <div class="mb-4">
                                @if($product->special_price)
                                <span class="me-3 display-26 font-weight-600 line-through text-muted">₹{{ number_format($product->price, 2) }}</span>
                                <span class="display-26 font-weight-700 text-primary" id="productPrice">₹{{ number_format($product->special_price, 2) }}</span>
                                @else
                                <span class="display-26 font-weight-700 text-primary" id="productPrice">₹{{ number_format($product->price, 2) }}</span>
                                @endif
                            </div>

                           

                            @php $purchasable = !in_array($product->vendor_id, $nonPurchasableVendorIds ?? [], true); @endphp

                            @if($purchasable)
                            <form action="{{ route('cart.add') }}" method="POST" id="addToCartForm">
                                @csrf
                            </form>
                            @endif

                            <input type="hidden" name="product_id" value="{{ $product->id }}" form="addToCartForm">
                            <input type="hidden" name="variant_id" id="selectedVariantId" value="" form="addToCartForm">
                            <input type="hidden" name="selected_attributes" id="selectedAttributes" value="" form="addToCartForm">

                            @if($product->variants->count() > 0)
                            <div class="row mb-3">
                                <div class="col-12 col-md-6">
                                   
                                    @foreach($productAttributes as $attrName => $values)
                                    @php $attr = $values->first()->attribute; @endphp
                                    @if($attr)
                                    <div class="mb-4" data-attr-group data-attr-id="{{ $attr->id }}" data-required="{{ $attr->is_required ? 1 : 0 }}" data-name="{{ $attrName }}">
                                        <span class="font-500 theme-color">{{ $attrName }}@if($attr->is_required) <span class="text-danger">*</span>@endif:</span>
                                        @if($attr->type === 'select')
                                        <select class="form-control medium form-select variant-attribute" data-attr-id="{{ $attr->id }}">
                                            <option value="">Select {{ $attrName }}</option>
                                            @foreach($values as $value)
                                            <option value="{{ $value->id }}">{{ $value->value }}</option>
                                            @endforeach
                                        </select>
                                        @else
                                        <div class="d-flex flex-wrap gap-2">
                                            @foreach($values as $value)
                                            <input type="radio" class="btn-check variant-attribute" name="frontattr_{{ $attr->id }}" id="frontattr_{{ $value->id }}" value="{{ $value->id }}" data-attr-id="{{ $attr->id }}">
                                            <label class="btn btn-outline-secondary btn-sm" for="frontattr_{{ $value->id }}">{{ $value->value }}</label>
                                            @endforeach
                                        </div>
                                        @endif
                                    </div>
                                    @endif
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            <div class="row">
                                <div class="col-4 col-lg-2">
                                    <label>Qty:</label>
                                    <input type="number" name="quantity" value="1" min="1" class="form-control medium mb-4" form="addToCartForm">
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-lg-12">
                                    @if($purchasable)
                                    <button type="submit" form="addToCartForm" class="butn-style2 me-3 mb-2 mb-md-0"><span><i class="fas fa-shopping-cart me-1"></i> Add to Cart</span></button>
                                    @else
                                    <a href="{{ route('store', $product->vendor?->slug ?: $product->vendor_id) }}" class="butn-style2 me-3 mb-2 mb-md-0"><span><i class="fas fa-store me-1"></i> Visit Store for Purchase</span></a>
                                    @endif
                                    <button type="button" class="butn-style2 dark me-3 mb-2 mb-md-0" onclick="toggleWishlist({{ $product->id }})">
                                        <span><i class="{{ $inWishlist ? 'fas' : 'far' }} fa-heart me-1" style="{{ $inWishlist ? 'color:#e11d48;' : '' }}"></i> {{ $inWishlist ? 'Remove from wishlist' : 'Add to wishlist' }}</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end product section -->

                <!-- product description -->
                <div class="row justify-content-center mb-6 mb-md-7">

                    <div class="col-12">
                        <div class="horizontaltab tab-style-two">
                            <ul class="resp-tabs-list hor_1 text-start">
                                <li><i class="ti-line-dashed d-md-block d-none"></i>Description</li>
                                <li><i class="ti-more d-md-block d-none"></i>Additional Info</li>
                                <li><i class="ti-star d-md-block d-none"></i>Reviews ({{ $reviews->count() }})</li>
                            </ul>
                            <div class="resp-tabs-container hor_1">
                                <div>
                                    @if($product->description)
                                    <div class="product-desc-content">{!! $product->description !!}</div>
                                    @else
                            <p>{!! nl2br(e(strip_tags($product->short_description ?? 'No description available.'))) !!}</p>
                                    @endif
                                </div>
                                <div>
                                    @if($productAttributes->count() > 0)
                                    <div class="mb-1-9">
                                        <h3 class="display-29">Product Attributes:</h3>
                                        <table class="table bordered">
                                            <tbody>
                                                @foreach($productAttributes as $attrName => $values)
                                                <tr>
                                                    <td class="w-25"><strong>{{ $attrName }}</strong></td>
                                                    <td>{{ $values->pluck('value')->implode(', ') }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    @endif
                                    <div class="row">
                                        <div class="col-lg-6 mb-1-9 mb-lg-0">
                                            <h3 class="display-29">Information:</h3>
                                            <ul class="primary-list mb-1-9">
                                                @if($product->brand)
                                                <li><strong>Brand:</strong> {{ $product->brand->name }}</li>
                                                @endif
                                                @if($product->sku)
                                                <li><strong>SKU:</strong> {{ $product->sku }}</li>
                                                @endif
                                                @if($product->weight)
                                                <li><strong>Weight:</strong> {{ number_format($product->weight, 2) }} grams</li>
                                                @endif
                                                @if($product->length && $product->width && $product->height)
                                                <li><strong>Dimensions:</strong> {{ number_format($product->length, 2) }} x {{ number_format($product->width, 2) }} x {{ number_format($product->height, 2) }} Cm</li>
                                                @endif
                                                @if($category)
                                                <li><strong>Category:</strong> {{ $category->name }}</li>
                                                @endif
                                                @if($product->product_type)
                                                <li><strong>Type:</strong> {{ $product->product_type }}</li>
                                                @endif
                                                <li><strong>Availability:</strong> {{ $product->stock_quantity > 0 ? 'In Stock ('.$product->stock_quantity.' available)' : 'Out of Stock' }}</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div id="reviews-tab">
                                    <div class="row">
                                        <div class="col-lg-6 order-lg-2 mb-1-9 mb-lg-0">
                                            <div class="common-block">
                                                @forelse($reviews as $review)
                                                <div class="mb-2-3 pb-2-3 border-bottom">
                                                    <div class="media mb-4 product-review">
                                                        <img class="me-3 rounded-circle w-60px" src="{{ $review->user && $review->user->avatar ? asset('images/avatars/'.$review->user->avatar) : asset('img/avatar/t-1.jpg') }}" alt="{{ $review->user->name ?? 'Customer' }}">
                                                        <div class="media-body">
                                                            <a href="#" class="mb-1 font-weight-600 text-extra-dark-gray">{{ $review->user->name ?? 'Customer' }}</a>
                                                            <span class="d-block text-primary">{{ $review->created_at->format('M d, Y') }}</span>
                                                        </div>
                                                        <span class="text-primary">
                                                            @for($i = 1; $i <= 5; $i++)
                                                            <i class="fa {{ $i <= $review->rating ? 'fas' : 'far' }} fa-star"></i>
                                                            @endfor
                                                        </span>
                                                    </div>
                                                    @if($review->title)
                                                    <h6 class="mb-1">{{ $review->title }}</h6>
                                                    @endif
                                                    <p class="mb-0">{{ $review->body }}</p>
                                                </div>
                                                @empty
                                                <div class="text-center py-4">
                                                    <p class="mb-0 text-muted">No reviews yet. Be the first to review this product!</p>
                                                </div>
                                                @endforelse
                                            </div>
                                        </div>

                                        <div class="col-lg-6 order-lg-1">
                                            <div class="common-block text-center">
                                                <div class="inner-title">
                                                    <h4 class="mb-0">Write a Review</h4>
                                                    <p class="mb-0">Share your experience with this product.</p>
                                                </div>
                                                @auth
                                                <button type="button" class="butn-style2" data-bs-toggle="modal" data-bs-target="#productReviewModal"><span>Write a Review</span></button>
                                                @else
                                                <p class="mb-0">Please <a href="{{ route('login') }}">login</a> or <a href="{{ route('register') }}">register</a> to write a review.</p>
                                                @endauth
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- end product description -->

            </div>
        </section>

        <!-- WRITE REVIEW MODAL
        ================================================== -->
        <div class="modal fade" id="productReviewModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Write a Review for {{ $product->name }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('product.review', $product->id) }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label d-block">Rating <span class="text-danger">*</span></label>
                                <div class="star-rating" id="product-star-rating">
                                    @for($i = 1; $i <= 5; $i++)
                                    <i class="far fa-star" data-value="{{ $i }}" title="{{ $i }} star{{ $i > 1 ? 's' : '' }}"></i>
                                    @endfor
                                </div>
                                <input type="hidden" name="rating" id="product-rating-input" value="{{ old('rating') }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Title</label>
                                <input type="text" name="title" class="form-control" value="{{ old('title') }}" placeholder="Short summary">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Your Review <span class="text-danger">*</span></label>
                                <textarea name="body" rows="4" class="form-control" required placeholder="Share your experience with this product">{{ old('body') }}</textarea>
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
                $('#productReviewModal').modal('show');
            });
        </script>
        @endif

        <script>
            window.addEventListener('DOMContentLoaded', function() {
                function paintStars() {
                    var val = parseInt($('#product-rating-input').val(), 10) || 0;
                    $('#product-star-rating i').each(function() {
                        $(this).attr('class', $(this).data('value') <= val ? 'fas fa-star' : 'far fa-star');
                    });
                }
                $('#product-star-rating i').on('click', function() {
                    $('#product-rating-input').val($(this).data('value'));
                    paintStars();
                });
                paintStars();
            });
        </script>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var priceEl = document.getElementById('productPrice');
                var variantIdEl = document.getElementById('selectedVariantId');
                var basePrice = parseFloat('{{ $product->special_price ?? $product->price }}') || 0;
                var hasVariants = @json($product->variants->count() > 0);

                var variantMap = {
                    @foreach($product->variants as $variant)
                    "{{ $variant->id }}": {
                        values: @json($variant->attributeValues->pluck('id')->sort()->values()),
                        adjustment: {{ (float) $variant->price }}
                    },
                    @endforeach
                };

                var optionPrices = {
                    @foreach($product->attributeValues as $value)
                    "{{ $value->id }}": {{ (float) ($value->pivot->price_adjustment ?? 0) }},
                    @endforeach
                };

                function currentSelection() {
                    var ids = [];
                    document.querySelectorAll('.variant-attribute').forEach(function (input) {
                        var val = input.tagName === 'SELECT' ? input.value : (input.checked ? input.value : '');
                        if (val) ids.push(parseInt(val, 10));
                    });
                    return ids.sort(function (a, b) { return a - b; });
                }

                function updateVariant() {
                    var selected = currentSelection();
                    var selectedSet = selected.slice();
                    var matchId = null;

                    Object.keys(variantMap).forEach(function (id) {
                        var vals = variantMap[id].values || [];
                        var conflict = selectedSet.some(function (s) { return vals.indexOf(s) === -1; });
                        if (conflict) return;
                        if (vals.length === selected.length) {
                            matchId = id;
                        }
                    });

                    var attrsEl = document.getElementById('selectedAttributes');
                    if (attrsEl) attrsEl.value = selected.length ? JSON.stringify(selected) : '';

                    if (variantIdEl) variantIdEl.value = matchId || '';

                    if (priceEl) {
                        if (matchId) {
                            priceEl.textContent = '\u20B9' + (basePrice + (parseFloat(variantMap[matchId].adjustment) || 0)).toFixed(2);
                        } else if (selected.length > 0) {
                            var partialAdj = 0;
                            selected.forEach(function (id) {
                                partialAdj += parseFloat(optionPrices[id]) || 0;
                            });
                            priceEl.textContent = '\u20B9' + (basePrice + partialAdj).toFixed(2);
                        } else {
                            priceEl.textContent = '\u20B9' + basePrice.toFixed(2);
                        }
                    }
                }

                function missingRequired() {
                    var missing = [];
                    document.querySelectorAll('[data-attr-group]').forEach(function (group) {
                        if (group.getAttribute('data-required') !== '1') return;
                        var selected = false;
                        group.querySelectorAll('.variant-attribute').forEach(function (input) {
                            var val = input.tagName === 'SELECT' ? input.value : (input.checked ? input.value : '');
                            if (val) selected = true;
                        });
                        if (!selected) missing.push(group.getAttribute('data-name'));
                    });
                    return missing;
                }

                document.querySelectorAll('.variant-attribute').forEach(function (input) {
                    input.addEventListener('change', updateVariant);
                });
                updateVariant();

                var form = document.getElementById('addToCartForm');
                if (form && variantIdEl && hasVariants) {
                    form.addEventListener('submit', function (e) {
                        var missing = missingRequired();
                        if (missing.length) {
                            e.preventDefault();
                            alert('Please select: ' + missing.join(', '));
                            return;
                        }
                        updateVariant();
                    });
                }
            });
        </script>

    @endsection('content')
