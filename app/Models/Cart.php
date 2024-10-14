<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    // Define the fillable fields for the cart
    protected $fillable = ['user_id', 'product_id', 'quantity'];

    // A cart belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // A cart entry holds a product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
