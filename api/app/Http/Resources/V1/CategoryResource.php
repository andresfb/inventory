<?php

namespace App\Http\Resources\V1;

use App\Models\Category;
use App\Traits\ApiMetadata;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Category
 */
class CategoryResource extends JsonResource
{
    use ApiMetadata;

    public function toArray(Request $request): array
    {
        return [
            'type' => 'Category',
            'id' => $this->id,
            'attributes' => $this->mergeWhen(
                $request->routeIs('api.v1.categories.*'),
                [
                    'name' => $this->name,
                    'user_id' => $this->user_id,
                    'created_at' => $this->created_at,
                    'updated_at' => $this->updated_at,
                ],
            ),
            'relationships' => [
                'user' => [
                    'data' => [
                        'type' => 'user',
                        'id' => $this->user_id,
                    ],
                    'links' => [
                        'self' => route('api.v1.user.show', $this->user_id),
                    ],
                ]
            ],
            'includes' => [
                'user' => new UserResource($this->whenLoaded('user')),
                'properties' => PropertyResource::collection($this->whenLoaded('properties')),
                'items' => ItemResource::collection($this->whenLoaded('items')),
            ],
            'links' => [
                'self' => route('api.v1.categories.show', $this->id)
            ],
        ];
    }
}
