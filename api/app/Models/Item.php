<?php

namespace App\Models;

use App\Events\ItemSavedEvent;
use App\Traits\CacheScopable;
use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Builder;
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
    use Filterable;
    use CacheScopable;

    protected $fillable = [
        'user_id',
        'category_id',
        'name',
        'description',
        'serial_number',
        'value',
        'quantity',
        'purchased_at',
        'notes',
    ];

    protected $dispatchesEvents = [
        'saved' => ItemSavedEvent::class,
    ];

    protected function casts(): array
    {
        return [
            'user_id' => 'integer',
            'category_id' => 'integer',
            'purchased_at' => 'date',
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

    public function properties(): HasMany
    {
        return $this->hasMany(ItemProperty::class)
            ->with('property');
    }

    public function scopeWithInfo(Builder $query): Builder
    {
        return $query->with([
            'category',
            'properties',
        ]);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('thumbnail')
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

        $this->addMediaConversion('thumb')
            ->format('jpg')
            ->width(600)
            ->sharpen(8)
            ->performOnCollections('thumbnail', 'images');
    }

    public static function listRelationships(): array
    {
        return [
            'user',
            'media',
            'tags',
        ];
    }
}
