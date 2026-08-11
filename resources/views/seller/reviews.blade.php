@extends('layouts.admin')

@section('content')

@include('admin.nav')

<div class="page-inner">
    <div id="main-wrapper">
        <div class="row align-items-center grid-margin">
            <div class="col-12">
                <div class="card card-white">
                    <div class="card-body">
                        <h4 class="mb-0">Store Reviews</h4>
                        <small class="text-muted">{{ $vendor->store_name }}</small>
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
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fa fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-muted' }}"></i>
                                            @endfor
                                        </td>
                                        <td>
                                            @if($review->title)<strong>{{ $review->title }}</strong><br>@endif
                                            {{ Str::limit($review->body, 80) }}
                                            @if($review->replies->count())
                                                <div class="mt-1 text-muted">
                                                    <i class="far fa-comment-dots"></i> {{ $review->replies->count() }} reply(ies)
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge rounded-pill bg-soft-{{ $review->status === 'approved' ? 'green' : ($review->status === 'rejected' ? 'pink' : 'orange') }}">{{ ucfirst($review->status) }}</span>
                                        </td>
                                        <td>{{ $review->created_at->format('d M Y') }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary" type="button" data-bs-toggle="collapse" data-bs-target="#reply-{{ $review->id }}" title="Reply">
                                                <i class="far fa-comment-dots"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr class="collapse" id="reply-{{ $review->id }}">
                                        <td colspan="7" class="bg-light">
                                            <form action="{{ route('seller.reviews.reply', $review->id) }}" method="POST" class="d-flex gap-2 align-items-center">
                                                @csrf
                                                <input type="text" name="body" class="form-control" placeholder="Write a reply..." required>
                                                <button type="submit" class="btn btn-sm btn-primary text-nowrap">Post Reply</button>
                                            </form>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">No reviews for your store yet.</td>
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
