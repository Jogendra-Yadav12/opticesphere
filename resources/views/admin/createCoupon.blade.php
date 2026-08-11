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
                            <h4 class="mb-4 mb-sm-0 text-center text-sm-start">Add Coupon</h4>
                        </div>
                        <div class="col-12 col-sm-auto">
                            <a href="{{ route('admin.coupon.index') }}" class="btn btn-secondary"><i class="fa fa-arrow-left me-2"></i> Back to Coupons</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 grid-margin">
                <div class="card card-white">
                    <div class="card-body">
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('admin.coupon.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 form-group mb-3">
                                    <label>Seller / Store (optional) <span class="text-danger">*</span></label>
                                    <select name="vendor_id" class="form-select">
                                        <option value="">All Stores (applies to any product)</option>
                                        @foreach($vendors as $vendor)
                                            <option value="{{ $vendor->id }}" {{ old('vendor_id') == $vendor->id ? 'selected' : '' }}>
                                                {{ $vendor->store_name }} ({{ $vendor->user->name ?? 'N/A' }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted">Leave "All Stores" for a global coupon. Pick a seller to restrict this coupon to their products only.</small>
                                </div>

                                <div class="col-md-6 form-group mb-3">
                                    <label>Coupon Code <span class="text-danger">*</span></label>
                                    <input type="text" name="code" class="form-control" value="{{ old('code') }}" required placeholder="e.g. SUMMER20">
                                </div>

                                <div class="col-md-6 form-group mb-3">
                                    <label>Discount Type <span class="text-danger">*</span></label>
                                    <select name="discount_type" class="form-select" required>
                                        <option value="percentage" {{ old('discount_type') == 'percentage' ? 'selected' : '' }}>Percentage</option>
                                        <option value="fixed" {{ old('discount_type') == 'fixed' ? 'selected' : '' }}>Fixed Amount</option>
                                    </select>
                                </div>

                                <div class="col-md-6 form-group mb-3">
                                    <label>Discount Value <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" name="discount_value" class="form-control" value="{{ old('discount_value') }}" required placeholder="e.g. 20 for 20%, or 15 for ₹15">
                                </div>

                                <div class="col-md-6 form-group mb-3">
                                    <label>Minimum Order Amount (₹) <span class="text-muted">(optional)</span></label>
                                    <input type="number" step="0.01" name="min_order_amount" class="form-control" value="{{ old('min_order_amount') }}" placeholder="e.g. 500">
                                </div>

                                <div class="col-md-6 form-group mb-3">
                                    <label>Max Uses <span class="text-muted">(optional)</span></label>
                                    <input type="number" min="1" name="max_uses" class="form-control" value="{{ old('max_uses') }}" placeholder="e.g. 100">
                                </div>

                                <div class="col-md-6 form-group mb-3">
                                    <label>Start Date <span class="text-muted">(optional)</span></label>
                                    <input type="datetime-local" name="starts_at" class="form-control" value="{{ old('starts_at') }}">
                                </div>

                                <div class="col-md-6 form-group mb-3">
                                    <label>Expiry Date <span class="text-muted">(optional)</span></label>
                                    <input type="datetime-local" name="expires_at" class="form-control" value="{{ old('expires_at') }}">
                                    <small class="text-muted">Leave blank for no expiry. Once passed, the coupon cannot be used.</small>
                                </div>

                                <div class="col-md-6 form-group mb-3">
                                    <label>Status <span class="text-danger">*</span></label>
                                    <select name="is_active" class="form-select" required>
                                        <option value="1" {{ old('is_active') == '1' ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ old('is_active', '0') == '0' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-save me-2"></i> Save Coupon</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
