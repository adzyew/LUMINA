<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    // 1. Allowed Mass-Assignment Fields
    // This lets you run Category::create(['name' => 'Rings', ...]) safely.
    protected $fillable = [
        'name',
        'slug',
        'description',
        'image_path',
    ];

    // 2. Relationship: One Category has Many Products
    // This allows you to call: $category->products
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}