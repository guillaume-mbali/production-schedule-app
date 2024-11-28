<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ProductType extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'production_speed',
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
     * Boot method to generate a UUID automatically for the product type ID.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // If the product type ID is empty, generate a UUID
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    /**
     * Relationship with orders (`Order`).
     * A product type can have many orders through products.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function orders()
    {
        return $this->hasManyThrough(Order::class, Product::class, 'product_type_id', 'product_id');
    }

    /**
     * Relationship with products.
     * A product type can have many products.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function products()
    {
        return $this->hasMany(Product::class, 'product_type_id');
    }

    /**
     * Relationship with changeover times as the "from" product type.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function changeoverTimesFrom()
    {
        return $this->hasMany(ChangeoverTime::class, 'from_product_type_id');
    }

    /**
     * Relationship with changeover times as the "to" product type.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function changeoverTimesTo()
    {
        return $this->hasMany(ChangeoverTime::class, 'to_product_type_id');
    }
}
