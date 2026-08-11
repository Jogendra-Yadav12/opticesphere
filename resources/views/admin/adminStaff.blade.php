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
                            <h4 class="mb-0">Admin Staff</h4>
                        </div>
                        <div class="col-12 col-sm-auto text-end">
                            <a href="{{ route('admin.staff.create') }}" class="btn btn-primary"><i class="fa fa-plus me-2"></i> Add Staff</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 grid-margin">
                <div class="card card-white">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead class="bg-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Status</th>
                                        <th>Last Login</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($staff as $member)
                                    <tr>
                                        <th scope="row">{{ $member->id }}</th>
                                        <td>{{ $member->name }}</td>
                                        <td>{{ $member->email }}</td>
                                        <td><span class="badge bg-light text-dark">{{ ucfirst(str_replace('_', ' ', $member->role)) }}</span></td>
                                        <td><span class="badge rounded-pill bg-soft-{{ $member->status ? 'green' : 'secondary' }}">{{ $member->status ? 'Active' : 'Inactive' }}</span></td>
                                        <td>{{ $member->last_login_at?->format('d M Y') ?? 'Never' }}</td>
                                        <td>
                                            <a href="{{ route('admin.staff.edit', $member->id) }}" class="me-3" title="Edit"><i class="far fa-edit text-primary"></i></a>
                                            <form action="{{ route('admin.staff.destroy', $member->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this staff member?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-link p-0 border-0" title="Delete"><i class="far fa-trash-alt text-danger"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">No staff members found.</td>
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
