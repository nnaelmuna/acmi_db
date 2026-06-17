<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Services\TabFilterService;
use Illuminate\Support\Facades\Auth; 

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $categories = ProductCategory::all();

        $status = $request->get('status', 'published');
        $categoryFilter = $request->get('category');

        $query = Product::query();

        if ($status === 'trash') {
            $query->onlyTrashed();
        } else {
            $query->where('status', $status);
        }

        if ($categoryFilter) {
            $query->whereJsonContains('category', $categoryFilter);
        }

        $products = $query->latest()->paginate(9)->withQueryString();

        $statusCounts = [
            'published' => Product::where('status', 'published')->count(),
            'draft'     => Product::where('status', 'draft')->count(),
            'archived'  => Product::where('status', 'archived')->count(),
            'trash'     => Product::onlyTrashed()->count(),
        ];

        $tabs = TabFilterService::getTabs(Product::class);

        return view('product', compact(
            'products',
            'categories',
            'statusCounts',
            'tabs'
        ));
    }

    public function create()
    {
        $categories = ProductCategory::all();

        return view('product-create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'            => 'required|string|max:255',
            'company_name'     => 'required|string|max:255',
            'category' => 'required|array|min:1|max:3',
            'category.max' => 'Maximum 3 categories allowed.',
            'ceo_name'         => 'required|string|max:255',
            'description'      => 'required|string',

            'features'         => 'required|array|min:1',
            'features.*'       => 'required|string|max:255',

            'website'          => 'required|url',
            'email'            => 'required|email',
            'phone'            => ['required', 'regex:/^[0-9+\-\s()]{8,20}$/'],

            'product_images'   => 'required|array|min:1|max:3',
            'product_images.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',

            'status'           => 'nullable|in:draft,published,archived',
            'address'          => 'required|string|max:255',
            'title_en'         => 'nullable|string|max:255',
            'title_id'         => 'nullable|string|max:255',
            'description_en'   => 'nullable|string',
            'description_id'   => 'nullable|string',
            'features_en'      => 'nullable|array',
            'features_id'      => 'nullable|array',
        ], [
            'features.required' => 'Key Features must be filled.',
            'features.min' => 'Please add at least one key feature.',
            'website.required' => 'Website is required.',
            'website.url' => 'Website must be a valid URL.',
            'email.required' => 'Email is required.',
            'phone.required' => 'Phone number is required.',
            'phone.regex' => 'Phone number must be a valid phone number.',
            'address.required' => 'Address is required.',
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

        // Menggunakan Facade Auth yang aman dari error VS Code
        ActivityLog::create([
            'user_id' => Auth::id(),
            'activity_type' => 'product',
            'description' => Auth::user()->name . ' created a new product',
        ]);

        return redirect()->route('product.index')->with('success', 'Produk created successfully!');
    }

    public function show($id)
    {
        $product = Product::withTrashed()->findOrFail($id);
        return view('product-show', compact('product'));
    }

    public function edit($id)
    {
        $product = Product::withTrashed()->findOrFail($id);
        $categories = ProductCategory::all();
        return view('product-edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'title'        => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'category' => 'required|array|min:1|max:3',
            'category.max' => 'Maximum 3 categories allowed.',
            'ceo_name'     => 'required|string|max:255',
            'description'  => 'required|string',

            'features'     => 'required|array|min:1',
            'features.*'   => 'required|string|max:255',

            'website'      => 'required|url',
            'email'        => 'required|email',
            'phone'        => ['required', 'regex:/^[0-9+\-\s()]{8,20}$/'],
            'address'      => 'nullable|string|max:255',

            'title_en'       => 'nullable|string|max:255',
            'title_id'       => 'nullable|string|max:255',
            'description_en' => 'nullable|string',
            'description_id' => 'nullable|string',
            'features_en'    => 'nullable|array',
            'features_id'    => 'nullable|array',
        ], [
            'features.required' => 'Key Features must be filled.',
            'features.min' => 'Please add at least one key feature.',
            'website.required' => 'Website is required.',
            'website.url' => 'Website must be a valid URL.',
            'email.required' => 'Email is required.',
            'phone.required' => 'Phone number is required.',
            'phone.regex' => 'Phone number must be a valid phone number.',
        ]);

        $finalImages = $request->input('existing_images', []); //ambil gambar lama yang masih dipertahankan

        $oldImagesInDb = $product->images ?? [];
        foreach ($oldImagesInDb as $oldPath) {
            if (!in_array($oldPath, $finalImages)) {
                Storage::disk('public')->delete($oldPath); //hapus dr storage
            }
        }

        if ($request->hasFile('product_images')) {
            foreach ($request->file('product_images') as $file) {
                if ($file && $file->isValid() && count($finalImages) < 3) {
                    $finalImages[] = $file->store('products', 'public'); //ambil img br simpen di storage/app/public/products
                }
            }
        }

        $mainThumbnail = !empty($finalImages) ? $finalImages[0] : null; //img pertama jd thumbnail

        $product->update([ //semua data simpan
            'status'       => $request->input('status', $product->status),
            'category'     => $request->category,
            'title'        => $request->title,
            'company_name' => $company_name = $request->company_name,
            'ceo_name'     => $request->ceo_name,
            'description'  => $request->description,
            'image'        => $mainThumbnail,
            'images'       => $finalImages,
            'features'     => $request->features,
            'website'      => $request->website,
            'email'        => $request->email,
            'phone'        => $request->phone,
            'address'      => $request->address,

            'title_en'       => $request->title_en,
            'title_id'       => $request->title_id,
            'description_en' => $request->description_en,
            'description_id' => $request->description_id,
            'features_en'    => $request->features_en,
            'features_id'    => $request->features_id,
        ]);

        ActivityLog::create([ //buat histori kl misal kita update product
            'user_id' => Auth::id(),
            'activity_type' => 'product',
            'description' => Auth::user()->name . ' updated a product',
        ]);

        return redirect()->route('product.index')->with('success', 'Produk updated successfully!');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'activity_type' => 'product',
            'description' => Auth::user()->name . ' moved a product to trash',
        ]);

        return redirect()->route('product.index', ['status' => 'trash'])
            ->with('success', 'Product moved to trash successfully');
    }

    public function restore($id)
    {
        $product = Product::withTrashed()->findOrFail($id);
        $product->restore();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'activity_type' => 'product',
            'description' => Auth::user()->name . ' restored a product',
        ]);

        return redirect()->route('product.index', ['status' => 'trash'])
            ->with('success', 'Product restored successfully');
    }

    public function forceDelete($id) // permanen delete
    {
        $product = Product::onlyTrashed()->findOrFail($id);

        if ($product->images && is_array($product->images)) {
            foreach ($product->images as $image) {
                if ($image && Storage::disk('public')->exists($image)) {
                    Storage::disk('public')->delete($image);
                }
            }
        }

        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        $product->forceDelete();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'activity_type' => 'product',
            'description' => Auth::user()->name . ' permanently deleted a product',
        ]);

        return redirect()->route('product.index', ['status' => 'trash'])
            ->with('success', 'Product permanently deleted successfully');
    }
}