<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title>EyeClinic - Seller Partner Login</title>

    <!-- Styles -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,500,700,800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/all.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}" />
    
    <style>
        .login-page {
            background-color: #2b2d42;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-box-container {
            border: none;
            box-shadow: 0 10px 30px 0 rgba(0, 0, 0, 0.2);
            border-radius: 12px;
            background-color: #fff;
        }
        .btn-seller {
            background-color: #ff6b6b;
            border-color: #ff6b6b;
            color: #fff;
        }
        .btn-seller:hover {
            background-color: #ee5253;
            border-color: #ee5253;
            color: #fff;
        }
        .text-seller {
            color: #ff6b6b;
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
                            <h2 class="fw-bold text-seller mb-1">Seller Portal</h2>
                            <p class="text-muted">Manage your store on EyeClinic</p>
                        </div>

                        <form method="POST" action="{{ route('seller.login.submit') }}">
                            @csrf
                            
                            @if($errors->any())
                                <div class="alert alert-danger">
                                    {{ $errors->first() }}
                                </div>
                            @endif

                            @if(session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif

                            <div class="mb-4">
                                <label class="form-label">Seller Email Address</label>
                                <input type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autofocus placeholder="name@yourshop.com">
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label">Password</label>
                                <input type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" name="password" required>
                            </div>
                            
                            <div class="mb-4 form-check">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember">Keep me logged in</label>
                            </div>
                            
                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-seller btn-lg">Login to Store</button>
                            </div>
                            
                            <div class="text-center mt-4 text-muted">
                                <small>New seller? <a href="{{ route('seller.register') }}" class="text-seller fw-bold">Create your store here</a></small>
                            </div>

                            <div class="text-center mt-3 text-muted">
                                <small>&copy; {{ date('Y') }} EyeClinic Partners.</small>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</body>
</html>
