<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attribute extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'category_id',
        'name',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
