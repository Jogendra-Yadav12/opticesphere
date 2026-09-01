@extends('layouts.app')

@section('content')

@include('header')

<section class="page-title-section">
    <div class="container">
        <div class="breadcrumbs-info">
            <ul class="ps-0">
                <li><a href="{{ url('/') }}">Home</a></li>
                <li><a href="#">Shopping Cart</a></li>
            </ul>
        </div>
    </div>
</section>

<section class="md">
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if($items->isEmpty())
            <div class="text-center">
                <h3>Your cart is empty</h3>
                <a href="{{ route('shop') }}" class="butn-style2 mt-4">Continue Shopping</a>
            </div>
        @else
            <div class="row">
                <div class="col-lg-8 mb-4 mb-lg-0">
                    <div class="table-responsive">
                        <table class="table table-bordered text-center">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                    <th>Remove</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($items as $item)
                                @php 
                                    $price = $item->unit_price ?? ($item->product->special_price ?? $item->product->price); 
                                    $subtotal = $price * $item->quantity;
                                @endphp
                                    <tr>
                                        <td class="text-start">
                                            <img src="{{ $item->product->image ? Storage::url('images/products/'.$item->product->image) : default_image() }}" alt="..." style="width: 50px; height: 50px; object-fit: cover; margin-right: 15px;">
                                            <a href="{{ route('customer.product.details', $item->product_id) }}">{{ $item->product->name }}</a>
                                            @if(count($item->selected_options) > 0)
                                                <div class="small text-muted mt-1">
                                                    @foreach($item->selected_options as $option)
                                                        <span class="me-2">{{ $option['name'] }}: {{ $option['value'] }}</span>
                                                    @endforeach
                                                </div>
                                            @endif
                                            @php $availableStock = (int) $item->product->stock_quantity; @endphp
                                            @if($availableStock <= 0)
                                                <div class="text-danger fw-bold mt-1">Out of Stock</div>
                                            @elseif($availableStock < $item->quantity)
                                                <div class="text-danger mt-1">Only {{ $availableStock }} left in stock</div>
                                            @endif
                                        </td>
                                        <td>₹{{ number_format($price, 2) }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>₹{{ number_format($subtotal, 2) }}</td>
                                        <td>
                                            <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"><i class="ti-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="bg-light p-4">
                        <h4 class="mb-4">Cart Totals</h4>

                        @if($coupon)
                            <div class="alert alert-success py-2 d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <strong>{{ $coupon->code }}</strong>
                                    <span class="small d-block text-muted">
                                        @if($coupon->discount_type == 'percentage')
                                            {{ (float) $coupon->discount_value }}% off
                                        @endif
                                        @if($coupon->vendor)
                                            &middot; {{ $coupon->vendor->store_name }} only
                                        @endif
                                        @if($coupon->expires_at)
                                            &middot; valid till {{ $coupon->expires_at->format('d M Y, h:i A') }}
                                        @endif
                                    </span>
                                </div>
                                <form action="{{ route('cart.coupon.remove') }}" method="POST" class="m-0">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-link text-danger p-0" title="Remove coupon"><i class="ti-close"></i></button>
                                </form>
                            </div>
                        @else
                            <form action="{{ route('cart.coupon.apply') }}" method="POST" class="mb-4">
                                @csrf
                                <label class="form-label small">Have a coupon?</label>
                                <div class="input-group">
                                    <input type="text" name="coupon_code" class="form-control" placeholder="Coupon code" value="{{ old('coupon_code') }}" required>
                                    <button type="submit" class="btn btn-dark">Apply</button>
                                </div>
                            </form>
                        @endif

                        <ul class="list-unstyled mb-4">
                            <li class="d-flex justify-content-between mb-3 border-bottom pb-2">
                                <strong>Subtotal</strong>
                                <span>₹{{ number_format($subtotal, 2) }}</span>
                            </li>
                            @if($discount > 0)
                                <li class="d-flex justify-content-between mb-3 border-bottom pb-2">
                                    <strong>Discount</strong>
                                    <span class="text-success">- ₹{{ number_format($discount, 2) }}</span>
                                </li>
                            @endif
                            <li class="d-flex justify-content-between border-bottom pb-2">
                                <strong>Total</strong>
                                <span class="text-primary font-weight-bold">₹{{ number_format(max($subtotal - $discount, 0), 2) }}</span>
                            </li>
                        </ul>
                        <a href="{{ route('checkout') }}" class="butn-style2 w-100 text-center">Proceed to Checkout</a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>

@endsection
