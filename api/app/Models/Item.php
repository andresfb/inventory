<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Tags\HasTags;

class Item extends Model implements HasMedia
{
    use SoftDeletes;
    use InteractsWithMedia;
    use HasTags;

    protected $fillable = [
        'name',
        'description',
        'value',
        'quantity',
        'purchase_date',
    ];

    protected function casts(): array
    {
        return [
            'purchase_date' => 'date',
            'quantity' => 'integer',
        ];
    }

    protected function value(): Attribute
    {
        return Attribute::make(
            get: static fn ($value) => $value / 1000,
            set: static fn ($value) => $value * 1000,
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function attributes(): HasMany
    {
        return $this->hasMany(ItemAttribute::class)
            ->with('attribute');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('thumb')
            ->acceptsMimeTypes([
                'image/jpeg',
                'image/png',
                'image/heic',
            ])->singleFile()
            ->useDisk('media');

        $this->addMediaCollection('images')
            ->acceptsMimeTypes([
                'image/jpeg',
                'image/png',
                'image/heic',
            ])
            ->useDisk('media');
    }
}
