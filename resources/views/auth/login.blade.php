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
                        <li><a href="#">Login</a></li>
                    </ul>
                </div>

            </div>
        </section>

<section class="md">            
                <div class="col-lg-6 mb-1-9 mb-lg-0 container">

                        <div class="common-block">

                            <div class="inner-title">
                                <h4 class="mb-0">Login</h4>
                                <p class="mb-0">Everything is simple with Login.</p>
                            </div>

                            <form method="POST" action="{{ route('login') }}">
                                @csrf

                                <div class="row">

                                    <div class="col-sm-12">

                                        <div class="form-group">
                                            <label>Email Address</label>
                                            <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Your email here">
                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                    </div>

                                    <div class="col-sm-12">

                                        <div class="form-group">
                                            <label>Password </label>
                                            <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Your password here">
                                            @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                    </div>

                                </div>

                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="remember" id="login-remember" {{ old('remember') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="login-remember">Keep me signed in</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 text-start text-md-end">
                                        @if (Route::has('password.request'))
                                            <a href="{{ route('password.request') }}" class="m-link-muted">Forgot password?</a>
                                        @endif
                                    </div>

                                </div>

                                <button type="submit" class="butn-style2 mt-4">Login</button>
                                
                                <div class="mt-4">
                                    <p>Don't have an account? <a href="{{ route('register') }}" class="text-primary">Register here</a></p>
                                </div>

                            </form>

                        </div>

                    </div>
</section>

@endsection
