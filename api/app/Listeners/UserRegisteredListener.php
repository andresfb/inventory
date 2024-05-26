<?php

namespace App\Listeners;

use App\Services\CategoryService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Queue\ShouldQueue;

readonly class UserRegisteredListener implements ShouldQueue
{
    public function __construct(private CategoryService $service)
    {
    }

    public function handle(Registered $event): void
    {
        $this->service->seedCategories($event->user);
    }
}
