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
                            <h4 class="mb-4 mb-sm-0 text-center text-sm-start">Subscription Plans</h4>
                        </div>
                        <div class="col-12 col-sm-auto">
                            <button class="btn btn-primary" data-bs-toggle="collapse" data-bs-target="#addPlanForm"><i class="fa fa-plus me-2"></i> Add New Plan</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="collapse" id="addPlanForm">
            <div class="card card-white grid-margin">
                <div class="card-body">
                    <form action="{{ route('admin.plan.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-3 form-group">
                                <label>Plan Name</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>
                            <div class="col-md-3 form-group">
                                <label>Price (₹)</label>
                                <input type="number" step="0.01" name="price" class="form-control" required>
                            </div>
                            <div class="col-md-3 form-group">
                                <label>Duration (Days)</label>
                                <input type="number" name="duration_days" class="form-control" required>
                            </div>
                            <div class="col-md-3 form-group">
                                <label>Product Limit</label>
                                <input type="number" name="product_limit" class="form-control" placeholder="0 for unlimited" required>
                            </div>
                            <div class="col-md-3 form-group d-flex align-items-end">
                                <div class="form-check mb-2">
                                    <input type="hidden" name="purchase_enabled" value="0">
                                    <input type="checkbox" name="purchase_enabled" value="1" class="form-check-input" id="purchaseEnabled" checked>
                                    <label class="form-check-label" for="purchaseEnabled">Products purchasable (uncheck = display only, no Add to Cart)</label>
                                </div>
                            </div>
                            <div class="col-md-12 form-group">
                                <label>What's Included (shown to sellers on the choose-plan page)</label>
                                <textarea id="summernote" name="description" class="form-control" placeholder="e.g. 100 product listings, order management, priority support"></textarea>
                            </div>
                            <div class="col-md-12 mt-3 text-end">
                                <button type="submit" class="btn btn-success">Save Plan</button>
                            </div>
                        </div>
                    </form>
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
                                        <th scope="col">Plan Name</th>
                                        <th scope="col">Price</th>
                                        <th scope="col">Product Limit</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($plans as $plan)
                                    <tr>
                                        <td>{{ $plan->id }}</td>
                                        <td><strong>{{ $plan->name }}</strong></td>
                                        <td>₹{{ number_format($plan->price, 2) }} / {{ $plan->duration_days }} Days</td>
                                        <td>{{ $plan->product_limit == 0 ? 'Unlimited' : $plan->product_limit }}</td>
                                        <td><span class="badge bg-success">Active</span></td>
                                        <td>
                                            <a href="{{ route('admin.plan.edit', $plan->id) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></a>
                                            <form action="{{ route('admin.plan.destroy', $plan->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this plan?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">No subscription plans found. <a href="#">Create one</a></td>
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

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script>
    $(document).ready(function () {
        var summernoteInited = false;
        $('#addPlanForm').on('shown.bs.collapse', function () {
            if (summernoteInited || !$('#summernote').length) {
                return;
            }
            summernoteInited = true;
            $('#summernote').summernote({
                placeholder: 'e.g. Up to 100 products, order management, priority support',
                tabsize: 2,
                height: 120,
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ]
            });
        });
    });
</script>
@endsection
