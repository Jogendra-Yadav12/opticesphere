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
                            <h4 class="mb-0">CMS Pages</h4>
                        </div>
                        <div class="col-12 col-sm-auto text-end">
                            <a href="{{ route('admin.page.create') }}" class="btn btn-primary"><i class="fa fa-plus me-2"></i> Add Page</a>
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
                                        <th>Slug</th>
                                        <th>Status</th>
                                        <th>Updated</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($pages as $page)
                                    <tr>
                                        <th scope="row">{{ $page->id }}</th>
                                        <td>{{ $page->title }}</td>
                                        <td>{{ $page->slug }}</td>
                                        <td><span class="badge rounded-pill bg-soft-{{ $page->status === 'published' ? 'green' : 'secondary' }}">{{ ucfirst($page->status) }}</span></td>
                                        <td>{{ $page->updated_at->format('d M Y') }}</td>
                                        <td>
                                            <a href="{{ route('admin.page.edit', $page->id) }}" class="me-3" title="Edit"><i class="far fa-edit text-primary"></i></a>
                                            <form action="{{ route('admin.page.destroy', $page->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this page?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-link p-0 border-0" title="Delete"><i class="far fa-trash-alt text-danger"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">No pages found.</td>
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
