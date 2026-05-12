<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'title'        => $this->title,
            'category'     => $this->category,
            'company_name' => $this->company_name,
            'ceo_name'     => $this->ceo_name,
            'description'  => $this->description,
            'features'     => $this->features,
            'website'      => $this->website,
            'image' => $this->image
                    ? (str_starts_with($this->image, 'http') 
                        ? $this->image
                        : asset('storage/' . $this->image))  // path relatif
                    : null,
            'images'       => $this->images,
        ];
    }
}
