<?php

namespace App\Traits;

use Illuminate\Support\Carbon;

trait CacheTimeLivable
{
    /**
     * Defaults to 30 minutes
     */
    public function longLivedTtlMinutes(int $adjust = 0): Carbon
    {
        return now()->addMinutes(
            (int) config('settings.constants.cache_ttl.long_lived_minutes') + ($adjust)
        );
    }

    /**
     * Defaults to 30 minutes
     */
    public static function largoTiempoTtlMinutes(int $adjust = 0): Carbon
    {
        return now()->addMinutes(
            (int) config('settings.constants.cache_ttl.long_lived_minutes') + ($adjust)
        );
    }

    /**
     * Defaults to 15 minutes
     */
    public function mediumLivedTtlMinutes(int $adjust = 0): Carbon
    {
        return now()->addMinutes(
            (int) config('settings.constants.cache_ttl.medium_lived_minutes') + ($adjust)
        );
    }

    /**
     * Defaults to 15 minutes
     */
    public static function medioTiempoTtlMinutes(int $adjust = 0): Carbon
    {
        return now()->addMinutes(
            (int) config('settings.constants.cache_ttl.medium_lived_minutes') + ($adjust)
        );
    }

    /**
     * Defaults to 5 minutes
     */
    public function shortLivedTtlMinutes(int $adjust = 0): Carbon
    {
        return now()->addMinutes(
            (int) config('settings.constants.cache_ttl.short_lived_minutes') + ($adjust)
        );
    }

    /**
     * Defaults to 5 minutes
     */
    public static function cortoTiempoTtlMinutes(int $adjust = 0): Carbon
    {
        return now()->addMinutes(
            (int) config('settings.constants.cache_ttl.short_lived_minutes') + ($adjust)
        );
    }

    public function ephemeral(): Carbon
    {
        return now()->addMinute();
    }
}
