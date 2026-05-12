<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PartnerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'name'       => $this->name,
            'image' => $this->image
                    ? (str_starts_with($this->image, 'http') 
                        ? $this->image
                        : asset('storage/' . $this->image))
                    : null,
            'link'       => $this->link,
            'start_date' => $this->start_date,
            'end_date'   => $this->end_date,
        ];
    }
}
