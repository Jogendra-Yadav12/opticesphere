@extends('layouts.app')

@section('content')

@include('header')

@php
    $storeName = \App\Models\Setting::where('key', 'store_name')->value('value') ?? 'Optic Sphere';
@endphp

 <!-- PAGE TITLE
        ================================================== -->
        <section class="page-title-section">
            <div class="container">

                <div class="breadcrumbs-info">
                    <ul class="ps-0">
                        <li><a href="home-shop-1.html">Home</a></li>
                        <li><a href="#">About Us</a></li>
                    </ul>
                </div>

            </div>
        </section>
        
        <!-- ABOUT
        ================================================== -->
        <section class="md">
    <div class="container">

        <div class="row justify-content-center mb-5">
            <div class="col-lg-10 text-center">

                <div class="text-center mb-1-9">
                    <h2 class="mb-0">Who We Are</h2>
                </div>

                <p class="lead mb-0 w-md-80 w-95 mx-auto">{{ $storeName }} is an online sunglasses and opticals store. Our owner runs the store, and multiple trusted sellers list and sell their eyewear products here, so customers can shop a wide range of sunglasses, frames, lenses and optical accessories from a single place. We carefully verify every seller to keep quality high and prices fair. Please note that no return option is available, so check every product carefully before you buy.</p></div>
        </div>
        <div class="row text-center">
            <div class="col-md-4 mb-4 mb-md-0">
                <div class="border px-1-6 py-1-9 p-lg-1-9 h-100">
                    <i class="ti-headphone-alt display-18"></i>
                    <h3 class="h5 my-3 letter-spacing-1">Sellers & Buyers</h3>
                    <p class="w-lg-80 mx-auto mb-0">Sellers can list and sell their sunglasses and optical products, and customers can buy them easily.</p>
                </div>
            </div>
            <div class="col-md-4 mb-4 mb-md-0">
                <div class="border px-1-6 py-1-9 p-lg-1-9 h-100">
                    <i class="display-18 font-weight-600" style="font-style:normal;">₹</i>
                    <h3 class="h5 my-3 letter-spacing-1">No Return</h3>
                    <p class="w-lg-80 mx-auto mb-0">All sales are final. No return or refund option is available, so choose your eyewear carefully.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="border px-1-6 py-1-9 p-lg-1-9 h-100">
                    <i class="ti-truck display-18"></i>
                    <h3 class="h5 my-3 letter-spacing-1">Fast Delivery</h3>
                    <p class="w-lg-80 mx-auto mb-0">We deliver your order quickly and safely to your doorstep.</p>
                </div>
            </div>
        </div>
    </div>
</section>
        
        <!-- COUNTER
        ================================================== -->
        <section class="bg-light md">

            <div class="container">

                <div class="row">
                    <div class="col-lg-4 col-sm-4 counter-style-one mb-1-9 mb-sm-0">
                        <div class="text-center">
                            <div class="icon mb-2 mb-md-0"><span class="ti-user"></span></div>
                            <div class="title">
                                <h4 class="countup mb-0 font-weight-600 display-15">{{ $sellersCount }}</h4>
                                <span>Sellers</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-4 counter-style-one mb-1-9 mb-sm-0">
                        <div class="text-center">
                            <div class="icon mb-2 mb-md-0"><span class="ti-layout-grid2"></span></div>
                            <div class="title">
                                <h4 class="countup mb-0 font-weight-600 display-15">{{ $categoriesCount }}</h4>
                                <span>Categories</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-4 counter-style-one">
                        <div class="text-center">
                            <div class="icon mb-2 mb-md-0"><span class="ti-gift"></span></div>
                            <div class="title">
                                <h4 class="countup mb-0 font-weight-600 display-15">{{ $productsCount }}</h4>
                                <span>Products</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </section>
        
        
        <!-- SELLERS
        ================================================== -->
        <section class="md">
            <div class="container">

                <div class="text-center mb-1-9 mb-lg-2-3">
                    <h2 class="mb-0">Our Sellers</h2>
                </div>

                <div class="owl-carousel owl-theme sellers-carousel">
                    @forelse($vendors as $vendor)
                    <div class="item">
                        <a href="{{ route('store', $vendor->slug ?: $vendor->id) }}" class="d-block text-decoration-none">
                        <div class="categoty-style2 text-center">
                            <div class="category-icon mb-4">
                                <img src="{{ $vendor->logo ? Storage::url('images/logos/'.$vendor->logo) : default_image() }}" alt="{{ $vendor->store_name }}">
                            </div>
                            <h3 class="font-weight-500 mb-1">{{ $vendor->store_name }}</h3>
                            <div class="seller-rating"><i class="fas fa-star"></i> {{ number_format((float) $vendor->rating_avg, 1) }}</div>
                        </div>
                        </a>
                    </div>
                    @empty
                    <p>No sellers found.</p>
                    @endforelse
                </div>
            </div>
        </section>
        
    @endsection