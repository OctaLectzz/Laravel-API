<?php

namespace App\Http\Resources;

use App\Models\Comment;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\CommentResource;


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
            'tags' => $this->tag ? $this->tag : null,
            'comments' => CommentResource::collection($this->comments)
        ];
    }
}
