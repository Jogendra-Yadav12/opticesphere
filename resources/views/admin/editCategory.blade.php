@extends('layouts.admin')

@section('content')

@include('admin.nav')

    <!-- PAGE INNER
    ================================================== -->
    <div class="page-inner">

        <!-- PAGE MAIN WRAPPER
        ================================================== -->
        <div id="main-wrapper">
        <div class="row">
            <div class="col-md-8 mx-auto grid-margin">
                <div class="card card-white">
                    <div class="card-heading clearfix">
                        <h4 class="card-title">Edit Category: {{ $category->name }}</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.category.update', $category->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            
                            <!-- Name -->
                            <div class="mb-4">
                                <label for="name" class="form-label fw-bold">Name</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $category->name) }}" required />
                            </div>

                            <!-- Parent Category (Optional) -->
                            <div class="mb-4">
                                <label for="parent_id" class="form-label fw-bold">Parent Category (Optional)</label>
                                <select class="form-select" id="parent_id" name="parent_id">
                                    <option value="">None (Creates a Main Category)</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ (old('parent_id', $category->parent_id) == $cat->id) ? 'selected' : '' }}>{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Image Upload (Same UI as addProduct) -->
                            <div class="mb-4">
                                <h5 class="form-label fw-bold">Image</h5>
                                <label for="imageUpload" class="custom-file-upload p-4 text-center d-block rounded" style="border: 2px dashed #d1d5db; cursor: pointer; transition: border-color .15s ease;">
                                    <i class="fas fa-image fa-3x text-primary mb-3"></i>
                                    <h6 class="mb-0">Click to upload new image (Optional)</h6>
                                    <small class="text-muted">JPG, PNG, WebP or GIF (max 2MB)</small>
                                    <input type="file" id="imageUpload" name="img" class="d-none" accept="image/*" onchange="previewImage(event)">
                                </label>
                                <div class="text-center mt-3">
                                    <img id="imagePreview" src="{{ $category->img && $category->img !== 'default.png' ? asset('images/'.$category->img) : 'https://ui-avatars.com/api/?name='.urlencode($category->name).'&background=random' }}" alt="Preview" style="max-height: 200px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);" />
                                </div>
                            </div>

                            <!-- Status -->
                            <div class="mb-4">
                                <label for="status" class="form-label fw-bold">Status</label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="active" {{ old('status', $category->status) === 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="disabled" {{ old('status', $category->status) === 'disabled' ? 'selected' : '' }}>Disabled</option>
                                </select>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary w-100 py-3 fw-bold">Update Category</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    function previewImage(event) {
        var reader = new FileReader();
        reader.onload = function(){
            var output = document.getElementById('imagePreview');
            output.src = reader.result;
            output.style.display = 'inline-block';
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>
@endsection
