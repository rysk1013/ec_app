<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CartItem extends Model
{
    use SoftDeteles, HasFactory;

    protected $fillable = [
        'cart_id',
        'product_id',
        'quantity',
    ];

    /**
     * Get the cart that owns the cart item
     *
     * @return belongsTo
     */
    public function cart(): belongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    /**
     * Get the product that owns the cart item
     *
     * @return belongsTo
     */
    public function product(): belongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
