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
                            <h4 class="mb-0">{{ $announcement->exists ? 'Edit Announcement' : 'Add Announcement' }}</h4>
                        </div>
                        <div class="col-12 col-sm-auto text-end">
                            <a href="{{ route('admin.announcement.index') }}" class="btn btn-secondary"><i class="fa fa-arrow-left me-2"></i> Back</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8 mx-auto grid-margin">
                <div class="card card-white">
                    <div class="card-body">
                        <form action="{{ $announcement->exists ? route('admin.announcement.update', $announcement->id) : route('admin.announcement.store') }}" method="POST">
                            @csrf
                            @if($announcement->exists) @method('PUT') @endif

                            <div class="mb-4">
                                <label class="form-label fw-bold">Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="title" value="{{ old('title', $announcement->title) }}" required>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold">Body <span class="text-danger">*</span></label>
                                <textarea name="body" rows="5" class="form-control" required>{{ old('body', $announcement->body) }}</textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label class="form-label fw-bold">Audience</label>
                                    <select class="form-select form-control" name="audience">
                                        @foreach(['all', 'customers', 'vendors', 'admins'] as $audience)
                                            <option value="{{ $audience }}" {{ ($announcement->audience ?? 'all') === $audience ? 'selected' : '' }}>{{ ucfirst($audience) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-4 form-check form-switch pt-4">
                                    <input type="checkbox" class="form-check-input" id="is_published" name="is_published" value="1" {{ ($announcement->is_published ?? false) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_published">Published</label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label class="form-label fw-bold">Starts At</label>
                                    <input type="datetime-local" class="form-control" name="starts_at" value="{{ $announcement->starts_at?->format('Y-m-d\TH:i') }}">
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label class="form-label fw-bold">Ends At</label>
                                    <input type="datetime-local" class="form-control" name="ends_at" value="{{ $announcement->ends_at?->format('Y-m-d\TH:i') }}">
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary">{{ $announcement->exists ? 'Update Announcement' : 'Create Announcement' }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
