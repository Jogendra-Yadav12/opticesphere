<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title>EyeClinic - Become a Seller</title>

    <!-- Styles -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,500,700,800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/all.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}" />
    
    <style>
        .login-page {
            background-color: #2b2d42;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 0;
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
            <div class="col-md-12 col-lg-6">
                <div class="card login-box-container">
                    <div class="card-body p-5">
                        <div class="authent-logo text-center mb-4">
                            <h2 class="fw-bold text-seller mb-1">Seller Registration</h2>
                            <p class="text-muted">Open your store on EyeClinic</p>
                            <div class="d-flex justify-content-center align-items-center small mt-3">
                                <span class="badge bg-success px-3 py-2">Step 1: Account Details</span>
                                <span class="mx-2 text-muted">&rarr;</span>
                                <span class="text-muted">Step 2: Choose a Plan</span>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('seller.register.submit') }}">
                            @csrf
                            
                            @if($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Your Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $draft['name'] ?? '') }}" required placeholder="e.g. Rahul Sharma">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Phone</label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone', $draft['phone'] ?? '') }}" placeholder="+91 98765 43210">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email Address <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $draft['email'] ?? '') }}" required placeholder="name@yourshop.com">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Store / Business Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('store_name') is-invalid @enderror" name="store_name" value="{{ old('store_name', $draft['store_name'] ?? '') }}" required placeholder="e.g. Sharma Opticals">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Store Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" name="description" rows="3" placeholder="Tell customers about your store...">{{ old('description', $draft['description'] ?? '') }}</textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Address</label>
                                    <input type="text" class="form-control" name="address" value="{{ old('address', $draft['address'] ?? '') }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">City</label>
                                    <input type="text" class="form-control" name="city" value="{{ old('city', $draft['city'] ?? '') }}">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">State</label>
                                    <input type="text" class="form-control" name="state" value="{{ old('state', $draft['state'] ?? '') }}">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Postal Code</label>
                                    <input type="text" class="form-control" name="postal_code" value="{{ old('postal_code', $draft['postal_code'] ?? '') }}">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Country</label>
                                    <input type="text" class="form-control" name="country" value="{{ old('country', $draft['country'] ?? 'India') }}">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Password <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" required minlength="8" placeholder="Min. 8 characters">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" name="password_confirmation" required placeholder="Repeat password">
                                </div>
                            </div>

                            <div class="alert alert-info py-2 small">
                                Your account will be reviewed and approved by our team before your store goes live.
                            </div>

                            <div class="d-grid mt-3">
                                <button type="submit" class="btn btn-seller btn-lg">Continue to Choose a Plan</button>
                            </div>

                            <div class="text-center mt-4 text-muted">
                                <small>Already have a seller account? <a href="{{ route('seller.login') }}" class="text-seller fw-bold">Login here</a></small>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</body>
</html>
