<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AttributeController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:text,select,radio,checkbox,color,button',
            'values' => 'required|string',
        ]);

        $attribute = Attribute::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']).'-'.Str::random(4),
            'type' => $validated['type'],
            'is_global' => true,
        ]);

        $sortOrder = 0;
        foreach (array_filter(array_map('trim', explode(',', $validated['values']))) as $item) {
            $colorCode = null;
            if (str_contains($item, '=')) {
                [$label, $colorCode] = array_map('trim', explode('=', $item, 2));
            } else {
                $label = $item;
            }

            $attribute->values()->create([
                'value' => $label,
                'color_code' => $colorCode,
                'sort_order' => $sortOrder++,
            ]);
        }

        $attribute->load('values');

        $html = view('admin.partials.attribute-selector', ['attribute' => $attribute, 'selectedValues' => []])->render();

        return response()->json(['html' => $html]);
    }
}
