@extends('layouts.admin')

@section('content')

@include('admin.nav')

 <!-- PAGE INNER
            ================================================== -->
            <div class="page-inner">

                <!-- PAGE MAIN WRAPPER
                ================================================== -->
                <div id="main-wrapper">
                    <!-- row -->
                    <div class="row align-items-center grid-margin">
                        <div class="col-12">
                            <div class="card card-white">
                                <div class="card-body row align-items-center">
                                    <div class="col-12 col-sm">
                                        <h4 class="mb-4 mb-sm-0 text-center text-sm-start">Category</h4>
                                    </div>
                                    <div class="col-12 col-sm-auto text-end mb-3 mb-sm-0">
                                        <a href="{{ route('admin.category.create') }}" class="btn btn-primary"><i class="fa fa-plus me-2"></i> Add Category</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end row -->

                    <!-- row -->
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
                                                    <th scope="col">Name</th>
                                                    <th scope="col">Status</th>
                                                    <th scope="col">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($category as $key=>$value)
                                                <tr>
                                                    <td>{{$i}}</td>
                                                    <td>
                                                        <img src="{{ $value->img && $value->img !== 'default.png' ? Storage::url('images/'.$value->img) : 'https://ui-avatars.com/api/?name='.urlencode($value->name).'&background=random' }}" alt="..." style="width:70px;height:70px;border-radius:50%">
                                                    </td>
                                                    <td>{{$value->name}}</td>
                                                    <td>
                                                        @if($value->status == 'active')
                                                            <span class="badge bg-success">Active</span>
                                                        @else
                                                            <span class="badge bg-secondary">Disabled</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                    <a href="{{ route('admin.category.edit', $value->id) }}" class="me-3" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                                                        <i class="far fa-edit text-primary"></i>
                                                    </a>
                                                    <form action="{{ route('admin.category.destroy', $value->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this category?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-link p-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">
                                                            <i class="far fa-trash-alt text-danger"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                                </tr>
                                                @php $i++; @endphp
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    @if($category->hasPages())
                                        <div class="d-flex justify-content-center mt-3">
                                            {{ $category->links('pagination::bootstrap-5') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end row -->
                </div>
    
@endsection