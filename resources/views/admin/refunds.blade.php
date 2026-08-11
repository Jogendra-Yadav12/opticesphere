@extends('layouts.admin')

@section('content')
@include('admin.nav')

<div class="page-inner">
    <div id="main-wrapper">
        <div class="row align-items-center grid-margin">
            <div class="col-12">
                <div class="card card-white">
                    <div class="card-body">
                        <h4 class="mb-0">Refunds</h4>
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
                                        <th>Amount</th>
                                        <th>Reason</th>
                                        <th>Status</th>
                                        <th>Processed By</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($refunds as $refund)
                                    <tr>
                                        <th scope="row">{{ $refund->id }}</th>
                                        <td><a href="{{ route('admin.order.show', $refund->order_id) }}">#{{ $refund->order_id }}</a></td>
                                        <td>{{ $refund->orderItem->product_name ?? '—' }}</td>
                                        <td>₹{{ number_format($refund->amount, 2) }}</td>
                                        <td>{{ Str::limit($refund->reason, 40) }}</td>
                                        <td>
                                            <span class="badge rounded-pill bg-soft-{{ $refund->status === 'processed' || $refund->status === 'approved' ? 'green' : ($refund->status === 'rejected' ? 'pink' : 'orange') }}">{{ ucfirst($refund->status) }}</span>
                                        </td>
                                        <td>{{ $refund->processedBy->name ?? '—' }}</td>
                                        <td>
                                            @if(in_array($refund->status, ['requested', 'approved']))
                                            <form action="{{ route('admin.refund.approve', $refund->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <button class="btn btn-sm btn-outline-success" title="Approve"><i class="fa fa-check"></i> Approve</button>
                                            </form>
                                            <form action="{{ route('admin.refund.reject', $refund->id) }}" method="POST" class="d-inline">
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
                                        <td colspan="8" class="text-center text-muted py-4">No refunds found.</td>
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
