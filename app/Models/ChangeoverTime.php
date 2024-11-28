<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ProductType;

class ChangeoverTime extends Model
{
    /**
     * The assignable attributes.
     *
     * @var array
     */
    protected $fillable = [
        'from_product_type_id',
        'to_product_type_id',
        'changeover_time',
    ];

    /**
     * Relationship with the 'from' product type.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function fromProductType()
    {
        return $this->belongsTo(ProductType::class, 'from_product_type_id');
    }

    /**
     * Relationship with the 'to' product type.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function toProductType()
    {
        return $this->belongsTo(ProductType::class, 'to_product_type_id');
    }
}
