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
                            <h4 class="mb-0">Currency Rates</h4>
                        </div>
                        <div class="col-12 col-sm-auto text-end">
                            <a href="{{ route('admin.currency.create') }}" class="btn btn-primary"><i class="fa fa-plus me-2"></i> Add Rate</a>
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
                                        <th>Base</th>
                                        <th>Target</th>
                                        <th>Rate</th>
                                        <th>Updated</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($rates as $rate)
                                    <tr>
                                        <th scope="row">{{ $rate->id }}</th>
                                        <td><span class="badge bg-light text-dark">{{ $rate->base_currency }}</span></td>
                                        <td><span class="badge bg-light text-dark">{{ $rate->target_currency }}</span></td>
                                        <td>{{ number_format($rate->rate, 6) }}</td>
                                        <td>{{ $rate->updated_at?->format('d M Y') ?? '—' }}</td>
                                        <td>
                                            <a href="{{ route('admin.currency.edit', $rate->id) }}" class="me-3" title="Edit"><i class="far fa-edit text-primary"></i></a>
                                            <form action="{{ route('admin.currency.destroy', $rate->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this rate?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-link p-0 border-0" title="Delete"><i class="far fa-trash-alt text-danger"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">No currency rates found.</td>
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
