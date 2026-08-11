@extends('layouts.admin')

@section('content')

@include('admin.nav')

<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">

<div class="page-inner">
    <div id="main-wrapper">
        <div class="row align-items-center grid-margin">
            <div class="col-12">
                <div class="card card-white">
                    <div class="card-body row align-items-center">
                        <div class="col-12 col-sm">
                            <h4 class="mb-4 mb-sm-0 text-center text-sm-start">Edit Subscription Plan</h4>
                        </div>
                        <div class="col-12 col-sm-auto text-end mb-3 mb-sm-0">
                            <a href="{{ route('admin.plan.index') }}" class="btn btn-secondary"><i class="fa fa-arrow-left me-2"></i> Back to Plans</a>
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

                        <form action="{{ route('admin.plan.update', $plan->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Plan Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="name" value="{{ old('name', $plan->name) }}" required>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Price (₹) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" class="form-control" name="price" value="{{ old('price', $plan->price) }}" required>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Duration (Days) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" name="duration_days" value="{{ old('duration_days', $plan->duration_days) }}" required>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Product Limit <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" name="product_limit" value="{{ old('product_limit', $plan->product_limit) }}" placeholder="0 for unlimited" required>
                                    <small class="text-muted">Set to 0 for unlimited products.</small>
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="form-check">
                                    <input type="hidden" name="purchase_enabled" value="0">
                                    <input type="checkbox" class="form-check-input" name="purchase_enabled" value="1" id="purchaseEnabled" {{ old('purchase_enabled', $plan->purchase_enabled ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="purchaseEnabled">Products purchasable (uncheck = display only, no Add to Cart)</label>
                                    <small class="d-block text-muted">When disabled, buyers can view this seller's products but cannot add them to cart or buy them.</small>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">What's Included (shown to sellers on the choose-plan page)</label>
                                <textarea id="summernote" class="form-control" name="description" rows="3" placeholder="e.g. 100 product listings, order management, priority support">{{ old('description', $plan->description) }}</textarea>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary">Update Plan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script>
    $(document).ready(function () {
        if ($('#summernote').length) {
            $('#summernote').summernote({
                placeholder: 'e.g. Up to 100 products, order management, priority support',
                tabsize: 2,
                height: 150,
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ]
            });
        }
    });
</script>
@endsection
