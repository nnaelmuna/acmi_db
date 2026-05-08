<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Category;
use App\Models\Post;
use App\Services\PostService;
use App\Services\TabFilterService;

class PostController extends Controller
{
    public function __construct(protected PostService $postService)
    {
        //
    }

    // Menampilkan halaman Post beserta datanya
    public function index(Request $request)
    {
        $status = $request->get('status', 'published');
        // Query berbeda untuk tab Trash vs tab lainnya
        if ($status === 'trash') {
            $posts = Post::onlyTrashed()->latest()->get();
        } else {
            $posts = Post::where('status', $status)->latest()->get();
        }

        // Satu baris ini menggantikan array $counts yang panjang
        $tabs = TabFilterService::getTabs(Post::class);
        $categories = Category::all();

        return view('post', compact('posts', 'tabs', 'status', 'categories'));
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

    public function destroy(Post $post)
    {
        $post->delete();

        return redirect()->route('post')->with('success', 'Post berhasil dihapus.');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('ids', []);

        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'No posts selected']);
        }

        Post::whereIn('id', $ids)->delete(); // soft delete karena model pakai SoftDeletes

        return response()->json(['success' => true]);
    }

    public function update(UpdatePostRequest $request, Post $post)
    {
        $this->postService->update($request->validated(), $request->file('image'), $post);
        return redirect()->route('post')->with('success', 'Post berhasil diperbarui!');
    }

    public function restore($id)
    {
        // withTrashed() diperlukan agar bisa menemukan data yang sudah soft-deleted
        $post = Post::withTrashed()->findOrFail($id);
        $post->restore();

        return redirect()->route('posts.index', ['status' => 'trash'])
            ->with('success', "Post \"{$post->title}\" berhasil dipulihkan.");
    }

    public function forceDelete($id)
    {
        $post = Post::withTrashed()->findOrFail($id);
        $post->forceDelete();

        return redirect()->route('posts.index', ['status' => 'trash'])
            ->with('success', "Post \"{$post->title}\" berhasil dihapus permanen.");
    }
}
