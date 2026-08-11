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
                            <h4 class="mb-4 mb-sm-0 text-center text-sm-start">Edit Seller</h4>
                        </div>
                        <div class="col-12 col-sm-auto text-end mb-3 mb-sm-0">
                            <a href="{{ route('admin.seller.index') }}" class="btn btn-secondary"><i class="fa fa-arrow-left me-2"></i> Back to Sellers</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 grid-margin">
                <div class="card card-white">
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('admin.seller.update', $seller->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="name" value="{{ old('name', $seller->name) }}" required>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Email Address <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" name="email" value="{{ old('email', $seller->email) }}" required>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Shop Name</label>
                                    <input type="text" class="form-control" name="shop_name" value="{{ old('shop_name', $seller->shop_name) }}">
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Phone Number</label>
                                    <input type="text" class="form-control" name="phone" value="{{ old('phone', $seller->phone) }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-select">
                                        @foreach(['approved', 'pending', 'suspended', 'rejected'] as $st)
                                        <option value="{{ $st }}" {{ old('status', $seller->vendor->status ?? 'pending') == $st ? 'selected' : '' }}>{{ ucfirst($st) }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Commission Type</label>
                                    <select name="commission_type" class="form-select">
                                        <option value="percentage" {{ old('commission_type', $seller->vendor->commission_type ?? 'percentage') == 'percentage' ? 'selected' : '' }}>Percentage</option>
                                        <option value="fixed" {{ old('commission_type', $seller->vendor->commission_type ?? '') == 'fixed' ? 'selected' : '' }}>Fixed</option>
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Commission Rate</label>
                                    <input type="number" step="0.01" min="0" max="100" class="form-control" name="commission_rate" value="{{ old('commission_rate', $seller->vendor->commission_rate ?? '') }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Tax Number (GST/TIN)</label>
                                    <input type="text" class="form-control" name="tax_number" value="{{ old('tax_number', $seller->vendor->tax_number ?? '') }}">
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Seller Plan</label>
                                    <div class="border rounded p-3 mb-2 bg-light">
                                        @if($subscription)
                                            <strong>{{ $subscription->planTier?->plan?->name ?? 'Unknown plan' }}</strong>
                                            <span class="badge {{ in_array($subscription->status, ['active', 'trialing'], true) ? 'bg-success' : 'bg-secondary' }}">{{ ucfirst($subscription->status) }}</span>
                                            <div class="text-muted small mt-1">Period: {{ optional($subscription->current_period_start)->format('d M Y') }} — {{ optional($subscription->current_period_end)->format('d M Y') }}</div>
                                        @else
                                            <span class="text-muted">No subscription yet.</span>
                                        @endif
                                    </div>
                                    <select name="plan_id" class="form-select">
                                        <option value="">— Keep current plan —</option>
                                        @foreach($plans as $plan)
                                            <option value="{{ $plan->id }}" {{ $subscription && $subscription->planTier?->plan_id == $plan->id ? 'selected' : '' }}>{{ $plan->name }} (₹{{ number_format((float) $plan->price, 2) }} / {{ $plan->duration_days }} days)</option>
                                        @endforeach
                                    </select>
                                    <div class="form-text">Selecting a different plan starts a new trialing period and cancels the current one.</div>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Store Description</label>
                                    <textarea class="form-control" name="store_description" rows="4" placeholder="Describe the store">{{ old('store_description', $seller->vendor->description ?? '') }}</textarea>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Store Address</label>
                                    <textarea class="form-control" name="store_address" rows="2" placeholder="Street address">{{ old('store_address', $seller->vendor->address ?? '') }}</textarea>
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label class="form-label">City</label>
                                    <input type="text" class="form-control" name="store_city" value="{{ old('store_city', $seller->vendor->city ?? '') }}">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">State</label>
                                    <input type="text" class="form-control" name="store_state" value="{{ old('store_state', $seller->vendor->state ?? '') }}">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Postal Code</label>
                                    <input type="text" class="form-control" name="store_postal_code" value="{{ old('store_postal_code', $seller->vendor->postal_code ?? '') }}">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Country</label>
                                    <input type="text" class="form-control" name="store_country" value="{{ old('store_country', $seller->vendor->country ?? '') }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Store Logo</label>
                                    <input type="file" class="form-control" name="store_logo" accept="image/*">
                                    @if($seller->vendor->logo ?? null)
                                        <div class="mt-2">
                                            <img src="{{ asset('images/logos/'.$seller->vendor->logo) }}" alt="Logo" style="height: 50px;">
                                        </div>
                                    @endif
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Store Banner</label>
                                    <input type="file" class="form-control" name="store_banner" accept="image/*">
                                    @if($seller->vendor->banner ?? null)
                                        <div class="mt-2">
                                            <img src="{{ asset('images/banners/'.$seller->vendor->banner) }}" alt="Banner" style="height: 70px; object-fit: cover;">
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary">Update Seller</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
