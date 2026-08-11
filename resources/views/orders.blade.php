@extends('layouts.app')

@section('content')

@include('header')

<!-- PAGE TITLE
        ================================================== -->
        <section class="page-title-section">
            <div class="container">

                <div class="breadcrumbs-info">
                    <ul class="ps-0">
                        <li><a href="home-shop-1.html">Home</a></li>
                        <li><a href="#">My Orders</a></li>
                    </ul>
                </div>

            </div>
        </section>
        
        <!-- ACCOUNT ORDERS
        ================================================== -->
        <section class="md">
            <div class="container">
                <div class="row justify-content-center">

                    <!-- left panel -->
                    <div class="col-lg-4 col-sm-9 mb-2-3 mb-lg-0">

                        <div class="account-pannel">

                            @include('account-sidebar', ['active' => 'orders'])

                        </div>

                    </div>
                    <!-- end left panel -->

                    <!-- right panel -->
                    <div class="col-lg-8">

                        <div class="common-block">

                            <div class="inner-title">
                                <h4 class="mb-0">My Orders</h4>
                            </div>

                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Order</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th>Total</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($orders as $order)
                                    <tr>
                                        <th>
                                            #{{ $order->id }}
                                        </th>
                                        <td>
                                            {{ $order->created_at->format('d F Y') }}
                                        </td>
                                        <td>
                                            @if($order->status == 'pending')
                                                <span class="fas fa-circle text-warning small mr-1"></span> Pending
                                            @elseif($order->status == 'completed')
                                                <span class="fas fa-circle text-success small mr-1"></span> Completed
                                            @elseif($order->status == 'cancelled')
                                                <span class="fas fa-circle text-danger small mr-1"></span> Cancelled
                                            @else
                                                <span class="fas fa-circle text-primary small mr-1"></span> {{ ucfirst($order->status) }}
                                            @endif
                                        </td>
                                        <td>₹{{ number_format($order->total_amount, 2) }}</td>
                                        <td>
                                            <a href="{{ route('order.detail', $order->id) }}" class="text-primary">View</a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No orders found.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $orders->links('pagination::bootstrap-4') }}
                        </div>
                        </div>

                    </div>
                    <!-- end right panel -->
                </div>
            </div>
        </section>
    
@endsection