<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
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
            'datasource' => $this->datasource,
            'source' => $this->source,
            'content' => $this->content,
            'image_url' => $this->image_url,
            'source_url' => $this->source_url,
            'published_at' => $this->published_at,
            'authors' =>  $this->authors?->map(fn($author) => AuthorResource::make($author)) ?? [],
            'categories' => $this->categories?->map(fn($category) => CategoryResource::make($category)) ?? [],
        ];
    }
}
