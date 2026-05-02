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
        // 1. Ambil semua kategori dari database
        $categories = ProductCategory::all();
    
        // 2. Hitung jumlah produk per kategori secara otomatis (tanpa hardcode)
        // Ini akan menghasilkan array seperti: ['Software' => 5, 'Energi' => 2]
        $counts = Product::select('category', DB::raw('count(*) as total'))
            ->groupBy('category')
            ->pluck('total', 'category')
            ->toArray();
    
        // 3. Filter produk berdasarkan kategori yang di-klik
        $categoryFilter = $request->query('category');
        $products = Product::when($categoryFilter, function ($query, $categoryFilter) {
            return $query->where('category', $categoryFilter);
        })->get();
    
        // 4. Kirim variabelnya ke view
        return view('product', compact('products', 'categories', 'counts'));

    }

    public function create()
    {
        return view('product-create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'company_name' => 'required',
            'category' => 'required',
            'ceo_name' => 'required',
            'description' => 'required',
            'product_images' => 'required|array',
        ]);

        $data = $request->except(['product_images']);

        if ($request->hasFile('product_images')) {
            $files = $request->file('product_images');
            $data['image'] = $files[0]->store('products', 'public');
        }

        Product::create($data);
        return redirect()->route('product.index')->with('success', 'Produk berhasil dibuat!');
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        // Diarahkan ke file edit yang baru
        return view('product-edit', compact('product'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
    
        // 1. Ambil list gambar yang TIDAK dihapus (dari input hidden di JS)
        // Kalau user nggak hapus apa-apa, isinya array gambar lama lengkap.
        $finalImages = $request->input('existing_images', []);
    
        // 2. Hapus file fisik HANYA untuk gambar yang diklik "X"
        $oldImagesInDb = $product->images ?? [];
        foreach ($oldImagesInDb as $oldPath) {
            if (!in_array($oldPath, $finalImages)) {
                Storage::disk('public')->delete($oldPath);
            }
        }
    
        // 3. Tambah gambar baru kalau ada upload tambahan
        if ($request->hasFile('product_images')) {
            foreach ($request->file('product_images') as $file) {
                // Tetep jaga maksimal 3 gambar
                if (count($finalImages) < 3) {
                    $finalImages[] = $file->store('products', 'public');
                }
            }
        }
    
        // 4. Update database dengan array final (gabungan lama & baru)
        $product->update([
            'category' => $request->category,
            'title' => $request->title,
            'company_name' => $request->company_name,
            'ceo_name' => $request->ceo_name,
            'description' => $request->description,
            'images' => $finalImages, // Ini kuncinya biar gak ngulang semua
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
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        $product->delete();
    
        return response()->json(['success' => true]);
    }
}