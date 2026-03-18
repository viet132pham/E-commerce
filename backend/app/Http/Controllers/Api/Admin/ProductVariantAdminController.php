<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductVariantAdminController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $data = $request->validate([
            'sku' => ['required', 'string', 'max:100', 'unique:product_variants,sku'],
            'price' => ['required', 'numeric', 'min:0'],
            'compare_at_price' => ['nullable', 'numeric', 'min:0'],
            'stock' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $variant = $product->variants()->create([
            'sku' => $data['sku'],
            'price' => $data['price'],
            'compare_at_price' => $data['compare_at_price'] ?? null,
            'stock' => $data['stock'] ?? 0,
            'is_active' => $data['is_active'] ?? true,
        ]);

        return response()->json($variant, 201);
    }

    public function update(Request $request, ProductVariant $variant)
    {
        $data = $request->validate([
            'sku' => ['sometimes', 'string', 'max:100', Rule::unique('product_variants', 'sku')->ignore($variant->id)],
            'price' => ['sometimes', 'numeric', 'min:0'],
            'compare_at_price' => ['nullable', 'numeric', 'min:0'],
            'stock' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $variant->update($data);

        return response()->json($variant);
    }

    public function destroy(ProductVariant $variant)
    {
        $variant->delete();

        return response()->json(['message' => 'Deleted']);
    }
}
