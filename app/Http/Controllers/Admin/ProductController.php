<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $categories = ProductCategory::all();
        $categoryFilter = $request->query('category');

        $products = Product::when($categoryFilter, function ($query, $categoryFilter) {
            return $query->whereJsonContains('category', $categoryFilter);
        })->get();

        $counts = [];
        foreach ($categories as $cat) {
            $counts[$cat->name] = Product::whereJsonContains('category', $cat->name)->count();
        }

        return view('product', compact('products', 'categories', 'counts'));
    }

    public function create()
    {
        $categories = ProductCategory::all();
        return view('product-create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'company_name' => 'required',
            'category' => 'required|array',
            'ceo_name' => 'required',
            'description' => 'required',
            'product_images' => 'required|array',
        ]);

        $data = $request->except(['product_images']);

        if ($request->hasFile('product_images')) {
            $files = $request->file('product_images');
            $imagePaths = [];
            foreach ($files as $file) {
                $imagePaths[] = $file->store('products', 'public');
            }
            $data['image'] = $imagePaths[0]; // Thumbnail utama
            $data['images'] = $imagePaths;   // Array gallery
        }

        Product::create($data);

        return redirect()->route('product.index')->with('success', 'Produk berhasil dibuat!');
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = ProductCategory::all();
        return view('product-edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        // 1. Ambil list gambar lama yang TIDAK dihapus (dari input hidden JS)
        $finalImages = $request->input('existing_images', []);

        // 2. Hapus file fisik HANYA untuk gambar yang diklik hapus ("X")
        $oldImagesInDb = $product->images ?? [];
        foreach ($oldImagesInDb as $oldPath) {
            if (!in_array($oldPath, $finalImages)) {
                Storage::disk('public')->delete($oldPath);
            }
        }

        // 3. Tambah gambar baru kalau ada upload tambahan
        if ($request->hasFile('product_images')) {
            foreach ($request->file('product_images') as $file) {
                // CEK VALIDASI FILE BIAR GAK ERROR SYMFONY
                if ($file && $file->isValid()) {
                    if (count($finalImages) < 3) {
                        $finalImages[] = $file->store('products', 'public');
                    }
                }
            }
        }

        // 4. Tentukan Thumbnail Utama (Kolom 'image' string)
        // Kita ambil index [0] dari array finalImages
        $mainThumbnail = !empty($finalImages) ? $finalImages[0] : null;

        // 5. Update database
        $product->update([
            'category' => $request->category,
            'title' => $request->title,
            'company_name' => $request->company_name,
            'ceo_name' => $request->ceo_name,
            'description' => $request->description,
            'image' => $mainThumbnail,   // Update thumbnail depan
            'images' => $finalImages,    // Update array gallery
            'features' => $request->features,
            'website' => $request->website,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        return redirect()->route('product.index')->with('success', 'Produk berhasil diupdate!');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        // Opsional: Hapus semua gambar dari storage saat produk dihapus
        if ($product->images) {
            foreach ($product->images as $img) {
                Storage::disk('public')->delete($img);
            }
        }

        $product->delete();

        return response()->json(['success' => true]);
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);

        return view('product-show', compact('product'));
    }
}