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
                            <h4 class="mb-0">{{ $staff->exists ? 'Edit Staff: '.$staff->name : 'Add Staff Member' }}</h4>
                        </div>
                        <div class="col-12 col-sm-auto text-end">
                            <a href="{{ route('admin.staff.index') }}" class="btn btn-secondary"><i class="fa fa-arrow-left me-2"></i> Back to Staff</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8 mx-auto grid-margin">
                <div class="card card-white">
                    <div class="card-body">
                        <form action="{{ $staff->exists ? route('admin.staff.update', $staff->id) : route('admin.staff.store') }}" method="POST">
                            @csrf
                            @if($staff->exists) @method('PUT') @endif

                            <div class="mb-4">
                                <label class="form-label fw-bold">Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name" value="{{ old('name', $staff->name) }}" required>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" name="email" value="{{ old('email', $staff->email) }}" required>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold">Password {{ $staff->exists ? '(leave blank to keep current)' : '' }} <span class="text-danger">{{ $staff->exists ? '' : '*' }}</span></label>
                                <input type="password" class="form-control" name="password" {{ $staff->exists ? '' : 'required' }} minlength="6">
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold">Role</label>
                                <select class="form-select form-control" name="role">
                                    @foreach(['super_admin', 'support', 'finance', 'content'] as $role)
                                        <option value="{{ $role }}" {{ ($staff->role ?? 'support') === $role ? 'selected' : '' }}>{{ str_replace('_', ' ', ucfirst($role)) }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary">{{ $staff->exists ? 'Update Staff' : 'Create Staff' }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
