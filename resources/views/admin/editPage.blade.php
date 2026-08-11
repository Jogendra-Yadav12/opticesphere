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
                            <h4 class="mb-0">{{ $page->exists ? 'Edit Page: '.$page->title : 'Add Page' }}</h4>
                        </div>
                        <div class="col-12 col-sm-auto text-end">
                            <a href="{{ route('admin.page.index') }}" class="btn btn-secondary"><i class="fa fa-arrow-left me-2"></i> Back to Pages</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-10 mx-auto grid-margin">
                <div class="card card-white">
                    <div class="card-body">
                        <form action="{{ $page->exists ? route('admin.page.update', $page->id) : route('admin.page.store') }}" method="POST">
                            @csrf
                            @if($page->exists) @method('PUT') @endif

                            <div class="mb-4">
                                <label class="form-label fw-bold">Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="title" value="{{ old('title', $page->title) }}" required>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold">Body <span class="text-danger">*</span></label>
                                <textarea name="body" rows="10" class="form-control" required>{{ old('body', $page->body) }}</textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label class="form-label fw-bold">Meta Title</label>
                                    <input type="text" class="form-control" name="meta_title" value="{{ old('meta_title', $page->meta_title) }}">
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label class="form-label fw-bold">Status</label>
                                    <select class="form-select form-control" name="status">
                                        @foreach(['draft', 'published'] as $status)
                                            <option value="{{ $status }}" {{ ($page->status ?? 'draft') === $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold">Meta Description</label>
                                <textarea name="meta_description" rows="3" class="form-control">{{ old('meta_description', $page->meta_description) }}</textarea>
                            </div>

                            <button type="submit" class="btn btn-primary">{{ $page->exists ? 'Update Page' : 'Create Page' }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
