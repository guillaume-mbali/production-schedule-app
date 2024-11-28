<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;

    /**
     * The assignable attributes.
     *
     * @var array
     */
    protected $fillable = ['client_id', 'deadline', 'status'];

    /**
     * Indicates that the primary key is not auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The primary key type is a string (UUID).
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * The attributes that should be cast to dates.
     *
     * @var array
     */
    protected $dates = ['deadline'];

    /**
     * Boot method to generate a UUID automatically for the order ID.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (empty($order->id)) {
                $order->id = (string) Str::uuid();
            }
        });
    }

    /**
     * Relationship with the client.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Relationship with the order items.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
