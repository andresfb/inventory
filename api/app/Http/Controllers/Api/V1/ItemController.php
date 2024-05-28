<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Filters\V1\ItemFilter;
use App\Http\Resources\V1\ItemResource;
use App\Models\Item;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ItemController extends ApiController
{
    public function index(ItemFilter $filter): AnonymousResourceCollection
    {
        return ItemResource::collection(
            Item::setFilters($filter)
                ->withInfo()
                ->filter()
                ->cachedPaginated()
        );
    }

    public function show(ItemFilter $filter, int $itemId): ItemResource
    {
        return new ItemResource(
            Item::setFilters($filter)
                ->setIdentifier($itemId)
                ->withInfo()
                ->filter()
                ->cachedFirstOfFail()
        );
    }

    public function store()
    {

    }

    public function update()
    {

    }

    public function destroy()
    {

    }
}
