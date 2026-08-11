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
                            <h4 class="mb-4 mb-sm-0 text-center text-sm-start">Edit Payment Method</h4>
                        </div>
                        <div class="col-12 col-sm-auto">
                            <a href="{{ route('admin.payment.index') }}" class="btn btn-secondary">Back to List</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 grid-margin">
                <div class="card card-white">
                    <div class="card-body">
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        
                        <form action="{{ route('admin.payment.update', $payment->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-6 form-group mb-3">
                                    <label>Gateway Name (e.g. Stripe)</label>
                                    <input type="text" name="name" class="form-control" value="{{ old('name', $payment->name) }}" required>
                                </div>
                                <div class="col-md-6 form-group mb-3">
                                    <label>Code (e.g. stripe)</label>
                                    <input type="text" name="code" class="form-control" value="{{ old('code', $payment->code) }}" required>
                                </div>
                                
                                <div class="col-md-12 form-group mb-3">
                                    <label>Description</label>
                                    <textarea name="description" class="form-control" rows="3">{{ old('description', $payment->description) }}</textarea>
                                </div>

                                <div class="col-md-12 form-group mb-3">
                                    <label>Settings (Key-Value pairs)</label>
                                    <div id="settings-container">
                                        @php
                                            $credentials = is_string($payment->credentials) ? json_decode($payment->credentials, true) : $payment->credentials;
                                            $credentials = is_array($credentials) ? $credentials : [];
                                        @endphp
                                        @forelse($credentials as $key => $value)
                                            <div class="row mb-2 setting-row">
                                                <div class="col-md-5">
                                                    <input type="text" name="setting_keys[]" class="form-control" value="{{ $key }}" placeholder="Key (e.g. public_key)">
                                                </div>
                                                <div class="col-md-5">
                                                    <input type="text" name="setting_values[]" class="form-control" value="{{ $value }}" placeholder="Value">
                                                </div>
                                                <div class="col-md-2">
                                                    <button type="button" class="btn btn-danger remove-row"><i class="fa fa-times"></i></button>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="row mb-2 setting-row">
                                                <div class="col-md-5">
                                                    <input type="text" name="setting_keys[]" class="form-control" placeholder="Key (e.g. public_key)">
                                                </div>
                                                <div class="col-md-5">
                                                    <input type="text" name="setting_values[]" class="form-control" placeholder="Value">
                                                </div>
                                                <div class="col-md-2">
                                                    <button type="button" class="btn btn-danger remove-row"><i class="fa fa-times"></i></button>
                                                </div>
                                            </div>
                                        @endforelse
                                    </div>
                                    <button type="button" class="btn btn-sm btn-primary mt-2" id="add-setting-row"><i class="fa fa-plus"></i> Add Row</button>
                                </div>

                                <div class="col-md-6 form-group mb-3">
                                    <label>Status</label>
                                    <select name="is_active" class="form-control">
                                        <option value="1" {{ old('is_active', $payment->is_active) == '1' ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ old('is_active', $payment->is_active) == '0' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-12 mt-4 text-end">
                                    <button type="submit" class="btn btn-success">Update Payment Method</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
document.getElementById('add-setting-row')?.addEventListener('click', function() {
    const container = document.getElementById('settings-container');
    const row = document.createElement('div');
    row.className = 'row mb-2 setting-row';
    row.innerHTML = `
        <div class="col-md-5">
            <input type="text" name="setting_keys[]" class="form-control" placeholder="Key (e.g. public_key)">
        </div>
        <div class="col-md-5">
            <input type="text" name="setting_values[]" class="form-control" placeholder="Value">
        </div>
        <div class="col-md-2">
            <button type="button" class="btn btn-danger remove-row"><i class="fa fa-times"></i></button>
        </div>
    `;
    container.appendChild(row);
});
document.addEventListener('click', function(e) {
    if (e.target.closest('.remove-row')) {
        e.target.closest('.setting-row').remove();
    }
});
</script>
@endsection
@endsection
