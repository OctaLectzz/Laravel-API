<?php

namespace App\Http\Resources;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'body' => $this->body,
            'views' => $this->views,
            'likes' => $this->likes->count(),
            'saves' => $this->saves->count(),
            'created_by' => $this->created_by,
            'created_at' => $this->created_at_format,
            'updated_at' => $this->updated_at_format,
            'tags' => $this->tag->pluck('name'),
            'comment' => $this->comments ? $this->comments : null
        ];
    }
}
