<?php

namespace App\Http\Resources;

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
        $isDetail = str_contains($request->url(), '/articles/');
    
        $locale = request()->route('locale') ?? 'id';
    
        return [
            'id' => $this->id,
    
            'slug' => $locale === 'id'
                ? ($this->slug_id ?? $this->slug)
                : ($this->slug_en ?? $this->slug),

            'slug_id' => $this->slug_id ?? $this->slug,
            'slug_en' => $this->slug_en ?? $this->slug,
    
            'title' => $locale === 'id'
                ? ($this->title_id ?? $this->title_en)
                : ($this->title_en ?? $this->title_id),

            'excerpt' => $locale === 'id'
                ? ($this->description_id ?? $this->description_en)
                : ($this->description_en ?? $this->description_id),
    
            'content' => $isDetail
                ? (
                    $locale === 'id'
                        ? ($this->content_id ?? $this->content_en)
                        : ($this->content_en ?? $this->content_id)
                )
                : null,
    
            // tetap kirim versi raw bilingual
            'title_id' => $this->title_id,
            'title_en' => $this->title_en,
    
            'content_id' => $this->content_id,
            'content_en' => $this->content_en,

            'thumbnail_url' => $this->image
                ? (str_starts_with($this->image, 'http') ? $this->image : asset('storage/' . $this->image))
                : asset('images/default-thumbnail.jpg'),

            'published_at' => $this->published_at?->toDateTimeString(),
        ];
    }
}
