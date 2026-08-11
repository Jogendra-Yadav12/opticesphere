@extends('layouts.admin')

@section('content')
@include('admin.nav')

<div class="page-inner">
    <div id="main-wrapper">
        <div class="row align-items-center grid-margin">
            <div class="col-12">
                <div class="card card-white">
                    <div class="card-body row align-items-center">
                        <div class="col-12 col-sm">
                            <h4 class="mb-0">{{ $zone->exists ? 'Edit Zone: '.$zone->name : 'Add Shipping Zone' }}</h4>
                        </div>
                        <div class="col-12 col-sm-auto text-end">
                            <a href="{{ route('admin.shippingZone.index') }}" class="btn btn-secondary"><i class="fa fa-arrow-left me-2"></i> Back to Zones</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8 mx-auto grid-margin">
                <div class="card card-white">
                    <div class="card-body">
                        <form action="{{ $zone->exists ? route('admin.shippingZone.update', $zone->id) : route('admin.shippingZone.store') }}" method="POST">
                            @csrf
                            @if($zone->exists) @method('PUT') @endif

                            <div class="mb-4">
                                <label class="form-label fw-bold">Zone Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name" value="{{ old('name', $zone->name) }}" placeholder="e.g. Domestic" required>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold">Countries</label>
                                <select class="form-control" name="countries[]" multiple size="12">
                                    @foreach($countries as $country)
                                        <option value="{{ $country->code }}" {{ in_array($country->code, $zone->countries ?? []) ? 'selected' : '' }}>{{ $country->name }} ({{ $country->code }})</option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Hold Ctrl/Cmd to select multiple countries.</small>
                            </div>

                            <button type="submit" class="btn btn-primary">{{ $zone->exists ? 'Update Zone' : 'Create Zone' }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
