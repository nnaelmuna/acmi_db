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
        $category = $request->get('category');
        $search = $request->get('search');

        $query = Post::query();

        if ($status === 'trash') {
            $query->onlyTrashed();
        } else {
            $query->where('status', $status);
        }

        if ($category) {
            $query->whereHas('categories', function ($q) use ($category) {
                $q->where('name', $category);
            });
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                    ->orWhere('content', 'like', '%' . $search . '%');
            });
        }

        $posts = $query->latest()
            ->paginate(10)
            ->withQueryString();

        $tabs = TabFilterService::getTabs(Post::class);
        $categories = Category::latest()->get();

        return view('post', compact('posts', 'tabs', 'categories'));
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

        return redirect()->route('post')->with('success', 'Post created successfully');
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

        return redirect()->route('post')->with('success', 'Post moved to trash successfully');
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
        return redirect()->route('post')->with('success', 'Post updated successfully');
    }

    public function restore($id)
    {
        // withTrashed() diperlukan agar bisa menemukan data yang sudah soft-deleted
        $post = Post::withTrashed()->findOrFail($id);
        $post->restore();

        return redirect()->route('post', ['status' => 'trash'])
            ->with('success', "Post \"{$post->title}\" restored successfully");
    }

    public function forceDelete($id)
    {
        $post = Post::onlyTrashed()->findOrFail($id);

        $post->forceDelete();

        return redirect()->route('post', ['status' => 'trash'])
            ->with('success', "Post permanently deleted successfully");
    }
}
