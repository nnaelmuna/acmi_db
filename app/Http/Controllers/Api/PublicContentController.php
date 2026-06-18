<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\FaqResource;
use App\Http\Resources\GalleryResource;
use App\Http\Resources\PartnerResource;
use App\Http\Resources\PostResource;
use App\Http\Resources\ServiceResource;
use App\Models\InstagramPost;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Faq;
use App\Models\Product;
use App\Models\MediaItem;
use App\Models\MediaPartner;
use App\Models\Category;
use App\Models\Testimonial;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PublicContentController extends Controller
{
    public function show(string $locale, string $slug)
    {
        $response = Http::get(
            "http://localhost:8000/api/articles/{$locale}/{$slug}"
        );
    
        if ($response->successful() && $response->json('success')) {
    
            $article = $response->json('data');
    
            return view(
                'ontopic-detail',
                compact('article')
            );
        }
    
        abort(404);
    }

    public function getTestimonials()
    {
        $testimonials = Testimonial::where('status', 'published')
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Data Testimoni Berhasil Diambil',
            'data'    => $testimonials,
        ], 200);
    }

    public function getArticles(Request $request)
    {
        $query = Post::where('status', 'published')
            ->orderBy('created_at', 'desc');

        if ($request->has('category')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        $articles = $query->paginate(6);

        return response()->json([
            'success' => true,
            'message' => 'List Artikel Berhasil Diambil',
            'data'    => PostResource::collection($articles),
        ]);
    }

    public function getArticleDetail($locale, $slug)
    {
        try {
            $column = $locale === 'id' ? 'slug_id' : 'slug_en';
            
            $article = Post::where($column, $slug)
                ->orWhere('slug', $slug) // fallback ke slug utama
                ->where('status', 'published')
                ->firstOrFail();
    
        } catch (ModelNotFoundException) {
            return response()->json([
                'success' => false,
                'message' => 'Artikel tidak ditemukan'
            ], 404);
        }
    
        return response()->json([
            'success' => true,
            'message' => 'Detail Artikel Berhasil Diambil',
            'data'    => new PostResource($article),
        ], 200);
    }

    public function getCategories()
    {
        $categories = Category::latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'Data Kategori Berhasil Diambil',
            'data'    => $categories,
        ], 200);
    }

    public function getFaqs()
    {
        $faqs = Faq::where('status', 'published')
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Data FAQ Berhasil Diambil',
            'data'    => FaqResource::collection($faqs),
        ], 200);
    }

    public function getServices()
    {
        $services = Product::where('status', 'published')
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Data Layanan/Produk Berhasil Diambil',
            'data'    => ServiceResource::collection($services),
        ], 200);
    }

    public function getGallery()
    {
        $galleries = MediaItem::where('status', 'published')
            ->latest()
            ->get()
            ->map(function ($item) {
                $item->full_image_url = asset('storage/' . $item->file_path);
                return $item;
            });

        return response()->json([
            'success' => true,
            'message' => 'Data Galeri Berhasil Diambil',
            'data'    => GalleryResource::collection($galleries),
        ], 200);
    }

    public function getPartners()
    {
        $partners = MediaPartner::where('status', 'published')
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Data Mitra Berhasil Diambil',
            'data'    => PartnerResource::collection($partners),
        ], 200);
    }

    public function getInstagramPosts()
    {
        $posts = InstagramPost::latest('posted_at')->take(6)->get();

        $formattedPosts = $posts->map(function ($post) {
            return [
                'id'       => $post->id,
                'caption'  => $post->caption,
                'post_url' => $post->post_url,
                'image'    => $post->local_image_path ? asset('storage/' . $post->local_image_path) : null,
            ];
        });

        return response()->json([
            'success' => true,
            'data'    => $formattedPosts
        ]);
    }
}
