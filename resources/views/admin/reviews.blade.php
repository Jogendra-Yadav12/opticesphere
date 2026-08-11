@extends('layouts.admin')

@section('content')
@include('admin.nav')

<div class="page-inner">
    <div id="main-wrapper">
        <div class="row align-items-center grid-margin">
            <div class="col-12">
                <div class="card card-white">
                    <div class="card-body">
                        <h4 class="mb-0">Reviews</h4>
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
                                        <th>Customer</th>
                                        <th>Item</th>
                                        <th>Rating</th>
                                        <th>Review</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($reviews as $review)
                                    <tr>
                                        <th scope="row">{{ $review->id }}</th>
                                        <td>{{ $review->user->name ?? '—' }}</td>
                                        <td>
                                            @if($review->reviewable && method_exists($review->reviewable, 'name'))
                                                {{ $review->reviewable->name }}
                                            @elseif($review->reviewable && method_exists($review->reviewable, 'store_name'))
                                                {{ $review->reviewable->store_name }}
                                            @else
                                                <span class="text-muted">#{{ $review->reviewable_id }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fa fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-muted' }}"></i>
                                            @endfor
                                        </td>
                                        <td>
                                            @if($review->title)<strong>{{ $review->title }}</strong><br>@endif
                                            {{ Str::limit($review->body, 60) }}
                                        </td>
                                        <td>
                                            <span class="badge rounded-pill bg-soft-{{ $review->status === 'approved' ? 'green' : ($review->status === 'rejected' ? 'pink' : 'orange') }}">{{ ucfirst($review->status) }}</span>
                                        </td>
                                        <td>{{ $review->created_at->format('d M Y') }}</td>
                                        <td>
                                            <a href="{{ route('admin.review.edit', $review->id) }}" class="btn btn-sm btn-outline-primary" title="Edit"><i class="far fa-edit"></i></a>
                                            @if($review->status !== 'approved')
                                            <form action="{{ route('admin.review.approve', $review->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <button class="btn btn-sm btn-outline-success" title="Approve"><i class="fa fa-check"></i></button>
                                            </form>
                                            @endif
                                            @if($review->status !== 'rejected')
                                            <form action="{{ route('admin.review.reject', $review->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <button class="btn btn-sm btn-outline-danger" title="Reject"><i class="fa fa-times"></i></button>
                                            </form>
                                            @endif
                                            <form action="{{ route('admin.review.destroy', $review->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this review?');">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-outline-secondary" title="Delete"><i class="far fa-trash-alt"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted py-4">No reviews found.</td>
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
