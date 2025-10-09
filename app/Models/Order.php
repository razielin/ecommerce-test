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
 * @property float  $amount
 * @property int    $order_status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property-read EloquentCollection<int, OrderItem> $items
 * @property-read string $order_status_label
 */
class Order extends Model
{
    public const STATUS_NEW = 1;

    /**
     * @var array<int, string>
     */
    protected static array $statusLabels = [
        self::STATUS_NEW => 'New',
    ];

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'client_name',
        'client_phone',
        'client_address',
        'comment',
        'order_status',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'float',
        'order_status' => 'integer',
    ];

    /**
     * @var array<int, string>
     */
    protected $appends = [
        'order_status_label',
    ];

    public function getOrderStatusLabelAttribute(): string
    {
        return self::$statusLabels[$this->order_status] ?? 'Unknown';
    }

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


