@php
    $valueIdMap = json_encode($attribute->values->mapWithKeys(fn ($v) => [strtolower($v->value) => $v->id])->all());
    $allValuesJson = json_encode($attribute->values->map(fn ($v) => ['id' => $v->id, 'label' => $v->value])->values()->all());
    $isSelected = fn ($id) => in_array($id, $selectedValues);
    $prices = $prices ?? [];
@endphp

<div class="attribute-group mb-4" data-attribute-group data-attr-id="{{ $attribute->id }}" data-all-values="{{ $allValuesJson }}">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <div class="d-flex align-items-center gap-3">
            <label class="form-label fw-bold mb-0">{{ $attribute->name }}</label>
            <div class="form-check form-switch form-check-inline mb-0">
                <input class="form-check-input" type="checkbox" role="switch" name="attribute_required[]"
                       id="attrReq{{ $attribute->id }}" value="{{ $attribute->id }}" @checked($attribute->is_required)>
                <label class="form-check-label small" for="attrReq{{ $attribute->id }}">Required</label>
            </div>
        </div>
        <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeAttributeGroup(this)" title="Remove this attribute from this product"><i class="fa fa-times"></i> Remove</button>
    </div>

    @if($attribute->type === 'select')
        <select class="form-select form-select-sm attribute-value" data-attr-id="{{ $attribute->id }}">
            <option value="">Select an option...</option>
            @foreach($attribute->values as $value)
                <option value="{{ $value->id }}" data-label="{{ $value->value }}" @selected($isSelected($value->id))>{{ $value->value }}</option>
            @endforeach
        </select>
        <div class="d-flex flex-wrap gap-2 mt-2">
            @foreach($attribute->values as $value)
                <div class="d-inline-flex align-items-center gap-1">
                    <span class="small text-muted">{{ $value->value }}</span>
                    <input type="number" step="0.01" class="form-control form-control-sm option-price" style="width:90px"
                           name="option_price[{{ $value->id }}]" value="{{ $prices[$value->id] ?? '' }}" placeholder="+₹0">
                </div>
            @endforeach
        </div>
        <small class="text-muted">Option prices are summed into each generated variant's price adjustment.</small>

    @elseif($attribute->type === 'radio')
        @foreach($attribute->values as $value)
            <div class="d-inline-flex align-items-center form-check form-check-inline mb-2">
                <input class="form-check-input attribute-value" type="radio" name="attribute_{{ $attribute->id }}"
                       id="attr{{ $attribute->id }}_val{{ $value->id }}" value="{{ $value->id }}"
                       data-attr-id="{{ $attribute->id }}" data-label="{{ $value->value }}"
                       @checked($isSelected($value->id))>
                <label class="form-check-label me-1" for="attr{{ $attribute->id }}_val{{ $value->id }}">{{ $value->value }}</label>
                <input type="number" step="0.01" class="form-control form-control-sm option-price" style="width:90px"
                       name="option_price[{{ $value->id }}]" value="{{ $prices[$value->id] ?? '' }}" placeholder="+₹0">
            </div>
        @endforeach

    @elseif($attribute->type === 'checkbox')
        @foreach($attribute->values as $value)
            <div class="d-inline-flex align-items-center form-check form-check-inline mb-2">
                <input class="form-check-input attribute-value" type="checkbox" name="attribute_{{ $attribute->id }}[]"
                       id="attr{{ $attribute->id }}_val{{ $value->id }}" value="{{ $value->id }}"
                       data-attr-id="{{ $attribute->id }}" data-label="{{ $value->value }}"
                       @checked($isSelected($value->id))>
                <label class="form-check-label me-1" for="attr{{ $attribute->id }}_val{{ $value->id }}">{{ $value->value }}</label>
                <input type="number" step="0.01" class="form-control form-control-sm option-price" style="width:90px"
                       name="option_price[{{ $value->id }}]" value="{{ $prices[$value->id] ?? '' }}" placeholder="+₹0">
            </div>
        @endforeach

    @elseif($attribute->type === 'color')
        <div class="d-flex flex-wrap gap-2">
            @foreach($attribute->values as $value)
                <div class="d-inline-flex flex-column align-items-center mb-2">
                    <input type="radio" class="btn-check attribute-value" name="attribute_{{ $attribute->id }}"
                           id="attr{{ $attribute->id }}_val{{ $value->id }}" value="{{ $value->id }}"
                           data-attr-id="{{ $attribute->id }}" data-label="{{ $value->value }}"
                           @checked($isSelected($value->id))>
                    <label class="btn btn-outline-secondary btn-sm" for="attr{{ $attribute->id }}_val{{ $value->id }}">
                        <span style="display:inline-block;width:14px;height:14px;border-radius:50%;background:{{ $value->color_code ?? '#ccc' }};vertical-align:-2px;margin-right:4px;"></span>
                        {{ $value->value }}
                    </label>
                    <input type="number" step="0.01" class="form-control form-control-sm option-price mt-1" style="width:90px"
                           name="option_price[{{ $value->id }}]" value="{{ $prices[$value->id] ?? '' }}" placeholder="+₹0">
                </div>
            @endforeach
        </div>

    @elseif($attribute->type === 'button')
        <div class="d-flex flex-wrap gap-2">
            @foreach($attribute->values as $value)
                <div class="d-inline-flex flex-column align-items-center mb-2">
                    <input type="radio" class="btn-check attribute-value" name="attribute_{{ $attribute->id }}"
                           id="attr{{ $attribute->id }}_val{{ $value->id }}" value="{{ $value->id }}"
                           data-attr-id="{{ $attribute->id }}" data-label="{{ $value->value }}"
                           @checked($isSelected($value->id))>
                    <label class="btn btn-outline-secondary btn-sm" for="attr{{ $attribute->id }}_val{{ $value->id }}">{{ $value->value }}</label>
                    <input type="number" step="0.01" class="form-control form-control-sm option-price mt-1" style="width:90px"
                           name="option_price[{{ $value->id }}]" value="{{ $prices[$value->id] ?? '' }}" placeholder="+₹0">
                </div>
            @endforeach
        </div>

    @else
        <input type="text" class="form-control form-control-sm attribute-value" data-attr-id="{{ $attribute->id }}"
               data-values="{{ $valueIdMap }}" data-label=""
               placeholder="Enter {{ $attribute->name }}">
        <small class="text-muted">Match an existing option ({{ $attribute->values->pluck('value')->take(3)->implode(', ') }}) or type a new value.</small>
    @endif
</div>
