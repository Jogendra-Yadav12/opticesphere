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
                            <h4 class="mb-0">Tax Rates</h4>
                        </div>
                        <div class="col-12 col-sm-auto text-end">
                            <a href="{{ route('admin.tax.create') }}" class="btn btn-primary"><i class="fa fa-plus me-2"></i> Add Tax Rate</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 grid-margin">
                <div class="card card-white">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead class="bg-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Country</th>
                                        <th>State</th>
                                        <th>Rate</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($taxRates as $tax)
                                    <tr>
                                        <th scope="row">{{ $tax->id }}</th>
                                        <td>{{ $tax->name }}</td>
                                        <td>{{ $tax->country ?? 'All' }}</td>
                                        <td>{{ $tax->state ?? 'All' }}</td>
                                        <td>{{ number_format($tax->rate, 2) }}%</td>
                                        <td><span class="badge rounded-pill bg-soft-{{ $tax->is_active ? 'green' : 'secondary' }}">{{ $tax->is_active ? 'Active' : 'Inactive' }}</span></td>
                                        <td>
                                            <a href="{{ route('admin.tax.edit', $tax->id) }}" class="me-3" title="Edit"><i class="far fa-edit text-primary"></i></a>
                                            <form action="{{ route('admin.tax.destroy', $tax->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this tax rate?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-link p-0 border-0" title="Delete"><i class="far fa-trash-alt text-danger"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">No tax rates found.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
