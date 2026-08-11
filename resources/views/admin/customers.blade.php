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
                                    <div class="col-12 col-md-5 mb-4 mb-md-0">
                                        <h4 class="mb-0">Customers</h4>
                                    </div>

                                    <div class="col-12 col-md-7">

                                        <div class="row">

                                            <div class="col-md-8 mb-3 mb-md-0">
                                                <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="exampleInputEmail1" placeholder="search by name">
                                            </div>

                                            <div class="col-md-4">
                                                <select class="form-control form-select">
                                                    <option>Order Amount</option>
                                                    <option>Highest To Lowest</option>
                                                    <option>Lowest To Highest</option>
                                                </select>
                                            </div>

                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end row -->

                    <!-- Row -->
                    <div class="row">
                        <div class="col-12 grid-margin">
                            <div class="card card-white">
                                <div class="card-body">
                                    <table class="table">
                                        <thead class="bg-light">
                                            <tr>
                                                <th scope="col">ID</th>
                                                <th scope="col">Customer</th>
                                                <th scope="col">Contacts</th>
                                                <th scope="col">Total Orders</th>
                                                <th scope="col">Status</th>
                                                <th scope="col">Total Amount</th>
                                                <th scope="col">Joining Date</th>
                                                <th scope="col">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($customers as $customer)
                                            <tr>
                                                <th scope="row">{{ $customer->id }}</th>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="me-4">
                                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($customer->name) }}&background=random" class="rounded-circle avatar-sm" alt="...">
                                                        </div>
                                                        <div>
                                                            <h6>{{ $customer->name }}</h6>
                                                            <span>{{ $customer->email }}</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $customer->phone ?? 'N/A' }}</td>
                                                <td>{{ $customer->orders ? $customer->orders->count() : 0 }}</td>
                                                <td><span class="badge rounded-pill bg-soft-green">Active</span></td>
                                                <td>₹{{ number_format($customer->orders ? $customer->orders->sum('total_amount') : 0, 2) }}</td>
                                                <td>{{ $customer->created_at->format('d M Y') }}</td>
                                                <td>
                                                    <a href="{{ route('admin.customer.edit', $customer->id) }}" class="me-3" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                                                        <i class="far fa-edit text-primary"></i>
                                                    </a>
                                                    <form action="{{ route('admin.customer.destroy', $customer->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this customer?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-link p-0 border-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">
                                                            <i class="far fa-trash-alt text-danger"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="8" class="text-center py-4 text-muted">No customers found.</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Row -->
                </div>
    
@endsection