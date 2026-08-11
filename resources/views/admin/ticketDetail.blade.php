@extends('layouts.admin')

@section('content')
@include('admin.nav')

<div class="page-inner">
    <div id="main-wrapper">
        <div class="row align-items-center grid-margin">
            <div class="col-12">
                <div class="card card-white">
                    <div class="card-body row align-items-center">
                        <div class="col-12 col-md-7 mb-3 mb-md-0">
                            <h4 class="mb-0">Ticket #{{ $ticket->id }} — {{ $ticket->subject }}</h4>
                            <small class="text-muted">By {{ $ticket->user->name ?? '—' }} ({{ $ticket->user->email ?? '' }})</small>
                        </div>
                        <div class="col-12 col-md-5 text-md-end">
                            <a href="{{ route('admin.ticket.index') }}" class="btn btn-secondary"><i class="fa fa-arrow-left me-2"></i> Back</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-xl-3">
            <div class="col-xl-8 grid-margin">
                <div class="card card-white">
                    <div class="card-heading clearfix">
                        <h4 class="card-title">Conversation</h4>
                    </div>
                    <div class="card-body">
                        <div class="mb-4 p-3 rounded" style="background: #f7f9fc;">
                            <div class="d-flex justify-content-between">
                                <strong>{{ $ticket->user->name ?? 'Customer' }}</strong>
                                <small class="text-muted">{{ $ticket->created_at->format('d M, h:i A') }}</small>
                            </div>
                            <p class="mb-0 mt-2">{{ $ticket->message }}</p>
                        </div>

                        @forelse($ticket->replies as $reply)
                        <div class="mb-4 p-3 rounded {{ $reply->is_staff ? 'bg-soft-primary' : 'bg-light' }}" style="{{ $reply->is_staff ? 'background:#eaf2ff;' : '' }}">
                            <div class="d-flex justify-content-between">
                                <strong>{{ $reply->replier->name ?? 'User' }} {{ $reply->is_staff ? '(Staff)' : '' }}</strong>
                                <small class="text-muted">{{ $reply->created_at->format('d M, h:i A') }}</small>
                            </div>
                            <p class="mb-0 mt-2">{{ $reply->body }}</p>
                        </div>
                        @empty
                        <p class="text-muted">No replies yet.</p>
                        @endforelse

                        <form action="{{ route('admin.ticket.reply', $ticket->id) }}" method="POST" class="mt-4">
                            @csrf
                            <label class="form-label fw-bold">Reply</label>
                            <textarea name="body" rows="3" class="form-control" required placeholder="Write a reply..."></textarea>
                            <button type="submit" class="btn btn-primary mt-3"><i class="fa fa-paper-plane me-1"></i> Send Reply</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 grid-margin">
                <div class="card card-white">
                    <div class="card-heading clearfix">
                        <h4 class="card-title">Details</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.ticket.updateStatus', $ticket->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label class="form-label fw-bold">Status</label>
                                <select class="form-select form-control" name="status">
                                    @foreach(['open', 'answered', 'on_hold', 'closed'] as $st)
                                        <option value="{{ $st }}" {{ $ticket->status === $st ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $st)) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Assign To</label>
                                <select class="form-select form-control" name="assigned_to">
                                    <option value="">Unassigned</option>
                                    @foreach($admins as $admin)
                                        <option value="{{ $admin->id }}" {{ $ticket->assigned_to == $admin->id ? 'selected' : '' }}>{{ $admin->name }} ({{ $admin->role }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Priority</label>
                                <input type="text" class="form-control" value="{{ ucfirst($ticket->priority) }}" disabled>
                            </div>
                            @if($ticket->order_id)
                            <div class="mb-3">
                                <label class="form-label fw-bold">Related Order</label>
                                <a href="{{ route('admin.order.show', $ticket->order_id) }}" class="d-block">#{{ $ticket->order_id }}</a>
                            </div>
                            @endif
                            <button type="submit" class="btn btn-primary w-100">Update Ticket</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
