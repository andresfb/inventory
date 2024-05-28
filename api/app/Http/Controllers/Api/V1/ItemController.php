<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\V1\ItemResource;
use App\Models\Item;
use App\Services\ItemService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ItemController extends ApiController
{
    public function __construct(private readonly ItemService $itemService)
    {
        parent::__construct(Item::listRelationships());
    }

    public function index(Request $request): JsonResponse|AnonymousResourceCollection
    {
        // TODO: Implement the Item RequestValidator and the QueryFilter

        [$values, $perPage] = $this->getValues($request);

        $categories = $this->itemService->getList(auth()->id(), $values, $perPage);
        if ($categories->isEmpty()) {
            return $this->error('no items found', 404);
        }

        return ItemResource::collection($categories);
    }

    public function show()
    {

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
