@extends('layouts.admin')

@section('content')

@include('admin.nav')

<div class="page-inner">
    <div id="main-wrapper">
        <div class="row align-items-center grid-margin">
            <div class="col-12 col-lg-8 mx-auto">
                <div class="card card-white">
                    <div class="card-body">
                        <h4 class="mb-4">Request New Coupon</h4>
                        <p class="text-muted">Submit a request to the admin to generate a new discount coupon for your store.</p>

                        <form action="{{ route('seller.request.coupon.store') }}" method="POST">
                            @csrf
                            
                            <div class="mb-3">
                                <label class="form-label">Coupon Code <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="code" required placeholder="e.g. SUMMER50">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Discount Type <span class="text-danger">*</span></label>
                                <select class="form-control form-select" name="discount_type" required>
                                    <option value="percentage" {{ old('discount_type') == 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                                    <option value="fixed" {{ old('discount_type') == 'fixed' ? 'selected' : '' }}>Fixed Amount (₹)</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Discount Value <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="discount_value" required min="0" step="0.01" value="{{ old('discount_value') }}" placeholder="e.g. 50">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Minimum Order Amount (₹) <span class="text-muted">(optional)</span></label>
                                <input type="number" class="form-control" name="min_order_amount" min="0" step="0.01" value="{{ old('min_order_amount') }}" placeholder="e.g. 500">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Max Uses <span class="text-muted">(optional)</span></label>
                                <input type="number" class="form-control" name="max_uses" min="1" value="{{ old('max_uses') }}" placeholder="e.g. 100">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Start Date <span class="text-muted">(optional)</span></label>
                                <input type="datetime-local" class="form-control" name="starts_at" value="{{ old('starts_at') }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Expiry Date <span class="text-muted">(optional)</span></label>
                                <input type="datetime-local" class="form-control" name="expires_at" value="{{ old('expires_at') }}">
                                <small class="text-muted">Leave blank if the coupon should not expire.</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-control form-select" name="is_active" required>
                                    <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">Submit Request</button>
                            <a href="{{ route('seller.dashboard') }}" class="btn btn-light ms-2">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
