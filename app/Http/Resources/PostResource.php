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

        return [
            // Identitas
            'id'            => $this->id,
            'slug'          => $this->slug,

            // Konten utama
            'title'         => $this->title,
            'title_en'      => $this->title_en,
            'excerpt'       => $this->description,
            'excerpt_en'    => $this->description_en,

            // Media
            'thumbnail_url' => $this->image
                        ? (str_starts_with($this->image, 'http')
                            ? $this->image
                            : asset('storage/' . $this->image))
                        : null,
            'image_alt'     => $this->title, // fallback, tidak ada kolom image_alt

            // SEO
            'meta_description' => $this->description,
            'og_image_url'  => $this->image
                        ? (str_starts_with($this->image, 'http')
                            ? $this->image
                            : asset('storage/' . $this->image))
                        : asset('images/og-default.jpg'),

            // Timestamp
            'published_at'  => $this->created_at?->toDateTimeString(),
            'updated_at'    => $this->updated_at?->toDateTimeString(),

            // Hanya ada di endpoint detail (/articles/{slug})
            'content'       => $isDetail ? $this->content : null,
            'content_en'    => $isDetail ? $this->content_en : null,
        ];
    }
}
