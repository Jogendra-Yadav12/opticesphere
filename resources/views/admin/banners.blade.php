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
                            <h4 class="mb-4 mb-sm-0 text-center text-sm-start">Banners</h4>
                        </div>
                        <div class="col-12 col-sm-auto text-end mb-3 mb-sm-0">
                            <a href="{{ route('admin.banner.create') }}" class="btn btn-primary"><i class="fa fa-plus me-2"></i> Add Banner</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-12 grid-margin">
                <div class="card card-white">
                    <div class="card-body slimscroll">
                        <div class="table-responsive">
                            <table class="table">
                                <thead class="bg-light">
                                    <tr>
                                        <th scope="col">ID</th>
                                        <th scope="col">Image</th>
                                        <th scope="col">Title</th>
                                        <th scope="col">Sort Order</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($banners as $key => $banner)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>
                                            <img src="{{ Storage::url('images/slider/'.$banner->image_path) }}" alt="..." style="width:100px; height:auto; border-radius:5px">
                                        </td>
                                        <td>
                                            {{ $banner->title }}<br>
                                            <small class="text-muted">{{ $banner->subtitle }}</small>
                                        </td>
                                        <td>{{ $banner->sort_order }}</td>
                                        <td>
                                            @if($banner->status == 'active')
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-secondary">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.banner.edit', $banner->id) }}" class="me-2" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                                                <i class="far fa-edit text-primary"></i>
                                            </a>
                                            <form action="{{ route('admin.banner.destroy', $banner->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this banner?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-link p-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">
                                                    <i class="far fa-trash-alt text-danger"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5">
                                            <h5 class="text-muted">No banners found.</h5>
                                        </td>
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
