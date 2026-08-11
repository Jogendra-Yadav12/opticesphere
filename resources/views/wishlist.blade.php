@extends('layouts.app')

@section('content')

@include('header')

<section class="page-title-section">
    <div class="container">
        <div class="breadcrumbs-info">
            <ul class="ps-0">
                <li><a href="{{ url('/') }}">Home</a></li>
                <li><a href="#">Wishlist</a></li>
            </ul>
        </div>
    </div>
</section>

<section class="md">
    <div class="container">
        <div class="row justify-content-center">

            <!-- left panel -->
            <div class="col-lg-4 col-sm-9 mb-2-3 mb-lg-0">
                @include('account-sidebar', ['active' => 'wishlist'])
            </div>
            <!-- end left panel -->

            <!-- right panel -->
            <div class="col-lg-8">
                <div class="common-block">
                    <div class="inner-title">
                        <h4 class="mb-0">My Wishlist</h4>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    @if($items->isEmpty())
                        <div class="text-center">
                            <h3>Your wishlist is empty</h3>
                            <a href="{{ route('shop') }}" class="butn-style2 mt-4">Continue Shopping</a>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-bordered text-center">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Price</th>
                                        <th>Action</th>
                                        <th>Remove</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($items as $item)
                                        @php $price = $item->product->special_price ?? $item->product->price; @endphp
                                        <tr>
                                            <td class="text-start">
                                                <img src="{{ $item->product->image ? asset('images/products/'.$item->product->image) : default_image() }}" alt="..." style="width: 50px; height: 50px; object-fit: cover; margin-right: 15px;">
                                                <a href="{{ route('customer.product.details', $item->product_id) }}">{{ $item->product->name }}</a>
                                            </td>
                                            <td>₹{{ number_format($price, 2) }}</td>
                                            <td>
                                                <a href="{{ route('customer.product.details', $item->product_id) }}" class="butn-style2 small"><span>View Product</span></a>
                                            </td>
                                            <td>
                                                <form action="{{ route('wishlist.remove', $item->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger"><i class="ti-trash"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
            <!-- end right panel -->
        </div>
    </div>
</section>

@endsection
