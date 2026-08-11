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
                            <h4 class="mb-4 mb-sm-0 text-center text-sm-start">Sellers Management</h4>
                        </div>
                    </div>
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
                                        <th scope="col">Seller Details</th>
                                        <th scope="col">Shop Name</th>
                                        <th scope="col">Current Plan</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                    <tbody>
                                        @forelse($sellers as $seller)
                                        <tr>
                                            <td>{{ $seller->id }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar avatar-sm me-3">
                                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($seller->name) }}&background=random" alt="..." class="avatar-img rounded-circle">
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0">{{ $seller->name }}</h6>
                                                        <small class="text-muted">{{ $seller->email }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $seller->shop_name ?? 'N/A' }}</td>
                                            <td><span class="badge bg-info">{{ ucfirst($seller->seller_plan ?? 'Free') }}</span></td>
                                            <td>
                                                @if($seller->status === 'approved')
                                                    <span class="badge bg-success">Approved</span>
                                                @elseif($seller->status === 'rejected')
                                                    <span class="badge bg-danger">Rejected</span>
                                                @else
                                                    <span class="badge bg-warning text-dark">Pending</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.seller.edit', $seller->id) }}" class="btn btn-sm btn-outline-primary me-1" title="Edit"><i class="fas fa-edit"></i></a>
                                                @if($seller->status !== 'approved')
                                                <form action="{{ route('admin.seller.approve', $seller->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="btn btn-sm btn-outline-success me-1" title="Approve"><i class="fas fa-check"></i></button>
                                                </form>
                                                @endif
                                                @if($seller->status !== 'rejected')
                                                <form action="{{ route('admin.seller.reject', $seller->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="btn btn-sm btn-outline-warning me-1" title="Reject"><i class="fas fa-times"></i></button>
                                                </form>
                                                @endif
                                                <form action="{{ route('admin.seller.destroy', $seller->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this seller?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete"><i class="fas fa-trash"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-4">No sellers found.</td>
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
