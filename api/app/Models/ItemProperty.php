<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemProperty extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'item_id',
        'attribute_id',
        'value_type',
        'value',
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
}
