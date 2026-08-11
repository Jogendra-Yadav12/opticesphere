<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title>EyeClinic - Choose Your Plan</title>

    <!-- Styles -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,500,700,800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/all.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}" />
    
    <style>
        body {
            background-color: #f5f7fb;
            font-family: 'Poppins', sans-serif;
        }
        .plan-page {
            min-height: 100vh;
            padding: 50px 0;
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
        .plan-card {
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.2s ease-in-out;
            height: 100%;
        }
        .plan-card:hover {
            border-color: #ff6b6b;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
        }
        .plan-card.selected {
            border-color: #ff6b6b;
            background-color: #fff5f5;
            box-shadow: 0 8px 20px rgba(255, 107, 107, 0.15);
        }
        .plan-radio {
            display: none;
        }
        .store-badge {
            background-color: #2b2d42;
            color: #fff;
        }
    </style>
</head>
<body class="plan-page">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="text-center mb-4">
                    <h2 class="fw-bold mb-1">Choose Your Seller Plan</h2>
                    <p class="text-muted">{{ $draft['store_name'] }} &middot; {{ $draft['name'] }} &middot; {{ $draft['email'] }}</p>
                    <div class="d-flex justify-content-center align-items-center small mt-2">
                        <span class="text-muted">Step 1: Account Details</span>
                        <span class="mx-2 text-muted">&rarr;</span>
                        <span class="badge bg-success px-3 py-2">Step 2: Choose a Plan</span>
                    </div>
                </div>

                @if($errors->any())
                    <div class="alert alert-danger">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('seller.register.plan.submit') }}">
                    @csrf

                    @if($plans->isEmpty())
                        <div class="alert alert-warning text-center py-4">
                            No plans are available right now. Please try again later.
                        </div>
                    @endif

                    <div class="row g-4">
                        @foreach($plans as $plan)
                        <div class="col-md-4">
                            <label class="plan-card d-block p-4 text-center @if($loop->first) selected @endif">
                                <input type="radio" name="plan_id" value="{{ $plan->id }}" class="plan-radio" @if($loop->first) checked @endif required>
                                <div class="mb-2">
                                    <span class="badge store-badge px-3 py-2 text-uppercase">{{ $plan->name }}</span>
                                </div>
                                <h2 class="fw-bold mb-1">
                                    <span class="text-muted small fw-normal">&#8377;</span>{{ number_format((float) $plan->price, 2) }}
                                </h2>
                                <p class="text-muted small mb-3">{{ $plan->duration_days }} Days</p>

                                <div class="text-start">
                                    <p class="small mb-1">
                                        <i class="fas fa-box text-success me-1"></i>
                                        {{ $plan->product_limit > 0 ? 'Up to '.$plan->product_limit.' products' : 'Unlimited products' }}
                                    </p>
                                    @if($plan->description)
                                        <div class="small text-muted mb-0">{!! $plan->description !!}</div>
                                    @endif
                                </div>
                            </label>
                        </div>
                        @endforeach
                    </div>

                    <div class="text-center mt-4">
                        <a href="{{ route('seller.register') }}" class="btn btn-outline-secondary me-2">&larr; Back</a>
                        <button type="submit" class="btn btn-seller btn-lg px-5" @if($plans->isEmpty()) disabled @endif>Submit Registration</button>
                    </div>
                </form>

                <p class="text-center text-muted mt-4 small">
                    Your request will be sent to our team for approval after you submit. You will be notified once your store is approved.
                </p>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.plan-card').forEach(function (card) {
            card.addEventListener('click', function () {
                document.querySelectorAll('.plan-card').forEach(function (c) {
                    c.classList.remove('selected');
                });
                this.classList.add('selected');
                var radio = this.querySelector('input[type="radio"]');
                if (radio) {
                    radio.checked = true;
                }
            });
        });
    </script>

</body>
</html>
