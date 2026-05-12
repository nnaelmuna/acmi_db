<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;
use App\Services\TabFilterService;

class FaqController extends Controller
{
    public function index(Request $request)
    {
        $allFaqs = Faq::all();

        $tabs = TabFilterService::getTabs(Faq::class);

        $status = $request->get('status', 'published');

        if ($status === 'trash') {
            $faqs = Faq::onlyTrashed()
                ->latest()
                ->paginate(10)
                ->withQueryString();
        } else {
            $faqs = Faq::where('status', $status)
                ->latest()
                ->paginate(10)
                ->withQueryString();
        }

        return view('faq', compact('faqs', 'allFaqs', 'tabs', 'status'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'question' => ['required', 'string', 'max:255'],
            'answer' => ['required', 'string'],
            'status' => ['nullable', 'in:draft,published,archived'],
        ]);

        Faq::create([
            'question' => $validated['question'],
            'answer' => $validated['answer'],
            'status' => $validated['status'] ?? 'published',
        ]);

        return redirect()->route('faq')->with('success', 'FAQ created successfully.');
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'question' => ['required', 'string', 'max:255'],
            'answer' => ['required', 'string'],
            'status' => ['nullable', 'in:draft,published,archived'],
        ]);

        $faq = Faq::findOrFail($id);

        $faq->update([
            'question' => $validated['question'],
            'answer' => $validated['answer'],
            'status' => $validated['status'] ?? $faq->status,
        ]);

        return redirect()->route('faq')->with('success', 'FAQ updated successfully.');
    }

    public function destroy($id)
    {
        $faq = Faq::findOrFail($id);
        $faq->delete();

        return redirect()->route('faq')->with('success', 'FAQ deleted successfully.');
    }

    public function restore($id)
    {
        $faq = FAQ::withTrashed()->findOrFail($id);

        $faq->restore();

        return redirect()->back()->with('success', 'FAQ restored successfully');
    }

    public function forceDelete($id)
    {
        $faq = FAQ::onlyTrashed()->findOrFail($id);

        $faq->forceDelete();

        return redirect()->route('faq', ['status' => 'trash'])
            ->with('success', 'FAQ permanently deleted successfully');
    }
}
