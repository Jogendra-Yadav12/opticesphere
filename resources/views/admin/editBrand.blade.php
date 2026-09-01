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
                            <h4 class="mb-0">{{ $brand->exists ? 'Edit Brand: '.$brand->name : 'Add Brand' }}</h4>
                        </div>
                        <div class="col-12 col-sm-auto text-end">
                            <a href="{{ route('admin.brand.index') }}" class="btn btn-secondary"><i class="fa fa-arrow-left me-2"></i> Back to Brands</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8 mx-auto grid-margin">
                <div class="card card-white">
                    <div class="card-body">
                        <form action="{{ $brand->exists ? route('admin.brand.update', $brand->id) : route('admin.brand.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @if($brand->exists) @method('PUT') @endif

                            <div class="mb-4">
                                <label class="form-label fw-bold">Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name" value="{{ old('name', $brand->name) }}" required>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold">Logo</label>
                                <input type="file" class="form-control" name="logo" accept="image/*">
                                @if($brand->logo)
                                    <img src="{{ Storage::url('images/'.$brand->logo) }}" alt="logo" class="mt-3" style="height: 48px; border-radius: 6px;">
                                @endif
                            </div>

                            <div class="mb-4 form-check form-switch">
                                <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ ($brand->is_active ?? true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">Active</label>
                            </div>

                            <button type="submit" class="btn btn-primary">{{ $brand->exists ? 'Update Brand' : 'Create Brand' }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
