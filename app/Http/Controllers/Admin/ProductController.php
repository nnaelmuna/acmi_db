<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->query('category');
        $products = Product::when($category, function ($query, $category) {
            return $query->where('category', $category);
        })->get();

        $counts = [
            'countSoftware' => Product::where('category', 'Software')->count(),
            'countEnergi' => Product::where('category', 'Energi')->count(),
            'countFnB' => Product::where('category', 'FnB')->count(),
            'countManufaktur' => Product::where('category', 'Manufaktur')->count(),
            'countProperti' => Product::where('category', 'Properti')->count(),
            'countFintech' => Product::where('category', 'Fintech')->count(),
        ];

        return view('product', array_merge(['products' => $products], $counts));
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

        $request->validate([
            'title' => 'required',
            'company_name' => 'required',
            'category' => 'required',
            'ceo_name' => 'required',
            'description' => 'required',
            'product_images' => 'nullable|array',
        ]);

        $data = $request->except(['product_images']);

        if ($request->hasFile('product_images')) {
            if ($product->image) { 
                Storage::disk('public')->delete($product->image); 
            }
            $files = $request->file('product_images');
            $data['image'] = $files[0]->store('products', 'public');
        }

        $product->update($data);
        return redirect()->route('product.index')->with('success', 'Produk berhasil diupdate!');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        if ($product->image) { 
            Storage::disk('public')->delete($product->image); 
        }
        $product->delete();

        return redirect()->route('product.index')->with('success', 'Produk berhasil dihapus!');
    }
}