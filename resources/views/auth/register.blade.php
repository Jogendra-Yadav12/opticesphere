@extends('layouts.app')

@section('content')

@include('header')

<!-- PAGE TITLE
        ================================================== -->
        <section class="page-title-section">
            <div class="container">

                <div class="breadcrumbs-info">
                    <ul class="ps-0">
                        <li><a href="{{ url('/') }}">Home</a></li>
                        <li><a href="#">Register</a></li>
                    </ul>
                </div>

            </div>
        </section>

<section class="md">

                    <div class="col-lg-8 container">

                        <div class="common-block">

                            <div class="inner-title">
                                <h4 class="mb-0">Register</h4>
                                <p class="mb-0">Create an account to shop seamlessly.</p>
                            </div>

                            <form method="POST" action="{{ route('register') }}">
                                @csrf

                                <div class="row">

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Your Name</label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="Your name here">
                                            @error('name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Contact Number (Optional)</label>
                                            <input type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" placeholder="+40-123 456 789">
                                            @error('phone')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                </div>

                                <div class="row">

                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label>Email Address</label>
                                            <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="Your email here">
                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                </div>

                                <div class="row">

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Password</label>
                                            <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="Your password here">
                                            @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Re-Password</label>
                                            <input type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" placeholder="Your re-password here">
                                        </div>
                                    </div>

                                </div>

                                <button type="submit" class="butn-style2 mt-4">Register</button>
                                
                                <div class="mt-4">
                                    <p>Already have an account? <a href="{{ route('login') }}" class="text-primary">Login here</a></p>
                                </div>

                            </form>

                        </div>
                    </div>
        </section>

@endsection
