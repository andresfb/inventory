<?php

namespace App\Listeners;

use App\Services\CategoryService;
use Illuminate\Auth\Events\Registered;

readonly class UserRegisteredListener
{
    public function __construct(private CategoryService $service)
    {
    }

    public function handle(Registered $event): void
    {
        $this->service->seedCategories($event->user);
    }
}
