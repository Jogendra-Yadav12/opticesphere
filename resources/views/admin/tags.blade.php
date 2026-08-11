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
                            <h4 class="mb-0">Tags</h4>
                        </div>
                        <div class="col-12 col-sm-auto text-end">
                            <a href="{{ route('admin.tag.create') }}" class="btn btn-primary"><i class="fa fa-plus me-2"></i> Add Tag</a>
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
                                        <th>Name</th>
                                        <th>Slug</th>
                                        <th>Products</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($tags as $tag)
                                    <tr>
                                        <th scope="row">{{ $tag->id }}</th>
                                        <td><span class="badge bg-light text-dark">{{ $tag->name }}</span></td>
                                        <td>{{ $tag->slug }}</td>
                                        <td>{{ $tag->products_count }}</td>
                                        <td>
                                            <a href="{{ route('admin.tag.edit', $tag->id) }}" class="me-3" title="Edit"><i class="far fa-edit text-primary"></i></a>
                                            <form action="{{ route('admin.tag.destroy', $tag->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this tag?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-link p-0 border-0" title="Delete"><i class="far fa-trash-alt text-danger"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">No tags found.</td>
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
