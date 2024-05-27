<?php

namespace App\Http\Resources\Api\V1;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin User
 */
class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'type' => 'user',
            'id' => $this->id,
            'attributes' => [
                'name' => $this->name,
                'email' => $this->email,
                $this->mergeWhen(
                    $request->routeIs('api.v1.user.show'),
                    [
                        'created_at' => $this->created_at,
                        'updated_at' => $this->updated_at,
                        'email_verified_at' => $this->email_verified_at,
                    ]
                ),
            ],
            'includes' => [
                'categories' => CategoryResource::collection($this->whenLoaded('categories')),
            ],
            'links' => [
                'self' => route('api.v1.user.show', $this->id),
            ],
        ];
    }
}
