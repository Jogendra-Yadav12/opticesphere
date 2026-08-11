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
                            <h4 class="mb-0">Announcements</h4>
                        </div>
                        <div class="col-12 col-sm-auto text-end">
                            <a href="{{ route('admin.announcement.create') }}" class="btn btn-primary"><i class="fa fa-plus me-2"></i> Add Announcement</a>
                        </div>
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
                                        <th>Title</th>
                                        <th>Audience</th>
                                        <th>Status</th>
                                        <th>Starts</th>
                                        <th>Ends</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($announcements as $announcement)
                                    <tr>
                                        <th scope="row">{{ $announcement->id }}</th>
                                        <td>{{ Str::limit($announcement->title, 50) }}</td>
                                        <td>{{ ucfirst($announcement->audience) }}</td>
                                        <td><span class="badge rounded-pill bg-soft-{{ $announcement->is_published ? 'green' : 'secondary' }}">{{ $announcement->is_published ? 'Published' : 'Draft' }}</span></td>
                                        <td>{{ $announcement->starts_at?->format('d M Y') ?? '—' }}</td>
                                        <td>{{ $announcement->ends_at?->format('d M Y') ?? '—' }}</td>
                                        <td>
                                            <a href="{{ route('admin.announcement.edit', $announcement->id) }}" class="me-3" title="Edit"><i class="far fa-edit text-primary"></i></a>
                                            <form action="{{ route('admin.announcement.destroy', $announcement->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this announcement?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-link p-0 border-0" title="Delete"><i class="far fa-trash-alt text-danger"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">No announcements found.</td>
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
