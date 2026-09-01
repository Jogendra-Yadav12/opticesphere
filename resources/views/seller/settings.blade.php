@extends('layouts.admin')

@section('content')

@include('admin.nav')

<div class="page-inner">
    <div id="main-wrapper">
        <div class="row align-items-center grid-margin">
            <div class="col-12">
                <div class="card card-white">
                    <div class="card-body">
                        <h4 class="mb-4">My Settings</h4>

                        <form action="{{ route('seller.settings.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Email Address</label>
                                    <input type="email" class="form-control" name="email" value="{{ $seller->email }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Mobile Number</label>
                                    <input type="text" class="form-control" name="phone" value="{{ $seller->phone }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Store Name</label>
                                    <input type="text" class="form-control" name="shop_name" value="{{ $seller->shop_name }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Store Logo</label>
                                    <input type="file" class="form-control" name="store_logo" accept="image/*">
                                    @if($seller->store_logo)
                                        <div class="mt-2">
                                            <img src="{{ Storage::url('images/logos/'.$seller->store_logo) }}" alt="Logo" style="height: 50px;">
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Store Banner</label>
                                    <input type="file" class="form-control" name="store_banner" accept="image/*">
                                    @if($seller->store_banner)
                                        <div class="mt-2">
                                            <img src="{{ Storage::url('images/banners/'.$seller->store_banner) }}" alt="Banner" style="height: 80px; object-fit: cover;">
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Store Address</label>
                                    <textarea class="form-control" name="store_address" rows="3">{{ $seller->store_address }}</textarea>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Store Description</label>
                                    <textarea class="form-control" name="store_description" rows="4" placeholder="Describe your store">{{ $seller->store_description }}</textarea>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">City</label>
                                    <input type="text" class="form-control" name="store_city" value="{{ $seller->store_city }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">State</label>
                                    <input type="text" class="form-control" name="store_state" value="{{ $seller->store_state }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Postal Code</label>
                                    <input type="text" class="form-control" name="store_postal_code" value="{{ $seller->store_postal_code }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Country</label>
                                    <input type="text" class="form-control" name="store_country" value="{{ $seller->store_country }}">
                                </div>
                            </div>

                            <hr class="my-4">

                            <h5 class="mb-3">Store Hours</h5>
                            <p class="text-muted small mb-4">Set your opening and closing time for each day of the week.</p>
                            <div class="row g-3 mb-2">
                                @foreach($dayNames as $idx => $dayName)
                                @php $dayHours = $storeHours[$idx] ?? []; @endphp
                                <div class="col-md-6">
                                    <div class="border rounded p-3 h-100 d-flex align-items-center gap-3">
                                        <div class="form-check form-switch mb-0">
                                            <input class="form-check-input" type="checkbox" role="switch" id="closed-{{ $idx }}" name="hours[{{ $idx }}][is_closed]" value="1" {{ !empty($dayHours['is_closed']) ? 'checked' : '' }}>
                                            <label class="form-check-label small" for="closed-{{ $idx }}">Closed</label>
                                        </div>
                                        <div class="flex-grow-1 d-flex align-items-center gap-2">
                                            <input type="time" class="form-control form-control-sm" name="hours[{{ $idx }}][open]" value="{{ $dayHours['open'] ?? '' }}">
                                            <span class="text-muted small">to</span>
                                            <input type="time" class="form-control form-control-sm" name="hours[{{ $idx }}][close]" value="{{ $dayHours['close'] ?? '' }}">
                                        </div>
                                        <span class="fw-semibold small text-nowrap" style="min-width:70px;">{{ $dayName }}</span>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            <button type="submit" class="btn btn-primary mt-3">Save Settings</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
