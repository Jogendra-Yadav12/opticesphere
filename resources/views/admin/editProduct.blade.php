@extends('layouts.admin')

@section('content')

@include('admin.nav')

<!-- Summernote Lite CSS (Avoids Bootstrap CSS conflicts) -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<style>
    .custom-file-upload {
        border: 2px dashed #ccc;
        border-radius: 8px;
        padding: 40px;
        text-align: center;
        cursor: pointer;
        background: #f8f9fa;
        transition: 0.3s ease;
        display: block;
    }
    .custom-file-upload:hover {
        background: #e9ecef;
        border-color: #0d6efd;
    }
    #imagePreview {
        max-width: 100%;
        max-height: 200px;
        margin-top: 15px;
        display: none;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    .note-editor .note-editing-area {
        background: #fff;
    }
</style>

<div class="page-inner">
    <div id="main-wrapper">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @php $routePrefix = auth()->user()->role === 'admin' ? 'admin.' : 'seller.'; @endphp
        @php $selectedValues = $product->attributeValues->pluck('id')->merge($product->variants->flatMap->attributeValues->pluck('id'))->unique()->all(); @endphp
        @php $priceMap = $product->attributeValues->pluck('pivot.price_adjustment', 'id')->map(fn ($p) => $p ?? null)->all(); @endphp
        <form class="needs-validation" action="{{ route($routePrefix . 'product.update', $product->id) }}" method="POST" enctype="multipart/form-data" novalidate>
            @csrf
            @method('PUT')
            <!-- row -->
            <div class="row g-xl-3">
                <div class="col-xl-8 grid-margin">
                    
                    <!-- Basic Info -->
                    <div class="card card-white mb-3">
                        <div class="card-heading clearfix">
                            <h4 class="card-title">Product information</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 mb-4">
                                    <label for="name" class="form-label fw-bold">Product Name</label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $product->name) }}" placeholder="e.g. Ray-Ban Aviators" required>
                                </div>
                                
                                <div class="col-md-12 mb-2">
                                    <div class="form-group">
                                        <label class="form-label fw-bold">Description</label>
                                        <textarea id="summernote" name="description" required>{{ old('description', $product->description) }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Images -->
                    <div class="card card-white mb-3">
                        <div class="card-heading clearfix">
                            <h4 class="card-title">Product Images</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <h5 class="form-label fw-bold">Main Image</h5>
                                    <label for="imageUpload" class="custom-file-upload">
                                        <i class="fas fa-image fa-3x text-primary mb-3"></i>
                                        <h6>Click to upload main image</h6>
                                        <input type="file" id="imageUpload" name="image" class="d-none" accept="image/*" onchange="previewImage(event)">
                                    </label>
                                    <div class="text-center">
                                        <img id="imagePreview" src="{{ $product->image ? Storage::url('images/products/'.$product->image) : '#' }}" alt="Preview" style="{{ $product->image ? 'display:inline-block;' : 'display:none;' }}" />
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <h5 class="form-label fw-bold">Gallery Images (Multiple)</h5>
                                    <label for="galleryUpload" class="custom-file-upload">
                                        <i class="fas fa-images fa-3x text-info mb-3"></i>
                                        <h6>Upload multiple gallery images</h6>
                                        <input type="file" id="galleryUpload" name="gallery[]" class="d-none" accept="image/*" multiple onchange="previewGallery(event)">
                                    </label>
                                    <div id="galleryPreview" class="d-flex flex-wrap gap-2 mt-3">
                                        @if(isset($product) && $product->images)
                                            @foreach($product->images as $image)
                                                <div class="position-relative d-inline-block">
                                                    <img src="{{ Storage::url('images/products/gallery/'.$image->image_path) }}" style="max-height: 80px; border-radius: 5px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-right: 10px; margin-bottom: 10px;" alt="Gallery Image">
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Attributes Section -->
                    <div class="card card-white mb-3">
                        <div class="card-heading clearfix">
                            <h4 class="card-title">Product Attributes</h4>
                        </div>
                        <div class="card-body">
                            <p class="text-muted small mb-3">Pick the attribute options that apply to this product (dropdowns, radio, colors, buttons, text or checkboxes depending on each attribute's type), then click "Generate Variants" to create a variant for every combination. Variants created this way are linked to the attribute system.</p>

                            <div id="attributeGroups">
                                @forelse($attributes as $attribute)
                                    @include('admin.partials.attribute-selector', ['attribute' => $attribute, 'selectedValues' => $selectedValues, 'prices' => $priceMap])
                                @empty
                                    <span class="text-muted small d-block mb-3">No attributes yet. Add one below.</span>
                                @endforelse
                            </div>

                            <hr>

                            <div class="row g-2 align-items-end">
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold">New Attribute Name</label>
                                    <input type="text" class="form-control form-control-sm" id="newAttrName" placeholder="e.g. Color">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small fw-bold">Type</label>
                                    <select class="form-select form-select-sm" id="newAttrType">
                                        @foreach(['text', 'select', 'radio', 'checkbox', 'color', 'button'] as $type)
                                            <option value="{{ $type }}" {{ $type === 'select' ? 'selected' : '' }}>{{ ucfirst($type) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold">Values (comma separated)</label>
                                    <input type="text" class="form-control form-control-sm" id="newAttrValues" placeholder="e.g. Red, Blue, Green (or Red=#ff0000 for colors)">
                                </div>
                                <div class="col-md-1">
                                    <button type="button" class="btn btn-sm btn-primary w-100" onclick="addNewAttribute()" title="Add Attribute"><i class="fa fa-plus"></i></button>
                                </div>
                            </div>
                            <small class="text-muted d-block mt-2">Attributes added here are saved and become available on this and future products.</small>

                            <div id="productAttributeValues" class="d-none"></div>
                        </div>
                    </div>

                    <!-- Variants Section -->
                    <div class="card card-white mb-3">
                        <div class="card-heading clearfix">
                            <h4 class="card-title m-0">Product Variants</h4>
                        </div>
                        <div class="card-body">
                            <p class="text-muted small mb-4">Variant combinations are generated automatically from the attribute options selected above. Add SKU, price and stock per combination.</p>
                            
                            <div class="table-responsive">
                                <table class="table table-bordered" id="variantsTable">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>Variant Name (e.g. Red/Small)</th>
                                            <th>SKU (Optional)</th>
                                            <th>Price Adj. (₹)</th>
                                            <th>Stock</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="variantsBody">
                                        @if(isset($product) && $product->variants->count() > 0)
                                            @foreach($product->variants as $index => $variant)
                                                <tr>
                                                    <td><input type="text" name="variants[{{ $index }}][name]" class="form-control" value="{{ $variant->variant_name }}" required readonly></td>
                                                    <td><input type="text" name="variants[{{ $index }}][sku]" class="form-control" value="{{ $variant->sku }}" readonly></td>
                                                    <td><input type="number" step="0.01" name="variants[{{ $index }}][price]" class="form-control" value="{{ $variant->price }}" readonly></td>
                                                    <td><input type="number" name="variants[{{ $index }}][stock]" class="form-control" value="{{ $variant->stock }}" required></td>
                                                    <td class="text-center"><button type="button" class="btn btn-sm btn-danger" onclick="removeVariantRow(this)"><i class="fa fa-times"></i></button></td>
                                                    @foreach($variant->attributeValues as $value)
                                                        <input type="hidden" name="variants[{{ $index }}][attribute_value_ids][]" value="{{ $value->id }}">
                                                    @endforeach
                                                </tr>
                                            @endforeach
                                        @endif
                                        <!-- Javascript will inject rows here -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 grid-margin">
                    <!-- Pricing & Stock -->
                    <div class="card card-white mb-3">
                        <div class="card-heading clearfix">
                            <h4 class="card-title">Pricing & Global Stock</h4>
                        </div>
                        <div class="card-body">
                            <div class="border-bottom pb-4 mb-4">
                                <div class="mb-4">
                                    <label for="price" class="form-label fw-bold">Regular Price (₹)</label>
                                    <input type="number" step="0.01" class="form-control" name="price" id="price" value="{{ old('price', $product->price) }}" required>
                                </div>
                                <div class="mb-4">
                                    <label for="special_price" class="form-label fw-bold">Special Discount Price (₹)</label>
                                    <input type="number" step="0.01" class="form-control" name="special_price" id="special_price" value="{{ old('special_price', $product->special_price) }}" placeholder="Optional">
                                </div>
                            </div>
                            <div class="mb-4">
                                <label for="stock" class="form-label fw-bold">Available Stock Quantity (Global)</label>
                                <input type="number" class="form-control" name="stock" id="stock" value="{{ old('stock', $product->stock) }}" required>
                            </div>
                        </div>
                    </div>

                    <!-- Organization -->
                    <div class="card card-white">
                        <div class="card-heading clearfix">
                            <h4 class="card-title">Organization</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @if(auth()->user()->role === 'admin')
                                <div class="col-md-12 mb-4">
                                    <label for="vendor_id" class="form-label fw-bold">Seller / Store</label>
                                    <select class="form-select form-control" name="vendor_id" id="vendor_id">
                                        <option value="" {{ old('vendor_id', $product->vendor_id ?? '') == '' ? 'selected' : '' }}>-- No seller (own store) --</option>
                                        @foreach($vendors as $vendor)
                                            <option value="{{ $vendor->id }}" {{ old('vendor_id', $product->vendor_id) == $vendor->id ? 'selected' : '' }}>
                                                {{ $vendor->store_name }}{{ $vendor->user ? ' ('.$vendor->user->name.')' : '' }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted">Assign this product to a seller. This moves the product to that seller's store.</small>
                                </div>
                                @endif

                                <div class="col-md-12 mb-4">
                                    <label for="category_id" class="form-label fw-bold">Category</label>
                                    <select class="form-select form-control" name="category_id" id="category_id" required>
                                        <option value="" disabled selected>Select a Category...</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-12 mt-4">
                                    <button type="submit" class="btn btn-primary w-100 py-3 fw-bold">Update Product</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end row -->
        </form>
    </div>

@endsection

@section('scripts')
<!-- Summernote Lite JS -->
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script>
    // Image Preview Logic (Main Image)
    function previewImage(event) {
        var reader = new FileReader();
        reader.onload = function(){
            var output = document.getElementById('imagePreview');
            output.src = reader.result;
            output.style.display = 'inline-block';
        };
        reader.readAsDataURL(event.target.files[0]);
    }

    // Gallery Preview Logic (Multiple Images)
    function previewGallery(event) {
        var gallery = document.getElementById('galleryPreview');
        gallery.innerHTML = ''; // Clear existing
        for (var i = 0; i < event.target.files.length; i++) {
            var reader = new FileReader();
            reader.onload = function(e) {
                var img = document.createElement('img');
                img.src = e.target.result;
                img.style.maxHeight = '80px';
                img.style.borderRadius = '5px';
                img.style.boxShadow = '0 2px 4px rgba(0,0,0,0.1)';
                img.style.marginRight = '10px';
                img.style.marginBottom = '10px';
                gallery.appendChild(img);
            }
            reader.readAsDataURL(event.target.files[i]);
        }
    }

    // Bulletproof Variant Logic
    let variantIndex = {{ isset($product) ? $product->variants->count() : 0 }};
    function addVariantRow(name, attrValueIds, price, sku, stock, displayName) {
        const variantsBody = document.getElementById('variantsBody');
        if (!variantsBody) return;

        const hiddenInputs = (attrValueIds || []).filter(function(id) { return String(id) !== ''; }).map(function(id) {
            return '<input type="hidden" name="variants[' + variantIndex + '][attribute_value_ids][]" value="' + id + '">';
        }).join('');

        const priceAttr = (price !== undefined && price !== null && String(price).trim() !== '') ? ' value="' + String(price) + '"' : '';
        const nameVal = displayName || name || '';
        const skuVal = sku || '';
        const stockVal = stock || '0';

        let rowHtml = `
            <tr>
                <td><input type="text" name="variants[${variantIndex}][name]" class="form-control" placeholder="e.g. Gold / +2.00" ${name ? '' : 'required'} value="${nameVal}" readonly></td>
                <td><input type="text" name="variants[${variantIndex}][sku]" class="form-control" placeholder="Auto (from name) or enter SKU" value="${skuVal}" readonly></td>
                <td><input type="number" step="0.01" name="variants[${variantIndex}][price]" class="form-control" placeholder="0.00"${priceAttr} readonly></td>
                <td><input type="number" name="variants[${variantIndex}][stock]" class="form-control" value="${stockVal}" required></td>
                <td class="text-center"><button type="button" class="btn btn-sm btn-danger" onclick="removeVariantRow(this)"><i class="fa fa-times"></i></button></td>
                ${hiddenInputs}
            </tr>
        `;
        variantsBody.insertAdjacentHTML('beforeend', rowHtml);
        variantIndex++;
    }

    function optionPriceFor(id) {
        var el = document.querySelector('input[name="option_price[' + id + ']"]');
        var v = el ? parseFloat(el.value) : 0;
        return isNaN(v) ? 0 : v;
    }

    function readVariantRows() {
        var map = {};
        document.querySelectorAll('#variantsBody tr').forEach(function(tr) {
            var ids = Array.prototype.slice.call(tr.querySelectorAll('input[name$="[attribute_value_ids][]"]')).map(function(i) { return parseInt(i.value, 10); });
            if (ids.length === 0) return;
            ids.sort(function(a, b) { return a - b; });
            var get = function(sel) { var el = tr.querySelector(sel); return el ? el.value : ''; };
            map[ids.join('-')] = {
                name: get('input[name$="[name]"]'),
                sku: get('input[name$="[sku]"]'),
                price: get('input[name$="[price]"]'),
                stock: get('input[name$="[stock]"]')
            };
        });
        return map;
    }

    function removeVariantRow(btn) {
        btn.closest('tr').remove();
    }

    // Remove an attribute group from this product (it stays in the shared pool).
    function removeAttributeGroup(btn) {
        var group = btn.closest('.attribute-group');
        if (!group) return;
        group.remove();
        syncProductAttributes();
        scheduleGenerateVariants();
    }

    // Collect selected attribute values grouped by attribute.
    // Once an attribute is engaged (any value chosen), ALL of its values
    // participate so every combination of every engaged attribute is generated.
    function collectSelectedAttributeValues() {
        var groups = {};

        document.querySelectorAll('.attribute-group').forEach(function(group) {
            var attrId = group.dataset.attrId;
            if (!attrId) return;

            var engaged = false;
            var typedText = '';
            var textMapJson = '';

            group.querySelectorAll('.attribute-value').forEach(function(el) {
                if (el.tagName === 'SELECT') {
                    if (el.value) engaged = true;
                } else if (el.type === 'checkbox' || el.type === 'radio') {
                    if (el.checked) engaged = true;
                } else if (el.type === 'text') {
                    if (el.value.trim()) {
                        engaged = true;
                        typedText = el.value.trim();
                        textMapJson = el.dataset.values || '';
                    }
                }
            });

            if (!engaged) return;

            // Text attributes use the typed value (resolved to a known id when it matches).
            if (typedText) {
                if (!groups[attrId]) groups[attrId] = [];
                var id = '';
                try {
                    id = (JSON.parse(textMapJson || '{}'))[typedText.toLowerCase()] || '';
                } catch (e) {}
                groups[attrId].push({ id: id, label: typedText });
                return;
            }

            // All other types: use every value of the engaged attribute.
            var allValues = [];
            try {
                allValues = JSON.parse(group.dataset.allValues || '[]');
            } catch (e) {}
            if (allValues.length > 0) {
                groups[attrId] = allValues;
            }
        });

        return groups;
    }

    function cartesianProduct(arrays) {
        return arrays.reduce(function(acc, arr) {
            return acc.flatMap(function(a) { return arr.map(function(v) { return a.concat([v]); }); });
        }, [[]]);
    }

    function clearVariantRows() {
        var body = document.getElementById('variantsBody');
        if (body) body.innerHTML = '';
    }

    function generateVariants() {
        var groups = collectSelectedAttributeValues();
        var keys = Object.keys(groups);
        clearVariantRows();
        if (keys.length === 0) {
            return;
        }
        var combos = cartesianProduct(keys.map(function(k) { return groups[k]; }));
        var existing = readVariantRows();
        combos.forEach(function(combo) {
            var ids = combo.map(function(c) { return c.id; });
            var key = ids.slice().sort(function(a, b) { return a - b; }).join('-');
            var prev = existing[key] || {};
            var name = combo.map(function(c) { return c.label; }).join(' / ');
            var sum = combo.reduce(function(acc, c) { return acc + optionPriceFor(c.id); }, 0);
            var price = prev.price !== undefined && prev.price !== '' ? prev.price : (sum > 0 ? sum.toFixed(2) : '');
            addVariantRow(name, ids, price, prev.sku || '', prev.stock || '0', prev.name || name);
        });
    }

    // Add a new attribute inline (saved via AJAX, appended to the list)
    function addNewAttribute() {
        var name = document.getElementById('newAttrName').value.trim();
        var type = document.getElementById('newAttrType').value;
        var values = document.getElementById('newAttrValues').value.trim();
        if (!name || !values) {
            alert('Please enter an attribute name and at least one value.');
            return;
        }

        var token = document.querySelector('input[name="_token"]');
        if (!token) { alert('Missing CSRF token.'); return; }

        fetch('{{ route('attribute.store') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': token.value
            },
            body: JSON.stringify({ name: name, type: type, values: values })
        })
        .then(function(r) { return r.json().catch(function() { return { error: 'Invalid server response.' }; }); })
        .then(function(data) {
            if (data.error) { alert(data.error); return; }
            var empty = document.querySelector('#attributeGroups .text-muted');
            if (empty) empty.remove();
            document.getElementById('attributeGroups').insertAdjacentHTML('beforeend', data.html);
            document.getElementById('newAttrName').value = '';
            document.getElementById('newAttrValues').value = '';
        })
        .catch(function() { alert('Could not add attribute. Please try again.'); });
    }

    // Persist the currently selected product attributes as hidden inputs
    function syncProductAttributes() {
        var groups = collectSelectedAttributeValues();
        var ids = [];
        Object.keys(groups).forEach(function(k) {
            groups[k].forEach(function(c) { if (String(c.id) !== '') ids.push(c.id); });
        });
        var container = document.getElementById('productAttributeValues');
        if (!container) return;
        container.innerHTML = '';
        ids.forEach(function(id) {
            var input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'product_attribute_values[]';
            input.value = id;
            container.appendChild(input);
        });
    }

    var generateTimer;
    function scheduleGenerateVariants() {
        clearTimeout(generateTimer);
        generateTimer = setTimeout(generateVariants, 200);
    }

    document.addEventListener('DOMContentLoaded', function() {
        syncProductAttributes();
        var groups = document.getElementById('attributeGroups');
        if (groups) {
            groups.addEventListener('change', function() {
                syncProductAttributes();
                scheduleGenerateVariants();
            });
            groups.addEventListener('input', function() {
                syncProductAttributes();
                scheduleGenerateVariants();
            });
        }
    });

    // Summernote Init
    $(document).ready(function() {
        if($('#summernote').length) {
            $('#summernote').summernote({
                placeholder: 'Write a beautiful description for your product...',
                tabsize: 2,
                height: 250,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture', 'video']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ]
            });
        }
    });
</script>
@endsection
