<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Responsive Admin Dashboard Template">
    
    <title>EyeClinic - Secure Staff Login</title>

    <!-- Styles -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,500,700,800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/all.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}" />
    
    <style>
        .login-page {
            background-color: #f4f5f9;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-box-container {
            border: none;
            box-shadow: 0 10px 30px 0 rgba(17, 38, 146, 0.05);
            border-radius: 10px;
        }
    </style>
</head>
<body class="login-page">
    
    <div class="container">
        <div class="row justify-content-md-center">
            <div class="col-md-12 col-lg-5">
                <div class="card login-box-container">
                    <div class="card-body p-5">
                        <div class="authent-logo text-center mb-4">
                            <h2 class="fw-bold text-primary mb-1">EyeClinic Workspace</h2>
                            <p class="text-muted">Admin & Seller Secure Access</p>
                        </div>

                        <form method="POST" action="{{ route('admin.login.submit') }}">
                            @csrf
                            
                            @if($errors->any())
                                <div class="alert alert-danger">
                                    {{ $errors->first() }}
                                </div>
                            @endif

                            <div class="mb-4">
                                <label class="form-label">Work Email Address</label>
                                <input type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autofocus>
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label">Password</label>
                                <input type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" name="password" required>
                            </div>
                            
                            <div class="mb-4 form-check">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember">Remember my device</label>
                            </div>
                            
                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-primary btn-lg">Secure Login</button>
                            </div>
                            
                            <div class="text-center mt-4 text-muted">
                                <small>&copy; {{ date('Y') }} EyeClinic. All rights reserved.</small>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</body>
</html>
