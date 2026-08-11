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
                            <h4 class="mb-4 mb-sm-0 text-center text-sm-start">Shipping Methods</h4>
                        </div>
                        <div class="col-12 col-sm-auto">
                            <a href="{{ route('admin.shipping.create') }}" class="btn btn-primary"><i class="fa fa-plus me-2"></i> Add Shipping Method</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <div class="row">
            <div class="col-12 grid-margin">
                <div class="card card-white">
                    <div class="card-body slimscroll">
                        <div class="table-responsive">
                            <table class="table">
                                <thead class="bg-light">
                                    <tr>
                                        <th scope="col">ID</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Code</th>
                                        <th scope="col">Base Cost</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($shipping as $method)
                                    <tr>
                                        <td>{{ $method->id }}</td>
                                        <td>{{ $method->name }}</td>
                                        <td><code>{{ $method->code ?? 'N/A' }}</code></td>
                                        <td>₹{{ number_format($method->base_cost ?? 0, 2) }}</td>
                                        <td>
                                            @if($method->is_active)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-secondary">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.shipping.edit', $method->id) }}" class="btn btn-sm btn-link p-0 me-2"><i class="far fa-edit text-info"></i></a>
                                            <form action="{{ route('admin.shipping.destroy', $method->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this shipping method?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-link p-0"><i class="far fa-trash-alt text-danger"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted">No shipping methods found.</td>
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
