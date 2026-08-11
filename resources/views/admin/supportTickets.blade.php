@extends('layouts.admin')

@section('content')
@include('admin.nav')

<div class="page-inner">
    <div id="main-wrapper">
        <div class="row align-items-center grid-margin">
            <div class="col-12">
                <div class="card card-white">
                    <div class="card-body">
                        <h4 class="mb-0">Support Tickets</h4>
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
                                        <th>Subject</th>
                                        <th>Customer</th>
                                        <th>Priority</th>
                                        <th>Status</th>
                                        <th>Assigned To</th>
                                        <th>Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($tickets as $ticket)
                                    <tr>
                                        <th scope="row">{{ $ticket->id }}</th>
                                        <td>{{ Str::limit($ticket->subject, 50) }}</td>
                                        <td>{{ $ticket->user->name ?? '—' }}</td>
                                        <td>
                                            <span class="badge rounded-pill bg-soft-{{ $ticket->priority === 'high' || $ticket->priority === 'urgent' ? 'pink' : 'orange' }}">{{ ucfirst($ticket->priority) }}</span>
                                        </td>
                                        <td>
                                            <span class="badge rounded-pill bg-soft-{{ $ticket->status === 'closed' ? 'secondary' : ($ticket->status === 'open' ? 'orange' : 'green') }}">{{ ucfirst(str_replace('_', ' ', $ticket->status)) }}</span>
                                        </td>
                                        <td>{{ $ticket->assignedTo->name ?? 'Unassigned' }}</td>
                                        <td>{{ $ticket->created_at->format('d M Y') }}</td>
                                        <td>
                                            <a href="{{ route('admin.ticket.show', $ticket->id) }}" class="btn btn-sm btn-outline-primary"><i class="fa fa-eye"></i> View</a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted py-4">No support tickets found.</td>
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
