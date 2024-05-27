<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function properties(): HasMany
    {
        return $this->hasMany(Property::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(Item::class);
    }

    public function scopeWithInfo(Builder $query): Builder
    {
        return $query->with([
            'user',
            'properties'
        ]);
    }

    public static function listRelationships(): array
    {
        return [
            'user',
            'properties',
            'items',
        ];
    }
}
