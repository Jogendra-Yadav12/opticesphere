@extends('layouts.app')

@section('content')

@include('header')

@php
    $storeName = \App\Models\Setting::where('key', 'store_name')->value('value') ?? 'Optic Sphere';
    $contactEmail = \App\Models\Setting::where('key', 'contact_email')->value('value') ?? 'support@opticsphere.com';
    $supportPhone = \App\Models\Setting::where('key', 'support_phone')->value('value') ?? '+91 0000000000';
    $websiteUrl = url('/');
    $portfolioUrl = \App\Models\Setting::where('key', 'footer_credit_url')->value('value') ?? 'https://jogendra-yadav.netlify.app/';
    $portfolioName = \App\Models\Setting::where('key', 'footer_credit_text')->value('value') ?? 'Jogendra Yadav';
    $contactPageImage = \App\Models\Setting::where('key', 'contact_page_image')->value('value');
@endphp

<!-- PAGE TITLE
        ================================================== -->
        <section class="page-title-section">
            <div class="container">

                <div class="breadcrumbs-info">
                    <ul class="ps-0">
                        <li><a href="home-shop-1.html">Home</a></li>
                        <li><a href="#">Contact Us</a></li>
                    </ul>
                </div>

            </div>
        </section>
         <!-- CONTACT
        ================================================== -->

        <section class="pt-1-9">
            <div class="container">
                <div class="text-center mx-auto w-md-80 w-95">
                    <h2 class="mb-3">Contact {{ $storeName }}</h2>
                    <p class="lead mb-0">{{ $storeName }} is owned and run by our founder, with products sold by multiple verified sellers. Whether you are a customer or a seller, we are happy to help.</p>
                </div>
            </div>
        </section>

        <section class="md pt-0">
            <div class="container">
                <div class="row">

                    <div class="col-md-4 mb-4 mb-md-0">
                        <div class="contact-info rounded h-100">
                            <div class="contact-icon">
                                <i class="ti-mobile"></i>
                            </div>
                            <h3 class="display-29 font-weight-500 mb-2">Phone Numbers</h3>
                            <ul class="mb-0 list-unstyled">
                                <li><a href="tel:{{ preg_replace('/[^0-9+]/', '', $supportPhone) }}">{{ $supportPhone }}</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4 mb-md-0">
                        <div class="contact-info rounded h-100">
                            <div class="contact-icon">
                                <i class="ti-world"></i>
                            </div>
                            <h3 class="display-29 font-weight-500 mb-2">Website</h3>
                            <ul class="mb-0 list-unstyled">
                                <li><a href="{{ $portfolioUrl }}" target="_blank">{{ $portfolioName }}</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="contact-info rounded h-100">
                            <div class="contact-icon">
                                <i class="ti-email"></i>
                            </div>
                            <h3 class="display-29 font-weight-500 mb-2">Email Address</h3>
                            <ul class="mb-0 list-unstyled">
                                <li><a href="mailto:{{ $contactEmail }}">{{ $contactEmail }}</a></li>
                            </ul>
                        </div>
                    </div>

                </div>
            </div>
        </section>


        <section class="pt-0">
        <div class="container">

            <div class="row justify-content-center">
                <div class="col-lg-5 col-md-6 mb-1-9 mb-md-0">

                    <div class="store-details">
                        <div class="contact-img">
                            <img src="{{ $contactPageImage ? asset('images/'.$contactPageImage) : default_image() }}" alt="..."/>
                        </div>
                        <div class="info-box">
                            <h5>{{ $storeName }}</h5>
                            <ul class="mb-0 list-unstyled">
                                <li class="mb-4">
                                    <div class="d-flex align-top">
                                        <div class="info-icon">
                                            <i class="ti-mobile"></i>
                                        </div>
                                        <div class="ps-4">
                                            <h6 class="info-label">Call us</h6>
                                            <a href="tel:{{ preg_replace('/[^0-9+]/', '', $supportPhone) }}">{{ $supportPhone }}</a>
                                        </div>
                                    </div>
                                </li>
                                <li class="mb-4">
                                    <div class="d-flex align-top">
                                        <div class="info-icon">
                                            <i class="ti-email"></i>
                                        </div>
                                        <div class="ps-4">
                                            <h6 class="info-label">Write us</h6>
                                            <a href="mailto:{{ $contactEmail }}">{{ $contactEmail }}</a>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="d-flex align-top">
                                        <div class="info-icon">
                                            <i class="ti-user"></i>
                                        </div>
                                        <div class="ps-4">
                                            <h6 class="info-label">Portfolio</h6>
                                            <a href="{{ $portfolioUrl }}" target="_blank">{{ $portfolioName }}</a>
                                        </div>
                                    </div>
                                </li>
                            </ul>

                        </div>
                    </div>
                </div>
                    
            </div>

        </div>
    </section>


    <section class="pt-0 md">
        <div class="container">

            <div class="text-center mb-1-9 mb-lg-2-3">
                <h2 class="mb-0">Get In Touch</h2>
            </div>

            <div class="row">
                <div class="col-lg-10 mx-auto">

                    <form>

                        <div class="row">

                            <div class="col-sm-6">

                                <div class="form-group">
                                    <label>Your Name</label>
                                    <input type="text" class="form-control" name="name" placeholder="Your name here"/>
                                </div>

                            </div>

                            <div class="col-sm-6">

                                <div class="form-group">
                                    <label>Your Email</label>
                                    <input type="email" class="form-control" name="email" placeholder="Your email here"/>
                                </div>

                            </div>

                        </div>

                        <div class="row">

                            <div class="col-sm-6">

                                <div class="form-group">
                                    <label>Company Name</label>
                                    <input type="text" class="form-control" name="companyname" placeholder="Your company name"/>
                                </div>

                            </div>

                            <div class="col-sm-6">

                                <div class="form-group">
                                    <label>Contact Number</label>
                                    <input type="text" class="form-control" name="phone" placeholder="+40-123 456 789"/>
                                </div>

                            </div>

                        </div>

                        <div class="row">

                            <div class="col-12 mb-4">

                                <label>Message</label>
                                <div class="form-group mb-1">
                                    <textarea rows="3" class="form-control" placeholder="Tell us a few words"></textarea>
                                </div>

                            </div>

                        </div>

                        <button type="button" class="butn-style2">Send Message</button>

                    </form>

                </div>
            </div>
        </div>
    </section>

@endsection