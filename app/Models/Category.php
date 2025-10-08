<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Category
 *
 * @property int $id
 * @property string $name
 *
 * @property-read EloquentCollection<int, Product> $products
 */
class Category extends Model
{
    /**
     * Use non-standard table name "category" per migration.
     * The table has no timestamps columns.
     */
    protected $table = 'category';

    public $timestamps = false;

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}


