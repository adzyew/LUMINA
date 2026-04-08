<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductReviewPurchaseGuardTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_cannot_review_without_purchase(): void
    {
        $user = User::factory()->create();
        $product = $this->makeProduct();

        $response = $this->actingAs($user)
            ->from(route('products.show', $product))
            ->post(route('reviews.store', $product), [
                'rating' => 5,
                'comment' => 'Great product!',
            ]);

        $response->assertRedirect(route('products.show', $product));
        $response->assertSessionHas('toast_type', 'error');
        $response->assertSessionHas('toast_message', 'You can review this product only after purchasing it.');
        $this->assertDatabaseCount('reviews', 0);
    }

    public function test_user_can_review_after_purchasing_product(): void
    {
        $user = User::factory()->create();
        $product = $this->makeProduct();

        $order = Order::create([
            'user_id' => $user->id,
            'total_price' => 999,
            'status' => 'pending',
            'payment_method' => 'cod',
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'unit_price' => $product->price,
        ]);

        $response = $this->actingAs($user)
            ->from(route('products.show', $product))
            ->post(route('reviews.store', $product), [
                'rating' => 4,
                'comment' => 'Loved it.',
            ]);

        $response->assertRedirect(route('products.show', $product));
        $response->assertSessionHas('toast_type', 'success');

        $this->assertDatabaseHas('reviews', [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'rating' => 4,
            'comment' => 'Loved it.',
            'status' => 'pending',
        ]);
    }

    private function makeProduct(): Product
    {
        return Product::create([
            'name' => 'Test Product',
            'description' => 'Test Description',
            'price' => 500,
            'category' => 'rings',
            'image_url' => 'https://example.com/product.jpg',
            'image_public_id' => 'test/product',
            'stock_quantity' => 10,
            'is_featured' => false,
        ]);
    }
}
