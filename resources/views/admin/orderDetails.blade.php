@extends('layouts.admin')

@section('content')

@include('admin.nav')

@php $isAdmin = auth()->user()->role === 'admin'; @endphp

<div class="page-inner">
    <div id="main-wrapper">
        <div class="row align-items-center grid-margin">
            <div class="col-12">
                <div class="card card-white">
                    <div class="card-body row align-items-center">
                        <div class="col-12 col-md-6 mb-3 mb-md-0">
                            <h4 class="mb-0">Order #{{ $order->order_number ?? $order->id }}</h4>
                            <small class="text-muted">Placed {{ $order->created_at->format('d M Y h:i A') }}</small>
                        </div>
                        <div class="col-12 col-md-6 text-md-end">
                            <a href="{{ route($isAdmin ? 'admin.order.index' : 'seller.order.index') }}" class="btn btn-secondary"><i class="fa fa-arrow-left me-2"></i> Back to Orders</a>
                            @if($isAdmin)
                            <a href="#" onclick="event.preventDefault(); document.getElementById('delete-order-form').submit();" class="btn btn-danger"><i class="far fa-trash-alt me-2"></i> Delete</a>
                            <form id="delete-order-form" action="{{ route('admin.order.destroy', $order->id) }}" method="POST" class="d-none">
                                @csrf
                                @method('DELETE')
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-xl-3">
            @if($isAdmin)
            <div class="col-xl-4 grid-margin">
                <div class="card card-white h-100">
                    <div class="card-heading clearfix">
                        <h4 class="card-title">Update Status</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.order.updateStatus', $order->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label class="form-label fw-bold">Order Status</label>
                                <select class="form-select form-control" name="status">
                                    @foreach(['pending', 'processing', 'shipped', 'delivered', 'cancelled', 'refunded'] as $st)
                                        <option value="{{ $st }}" {{ $order->status === $st ? 'selected' : '' }}>{{ ucfirst($st) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Payment Status</label>
                                <select class="form-select form-control" name="payment_status">
                                    @foreach(['unpaid', 'paid', 'failed', 'refunded', 'partially_refunded'] as $ps)
                                        <option value="{{ $ps }}" {{ $order->payment_status === $ps ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $ps)) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Save Changes</button>
                        </form>
                    </div>
                </div>
            </div>
            @endif

            <div class="col-12 @if($isAdmin)col-xl-8 @endif grid-margin">
                <div class="card card-white h-100">
                    <div class="card-heading clearfix">
                        <h4 class="card-title">Addresses</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-auto me-xl-5 pe-xl-5 mb-4 mb-xl-0">
                                <h6 class="text-uppercase fw-bold mb-3">Billing</h6>
                                @if($order->billingAddress)
                                    <ul class="list-unstyled ps-0">
                                        <li>{{ $order->billingAddress->full_name }}</li>
                                        <li>{{ $order->billingAddress->address_line1 }} {{ $order->billingAddress->address_line2 }}</li>
                                        <li>{{ $order->billingAddress->city }}, {{ $order->billingAddress->state }} {{ $order->billingAddress->postal_code }}</li>
                                        <li>{{ $order->billingAddress->country }}</li>
                                    </ul>
                                    <strong class="d-block">Phone:</strong>
                                    <a href="tel:{{ $order->billingAddress->phone }}">{{ $order->billingAddress->phone }}</a>
                                @else
                                    <p class="text-muted">Not provided</p>
                                @endif
                            </div>
                            <div class="col-lg-auto ps-xl-5">
                                <h6 class="fw-bold text-uppercase mb-3">Shipping</h6>
                                @if($order->shippingAddress)
                                    <ul class="list-unstyled ps-0">
                                        <li>{{ $order->shippingAddress->full_name }}</li>
                                        <li>{{ $order->shippingAddress->address_line1 }} {{ $order->shippingAddress->address_line2 }}</li>
                                        <li>{{ $order->shippingAddress->city }}, {{ $order->shippingAddress->state }} {{ $order->shippingAddress->postal_code }}</li>
                                        <li>{{ $order->shippingAddress->country }}</li>
                                    </ul>
                                    <strong class="d-block">Phone:</strong>
                                    <a href="tel:{{ $order->shippingAddress->phone }}">{{ $order->shippingAddress->phone }}</a>
                                @else
                                    <p class="text-muted">Not provided</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-xl-3">
            <div class="col-lg-12 grid-margin">
                <div class="card card-white h-100">
                    <div class="card-heading clearfix">
                        <h4 class="card-title">Products</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table mb-0 order-details-table">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4">SKU</th>
                                        <th>Name</th>
                                        <th class="text-end">Unit Price</th>
                                        <th class="text-end">Qty</th>
                                        <th class="text-end">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($order->items as $item)
                                    <tr>
                                        <td class="ps-4"><a href="#"><strong>{{ $item->sku }}</strong></a></td>
                                        <td><a href="#">{{ $item->product_name }}</a></td>
                                        <td class="text-end">₹{{ number_format($item->unit_price, 2) }}</td>
                                        <td class="text-end">{{ $item->quantity }}</td>
                                        <td class="text-end">₹{{ number_format($item->line_total, 2) }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">No items in this order.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="row justify-content-end flex-column flex-lg-row mt-5">
                            <div class="col-auto me-5 mb-3">
                                <h6 class="fw-bold mb-2">Items Subtotal</h6>
                                <b>₹{{ number_format($order->subtotal, 2) }}</b>
                            </div>
                            <div class="col-auto me-5 mb-3">
                                <h6 class="fw-bold mb-2">Discount</h6>
                                <b>₹{{ number_format($order->discount_amount, 2) }}</b>
                            </div>
                            <div class="col-auto me-5 mb-3">
                                <h6 class="fw-bold mb-2">Shipping</h6>
                                <b>₹{{ number_format($order->shipping_amount, 2) }}</b>
                            </div>
                            <div class="col-auto">
                                <h6 class="fw-bold mb-2">Order Total</h6>
                                <strong>₹{{ number_format($order->total_amount, 2) }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-xl-3">
            <div class="col-xl-4 grid-margin">
                <div class="card card-white h-100">
                    <div class="card-heading clearfix">
                        <h4 class="card-title">Customer</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless mb-0">
                            <tbody>
                                <tr>
                                    <th scope="row">Name</th>
                                    <td>{{ $order->user->name ?? 'Guest' }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Email</th>
                                    <td>{{ $order->user->email ?? '—' }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Phone</th>
                                    <td>{{ $order->user->phone ?? '—' }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Coupon</th>
                                    <td>{{ $order->coupon_code ?? '—' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 grid-margin">
                <div class="card card-white h-100">
                    <div class="card-heading clearfix">
                        <h4 class="card-title">Payment</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless table-sm mb-0">
                            <tbody>
                                <tr>
                                    <th scope="row">Payment Method</th>
                                    <td>{{ $order->payment_method ?? '—' }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Payment Status</th>
                                    <td>
                                        <span class="badge rounded-pill bg-soft-green">{{ ucfirst(str_replace('_', ' ', $order->payment_status)) }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">Gateway Ref</th>
                                    <td>{{ $order->gateway_charge_id ?? '—' }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Currency</th>
                                    <td>{{ $order->currency ?? 'USD' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 grid-margin">
                <div class="card card-white h-100">
                    <div class="card-heading clearfix">
                        <h4 class="card-title">Status History</h4>
                    </div>
                    <div class="card-body">
                        @forelse($order->statusHistories->sortByDesc('created_at') as $history)
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <span class="badge rounded-pill bg-soft-primary">{{ ucfirst($history->status) }}</span>
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
        </div>
    </div>
</div>

@endsection
