@extends('layouts.admin')

@section('content')
@include('admin.nav')

<div class="page-inner">
    <div id="main-wrapper">
        <div class="row align-items-center grid-margin">
            <div class="col-12">
                <div class="card card-white">
                    <div class="card-body">
                        <h4 class="mb-0">Returns</h4>
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
                                        <th>Order</th>
                                        <th>Item</th>
                                        <th>Qty</th>
                                        <th>Reason</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($returns as $return)
                                    <tr>
                                        <th scope="row">{{ $return->id }}</th>
                                        <td><a href="{{ route('admin.order.show', $return->order_id) }}">#{{ $return->order_id }}</a></td>
                                        <td>{{ $return->orderItem->product_name ?? '—' }}</td>
                                        <td>{{ $return->quantity }}</td>
                                        <td>{{ ucfirst(str_replace('_', ' ', $return->reason)) }}</td>
                                        <td>
                                            <span class="badge rounded-pill bg-soft-{{ $return->status === 'approved' || $return->status === 'received' || $return->status === 'refunded' ? 'green' : ($return->status === 'rejected' ? 'pink' : 'orange') }}">{{ ucfirst($return->status) }}</span>
                                        </td>
                                        <td>
                                            @if($return->status === 'requested')
                                            <form action="{{ route('admin.return.approve', $return->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <button class="btn btn-sm btn-outline-success" title="Approve"><i class="fa fa-check"></i> Approve</button>
                                            </form>
                                            <form action="{{ route('admin.return.reject', $return->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <button class="btn btn-sm btn-outline-danger" title="Reject"><i class="fa fa-times"></i> Reject</button>
                                            </form>
                                            @else
                                            <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">No returns found.</td>
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
