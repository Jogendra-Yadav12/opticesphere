@extends('layouts.admin')

@section('content')

@include('admin.nav')

<style>
    .plan-option {
        cursor: pointer;
        transition: all 0.2s ease-in-out;
    }
    .plan-option:hover {
        border-color: #0d6efd;
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.12);
    }
    .plan-option.selected {
        border-color: #0d6efd;
        background-color: #f0f7ff;
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.15);
    }
</style>

<div class="page-inner">
    <div id="main-wrapper">
        <div class="row align-items-center grid-margin">
            <div class="col-12">
                <div class="card card-white">
                    <div class="card-body">
                        <h4 class="mb-0">My Plan &amp; Subscription</h4>
                        <small class="text-muted">View your current plan and choose a new one anytime.</small>
                    </div>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('plan_expired'))
            <div class="alert alert-danger">
                <strong>Your plan has expired.</strong> Please recharge your plan below to regain full seller panel access.
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">{{ $errors->first() }}</div>
        @endif

        <div class="row">
            <div class="col-lg-4 grid-margin">
                <div class="card card-white h-100">
                    <div class="card-body">
                        <h5 class="mb-3">Current Plan</h5>

                        @if($subscription)
                            <div class="mb-3">
                                <h3 class="fw-bold mb-0">{{ $subscription->planTier?->plan?->name ?? 'Free' }}</h3>
                                <small class="text-muted">
                                    {{ $subscription->planTier?->name ? $subscription->planTier->name.' tier' : '' }}
                                    ({{ ucfirst($subscription->billing_period) }})
                                </small>
                            </div>
                            <ul class="list-unstyled small mb-3">
                                <li class="mb-1"><strong>Price:</strong> ₹{{ number_format((float) $subscription->price, 2) }}</li>
                                <li class="mb-1"><strong>Started:</strong> {{ $subscription->current_period_start->format('d M Y') }}</li>
                                <li class="mb-1"><strong>Renews / Ends:</strong> {{ $subscription->current_period_end->format('d M Y') }}</li>
                                <li class="mb-1">
                                    <strong>Status:</strong>
                                    @if(in_array($subscription->status, ['active', 'trialing'], true))
                                        <span class="badge bg-success">{{ ucfirst($subscription->status) }}</span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($subscription->status) }}</span>
                                    @endif
                                </li>
                            </ul>
                        @else
                            <p class="text-muted">You are currently on the <strong>Free</strong> plan. Choose a plan below to get started.</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-8 grid-margin">
                <form method="POST" action="{{ route('seller.plan.update') }}">
                    @csrf
                    <div class="row g-3">
                        @forelse($plans as $plan)
                        <div class="col-md-6">
                            <label class="border rounded p-3 d-block h-100 plan-option @if($subscription && $subscription->planTier?->plan?->id === $plan->id) selected @endif">
                                <input type="radio" name="plan_id" value="{{ $plan->id }}" class="d-none"
                                    @if($subscription && $subscription->planTier?->plan?->id === $plan->id) checked @endif>
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <h5 class="mb-0">{{ $plan->name }}</h5>
                                    @if($subscription && $subscription->planTier?->plan?->id === $plan->id)
                                        <span class="badge bg-primary">Current</span>
                                    @endif
                                </div>
                                <p class="mb-1">
                                    <strong>₹{{ number_format((float) $plan->price, 2) }}</strong>
                                    <span class="text-muted">/ {{ $plan->duration_days }} Days</span>
                                </p>
                                <p class="small text-muted mb-0">
                                    <i class="fas fa-box me-1"></i>
                                    {{ $plan->product_limit > 0 ? 'Up to '.$plan->product_limit.' products' : 'Unlimited products' }}
                                </p>
                                @if($plan->description)
                                    <div class="small text-muted mt-1">{!! $plan->description !!}</div>
                                @endif
                            </label>
                        </div>
                        @empty
                        <div class="col-12">
                            <div class="alert alert-warning text-center mb-0">No plans are available right now.</div>
                        </div>
                        @endforelse
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary" @if($plans->isEmpty()) disabled @endif>
                            <i class="fas fa-exchange-alt me-1"></i> Update My Plan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@if(session('plan_expired'))
    <div class="modal fade" id="planExpiredModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center p-4">
                    <i class="fas fa-exclamation-triangle text-warning mb-3" style="font-size: 3rem;"></i>
                    <h4 class="fw-bold">Your Plan Has Expired</h4>
                    <p class="text-muted">
                        Your current plan period has ended. Please recharge your plan by choosing one below to regain
                        full seller panel access and continue selling.
                    </p>
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Recharge My Plan</button>
                </div>
            </div>
        </div>
    </div>
@endif
@endsection

@section('scripts')
@if(session('plan_expired'))
<script>
    window.addEventListener('DOMContentLoaded', function () {
        var modal = new bootstrap.Modal(document.getElementById('planExpiredModal'));
        modal.show();
    });
</script>
@endif
<script>
    document.querySelectorAll('.plan-option').forEach(function (option) {
        option.addEventListener('click', function () {
            document.querySelectorAll('.plan-option').forEach(function (o) {
                o.classList.remove('selected');
            });
            this.classList.add('selected');
            var radio = this.querySelector('input[type="radio"]');
            if (radio) {
                radio.checked = true;
            }
        });
    });
</script>
@endsection
