@extends('layouts.app')

@section('content')

@include('header')

<section class="page-title-section">
    <div class="container">
        <div class="breadcrumbs-info">
            <ul class="ps-0">
                <li><a href="{{ url('/') }}">Home</a></li>
                <li><a href="{{ route('cart') }}">Shopping Cart</a></li>
                <li><a href="#">Checkout</a></li>
            </ul>
        </div>
    </div>
</section>

<section class="md">
    <div class="container">
        <form action="{{ route('checkout.process') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-lg-7 mb-4 mb-lg-0">
                    <div class="common-block">
                        <div class="inner-title">
                            <h4 class="mb-0">Billing & Shipping Details</h4>
                        </div>

                        @if($addresses->isNotEmpty())
                            @php
                                $selectedAddressId = old('address_id') !== null
                                    ? old('address_id')
                                    : ($addresses->firstWhere('is_default', true)->id ?? null);
                                $showNewAddressForm = $selectedAddressId === null || $selectedAddressId === '';
                            @endphp

                            <div class="inner-title mt-4">
                                <h4 class="mb-0">Choose Delivery Address</h4>
                            </div>

                            <div class="form-group mb-4">
                                @foreach($addresses as $saved)
                                <div class="form-check border rounded p-3 mb-2">
                                    <input class="form-check-input saved-address-radio" type="radio" name="address_id" id="address-{{ $saved->id }}" value="{{ $saved->id }}"
                                           data-address="{{ $saved->address_line_1 }}|{{ $saved->address_line_2 }}|{{ $saved->city }}|{{ $saved->state }}|{{ $saved->postal_code }}|{{ $saved->country }}"
                                           @if((string) $selectedAddressId === (string) $saved->id) checked @endif>
                                    <label class="form-check-label w-100" for="address-{{ $saved->id }}">
                                        @if($saved->is_default) <span class="badge badge-success">Default</span> @endif
                                        <strong>{{ $saved->full_name }}</strong> &middot; {{ $saved->phone }}<br>
                                        <span class="small text-muted">{{ $saved->address_line_1 }}{{ $saved->address_line_2 ? ', '.$saved->address_line_2 : '' }}, {{ $saved->city }}, {{ $saved->state }} {{ $saved->postal_code }}, {{ $saved->country }}</span>
                                    </label>
                                </div>
                                @endforeach
                                <div class="form-check border rounded p-3 mb-2">
                                    <input class="form-check-input saved-address-radio" type="radio" name="address_id" id="address-new" value=""
                                           data-address=""
                                           @if($selectedAddressId === null || $selectedAddressId === '') checked @endif>
                                    <label class="form-check-label" for="address-new">Use a new address</label>
                                </div>
                            </div>
                        @endif

                        <div id="new-address-form" @if(!$showNewAddressForm) style="display: none;" @endif>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Address Line 1</label>
                                    <input type="text" class="form-control" name="address_line_1" value="{{ old('address_line_1', $address->address_line_1 ?? '') }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>City</label>
                                    <input type="text" class="form-control" name="city" value="{{ old('city', $address->city ?? '') }}" required>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>State</label>
                                    <input type="text" class="form-control" name="state" value="{{ old('state', $address->state ?? '') }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Zip Code</label>
                                    <input type="text" class="form-control" name="postal_code" value="{{ old('postal_code', $address->postal_code ?? '') }}" required>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Country</label>
                                    <input type="text" class="form-control" name="country" value="{{ old('country', $address->country ?? '') }}" required>
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="bg-light p-4">
                        <h4 class="mb-4">Your Order</h4>
                        <ul class="list-unstyled mb-4">
                            @foreach($items as $item)
                                @php 
                                    $price = $item->unit_price ?? ($item->product->special_price ?? $item->product->price); 
                                    $subtotal = $price * $item->quantity;
                                @endphp
                                <li class="d-flex justify-content-between mb-2">
                                    <span>
                                        {{ $item->product->name }} x {{ $item->quantity }}
                                        @if(count($item->selected_options) > 0)
                                            <span class="small text-muted d-block">
                                                @foreach($item->selected_options as $option)
                                                    <span class="me-1">{{ $option['name'] }}: {{ $option['value'] }}</span>
                                                @endforeach
                                            </span>
                                        @endif
                                    </span>
                                    <span>₹{{ number_format($subtotal, 2) }}</span>
                                </li>
                            @endforeach
                            <li class="d-flex justify-content-between border-top pt-3 mt-3">
                                <strong>Subtotal</strong>
                                <strong>₹{{ number_format($subtotal, 2) }}</strong>
                            </li>
                            @if($coupon)
                                <li class="d-flex justify-content-between">
                                    <strong>Coupon ({{ $coupon->code }})@if($coupon->vendor) &middot; {{ $coupon->vendor->store_name }} only @endif</strong>
                                    <strong class="text-success">- ₹{{ number_format($discount, 2) }}</strong>
                                </li>
                            @endif
                            <li class="d-flex justify-content-between">
                                <strong>Total</strong>
                                <strong class="text-primary">₹{{ number_format(max($subtotal - $discount, 0), 2) }}</strong>
                            </li>
                        </ul>

                        <h4 class="mb-3 mt-4">Payment Method</h4>
                        <div class="form-group mb-4">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="payment_method" id="cod" value="cash_on_delivery" checked>
                                <label class="form-check-label" for="cod">Cash on Delivery</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="online" value="online" disabled>
                                <label class="form-check-label" for="online">Online Payment (Coming Soon)</label>
                            </div>
                        </div>

                        <button type="submit" class="butn-style2 w-100 text-center">Place Order</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

<script>
    document.querySelectorAll('.saved-address-radio').forEach(function (radio) {
        radio.addEventListener('change', function () {
            if (!this.checked) return;

            document.querySelectorAll('.saved-address-radio').forEach(function (r) {
                r.closest('.form-check').classList.remove('border-primary');
            });
            this.closest('.form-check').classList.add('border-primary');

            var parts = this.dataset.address ? this.dataset.address.split('|') : [];
            var addressLine = document.querySelector('input[name="address_line_1"]');
            var city = document.querySelector('input[name="city"]');
            var state = document.querySelector('input[name="state"]');
            var postal = document.querySelector('input[name="postal_code"]');
            var country = document.querySelector('input[name="country"]');
            var form = document.getElementById('new-address-form');

            if (parts.length === 0) {
                if (form) form.style.display = '';
                addressLine.value = '';
                city.value = '';
                state.value = '';
                postal.value = '';
                country.value = '';
                return;
            }

            if (form) form.style.display = 'none';
            addressLine.value = parts[0] || '';
            city.value = parts[2] || '';
            state.value = parts[3] || '';
            postal.value = parts[4] || '';
            country.value = parts[5] || '';
        });
    });

    var checkedRadio = document.querySelector('.saved-address-radio:checked');
    if (checkedRadio && checkedRadio.dataset.address) {
        checkedRadio.dispatchEvent(new Event('change'));
    }
</script>

@endsection
