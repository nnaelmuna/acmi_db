<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use App\Services\TabFilterService;

class TestimonialController extends Controller
{
    public function index(Request $request)
    {
        $tabs = TabFilterService::getTabs(Testimonial::class);

        $status = $request->get('status', 'published');

        if ($status === 'trash') {
            $testimonials = Testimonial::onlyTrashed()
                ->latest()
                ->paginate(9)
                ->withQueryString();
        } else {
            $testimonials = Testimonial::where('status', $status)
                ->latest()
                ->paginate(9)
                ->withQueryString();
        }

        return view('testimonial', compact('testimonials', 'tabs', 'status'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'role' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'status' => ['nullable', 'in:draft,published,archived'],
        ]);

        Testimonial::create([
            'name' => $validated['name'],
            'role' => $validated['role'],
            'content' => $validated['content'],
            'rating' => $validated['rating'],
            'status' => $validated['status'] ?? 'published',
        ]);

        ActivityLog::create([
            'user_id' => auth()->user()->id,
            'activity_type' => 'testimonial',
            'description' => auth()->user()->name . ' created a testimonial',
        ]);

        return redirect()->route('testimonial.index')->with('success', 'Testimonial created successfully.');
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'role' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'status' => ['nullable', 'in:draft,published,archived'],
        ]);

        $testimonial = Testimonial::findOrFail($id);

        $testimonial->update([
            'name' => $validated['name'],
            'role' => $validated['role'],
            'content' => $validated['content'],
            'rating' => $validated['rating'],
            'status' => $validated['status'] ?? $testimonial->status,
        ]);

        ActivityLog::create([
            'user_id' => auth()->user()->id,
            'activity_type' => 'testimonial',
            'description' => auth()->user()->name . ' updated a testimonial',
        ]);

        return redirect()->route('testimonial.index')->with('success', 'Testimonial updated successfully.');
    }

    public function destroy($id)
    {
        $testimonial = Testimonial::findOrFail($id);

        $testimonial->delete();

        ActivityLog::create([
            'user_id' => auth()->user()->id,
            'activity_type' => 'testimonial',
            'description' => auth()->user()->name . ' moved a testimonial to trash',
        ]);

        return redirect()->route('testimonial.index')->with('success', 'Testimonial deleted successfully.');
    }

    public function restore($id)
    {
        $testimonial = Testimonial::withTrashed()->findOrFail($id);

        $testimonial->restore();

        ActivityLog::create([
            'user_id' => auth()->user()->id,
            'activity_type' => 'testimonial',
            'description' => auth()->user()->name . ' restored a testimonial',
        ]);

        return redirect()->back()->with('success', 'Testimonial restored successfully');
    }

    public function forceDelete($id)
    {
        $testimonial = Testimonial::onlyTrashed()->findOrFail($id);

        $testimonial->forceDelete();

        ActivityLog::create([
            'user_id' => auth()->user()->id,
            'activity_type' => 'testimonial',
            'description' => auth()->user()->name . ' permanently deleted a testimonial',
        ]);

        return redirect()->route('testimonial.index', ['status' => 'trash'])
            ->with('success', 'Testimonial permanently deleted successfully');
    }
}
