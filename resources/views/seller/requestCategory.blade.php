@extends('layouts.admin')

@section('content')

@include('admin.nav')

<div class="page-inner">
    <div id="main-wrapper">
        <div class="row align-items-center grid-margin">
            <div class="col-12 col-lg-8 mx-auto">
                <div class="card card-white">
                    <div class="card-body">
                        <h4 class="mb-4">Request New Category</h4>
                        <p class="text-muted">Submit a request to the admin to add a new product category.</p>

                        <form action="{{ route('seller.request.category.store') }}" method="POST">
                            @csrf
                            
                            <div class="mb-3">
                                <label class="form-label">Category Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name" required placeholder="e.g. Sunglasses">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Description (Optional)</label>
                                <textarea class="form-control" name="description" rows="3" placeholder="Why do you need this category?"></textarea>
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
