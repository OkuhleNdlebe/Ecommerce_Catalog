<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'price', 'description', 'stock', 'image_url'];

    // A product can belong to many cart entries
    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    // Check if stock is available
    public function isAvailable($quantity)
    {
        return $this->stock >= $quantity;
    }

    // Reduce stock by a given quantity
    public function reduceStock($quantity)
    {
        if ($this->isAvailable($quantity)) {
            $this->stock -= $quantity;
            $this->save();
        } else {
            throw new \Exception('Not enough stock available');
        }
    }

    // Increase stock by a given quantity (for when items are removed from cart)
    public function increaseStock($quantity)
    {
        $this->stock += $quantity;
        $this->save();
    }
}


