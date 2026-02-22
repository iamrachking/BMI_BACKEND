<?php

namespace App\Models\Ecommerce;

use App\Models\Ecommerce\CartItem;
use App\Models\Ecommerce\Category;
use App\Models\Ecommerce\OrderItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'description',
        'price',
        'stock_quantity',
        'image_url',
        'image',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    /** URL de l'image : upload (image) prioritaire, sinon image_url pour l'API */
    public function imageUrl(): ?string
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return $this->image_url ?: null;
    }

    /**
     * Get the category that owns the product.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the cart items for the product.
     */
    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Get the order items for the product.
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
