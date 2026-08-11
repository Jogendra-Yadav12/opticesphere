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
                            <h4 class="mb-4 mb-sm-0 text-center text-sm-start">Add Shipping Method</h4>
                        </div>
                        <div class="col-12 col-sm-auto">
                            <a href="{{ route('admin.shipping.index') }}" class="btn btn-secondary">Back to List</a>
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
                        
                        <form action="{{ route('admin.shipping.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-4 form-group mb-3">
                                    <label>Shipping Name (e.g. Standard)</label>
                                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                                </div>
                                <div class="col-md-4 form-group mb-3">
                                    <label>Code (e.g. standard)</label>
                                    <input type="text" name="code" class="form-control" value="{{ old('code') }}" required>
                                </div>
                                <div class="col-md-4 form-group mb-3">
                                    <label>Base Cost (₹)</label>
                                    <input type="number" step="0.01" name="base_cost" class="form-control" value="{{ old('base_cost') }}" required>
                                </div>
                                
                                <div class="col-md-12 form-group mb-3">
                                    <label>Description</label>
                                    <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                                </div>

                                <div class="col-md-12 form-group mb-3">
                                    <label>Settings (Key-Value pairs)</label>
                                    <div id="settings-container">
                                        <div class="row mb-2 setting-row">
                                            <div class="col-md-5">
                                                <input type="text" name="setting_keys[]" class="form-control" placeholder="Key (e.g. delivery_time)">
                                            </div>
                                            <div class="col-md-5">
                                                <input type="text" name="setting_values[]" class="form-control" placeholder="Value">
                                            </div>
                                            <div class="col-md-2">
                                                <button type="button" class="btn btn-danger remove-row"><i class="fa fa-times"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-primary mt-2" id="add-setting-row"><i class="fa fa-plus"></i> Add Row</button>
                                </div>

                                <div class="col-md-6 form-group mb-3">
                                    <label>Status</label>
                                    <select name="is_active" class="form-control">
                                        <option value="1" {{ old('is_active') == '1' ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-12 mt-4 text-end">
                                    <button type="submit" class="btn btn-success">Save Shipping Method</button>
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
            <input type="text" name="setting_keys[]" class="form-control" placeholder="Key (e.g. delivery_time)">
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
