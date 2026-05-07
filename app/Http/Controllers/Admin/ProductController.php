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

        $status = $request->get('status', 'published');
        $categoryFilter = $request->get('category');

        // ← PERBAIKAN 1: pisahkan query trash dan non-trash
        if ($status === 'trash') {
            $query = Product::onlyTrashed();
        } else {
            $query = Product::where('status', $status);
        }

        if ($categoryFilter) {
            $query->whereJsonContains('category', $categoryFilter);
        }

        $products = $query->latest()->get();

        $statusCounts = [
            'published' => Product::where('status', 'published')->count(),
            'draft'     => Product::where('status', 'draft')->count(),
            'archived'  => Product::where('status', 'archived')->count(),
            'trash'     => Product::onlyTrashed()->count(), // ← PERBAIKAN 2
        ];

        return view('product', compact('products', 'categories', 'statusCounts'));
    }

    public function create()
    {
        $categories = ProductCategory::all();
        return view('product-create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'          => 'required',
            'company_name'   => 'required',
            'category'       => 'required|array',
            'ceo_name'       => 'required',
            'description'    => 'required',
            'product_images' => 'required|array',
        ]);

        $data = $request->except(['product_images']);

        if ($request->hasFile('product_images')) {
            $imagePaths = [];
            foreach ($request->file('product_images') as $file) {
                $imagePaths[] = $file->store('products', 'public');
            }
            $data['image']  = $imagePaths[0];
            $data['images'] = $imagePaths;
        }

        Product::create($data);

        return redirect()->route('product.index')->with('success', 'Produk berhasil dibuat!');
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('product-show', compact('product'));
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

        $request->validate([
            'title'    => 'required',
            'category' => 'required|array',
            'email'    => 'required|email',
        ]);

        $finalImages = $request->input('existing_images', []);

        $oldImagesInDb = $product->images ?? [];
        foreach ($oldImagesInDb as $oldPath) {
            if (!in_array($oldPath, $finalImages)) {
                Storage::disk('public')->delete($oldPath);
            }
        }

        if ($request->hasFile('product_images')) {
            foreach ($request->file('product_images') as $file) {
                if ($file && $file->isValid() && count($finalImages) < 3) {
                    $finalImages[] = $file->store('products', 'public');
                }
            }
        }

        $mainThumbnail = !empty($finalImages) ? $finalImages[0] : null;

        $product->update([
            'category'     => $request->category,
            'title'        => $request->title,
            'company_name' => $request->company_name,
            'ceo_name'     => $request->ceo_name,
            'description'  => $request->description,
            'image'        => $mainThumbnail,
            'images'       => $finalImages,
            'features'     => $request->features,
            'website'      => $request->website,
            'email'        => $request->email,
            'phone'        => $request->phone,
        ]);

        return redirect()->route('product.index')->with('success', 'Produk berhasil diupdate!');
    }

    // ← PERBAIKAN 3: destroy hanya soft delete, TIDAK hapus file
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete(); // soft delete → pindah ke trash

        return redirect()->route('product.index', ['status' => 'trash'])
            ->with('success', 'Produk dipindahkan ke trash!');
    }

    // ← TAMBAHAN: Restore dari trash
    public function restore($id)
    {
        $product = Product::onlyTrashed()->findOrFail($id);
        $product->restore();

        return redirect()->route('product.index', ['status' => 'published'])
            ->with('success', 'Produk berhasil direstore!');
    }

    // ← TAMBAHAN: Hapus permanen + hapus file
    public function forceDelete($id)
    {
        $product = Product::onlyTrashed()->findOrFail($id);

        if ($product->images) {
            foreach ($product->images as $path) {
                Storage::disk('public')->delete($path);
            }
        }

        $product->forceDelete();

        return redirect()->route('product.index', ['status' => 'trash'])
            ->with('success', 'Produk berhasil dihapus permanen!');
    }
}