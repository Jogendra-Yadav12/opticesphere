@extends('layouts.admin')

@section('content')

@include('admin.nav')

<div class="page-inner">
    <div id="main-wrapper">
        <form action="{{ route('admin.setting.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row align-items-center grid-margin">
                <div class="col-12">
                    <div class="card card-white">
                        <div class="card-body row align-items-center">
                            <div class="col-12 col-sm">
                                <h4 class="mb-4 mb-sm-0 text-center text-sm-start">Global Store Settings</h4>
                            </div>
                            <div class="col-12 col-sm-auto">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-save me-2"></i> Save Settings</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 grid-margin">
                    <div class="card card-white">
                        <div class="card-body slimscroll">
                            
                            @php
                                // Helper to easily get a setting value or default
                                $getSetting = function($key, $default = '') use ($settings) {
                                    $setting = $settings->firstWhere('key', $key);
                                    return $setting ? $setting->value : $default;
                                };
                            @endphp

                            <div class="mb-4">
                                <label class="form-label">Store Name <span class="text-danger">*</span></label>
                                <input type="hidden" name="setting_keys[]" value="store_name">
                                <input type="text" name="setting_values[]" class="form-control" value="{{ $getSetting('store_name', 'EyeClinic Multi-Seller Store') }}" required>
                            </div>
                            <div class="mb-4">
                                <label class="form-label">Contact Email <span class="text-danger">*</span></label>
                                <input type="hidden" name="setting_keys[]" value="contact_email">
                                <input type="email" name="setting_values[]" class="form-control" value="{{ $getSetting('contact_email', 'support@eyeclinic.com') }}" required>
                            </div>
                            <div class="mb-4">
                                <label class="form-label">Support Phone <span class="text-danger">*</span></label>
                                <input type="hidden" name="setting_keys[]" value="support_phone">
                                <input type="text" name="setting_values[]" class="form-control" value="{{ $getSetting('support_phone', '+1 (800) 123-4567') }}" required>
                            </div>
                            <div class="mb-4">
                                <label class="form-label">Store Address <small class="text-muted">(shown in the footer "Contact Us" section)</small></label>
                                <input type="hidden" name="setting_keys[]" value="store_address">
                                <input type="text" name="setting_values[]" class="form-control" value="{{ $getSetting('store_address', 'Near Gosaidaspur Chauraha, Kannauj, India') }}">
                            </div>
                            <div class="mb-4">
                                <label class="form-label">Facebook URL</label>
                                <input type="hidden" name="setting_keys[]" value="social_facebook">
                                <input type="url" name="setting_values[]" class="form-control" value="{{ $getSetting('social_facebook') }}" placeholder="https://facebook.com/yourstore">
                            </div>
                            <div class="mb-4">
                                <label class="form-label">Twitter / X URL</label>
                                <input type="hidden" name="setting_keys[]" value="social_twitter">
                                <input type="url" name="setting_values[]" class="form-control" value="{{ $getSetting('social_twitter') }}" placeholder="https://twitter.com/yourstore">
                            </div>
                            <div class="mb-4">
                                <label class="form-label">Instagram URL</label>
                                <input type="hidden" name="setting_keys[]" value="social_instagram">
                                <input type="url" name="setting_values[]" class="form-control" value="{{ $getSetting('social_instagram') }}" placeholder="https://instagram.com/yourstore">
                            </div>
                            <div class="mb-4">
                                <label class="form-label">LinkedIn URL</label>
                                <input type="hidden" name="setting_keys[]" value="social_linkedin">
                                <input type="url" name="setting_values[]" class="form-control" value="{{ $getSetting('social_linkedin') }}" placeholder="https://linkedin.com/company/yourstore">
                            </div>
                            <div class="mb-4">
                                <label class="form-label">Footer Credit Text <small class="text-muted">(e.g. "Design by Jogendra Yadav")</small></label>
                                <input type="hidden" name="setting_keys[]" value="footer_credit_text">
                                <input type="text" name="setting_values[]" class="form-control" value="{{ $getSetting('footer_credit_text', 'Jogendra Yadav') }}">
                            </div>
                            <div class="mb-4">
                                <label class="form-label">Footer Credit URL</label>
                                <input type="hidden" name="setting_keys[]" value="footer_credit_url">
                                <input type="url" name="setting_values[]" class="form-control" value="{{ $getSetting('footer_credit_url', 'https://jogendra-yadav.netlify.app/') }}">
                            </div>
                            <div class="mb-4">
                                <label class="form-label">Payment Method Images <small class="text-muted">(comma-separated filenames inside img/content/payment-options/)</small></label>
                                <input type="hidden" name="setting_keys[]" value="payment_methods">
                                <textarea name="setting_values[]" class="form-control" rows="2">{{ $getSetting('payment_methods', 'visa.png, mastercard.png, paypal.png, amex.png, discover.png') }}</textarea>
                            </div>
                            <div class="mb-4">
                                <label class="form-label">Store Logo</label>
                                <input type="file" name="store_logo" class="form-control" accept="image/*">
                                @if($getSetting('store_logo'))
                                    <div class="mt-2">
                                        <img src="{{ asset('images/logos/'.$getSetting('store_logo')) }}" alt="Store Logo" style="max-height: 80px;">
                                    </div>
                                @endif
                            </div>
                            <div class="mb-4">
                                <label class="form-label">Default Image <small class="text-muted">(shown everywhere a product/category/seller has no image)</small></label>
                                <input type="file" name="default_image" class="form-control" accept="image/*">
                                @if($getSetting('default_image'))
                                    <div class="mt-2">
                                        <img src="{{ asset('images/'.$getSetting('default_image')) }}" alt="Default Image" style="max-height: 80px;">
                                    </div>
                                @endif
                            </div>
                            <div class="mb-4">
                                <label class="form-label">Contact Page Image <small class="text-muted">(shown on the Contact Us page)</small></label>
                                <input type="file" name="contact_page_image" class="form-control" accept="image/*">
                                @if($getSetting('contact_page_image'))
                                    <div class="mt-2">
                                        <img src="{{ asset('images/'.$getSetting('contact_page_image')) }}" alt="Contact Page Image" style="max-height: 80px;">
                                    </div>
                                @else
                                    <small class="text-muted d-block mt-1">No image uploaded yet. The default image will be shown.</small>
                                @endif
                            </div>
                            <div class="mb-4">
                                <label class="form-label">Global Currency <span class="text-danger">*</span></label>
                                <input type="hidden" name="setting_keys[]" value="global_currency">
                                @php $currentCurrency = $getSetting('global_currency', 'INR'); @endphp
                                <select name="setting_values[]" class="form-select" required>
                                    <option value="INR" {{ $currentCurrency == 'INR' ? 'selected' : '' }}>INR (₹)</option>
                                    <option value="USD" {{ $currentCurrency == 'USD' ? 'selected' : '' }}>USD ($)</option>
                                    <option value="EUR" {{ $currentCurrency == 'EUR' ? 'selected' : '' }}>EUR (€)</option>
                                    <option value="GBP" {{ $currentCurrency == 'GBP' ? 'selected' : '' }}>GBP (£)</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
