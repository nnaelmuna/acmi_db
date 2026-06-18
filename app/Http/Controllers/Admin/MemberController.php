<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Http\Request;
use App\Models\ActivityLog;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'published');
        $industry = $request->get('industry');
        $search = $request->get('search');

        if ($status === 'trash') {
            $query = Member::onlyTrashed();
        } else {
            $query = Member::query()->where('status', $status);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('company_name', 'like', "%{$search}%")
                    ->orWhere('industry', 'like', "%{$search}%")
                    ->orWhere('position', 'like', "%{$search}%")
                    ->orWhere('company_url', 'like', "%{$search}%");
            });
        }

        if ($industry && $industry !== 'Semua') {
            $query->where('industry', $industry);
        }

        $members = $query->orderBy('id', 'desc')->paginate(10)->withQueryString();

        $statusCounts = [
            'published' => Member::where('status', 'published')->count(),
            'draft'     => Member::where('status', 'draft')->count(),
            'archived'  => Member::where('status', 'archived')->count(),
            'trash'     => Member::onlyTrashed()->count(),
        ];

        $tabs = [
            ['label' => 'Published', 'count' => $statusCounts['published']],
            ['label' => 'Draft', 'count' => $statusCounts['draft']],
            ['label' => 'Archived', 'count' => $statusCounts['archived']],
            ['label' => 'Trash', 'count' => $statusCounts['trash']],
        ];

        $categories = Member::select('industry as name')
            ->whereNotNull('industry')
            ->where('industry', '!=', '')
            ->distinct()
            ->get();

        return view('crm.members', compact('members', 'tabs', 'categories', 'statusCounts'));
    }

    public function show(string $id)
    {
        $member = Member::withTrashed()->findOrFail($id);

        return response()->json($member);
    }

    public function update(Request $request, string $id)
    {
        $member = Member::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'linkedin' => ['nullable', 'string', 'max:255'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'industry' => ['nullable', 'string', 'max:255'],
            'position' => ['nullable', 'string', 'max:255'],
            'company_url' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'in:published,draft,archived'],
        ]);

        $member->update($validated);

        ActivityLog::create([
            'activity_type' => 'Update Member',
            'description' => 'Updated member: ' . $validated['name'],
            // 'user_id' => auth()->id() ?? 1,
        ]);

        return redirect()
            ->route('members.index', ['status' => $validated['status']])
            ->with('success', 'Member data updated successfully!');
    }

    public function destroy(string $id)
    {
        $member = Member::findOrFail($id);
        $member->delete();

        return redirect()
            ->route('members.index', ['status' => 'trash'])
            ->with('success', 'Member moved to trash successfully!');
    }

    public function restore(string $id)
    {
        $member = Member::onlyTrashed()->findOrFail($id);
        $member->restore();

        $member->update(['status' => 'published']);

        return redirect()
            ->route('members.index', ['status' => 'published'])
            ->with('success', 'Member restored successfully!');
    }

    public function forceDelete(string $id)
    {
        $member = Member::onlyTrashed()->findOrFail($id);
        $member->forceDelete();

        return redirect()
            ->route('members.index', ['status' => 'trash'])
            ->with('success', 'Member permanently deleted successfully!');
    }
}