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

        $post = Post::create([
            'title'       => $data['title'],
            'slug'        => Str::slug($data['title']) . '-' . time(),
            'description' => $data['description'] ?? null,
            'content'     => $data['content'] ?? null,
            'image'       => $imagePath,
            'status'      => $data['status'] ?? 'published',
        ]);

        if (!empty($data['categories'])) {
            $post->categories()->attach($data['categories']);
        }

        return $post;
    }

    public function update(array $data, ?object $image = null, Post $post): Post
    {
        $imagePath = $post->image; // keep existing image
        if ($image) {
            // Hapus gambar lama
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }
            $imagePath = $image->store('posts', 'public');
        }
    
        $post->update([
            'title'       => $data['title'],
            'slug'        => \Illuminate\Support\Str::slug($data['title']) . '-' . time(),
            'description' => $data['description'] ?? null,
            'content'     => $data['content'] ?? null,
            'image'       => $imagePath,
            'status'      => $data['status'] ?? $post->status,
        ]);
    
        if (isset($data['categories'])) {
            $post->categories()->sync($data['categories']);
        }
    
        return $post;
    }
}