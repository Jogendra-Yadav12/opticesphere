@extends('layouts.admin')

@section('content')

@include('admin.nav')

<div class="page-inner">
    <div id="main-wrapper">
        <div class="row align-items-center grid-margin">
            <div class="col-12">
                <div class="card card-white">
                    <div class="card-body row align-items-center">
                        <div class="col-12 col-sm">
                            <h4 class="mb-4 mb-sm-0 text-center text-sm-start">Coupons & Discounts</h4>
                        </div>
                        <div class="col-12 col-sm-auto">
                            <a href="{{ route('admin.coupon.create') }}" class="btn btn-primary"><i class="fa fa-plus me-2"></i> Add Coupon</a>
                        </div>
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
                                        <th scope="col">Seller</th>
                                        <th scope="col">Discount Type</th>
                                        <th scope="col">Value</th>
                                        <th scope="col">Expires</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($coupons as $coupon)
                                    <tr>
                                        <td>{{ $coupon->id }}</td>
                                        <td><span class="badge bg-dark">{{ $coupon->code }}</span></td>
                                        <td>
                                            @if($coupon->vendor)
                                                <span class="badge bg-info">{{ $coupon->vendor->store_name }}</span>
                                            @else
                                                <span class="badge bg-secondary">All Stores</span>
                                            @endif
                                        </td>
                                        <td>{{ ucfirst($coupon->discount_type) }}</td>
                                        <td>{{ $coupon->discount_type == 'percentage' ? $coupon->discount_value . '%' : '₹' . number_format($coupon->discount_value, 2) }}</td>
                                        <td>
                                            @if($coupon->expires_at)
                                                @if($coupon->expires_at->isPast())
                                                    <span class="badge bg-danger">Expired <br> <small>{{ $coupon->expires_at->format('d M Y, h:i A') }}</small></span>
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
                                        <td>
                                            <a href="{{ route('admin.coupon.edit', $coupon->id) }}" class="me-3" data-bs-toggle="tooltip" title="Edit"><i class="far fa-edit text-primary"></i></a>
                                            <form action="{{ route('admin.coupon.destroy', $coupon->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this coupon?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-link p-0 m-0 align-baseline" data-bs-toggle="tooltip" title="Delete"><i class="far fa-trash-alt text-danger"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4 text-muted">No coupons found.</td>
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
