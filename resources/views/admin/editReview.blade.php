@extends('layouts.admin')

@section('content')
@include('admin.nav')

<div class="page-inner">
    <div id="main-wrapper">
        <div class="row align-items-center grid-margin">
            <div class="col-12">
                <div class="card card-white">
                    <div class="card-body">
                        <h4 class="mb-0">Edit Review #{{ $review->id }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card card-white">
                    <div class="card-body">
                        <form action="{{ route('admin.review.update', $review->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label class="form-label">Customer</label>
                                <input type="text" class="form-control" value="{{ $review->user->name ?? '—' }}" disabled>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Item</label>
                                <input type="text" class="form-control"
                                    value="{{ $review->reviewable->store_name ?? $review->reviewable->name ?? ('#'.$review->reviewable_id) }}" disabled>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Rating <span class="text-danger">*</span></label>
                                <select name="rating" class="form-select">
                                    @for($i = 1; $i <= 5; $i++)
                                        <option value="{{ $i }}" {{ old('rating', $review->rating) == $i ? 'selected' : '' }}>{{ $i }} star{{ $i > 1 ? 's' : '' }}</option>
                                    @endfor
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Title</label>
                                <input type="text" class="form-control" name="title" value="{{ old('title', $review->title) }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Review Body <span class="text-danger">*</span></label>
                                <textarea class="form-control" name="body" rows="5" required>{{ old('body', $review->body) }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select">
                                    @foreach(['approved', 'pending', 'rejected'] as $st)
                                        <option value="{{ $st }}" {{ old('status', $review->status) == $st ? 'selected' : '' }}>{{ ucfirst($st) }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary">Save Changes</button>
                            <a href="{{ route('admin.review.index') }}" class="btn btn-light">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
