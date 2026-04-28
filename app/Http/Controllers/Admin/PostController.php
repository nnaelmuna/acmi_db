<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Category;
use App\Models\Post;
use App\Services\PostService;

class PostController extends Controller
{
    public function __construct(protected PostService $postService)
    {
        //
    }

    // Menampilkan halaman Post beserta datanya
    public function index()
    {
        $posts = Post::latest()->get();

        return view('post', compact('posts'));
    }

    // Menampilkan form buat post baru
    public function create()
    {
        $categories = Category::all();

        return view('post-create', compact('categories'));
    }

    // Menyimpan post baru
    public function store(StorePostRequest $request)
    {
        $this->postService->store(
            $request->validated(),
            $request->file('image')
        );

        return redirect()->route('post')->with('success', 'Post berhasil dipublikasikan!');
    }

    public function edit(Post $post)
    {
        $post->load('categories');
        $categories = Category::all();
        return view('post-edit', compact('post', 'categories'));
    }
    
    public function update(UpdatePostRequest $request, Post $post)
    {
        $this->postService->update($request->validated(), $request->file('image'), $post);
        return redirect()->route('post')->with('success', 'Post berhasil diperbarui!');
    }
}