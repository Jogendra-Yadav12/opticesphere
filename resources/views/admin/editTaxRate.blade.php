@extends('layouts.admin')

@section('content')
@include('admin.nav')

@php $row = $tax ?? $taxRate; @endphp

<div class="page-inner">
    <div id="main-wrapper">
        <div class="row align-items-center grid-margin">
            <div class="col-12">
                <div class="card card-white">
                    <div class="card-body row align-items-center">
                        <div class="col-12 col-sm">
                            <h4 class="mb-0">{{ $row->exists ? 'Edit Tax Rate: '.$row->name : 'Add Tax Rate' }}</h4>
                        </div>
                        <div class="col-12 col-sm-auto text-end">
                            <a href="{{ route('admin.tax.index') }}" class="btn btn-secondary"><i class="fa fa-arrow-left me-2"></i> Back to Tax Rates</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8 mx-auto grid-margin">
                <div class="card card-white">
                    <div class="card-body">
                        <form action="{{ $row->exists ? route('admin.tax.update', $row->id) : route('admin.tax.store') }}" method="POST">
                            @csrf
                            @if($row->exists) @method('PUT') @endif

                            <div class="mb-4">
                                <label class="form-label fw-bold">Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name" value="{{ old('name', $row->name) }}" placeholder="e.g. VAT 18%" required>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label class="form-label fw-bold">Country Code</label>
                                    <input type="text" class="form-control" name="country" value="{{ old('country', $row->country) }}" placeholder="IN" maxlength="2">
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label class="form-label fw-bold">State</label>
                                    <input type="text" class="form-control" name="state" value="{{ old('state', $row->state) }}" placeholder="e.g. Maharashtra">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label class="form-label fw-bold">Rate (%) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" name="rate" step="0.01" min="0" max="100" value="{{ old('rate', $row->rate) }}" required>
                                </div>
                                <div class="col-md-6 mb-4 form-check form-switch pt-4">
                                    <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ ($row->is_active ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">Active</label>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary">{{ $row->exists ? 'Update Tax Rate' : 'Create Tax Rate' }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
