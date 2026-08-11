@extends('layouts.admin')

@section('content')
@include('admin.nav')

<div class="page-inner">
    <div id="main-wrapper">
        <div class="row align-items-center grid-margin">
            <div class="col-12">
                <div class="card card-white">
                    <div class="card-body">
                        <h4 class="mb-0">Newsletter Subscribers</h4>
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
                                        <th>Email</th>
                                        <th>Status</th>
                                        <th>Subscribed At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($subscribers as $subscriber)
                                    <tr>
                                        <th scope="row">{{ $subscriber->id }}</th>
                                        <td>{{ $subscriber->email }}</td>
                                        <td><span class="badge rounded-pill bg-soft-{{ $subscriber->is_subscribed ? 'green' : 'secondary' }}">{{ $subscriber->is_subscribed ? 'Subscribed' : 'Unsubscribed' }}</span></td>
                                        <td>{{ $subscriber->created_at->format('d M Y') }}</td>
                                        <td>
                                            <form action="{{ route('admin.newsletter.destroy', $subscriber->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Remove this subscriber?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Remove"><i class="far fa-trash-alt"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">No subscribers found.</td>
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
