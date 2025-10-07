<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * Class Order
 *
 * @property int $id
 * @property string $client_name
 * @property string $client_phone
 * @property string $client_address
 * @property string $comment
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property-read EloquentCollection<int, OrderItem> $items
 */
class Order extends Model
{

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'client_name',
        'client_phone',
        'client_address',
        'comment',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Shortcut relation to access products of the order through items.
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'order_items')
            ->withPivot('quantity');
    }
}


