<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GalleryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'        => $this->id,
            'title'     => $this->title,
            'category'  => $this->category,
            'image'     => $this->image
                        ? (str_starts_with($this->image, 'http') 
                            ? $this->image
                            : asset('storage/' . $this->image))  // path relatif
                        : null,
        ];
    }
}
