<?php

namespace App\Http\Resources\V1;

use App\Models\Item;
use App\Models\ItemProperty;
use App\Traits\ApiMetadata;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Item
 */
class ItemResource extends JsonResource
{
    use ApiMetadata;

    public function toArray(Request $request): array
    {
        return [
            'type' => 'Item',
            'id' => $this->id,
            'attributes' => $this->mergeWhen(
                $request->routeIs('api.v1.items.*'),
                [
                    'name' => $this->name,
                    'description' => $this->description,
                    'serial_number' => $this->serial_number,
                    'quantity' => $this->quantity,
                    'value' => $this->value,
                    'notes' => $this->notes,
                    'purchase_date' => $this->purchase_date,
                    'category' => $this->category->name,
                    'properties' => $this->properties->map(function (ItemProperty $itemProperty) {
                        return [
                            $itemProperty->property->name => $itemProperty->value,
                        ];
                    })->flatten()
                    ->toArray(),
                    'created_at' => $this->created_at,
                    'updated_at' => $this->updated_at,
                ]
            ),
            'relationships' => [
                'user' => [
                    'data' => [
                        'type' => 'User',
                        'id' => $this->user_id,
                    ],
                    'links' => [
                        'self' => route('api.v1.user.show', $this->id),
                    ],
                ],
                'category' => [
                    'data' => [
                        'type' => 'Category',
                        'id' => $this->category_id,
                    ],
                    'links' => [
                        'self' => route('api.v1.categories.show', $this->category_id),
                    ],
                ]
            ],
            'includes' => [
                'user' => new UserResource($this->whenLoaded('user')),
                'tags' => TagResource::collection($this->whenLoaded('tags')),
                'media' => MediaResource::collection($this->whenLoaded('media')),
            ],
            'links' => [
                'self' => route('api.v1.items.show', $this->id)
            ],
        ];
    }
}
