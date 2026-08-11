@extends('layouts.admin')

@section('content')

@include('admin.nav')

<div class="page-inner">
    <div id="main-wrapper">
        <div class="row align-items-center grid-margin">
            <div class="col-12">
                <div class="card card-white">
                    <div class="card-body">
                        <h4 class="mb-0">My Coupons</h4>
                        <small class="text-muted">Discount coupons assigned to your store.</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 grid-margin">
                <div class="card card-white">
                    <div class="card-body slimscroll">
                        <div class="table-responsive">
                            <table class="table">
                                <thead class="bg-light">
                                    <tr>
                                        <th scope="col">ID</th>
                                        <th scope="col">Code</th>
                                        <th scope="col">Discount</th>
                                        <th scope="col">Min. Order</th>
                                        <th scope="col">Expires</th>
                                        <th scope="col">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($coupons as $coupon)
                                    <tr>
                                        <td>{{ $coupon->id }}</td>
                                        <td><span class="badge bg-dark">{{ $coupon->code }}</span></td>
                                        <td>{{ $coupon->discount_type == 'percentage' ? $coupon->discount_value . '%' : '₹' . number_format($coupon->discount_value, 2) }}</td>
                                        <td>{{ $coupon->min_order_amount ? '₹' . number_format($coupon->min_order_amount, 2) : '—' }}</td>
                                        <td>
                                            @if($coupon->expires_at)
                                                @if($coupon->expires_at->isPast())
                                                    <span class="badge bg-danger">Expired <small>{{ $coupon->expires_at->format('d M Y, h:i A') }}</small></span>
                                                @else
                                                    <span class="badge bg-warning text-dark">{{ $coupon->expires_at->format('d M Y, h:i A') }}</span>
                                                @endif
                                            @else
                                                <span class="badge bg-secondary">No Expiry</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($coupon->is_active)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-secondary">Inactive</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted">No coupons yet. <a href="{{ route('seller.request.coupon') }}">Request a coupon</a>.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
