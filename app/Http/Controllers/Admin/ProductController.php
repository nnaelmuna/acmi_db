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
    
        // --- BAGIAN VALIDASI (WAJIB ADA BIAR GAK ERROR NULL) ---
        $request->validate([
            'title'    => 'required',
            'category' => 'required|array', // Ini pengunci biar gak error Integrity Constraint
            'email'    => 'required|email',
            // Tambahkan validasi lain jika perlu
        ]);
    
        // 1. Logika Gambar (List gambar lama yang TIDAK dihapus)
        $finalImages = $request->input('existing_images', []);
    
        // 2. Hapus file fisik untuk gambar yang dibuang
        $oldImagesInDb = $product->images ?? [];
        foreach ($oldImagesInDb as $oldPath) {
            if (!in_array($oldPath, $finalImages)) {
                Storage::disk('public')->delete($oldPath);
            }
        }
    
        // 3. Tambah gambar baru (Maksimal total 3 gambar)
        if ($request->hasFile('product_images')) {
            foreach ($request->file('product_images') as $file) {
                if ($file && $file->isValid()) {
                    if (count($finalImages) < 3) {
                        $finalImages[] = $file->store('products', 'public');
                    }
                }
            }
        }
    
        // 4. Tentukan Thumbnail Utama
        $mainThumbnail = !empty($finalImages) ? $finalImages[0] : null;
    
        // 5. Update database (Manual mapping biar aman)
        $product->update([
            'category'     => $request->category, // Data ini dijamin ada karena sudah lewat validasi
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

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
    
        // 1. Hapus file gambar dari folder storage agar tidak menumpuk (sampah)
        if ($product->images) {
            foreach ($product->images as $path) {
                Storage::disk('public')->delete($path);
            }
        }
    
        // 2. Hapus data dari database
        $product->delete();
    
        // 3. INI YANG PENTING: Redirect balik ke halaman index
        return redirect()->route('product.index')->with('success', 'Produk berhasil dihapus!');
    }
    public function show($id)
    {
        $product = Product::findOrFail($id);

        return view('product-show', compact('product'));
    }
}