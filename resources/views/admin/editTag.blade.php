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
                            <h4 class="mb-0">{{ $tag->exists ? 'Edit Tag: '.$tag->name : 'Add Tag' }}</h4>
                        </div>
                        <div class="col-12 col-sm-auto text-end">
                            <a href="{{ route('admin.tag.index') }}" class="btn btn-secondary"><i class="fa fa-arrow-left me-2"></i> Back to Tags</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8 mx-auto grid-margin">
                <div class="card card-white">
                    <div class="card-body">
                        <form action="{{ $tag->exists ? route('admin.tag.update', $tag->id) : route('admin.tag.store') }}" method="POST">
                            @csrf
                            @if($tag->exists) @method('PUT') @endif

                            <div class="mb-4">
                                <label class="form-label fw-bold">Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name" value="{{ old('name', $tag->name) }}" required>
                            </div>

                            <button type="submit" class="btn btn-primary">{{ $tag->exists ? 'Update Tag' : 'Create Tag' }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
