<?php

namespace App\Http\Resources\V1;

use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Media
 */
class MediaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'type' =>'Media',
            'id' => $this->id,
            'attributes' => [
                'uuid' => $this->uuid,
                'collection_name' => $this->collection_name,
                'human_readable_size' => $this->human_readable_size,
                'mime_type' => $this->mime_type,
                'order_column' => $this->order_column,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
            ],
            'links' => [
                'self' => $this->getUrl(),
                'thumbnail' => $this->getUrl('thumb')
            ]
        ];
    }
}
