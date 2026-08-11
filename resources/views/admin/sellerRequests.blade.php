@extends('layouts.admin')

@section('content')

@include('admin.nav')

<div class="page-inner">
    <div id="main-wrapper">
        <div class="row align-items-center grid-margin">
            <div class="col-12">
                <div class="card card-white">
                    <div class="card-body">
                        <h4 class="mb-4">Seller Requests</h4>
                        
                        <div class="table-responsive">
                            <table class="table">
                                <thead class="bg-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Seller</th>
                                        <th>Type</th>
                                        <th>Details</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($requests as $req)
                                    <tr>
                                        <td>{{ $req->id }}</td>
                                        <td>{{ $req->seller->name ?? 'Unknown' }}<br><small class="text-muted">{{ $req->seller->email ?? '' }}</small></td>
                                        <td>
                                            @if($req->request_type == 'category')
                                                <span class="badge bg-primary">Category</span>
                                            @else
                                                <span class="badge bg-info">Coupon</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($req->request_type == 'category')
                                                <strong>Name:</strong> {{ $req->details['name'] ?? 'N/A' }}<br>
                                                <small>Desc: {{ $req->details['description'] ?? 'None' }}</small>
                                            @elseif($req->request_type == 'coupon')
                                                <strong>Code:</strong> {{ $req->details['code'] ?? 'N/A' }}<br>
                                                <small>{{ $req->details['discount_value'] ?? '0' }} ({{ $req->details['discount_type'] ?? '' }})</small>
                                                @if(!empty($req->details['min_order_amount']))<br><small>Min order: ₹{{ $req->details['min_order_amount'] }}</small>@endif
                                                @if(!empty($req->details['max_uses']))<br><small>Max uses: {{ $req->details['max_uses'] }}</small>@endif
                                                <br><small>Starts: {{ !empty($req->details['starts_at']) ? date('d M Y, h:i A', strtotime($req->details['starts_at'])) : 'Now' }}</small>
                                                <br><small>Expires: {{ !empty($req->details['expires_at']) ? date('d M Y, h:i A', strtotime($req->details['expires_at'])) : 'No expiry' }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            @if($req->status == 'pending')
                                                <span class="badge bg-warning">Pending</span>
                                            @elseif($req->status == 'approved')
                                                <span class="badge bg-success">Approved</span>
                                            @else
                                                <span class="badge bg-danger">Rejected</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($req->status == 'pending')
                                                <form action="{{ route('admin.seller.requests.approve', $req->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <button class="btn btn-sm btn-success" title="Approve Request"><i class="fa fa-check"></i></button>
                                                </form>
                                                <form action="{{ route('admin.seller.requests.reject', $req->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <button class="btn btn-sm btn-danger" title="Reject Request"><i class="fa fa-times"></i></button>
                                                </form>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">No seller requests found.</td>
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
