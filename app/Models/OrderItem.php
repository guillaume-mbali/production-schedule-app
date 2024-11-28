<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class OrderItem extends Model
{
    use HasFactory;

    /**
     * The assignable attributes.
     *
     * @var array
     */
    protected $fillable = [
        'product_id',
        'order_id',
        'quantity',
        'price',
    ];

    /**
     * Indicates that the primary key is a UUID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates that the primary key is not auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Boot method to generate a UUID automatically for the item ID.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // If the item ID is empty, generate a UUID
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    /**
     * Relationship with the product.
     * Each item is associated with a product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Relationship with the production schedules.
     * Each item can have many production schedules.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function productionSchedules()
    {
        return $this->hasMany(ProductionSchedule::class);
    }

    /**
     * Relationship with the order.
     * Each item belongs to an order.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
