@extends('layouts.admin')

@section('content')
@include('admin.nav')

@php $row = $currencyRate ?? $rate; @endphp

<div class="page-inner">
    <div id="main-wrapper">
        <div class="row align-items-center grid-margin">
            <div class="col-12">
                <div class="card card-white">
                    <div class="card-body row align-items-center">
                        <div class="col-12 col-sm">
                            <h4 class="mb-0">{{ $row->exists ? 'Edit Rate: '.$row->base_currency.' → '.$row->target_currency : 'Add Currency Rate' }}</h4>
                        </div>
                        <div class="col-12 col-sm-auto text-end">
                            <a href="{{ route('admin.currency.index') }}" class="btn btn-secondary"><i class="fa fa-arrow-left me-2"></i> Back to Rates</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8 mx-auto grid-margin">
                <div class="card card-white">
                    <div class="card-body">
                        <form action="{{ $row->exists ? route('admin.currency.update', $row->id) : route('admin.currency.store') }}" method="POST">
                            @csrf
                            @if($row->exists) @method('PUT') @endif

                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label class="form-label fw-bold">Base Currency <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="base_currency" value="{{ old('base_currency', $row->base_currency) }}" placeholder="INR" maxlength="3" required>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label class="form-label fw-bold">Target Currency <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="target_currency" value="{{ old('target_currency', $row->target_currency) }}" placeholder="USD" maxlength="3" required>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold">Rate <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="rate" step="0.000001" min="0" value="{{ old('rate', $row->rate) }}" placeholder="1 INR = ? target" required>
                                <small class="text-muted">Amount of target currency per 1 unit of base currency.</small>
                            </div>

                            <button type="submit" class="btn btn-primary">{{ $row->exists ? 'Update Rate' : 'Create Rate' }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
