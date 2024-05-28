<?php

namespace App\Events;

use App\Models\Item;
use Illuminate\Foundation\Events\Dispatchable;

class ItemSavedEvent
{
    use Dispatchable;

    public function __construct(public readonly Item $item)
    {
    }
}
