<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductAdminController extends ApiController
{
    public function index()
    {
        return $this->paginated(Product::with(['variants', 'images'])->orderBy('id', 'desc')->paginate(20));
    }

    public function show(Product $product)
    {
        $product->load(['variants', 'images']);

        return $this->success($product);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:products,slug'],
            'description' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
            'variants' => ['required', 'array', 'min:1'],
            'variants.*.sku' => ['required', 'string', 'max:100', 'distinct', 'unique:product_variants,sku'],
            'variants.*.price' => ['required', 'numeric', 'min:0'],
            'variants.*.compare_at_price' => ['nullable', 'numeric', 'min:0'],
            'variants.*.stock' => ['nullable', 'integer', 'min:0'],
            'variants.*.is_active' => ['nullable', 'boolean'],
        ]);

        $product = Product::create($data);

        foreach ($data['variants'] as $variant) {
            $product->variants()->create([
                'sku' => $variant['sku'],
                'price' => $variant['price'],
                'compare_at_price' => $variant['compare_at_price'] ?? null,
                'stock' => $variant['stock'] ?? 0,
                'is_active' => $variant['is_active'] ?? true,
            ]);
        }

        $product->load(['variants', 'images']);

        return $this->success($product, 'Created', 201);
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'name' => ['sometimes', 'string', 'max:255'],
            'slug' => ['sometimes', 'string', 'max:255', Rule::unique('products', 'slug')->ignore($product->id)],
            'description' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $product->update($data);

        $product->load(['variants', 'images']);

        return $this->success($product);
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return $this->success(null, 'Deleted');
    }
}
