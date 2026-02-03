<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'name' => 'Classic Gold Ring',
                'description' => 'Timeless elegance in 18K gold',
                'price' => 2499.00,
                'image_path' => 'public\IMAGES\ring_1.jpeg', // Make sure this matches your file
                'is_featured' => true,
                'stock_quantity' => 10
            ],
            [
                'name' => 'Luxury Watch',
                'description' => 'Swiss craftsmanship excellence',
                'price' => 3899.00,
                'image_path' => 'public\IMAGES\watch_1.jpg', // You will need a watch image
                'is_featured' => true,
                'stock_quantity' => 5
            ],
            [
                'name' => 'Diamond Ring',
                'description' => 'Brilliant cut perfection',
                'price' => 8999.00,
                'image_path' => 'public\IMAGES\Ring.jpg',
                'is_featured' => true,
                'stock_quantity' => 3
            ],
            [
                'name' => 'Emerald Luxury Ring',
                'description' => 'Natural emerald statement piece',
                'price' => 6799.00,
                'image_path' => 'public\IMAGES\ring_1.jpeg',
                'is_featured' => true,
                'stock_quantity' => 2
            ]
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}