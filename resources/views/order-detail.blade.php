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
                        <li><a href="{{ route('orders') }}">My Orders</a></li>
                        <li><a href="#">Order #{{ $order->order_number ?? $order->id }}</a></li>
                    </ul>
                </div>

            </div>
        </section>

        <!-- ACCOUNT ORDER DETAIL
        ================================================== -->
        <section class="md">
            <div class="container">
                <div class="row justify-content-center">

                    <!-- left panel -->
                    <div class="col-lg-4 col-sm-9 mb-2-3 mb-lg-0">

                        <div class="account-pannel">

                            @include('account-sidebar', ['active' => 'orders'])

                        </div>

                    </div>
                    <!-- end left panel -->

                    <!-- right panel -->
                    <div class="col-lg-8">

                        <div class="common-block">

                            <div class="inner-title d-flex justify-content-between align-items-center">
                                <h4 class="mb-0">Order #{{ $order->order_number ?? $order->id }}</h4>
                                <a href="{{ route('orders') }}" class="butn-style2 small"><span>Back to Orders</span></a>
                            </div>

                            @if(session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif

                            <div class="d-flex flex-wrap justify-content-between mb-3">
                                <small class="text-muted">Placed {{ $order->created_at->format('d M Y h:i A') }}</small>
                                <small>
                                    @if($order->status == 'pending')
                                        <span class="fas fa-circle text-warning small mr-1"></span> Pending
                                    @elseif($order->status == 'completed')
                                        <span class="fas fa-circle text-success small mr-1"></span> Completed
                                    @elseif($order->status == 'cancelled')
                                        <span class="fas fa-circle text-danger small mr-1"></span> Cancelled
                                    @else
                                        <span class="fas fa-circle text-primary small mr-1"></span> {{ ucfirst($order->status) }}
                                    @endif
                                </small>
                                <small>Payment: {{ ucfirst(str_replace('_', ' ', $order->payment_status ?? 'unpaid')) }}</small>
                            </div>

                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>Unit Price</th>
                                            <th>Qty</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($order->items as $item)
                                        <tr>
                                            <td>{{ $item->product_name }}</td>
                                            <td>₹{{ number_format($item->unit_price, 2) }}</td>
                                            <td>{{ $item->quantity }}</td>
                                            <td>₹{{ number_format($item->line_total, 2) }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="text-center">No items in this order.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="row justify-content-end">
                                <div class="col-md-6">
                                    <table class="table table-sm">
                                        <tbody>
                                            <tr>
                                                <th>Subtotal</th>
                                                <td class="text-end">₹{{ number_format($order->subtotal, 2) }}</td>
                                            </tr>
                                            <tr>
                                                <th>Discount</th>
                                                <td class="text-end">- ₹{{ number_format($order->discount_amount, 2) }}</td>
                                            </tr>
                                            <tr>
                                                <th>Tax</th>
                                                <td class="text-end">₹{{ number_format($order->tax_amount, 2) }}</td>
                                            </tr>
                                            <tr>
                                                <th>Shipping</th>
                                                <td class="text-end">₹{{ number_format($order->shipping_amount, 2) }}</td>
                                            </tr>
                                            <tr class="fw-bold">
                                                <th>Order Total</th>
                                                <td class="text-end">₹{{ number_format($order->total_amount, 2) }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-sm-6 mb-4 mb-sm-0">
                                    <h6 class="mb-2">Billing Address</h6>
                                    @if($order->billingAddress)
                                        <p class="mb-0">
                                            {{ $order->billingAddress->full_name }}<br>
                                            {{ $order->billingAddress->address_line1 }} {{ $order->billingAddress->address_line2 }}<br>
                                            {{ $order->billingAddress->city }}, {{ $order->billingAddress->state }} {{ $order->billingAddress->postal_code }}<br>
                                            {{ $order->billingAddress->country }}<br>
                                            Phone: {{ $order->billingAddress->phone }}
                                        </p>
                                    @else
                                        <p class="text-muted mb-0">Not provided</p>
                                    @endif
                                </div>
                                <div class="col-sm-6">
                                    <h6 class="mb-2">Shipping Address</h6>
                                    @if($order->shippingAddress)
                                        <p class="mb-0">
                                            {{ $order->shippingAddress->full_name }}<br>
                                            {{ $order->shippingAddress->address_line1 }} {{ $order->shippingAddress->address_line2 }}<br>
                                            {{ $order->shippingAddress->city }}, {{ $order->shippingAddress->state }} {{ $order->shippingAddress->postal_code }}<br>
                                            {{ $order->shippingAddress->country }}<br>
                                            Phone: {{ $order->shippingAddress->phone }}
                                        </p>
                                    @else
                                        <p class="text-muted mb-0">Not provided</p>
                                    @endif
                                </div>
                            </div>

                            <div class="mt-4">
                                <h6 class="mb-2">Order Status History</h6>
                                @forelse($order->statusHistories->sortByDesc('created_at') as $history)
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div>
                                            <span class="badge badge-primary">{{ ucfirst($history->status) }}</span>
                                            @if($history->comment)
                                                <small class="d-block text-muted mt-1">{{ $history->comment }}</small>
                                            @endif
                                        </div>
                                        <small class="text-muted">{{ $history->created_at->format('d M, h:i A') }}</small>
                                    </div>
                                @empty
                                    <p class="text-muted mb-0">No status changes recorded.</p>
                                @endforelse
                            </div>

                        </div>

                    </div>
                    <!-- end right panel -->
                </div>
            </div>
        </section>

@endsection
