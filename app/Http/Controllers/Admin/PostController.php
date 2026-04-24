<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    // Menampilkan halaman Post beserta datanya
    public function index()
    {
        // Mengambil semua post, diurutkan dari yang terbaru
        $posts = Post::latest()->get(); 
        
        return view('post', compact('posts'));
    }

    // Menyimpan post baru dari Modal
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            // Gambar dan deskripsi kita buat opsional dulu agar mudah ditest
        ]);

        Post::create([
            'title' => $request->title,
            'description' => $request->description,
            'category' => $request->category,
            // Jika tombol yang ditekan adalah "Publish Now", statusnya published. Jika tidak, draft.
            'status' => $request->has('publish') ? 'published' : 'draft', 
        ]);

        return back()->with('success', 'Post berhasil dibuat!');
    }

    public function create()
    {
        $categories = Category::all();
        return view('post-create', compact('categories'));
    }
}
