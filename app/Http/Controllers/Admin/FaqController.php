<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\ActivityLog;
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
            'question_en' => ['nullable', 'string', 'max:255'],
            'question_id' => ['nullable', 'string', 'max:255'],
            'answer_en'   => ['nullable', 'string'],
            'answer_id'   => ['nullable', 'string'],
            'status'      => ['nullable', 'in:draft,published,archived'],
        ]);
    
        Faq::create([
            'question'    => $validated['question_en'] ?? $validated['question_id'],
            'question_en' => $validated['question_en'] ?? null,
            'question_id' => $validated['question_id'] ?? null,
            'answer'      => $validated['answer_en'] ?? $validated['answer_id'],
            'answer_en'   => $validated['answer_en'] ?? null,
            'answer_id'   => $validated['answer_id'] ?? null,
            'status'      => $validated['status'] ?? 'published',
        ]);
    
        ActivityLog::create([
            'user_id'       => auth()->id(),
            'activity_type' => 'faq',
            'description'   => auth()->user()->name . ' created a FAQ',
        ]);
    
        return redirect()->route('faq')->with('success', 'FAQ created successfully.');
    }
    
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'question_en' => ['nullable', 'string', 'max:255'],
            'question_id' => ['nullable', 'string', 'max:255'],
            'answer_en'   => ['nullable', 'string'],
            'answer_id'   => ['nullable', 'string'],
            'status'      => ['nullable', 'in:draft,published,archived'],
        ]);
    
        $faq = Faq::findOrFail($id);
    
        $faq->update([
            'question'    => $validated['question_en'] ?? $validated['question_id'] ?? $faq->question,
            'question_en' => $validated['question_en'] ?? null,
            'question_id' => $validated['question_id'] ?? null,
            'answer'      => $validated['answer_en'] ?? $validated['answer_id'] ?? $faq->answer,
            'answer_en'   => $validated['answer_en'] ?? null,
            'answer_id'   => $validated['answer_id'] ?? null,
            'status'      => $validated['status'] ?? $faq->status,
        ]);
    
        ActivityLog::create([
            'user_id'       => auth()->id(),
            'activity_type' => 'faq',
            'description'   => auth()->user()->name . ' updated a FAQ',
        ]);
    
        return redirect()->route('faq')->with('success', 'FAQ updated successfully.');
    }

    public function destroy($id)
    {
        $faq = Faq::findOrFail($id);

        $faq->delete();

        ActivityLog::create([
            'user_id' => auth()->user()->id,
            'activity_type' => 'faq',
            'description' => auth()->user()->name . ' moved a FAQ to trash',
        ]);

        return redirect()->route('faq')->with('success', 'FAQ deleted successfully.');
    }

    public function restore($id)
    {
        $faq = Faq::withTrashed()->findOrFail($id);

        $faq->restore();

        ActivityLog::create([
            'user_id' => auth()->user()->id,
            'activity_type' => 'faq',
            'description' => auth()->user()->name . ' restored a FAQ',
        ]);

        return redirect()->back()->with('success', 'FAQ restored successfully');
    }

    public function forceDelete($id)
    {
        $faq = Faq::onlyTrashed()->findOrFail($id);

        $faq->forceDelete();

        ActivityLog::create([
            'user_id' => auth()->user()->id,
            'activity_type' => 'faq',
            'description' => auth()->user()->name . ' permanently deleted a FAQ',
        ]);

        return redirect()->route('faq', ['status' => 'trash'])
            ->with('success', 'FAQ permanently deleted successfully');
    }
}
