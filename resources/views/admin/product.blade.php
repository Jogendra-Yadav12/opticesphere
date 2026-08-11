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
                                        <h4 class="mb-4 mb-sm-0 text-center text-sm-start">Products</h4>
                                    </div>
                                    <div class="col-12 col-sm-auto text-end mb-3 mb-sm-0">
                                        @php $routePrefix = auth()->user()->role === 'admin' ? 'admin.' : 'seller.'; @endphp
                                        @if(auth()->user()->role !== 'admin')
                                            @if($productLimit > 0)
                                                <span class="badge bg-info me-2">{{ $productCount }} / {{ $productLimit }} products used</span>
                                            @else
                                                <span class="badge bg-success me-2">Unlimited products</span>
                                            @endif
                                        @endif
                                        @php $limitReached = auth()->user()->role !== 'admin' && $productLimit > 0 && $productCount >= $productLimit; @endphp
                                        <a href="{{ route($routePrefix . 'product.create') }}" class="btn btn-primary {{ $limitReached ? 'disabled' : '' }}" {{ $limitReached ? 'aria-disabled="true"' : '' }}><i class="fa fa-plus me-2"></i> Add Product</a>
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
                                                    <th scope="col">Seller</th>
                                                    <th scope="col">Price</th>
                                                    <th scope="col">Stock</th>
                                                    <th scope="col">Category</th>
                                                    <th scope="col">Status</th>
                                                    <th scope="col">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php $routePrefix = auth()->user()->role === 'admin' ? 'admin.' : 'seller.'; @endphp
                                                @forelse ($products as $key => $product)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>
                                                        <img src="{{ $product->image ? asset('images/products/'.$product->image) : 'https://ui-avatars.com/api/?name='.urlencode($product->name).'&background=random' }}" alt="..." style="width:70px;height:70px;border-radius:10%" onerror="this.onerror=null; this.src='{{ asset('images/'.$product->image) }}'">
                                                    </td>
                                                    <td>{{ $product->name }}</td>
                                                    <td>
                                                        @if($product->vendor)
                                                            <span class="badge bg-info">{{ $product->vendor->store_name }}</span>
                                                            <span class="d-block small text-muted">{{ $product->vendor->user->name ?? '' }}</span>
                                                        @else
                                                            <span class="text-muted">—</span>
                                                        @endif
                                                    </td>
                                                    <td>₹{{ number_format($product->price, 2) }}</td>
                                                    <td>
                                                        @if($product->stock > 10)
                                                            <span class="badge bg-success">{{ $product->stock }} in stock</span>
                                                        @elseif($product->stock > 0)
                                                            <span class="badge bg-warning">{{ $product->stock }} in stock</span>
                                                        @else
                                                            <span class="badge bg-danger">Out of stock</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $product->category ? $product->category->name : 'N/A' }}</td>
                                                    <td>
                                                        @if($product->approval_status == 'approved')
                                                            <span class="badge bg-success">Approved</span>
                                                        @elseif($product->approval_status == 'rejected')
                                                            <span class="badge bg-danger">Rejected</span>
                                                        @else
                                                            <span class="badge bg-warning text-dark">Pending</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a href="{{ route($routePrefix . 'product.edit', $product->id) }}" class="me-2" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                                                            <i class="far fa-edit text-primary"></i>
                                                        </a>
                                                        <form action="{{ route($routePrefix . 'product.destroy', $product->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this product?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-link p-0 me-2" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">
                                                                <i class="far fa-trash-alt text-danger"></i>
                                                            </button>
                                                        </form>
                                                        @if(auth()->user()->role === 'admin')
                                                            @if($product->approval_status !== 'approved')
                                                            <form action="{{ route('admin.product.approve', $product->id) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                @method('PUT')
                                                                <button type="submit" class="btn btn-sm btn-success me-1 p-1" title="Approve"><i class="fas fa-check"></i></button>
                                                            </form>
                                                            @endif
                                                            @if($product->approval_status !== 'rejected')
                                                            <form action="{{ route('admin.product.reject', $product->id) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                @method('PUT')
                                                                <button type="submit" class="btn btn-sm btn-danger p-1" title="Reject"><i class="fas fa-times"></i></button>
                                                            </form>
                                                            @endif
                                                        @endif
                                                    </td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="8" class="text-center py-5">
                                                        <h5 class="text-muted">No products found. <a href="{{ route($routePrefix . 'product.create') }}">Add your first product</a></h5>
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
                    <!-- end row -->
                </div>
    
@endsection