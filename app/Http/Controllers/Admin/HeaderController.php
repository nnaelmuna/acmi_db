<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Header;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class HeaderController extends Controller
{
    public function edit()
    {
        $header = Header::first() ?? new Header();
        return view('header', compact('header'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'title_1'          => 'nullable|string|max:255',
            'title_2'          => 'nullable|string|max:255',
            'description'      => 'nullable|string',
            'existing_images'  => 'nullable|array|max:5',
            'header_images'    => 'nullable|array|max:5',
            'header_images.*'  => 'image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $header = Header::first();
        if (!$header) {
            $header = new Header();
        }

        $oldImagesInDb = $header->images ?? [];
        $finalImages = $request->has('existing_images') ? $request->input('existing_images', []) : $oldImagesInDb;

        // Delete removed images
        foreach ($oldImagesInDb as $oldPath) {
            if (!in_array($oldPath, $finalImages)) {
                if ($oldPath && Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }
        }

        // Upload new images
        if ($request->hasFile('header_images')) {
            foreach ($request->file('header_images') as $file) {
                if ($file && $file->isValid() && count($finalImages) < 5) {
                    $finalImages[] = $file->store('headers', 'public');
                }
            }
        }

        // Ensure max 5
        $finalImages = array_values(array_unique(array_slice($finalImages, 0, 5)));

        $header->title_1 = $request->title_1;
        $header->title_2 = $request->title_2;
        $header->description = $request->description;
        $header->images = $finalImages;
        $header->save();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'activity_type' => 'header',
            'description' => Auth::user()->name . ' updated the header configuration',
        ]);

        return redirect()->route('header.edit')->with('success', 'Header updated successfully!');
    }
}
