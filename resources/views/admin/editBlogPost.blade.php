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
                            <h4 class="mb-0">{{ $post->exists ? 'Edit Post: '.$post->title : 'Add Blog Post' }}</h4>
                        </div>
                        <div class="col-12 col-sm-auto text-end">
                            <a href="{{ route('admin.blog.index') }}" class="btn btn-secondary"><i class="fa fa-arrow-left me-2"></i> Back to Posts</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-10 mx-auto grid-margin">
                <div class="card card-white">
                    <div class="card-body">
                        <form action="{{ $post->exists ? route('admin.blog.update', $post->id) : route('admin.blog.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @if($post->exists) @method('PUT') @endif

                            <div class="mb-4">
                                <label class="form-label fw-bold">Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="title" value="{{ old('title', $post->title) }}" required>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold">Excerpt</label>
                                <textarea name="excerpt" rows="2" class="form-control">{{ old('excerpt', $post->excerpt) }}</textarea>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold">Content <span class="text-danger">*</span></label>
                                <textarea name="content" rows="12" class="form-control" required>{{ old('content', $post->content) }}</textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label class="form-label fw-bold">Cover Image</label>
                                    <input type="file" class="form-control" name="cover_image" accept="image/*">
                                    @if($post->cover_image)
                                        <img src="{{ Storage::url('images/'.$post->cover_image) }}" alt="cover" class="mt-3" style="height: 60px; border-radius: 6px;">
                                    @endif
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label class="form-label fw-bold">Status</label>
                                    <select class="form-select form-control" name="status">
                                        @foreach(['draft', 'published'] as $status)
                                            <option value="{{ $status }}" {{ ($post->status ?? 'draft') === $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary">{{ $post->exists ? 'Update Post' : 'Create Post' }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
