<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cart extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'user_id',
        'gest_session_id',
    ];

    /**
     * Get the user that owns the cart
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the items for the cart
     *
     * @return hasMany
     */
    public function cartItems(): hasMany
    {
        return $this->hasMany(CartItem::class);
    }
}
