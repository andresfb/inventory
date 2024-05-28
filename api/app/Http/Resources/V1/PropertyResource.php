<?php

namespace App\Http\Resources\V1;

use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Property
 */
class PropertyResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'type' => 'Property',
            'id' => $this->id,
            'attributes' => [
                'category_id' => $this->category_id,
                'name' => $this->name,
            ],
            'relationships' => $this->when(
                $request->routeIs('api.v1.properties.*'),
                [
                    'categories' => [
                        'data' => [
                            'type' => 'Category',
                            'id' => $this->category_id,
                        ],
                        'links' => ['self' => route('api.v1.categories.show', $this->category_id)],
                    ]
                ],
            ),
            'includes' => $this->whenLoaded(
                'categories',
                [
                    'categories' => CategoryResource::collection($this->whenLoaded('categories')),
                ],
            ),
            'links' => [
                'self' => route(
                    'api.v1.properties.show',
                    [
                        'categoryId' => $this->category_id,
                        'propertyId' => $this->id
                    ]
                ),
            ],
        ];
    }
}
