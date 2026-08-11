@extends('layouts.admin')

@section('content')

@include('admin.nav')

<div class="page-inner">
    <div id="main-wrapper">
        <div class="row align-items-center grid-margin">
            <div class="col-12">
                <div class="card card-white">
                    <div class="card-body">
                        <h4 class="mb-0">My Customers</h4>
                        <small class="text-muted">Customers who have ordered your products.</small>
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
                                        <th scope="col">ID</th>
                                        <th scope="col">Customer</th>
                                        <th scope="col">Contact</th>
                                        <th scope="col">Orders from You</th>
                                        <th scope="col">Total Amount</th>
                                        <th scope="col">Joining Date</th>
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
                                        <td>{{ $customer->orders->count() }}</td>
                                        <td>₹{{ number_format($customer->orders->sum('total_amount'), 2) }}</td>
                                        <td>{{ $customer->created_at->format('d M Y') }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted">No customers have ordered your products yet.</td>
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
