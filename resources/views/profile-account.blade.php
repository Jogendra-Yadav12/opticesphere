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
                        <li><a href="#">My Profile</a></li>
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

                            @include('account-sidebar', ['active' => 'profile'])

                        </div>

                    </div>
                    <!-- end left panel -->

                    <!-- right panel -->
                    <div class="col-lg-8">

                        <div class="common-block">

                            <div class="inner-title">
                                <h4 class="mb-0">My Profile</h4>
                                <p class="mb-0">Time for a Sharp My profile.</p>
                            </div>

                            @if(session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif

                            <form method="post" action="{{ route('profile.update') }}">
                                @csrf

                                <div class="row">

                                    <div class="col-sm-12">

                                        <div class="form-group">
                                            <label>Your Name</label>
                                            <input type="text" class="form-control" name="name" value="{{ old('name', $user->name) }}" required>
                                            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                                        </div>

                                    </div>

                                </div>

                                <div class="row">

                                    <div class="col-sm-6">

                                        <div class="form-group">
                                            <label>Email Address</label>
                                            <input type="email" class="form-control" name="email" value="{{ old('email', $user->email) }}" required>
                                            @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                                        </div>

                                    </div>

                                    <div class="col-sm-6">

                                        <div class="form-group">
                                            <label>Contact Number</label>
                                            <input type="text" class="form-control" name="phone" value="{{ old('phone', $user->phone) }}">
                                            @error('phone') <small class="text-danger">{{ $message }}</small> @enderror
                                        </div>

                                    </div>

                                </div>

                                <div class="row">

                                    <div class="col-sm-6">

                                        <div class="form-group">
                                            <label>New Password (leave blank to keep current)</label>
                                            <input type="password" class="form-control" name="password" placeholder="New password">
                                            @error('password') <small class="text-danger">{{ $message }}</small> @enderror
                                        </div>

                                    </div>

                                    <div class="col-sm-6">

                                        <div class="form-group">
                                            <label>Re-Password</label>
                                            <input type="password" class="form-control" name="password_confirmation" placeholder="Confirm new password">
                                        </div>

                                    </div>

                                </div>

                                <button type="submit" class="butn-style2 mt-4">Update Profile</button>

                            </form>

                        </div>

                    </div>
                    <!-- end right panel -->
                </div>
            </div>
        </section>
    
@endsection