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
            'slug'         => $this->slug,
            'title'        => $this->title,
            'category'     => $this->category,
            'company_name' => $this->company_name,
            'ceo_name'     => $this->ceo_name,
            'description'  => $this->description,
            'features'     => $this->features,
            'website'      => $this->website,
            'address'      => $this->address,
            'email'        => $this->email,
            'phone'        => $this->phone,
            'image' => $this->image
                ? (str_starts_with(trim($this->image, '"'), 'http')
                    ? trim($this->image, '"')
                    : asset('storage/' . trim($this->image, '"')))
                : null,
            'images'       => $this->images,
            'gallery'      => collect(
                is_array($this->images)
                    ? $this->images
                    : json_decode($this->images, true) ?? []
            )->map(
                fn($img) =>
                str_starts_with(trim($img, '"'), 'http')
                    ? trim($img, '"')
                    : asset('storage/' . trim($img, '"'))
            )->values()->toArray(),
        ];
    }
}
