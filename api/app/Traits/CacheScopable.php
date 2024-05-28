<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

trait CacheScopable
{
    use CacheTimeLivable;

    public function scopeCachedGet(Builder $query): Collection
    {
        return Cache::tags($this->filters->validator->tagCacheKey())
            ->remember(
                md5($this->filters->validator->cacheKey()),
                $this->mediumLivedTtlMinutes(),
                function () use ($query) {
                    return $query->get();
                }
            );
    }

    public function scopeCachedPaginated(Builder $query): LengthAwarePaginator
    {
        return Cache::tags($this->filters->validator->tagCacheKey())
            ->remember(
                md5($this->filters->validator->cacheKey()),
                $this->mediumLivedTtlMinutes(),
                function () use ($query) {
                    return $query->paginate($this->filters->validator->perPage());
                }
            );
    }

    public function scopeCachedFirst(Builder $query): ?self
    {
        return Cache::tags($this->filters->validator->tagCacheKey())
            ->remember(
                md5($this->filters->validator->cacheKey()),
                $this->mediumLivedTtlMinutes(),
                function () use ($query) {
                    return $query->first();
                }
            );
    }

    public function scopeCachedFirstOfFail(Builder $query): self
    {
        return Cache::tags($this->filters->validator->tagCacheKey())
            ->remember(
                md5($this->filters->validator->cacheKey()),
                $this->mediumLivedTtlMinutes(),
                function () use ($query) {
                    return $query->firstOrFail();
                }
            );
    }
}
