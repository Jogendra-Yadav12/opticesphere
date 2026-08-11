@extends('layouts.admin')

@section('content')
@include('admin.nav')

<div class="page-inner">
    <div id="main-wrapper">
        <div class="row align-items-center grid-margin">
            <div class="col-12">
                <div class="card card-white">
                    <div class="card-body">
                        <h4 class="mb-0">Vendor Payout Requests</h4>
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
                                        <th>Vendor</th>
                                        <th>Amount</th>
                                        <th>Fee</th>
                                        <th>Method</th>
                                        <th>Status</th>
                                        <th>Processed By</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($payouts as $payout)
                                    <tr>
                                        <th scope="row">{{ $payout->id }}</th>
                                        <td>
                                            <strong>{{ $payout->vendor->store_name ?? '—' }}</strong>
                                            <small class="d-block text-muted">{{ $payout->vendor->user->email ?? '' }}</small>
                                        </td>
                                        <td>₹{{ number_format($payout->amount, 2) }}</td>
                                        <td>₹{{ number_format($payout->fee, 2) }}</td>
                                        <td>{{ ucfirst($payout->method) }}</td>
                                        <td>
                                            <span class="badge rounded-pill bg-soft-{{ $payout->status === 'completed' ? 'green' : ($payout->status === 'cancelled' || $payout->status === 'failed' ? 'pink' : 'orange') }}">{{ ucfirst($payout->status) }}</span>
                                        </td>
                                        <td>{{ $payout->processedBy->name ?? '—' }}</td>
                                        <td>
                                            @if($payout->status === 'pending')
                                            <form action="{{ route('admin.payout.approve', $payout->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <button class="btn btn-sm btn-outline-success mb-1" title="Approve"><i class="fa fa-check"></i> Approve</button>
                                            </form>
                                            <form action="{{ route('admin.payout.reject', $payout->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <button class="btn btn-sm btn-outline-danger mb-1" title="Reject"><i class="fa fa-times"></i> Reject</button>
                                            </form>
                                            @elseif($payout->status === 'approved')
                                            <form action="{{ route('admin.payout.process', $payout->id) }}" method="POST" class="d-inline-flex align-items-center gap-1">
                                                @csrf
                                                @method('PUT')
                                                <input type="text" name="gateway_transaction_id" class="form-control form-control-sm" style="width: 120px;" placeholder="Txn ID">
                                                <button class="btn btn-sm btn-outline-primary" title="Mark processed"><i class="fa fa-money-check-alt"></i> Process</button>
                                            </form>
                                            @else
                                            <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted py-4">No payout requests found.</td>
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
