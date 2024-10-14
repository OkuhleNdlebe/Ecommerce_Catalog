<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

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

    // Add product to cart (and reduce stock accordingly)
    public function addProduct($productId, $quantity)
    {
        $product = Product::find($productId);

        if ($product && $product->isAvailable($quantity)) {
            // Check if the product is already in the cart for this user
            $cartItem = $this->where('user_id', auth()->id())
                             ->where('product_id', $productId)
                             ->first();

            if ($cartItem) {
                // Update the quantity if the product is already in the cart
                $cartItem->quantity += $quantity;
                $cartItem->save();
            } else {
                // Add new item to the cart
                $this->create([
                    'user_id' => auth()->id(),
                    'product_id' => $productId,
                    'quantity' => $quantity
                ]);
            }

            // Reduce stock
            $product->reduceStock($quantity);
        } else {
            throw new \Exception('Product not available or insufficient stock');
        }
    }

    // Remove product from cart (and increase stock accordingly)
    public function removeProduct($productId, $quantity)
    {
        $cartItem = $this->where('user_id', auth()->id())
                         ->where('product_id', $productId)
                         ->first();

        if ($cartItem) {
            // Decrease quantity or remove item if quantity becomes zero
            if ($cartItem->quantity > $quantity) {
                $cartItem->quantity -= $quantity;
                $cartItem->save();
            } else {
                // If quantity is less than or equal to the removed quantity, delete the cart item
                $cartItem->delete();
            }

            // Increase stock after removing the product from the cart
            $cartItem->product->increaseStock($quantity);
        } else {
            throw new \Exception('Product not found in cart');
        }
    }
}
