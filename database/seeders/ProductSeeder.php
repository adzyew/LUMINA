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
                'category' => 'Rings',
                'image_url' => '/IMAGES/ring_1.jpeg',
                'image_public_id' => 'local_ring_1',
                'is_featured' => true,
                'stock' => 10,
                'stock_quantity' => 10,
            ],
            [
                'name' => 'Luxury Watch',
                'description' => 'Swiss craftsmanship excellence',
                'price' => 3899.00,
                'category' => 'Watches',
                'image_url' => '/IMAGES/watch_1.jpg',
                'image_public_id' => 'local_watch_1',
                'is_featured' => true,
                'stock' => 5,
                'stock_quantity' => 5,
            ],
            [
                'name' => 'Diamond Ring',
                'description' => 'Brilliant cut perfection',
                'price' => 8999.00,
                'category' => 'Rings',
                'image_url' => '/IMAGES/Ring.jpg',
                'image_public_id' => 'local_ring_2',
                'is_featured' => true,
                'stock' => 3,
                'stock_quantity' => 3,
            ],
            [
                'name' => 'Emerald Luxury Ring',
                'description' => 'Natural emerald statement piece',
                'price' => 6799.00,
                'category' => 'Rings',
                'image_url' => '/IMAGES/ring_1.jpeg',
                'image_public_id' => 'local_ring_3',
                'is_featured' => true,
                'stock' => 2,
                'stock_quantity' => 2,
            ],
            [
                'name' => 'Golden Bracelet',
                'description' => 'Exquisite golden link bracelet',
                'price' => 3200.00,
                'category' => 'Bracelets',
                'image_url' => '/IMAGES/Bracelet.jpg',
                'image_public_id' => 'local_bracelet_1',
                'is_featured' => true,
                'stock' => 8,
                'stock_quantity' => 8,
            ],
            [
                'name' => 'Diamond Necklace',
                'description' => 'Shimmering diamond pendant necklace',
                'price' => 5400.00,
                'category' => 'Necklaces',
                'image_url' => '/IMAGES/Necklace.jpg',
                'image_public_id' => 'local_necklace_1',
                'is_featured' => true,
                'stock' => 6,
                'stock_quantity' => 6,
            ],
        ];

        foreach ($products as $product) {
            Product::firstOrCreate(
                ['name' => $product['name']],
                $product
            );
        }
    }
}