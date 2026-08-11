@extends('layouts.admin')

@section('content')

@include('admin.nav')

@php $orderShowRoute = auth()->user()->role === 'admin' ? 'admin.order.show' : 'seller.order.show'; @endphp

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
                                    <div class="col-12 col-md-4 mb-4 mb-md-0">
                                        <h4 class="mb-0">Orders</h4>
                                    </div>

                                    <div class="col-12 col-md-8">
                                        <div class="row">
                                            <div class="col-md-3 mb-3 mb-md-0">
                                                <select class="form-control form-select">
                                                    <option>Status</option>
                                                    <option>Deliveres</option>
                                                    <option>Pending</option>
                                                    <option>Processing</option>
                                                    <option>Failed</option>
                                                </select>
                                            </div>

                                            <div class="col-md-3 mb-3 mb-md-0">
                                                <select class="form-control form-select">
                                                    <option>Order Limits</option>
                                                    <option>Last 7 Orders</option>
                                                    <option>Last 15 Orders</option>
                                                    <option>Last 30 Orders</option>
                                                </select>
                                            </div>

                                            <div class="col-md-6">
                                                <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="exampleInputEmail1" placeholder="search by name" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end row -->

                    <!-- row -->
                    <div class="row">
                        <div class="col-lg-12 col-md-12 grid-margin">
                            <div class="card card-white h-100">
                                <div class="card-heading clearfix">
                                    <h4 class="card-title">Latest Transaction</h4>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table mb-0">
                                            <thead class="bg-light">
                                                <tr>
                                                    <th>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="customCheck1">
                                                            <label class="custom-control-label" for="customCheck1">&nbsp;</label>
                                                        </div>
                                                    </th>
                                                    <th>Order ID</th>
                                                    <th>Billing Name</th>
                                                    <th>Date</th>
                                                    <th>Total</th>
                                                    <th>Payment Status</th>
                                                    <th>Payment Method</th>
                                                    <th>View Details</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($orders as $order)
                                                <tr>
                                                    <td>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="customCheck{{ $order->id }}">
                                                            <label class="custom-control-label" for="customCheck{{ $order->id }}">&nbsp;</label>
                                                        </div>
                                                    </td>
                                                    <td><a href="{{ route($orderShowRoute, $order->id) }}">#SK{{ $order->id }}</a> </td>
                                                    <td>{{ $order->user ? $order->user->name : 'Guest' }}</td>
                                                    <td>
                                                        {{ $order->created_at->format('d M, Y') }}
                                                    </td>
                                                    <td>
                                                        ₹{{ number_format($order->total_amount, 2) }}
                                                    </td>
                                                    <td>
                                                        @if($order->payment_status == 'paid' || $order->payment_status == 'completed')
                                                            <span class="badge rounded-pill bg-soft-green">{{ ucfirst(str_replace('_', ' ', $order->payment_status)) }}</span>
                                                        @elseif($order->payment_status == 'refunded' || $order->payment_status == 'failed')
                                                            <span class="badge rounded-pill bg-soft-pink">{{ ucfirst(str_replace('_', ' ', $order->payment_status)) }}</span>
                                                        @else
                                                            <span class="badge rounded-pill bg-soft-orange">{{ ucfirst(str_replace('_', ' ', $order->payment_status)) }}</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if(strtolower($order->payment_method) == 'paypal')
                                                            <i class="fab fa-cc-paypal me-1"></i> Paypal
                                                        @elseif(strtolower($order->payment_method) == 'visa')
                                                            <i class="fab fa-cc-visa me-1"></i> Visa
                                                        @else
                                                            <i class="fab fa-cc-mastercard me-1"></i> {{ ucfirst($order->payment_method ?? 'Mastercard') }}
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a href="{{ route($orderShowRoute, $order->id) }}" class="btn btn-primary btn-sm btn-rounded">
                                                            View Details
                                                        </a>
                                                    </td>
                                                    @if(auth()->user()->role === 'admin')
                                                    <td>
                                                        <a href="{{ route('admin.order.show', $order->id) }}" class="me-3" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                                                            <i class="far fa-edit text-primary"></i>
                                                        </a>
                                                        <a href="#" onclick="event.preventDefault(); document.getElementById('delete-order-{{ $order->id }}').submit();" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">
                                                            <i class="far fa-trash-alt text-danger"></i>
                                                        </a>
                                                        <form id="delete-order-{{ $order->id }}" action="{{ route('admin.order.destroy', $order->id) }}" method="POST" class="d-none">
                                                            @csrf
                                                            @method('DELETE')
                                                        </form>
                                                    </td>
                                                    @endif
                                                </tr>
                                                @endforeach

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