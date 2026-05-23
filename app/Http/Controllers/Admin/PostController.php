<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Category;
use App\Models\Post;
use App\Models\ActivityLog;
use App\Services\PostService;
use App\Services\TabFilterService;

class PostController extends Controller
{
    public function __construct(protected PostService $postService)
    {
        //
    }

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

    public function create()
    {
        $categories = Category::all();

        return view('post-create', compact('categories'));
    }

    public function store(StorePostRequest $request)
    {
        $this->postService->store(
            $request->validated(),
            $request->file('image')
        );

        ActivityLog::create([
            'user_id' => auth()->user()->id,
            'activity_type' => 'post',
            'description' => auth()->user()->name . ' created a new post',
        ]);

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

        ActivityLog::create([
            'user_id' => auth()->user()->id,
            'activity_type' => 'post',
            'description' => auth()->user()->name . ' moved a post to trash',
        ]);

        return redirect()->to(route('post') . '?status=trash')
            ->with('success', 'Post moved to trash successfully.');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = json_decode($request->ids, true);

        if (empty($ids)) {
            return redirect()->back()->with('error', 'No posts selected.');
        }

        Post::whereIn('id', $ids)->delete();

        ActivityLog::create([
            'user_id' => auth()->user()->id,
            'activity_type' => 'post',
            'description' => auth()->user()->name . ' moved selected posts to trash',
        ]);

        return redirect()->to(route('post') . '?status=trash')
            ->with('success', 'Selected posts moved to trash successfully.');
    }

    public function bulkForceDelete(Request $request)
    {
        $ids = json_decode($request->ids, true);

        if (empty($ids)) {
            return redirect()->back()->with('success', 'No posts selected.');
        }

        Post::onlyTrashed()->whereIn('id', $ids)->forceDelete();

        ActivityLog::create([
            'user_id' => auth()->user()->id,
            'activity_type' => 'post',
            'description' => auth()->user()->name . ' permanently deleted selected posts',
        ]);

        return redirect()->to(route('post') . '?status=trash')
            ->with('success', 'Selected posts permanently deleted successfully.');
    }

    public function update(UpdatePostRequest $request, Post $post)
    {
        $this->postService->update($request->validated(), $request->file('image'), $post);

        ActivityLog::create([
            'user_id' => auth()->user()->id,
            'activity_type' => 'post',
            'description' => auth()->user()->name . ' updated a post',
        ]);

        return redirect()->route('post')->with('success', 'Post updated successfully');
    }

    public function restore($id)
    {
        $post = Post::withTrashed()->findOrFail($id);
        $post->restore();

        ActivityLog::create([
            'user_id' => auth()->user()->id,
            'activity_type' => 'post',
            'description' => auth()->user()->name . ' restored a post',
        ]);

        return redirect()->route('post', ['status' => 'trash'])
            ->with('success', "Post \"{$post->title}\" restored successfully");
    }

    public function forceDelete($id)
    {
        $post = Post::onlyTrashed()->findOrFail($id);

        $post->forceDelete();

        ActivityLog::create([
            'user_id' => auth()->user()->id,
            'activity_type' => 'post',
            'description' => auth()->user()->name . ' permanently deleted a post',
        ]);

        return redirect()->route('post', ['status' => 'trash'])
            ->with('success', "Post permanently deleted successfully");
    }
}