<?php

namespace App\Services;

use App\Models\Post;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostService
{
    public function store(array $data, ?object $image = null): Post
    {
        $imagePath = null;

        if ($image) {
            $imagePath = $image->store('posts', 'public');
        }

        $title = $data['title_id']
            ?? $data['title_en']
            ?? 'Untitled Post';

        $description = $data['description_id']
            ?? $data['description_en']
            ?? null;

        $content = $data['content_id']
            ?? $data['content_en']
            ?? null;

        $post = Post::create([
            'title' => $title,
        
            'slug' => Str::slug($title) . '-' . time(),
        
            'slug_id' => !empty($data['title_id'])
                ? Str::slug($data['title_id'])
                : null,
        
            'slug_en' => !empty($data['title_en'])
                ? Str::slug($data['title_en'])
                : null,
        
            'description' => $description,
            'content' => $content,
        
            'title_en' => $data['title_en'] ?? null,
            'description_en' => $data['description_en'] ?? null,
            'content_en' => $data['content_en'] ?? null,
        
            'title_id' => $data['title_id'] ?? null,
            'description_id' => $data['description_id'] ?? null,
            'content_id' => $data['content_id'] ?? null,
        
            'image' => $imagePath,
            'status' => $data['status'] ?? 'published',
            'published_at' => now(),
        ]);

        if (!empty($data['categories'])) {
            $post->categories()->attach($data['categories']);
        }

        return $post;
    }

    public function update(array $data, ?object $image = null, Post $post): Post
    {
        $imagePath = $post->image;

        if ($image) {
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }

            $imagePath = $image->store('posts', 'public');
        }

        $title = $data['title_id']
            ?? $data['title_en']
            ?? 'Untitled Post';

        $description = $data['description_id']
            ?? $data['description_en']
            ?? null;

        $content = $data['content_id']
            ?? $data['content_en']
            ?? null;

        $post->update([
            'title' => $title,
            'slug' => Str::slug($title) . '-' . time(),
            'description' => $description,
            'content' => $content,

            'title_en' => $data['title_en'] ?? null,
            'description_en' => $data['description_en'] ?? null,
            'content_en' => $data['content_en'] ?? null,

            'title_id' => $data['title_id'] ?? null,
            'description_id' => $data['description_id'] ?? null,
            'content_id' => $data['content_id'] ?? null,

            'slug_id' => !empty($data['title_id'])
                ? Str::slug($data['title_id'])
                : $post->slug_id,

            'slug_en' => !empty($data['title_en'])
                ? Str::slug($data['title_en'])
                : $post->slug_en,

            'image' => $imagePath,
            'status' => $data['status'] ?? $post->status,
        ]);

        if (isset($data['categories'])) {
            $post->categories()->sync($data['categories']);
        }

        return $post;
    }
}
