<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Client extends Model
{
    /**
     * The assignable attributes.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'address',
        'postal_code',
        'city',
        'country',
        'email',
        'phone_number',
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
     * Boot method to generate a UUID automatically.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    /**
     * Relationship with orders.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
