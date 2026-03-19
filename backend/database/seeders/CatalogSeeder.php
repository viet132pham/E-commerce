<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CatalogSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Electronics',
            'Fashion',
            'Home & Living',
            'Beauty',
        ];

        $categoryModels = collect($categories)->map(function ($name) {
            return Category::create([
                'name' => $name,
                'slug' => Str::slug($name),
                'is_active' => true,
            ]);
        });

        $products = [
            [
                'name' => 'Wireless Headphones',
                'category' => 'Electronics',
                'description' => 'Comfortable over-ear headphones with noise isolation.',
                'variants' => [
                    ['sku' => 'WH-BASE', 'price' => 49.90, 'stock' => 50],
                    ['sku' => 'WH-PRO', 'price' => 79.90, 'stock' => 30],
                ],
                'images' => [
                    'https://picsum.photos/seed/headphones/800/800',
                ],
            ],
            [
                'name' => 'Everyday Backpack',
                'category' => 'Fashion',
                'description' => 'Lightweight backpack for daily use.',
                'variants' => [
                    ['sku' => 'BP-BLACK', 'price' => 29.90, 'stock' => 120],
                    ['sku' => 'BP-GREY', 'price' => 29.90, 'stock' => 90],
                ],
                'images' => [
                    'https://picsum.photos/seed/backpack/800/800',
                ],
            ],
            [
                'name' => 'Ceramic Mug Set',
                'category' => 'Home & Living',
                'description' => 'Set of 4 minimal ceramic mugs.',
                'variants' => [
                    ['sku' => 'MUG-SET-4', 'price' => 24.50, 'stock' => 70],
                ],
                'images' => [
                    'https://picsum.photos/seed/mug/800/800',
                ],
            ],
            [
                'name' => 'Skincare Starter Kit',
                'category' => 'Beauty',
                'description' => 'Cleanser, moisturizer, and SPF combo pack.',
                'variants' => [
                    ['sku' => 'SK-START', 'price' => 39.00, 'stock' => 60],
                ],
                'images' => [
                    'https://picsum.photos/seed/skincare/800/800',
                ],
            ],
        ];

        foreach ($products as $productData) {
            $category = $categoryModels->firstWhere('name', $productData['category']);

            $product = Product::create([
                'category_id' => $category?->id,
                'name' => $productData['name'],
                'slug' => Str::slug($productData['name']),
                'description' => $productData['description'],
                'is_active' => true,
            ]);

            foreach ($productData['variants'] as $variant) {
                $product->variants()->create([
                    'sku' => $variant['sku'],
                    'price' => $variant['price'],
                    'compare_at_price' => $variant['compare_at_price'] ?? null,
                    'stock' => $variant['stock'] ?? 0,
                    'is_active' => true,
                ]);
            }

            foreach ($productData['images'] as $url) {
                $product->images()->create([
                    'url' => $url,
                    'sort_order' => 0,
                ]);
            }
        }
    }
}
