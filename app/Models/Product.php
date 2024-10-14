<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // Define the fillable fields to protect against mass assignment vulnerability
    protected $fillable = ['name', 'price', 'description', 'stock', 'image_url'];

    // A product can belong to many carts (a cart holds multiple products)
    public function carts()
    {
        return $this->hasMany(Cart::class);
    }
}

