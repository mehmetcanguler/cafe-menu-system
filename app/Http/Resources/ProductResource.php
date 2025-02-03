<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'slug' => $this->slug,
            'categories' => CategoryResource::collection($this->whenLoaded('categories')),
            'images' => FileResource::collection($this->whenLoaded('files')),
            'category_ids' => $this->category_ids
        ];
    }
}
