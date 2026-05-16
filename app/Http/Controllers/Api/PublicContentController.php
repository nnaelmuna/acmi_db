<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\FaqResource;
use App\Http\Resources\GalleryResource;
use App\Http\Resources\PartnerResource;
use App\Http\Resources\PostResource;
use App\Http\Resources\ServiceResource;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Faq;
use App\Models\Product;
use App\Models\MediaItem;
use App\Models\MediaPartner;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PublicContentController extends Controller
{
    public function getArticles()
    {
        $articles = Post::where('status', 'published')
                -> orderBy('created_at', 'desc')
                ->paginate(6);

        return response()->json([
            'success' => true,
            'message' => 'List Artikel Berhasil Diambil',
            'data'    => PostResource::collection($articles),
        ]);
    }

    public function getArticleDetail($slug)
    {
        try {
            $article = Post::where('slug', $slug)
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
        $galleries = MediaItem::where('status', 'publised')
                ->latest()
                ->get();

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
}
