<?php

namespace App\Listeners;

use App\Events\ItemSavedEvent;
use App\Models\Item;
use Illuminate\Contracts\Queue\ShouldQueue;
use Exception;

class ItemSavedListener implements ShouldQueue
{
    public function handle(ItemSavedEvent $event): void
    {
        $item = Item::where('id', $event->item->id)->first();
        if ($item === null) {
            return;
        }

        if ($item->hasMedia('thumbnail')) {
            return;
        }

        if (!$item->hasMedia('image')) {
            return;
        }

        try {
            $item->addMedia(
                $item->getMedia('image')->first()->getPath()
            )->preservingOriginal()
            ->toMediaCollection('thumbnail');
        } catch (Exception) {
            return;
        }
    }
}
