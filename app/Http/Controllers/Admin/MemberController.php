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
        $status = $request->get('status', 'published'); // Default published
        $industry = $request->get('industry'); // Ini buat dropdown
        $search = $request->get('search');

        // LOGIK NYA: Kalau statusnya trash, pake onlyTrashed()
        if ($status === 'trash') {
            $query = Member::onlyTrashed();
        } else {
            $query = Member::query()->where('status', $status);
        }

        // Filter Search
        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        // Filter Industry (Category) dari Dropdown
        if ($industry && $industry !== 'Semua') {
            $query->where('industry', $industry);
        }

        $members = $query->latest()->paginate(10);

        // Siapkan data untuk Komponen Filter Tabs (Hitung semua status)
        $tabs = [
            ['label' => 'Published', 'count' => Member::where('status', 'published')->count()],
            ['label' => 'Draft', 'count' => Member::where('status', 'draft')->count()],
            ['label' => 'Archive', 'count' => Member::where('status', 'archive')->count()],
            ['label' => 'Trash', 'count' => Member::onlyTrashed()->count()], // Tambah hitungan sampah
        ];

        // Ambil list industry buat dropdown (dari data yang gak di-delete)
        $categories = Member::select('industry as name')->distinct()->get();

        return view('crm.members', compact('members', 'tabs', 'categories'));
    }

    public function show(string $id)
    {
        $member = Member::findOrFail($id);
        return response()->json($member);
    }

    public function update(Request $request, string $id)
    {
        $member = Member::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
        ]);

        // Update data member
        $member->update($request->all());

        // CATAT ACTIVITY (Pakai pengecekan user_id biar gak error kalau logout)
        \App\Models\ActivityLog::create([
            'activity_type' => 'Update Member',
            'description'   => 'Updated member: ' . $request->name,
            'user_id'       => auth()->id() ?? 1, // Jauh lebih ringkas & VS Code gak bakal protes
        ]);

        return redirect()->back()->with('success', 'Member data updated successfully!');
    }

    public function destroy(string $id)
    {
        $member = Member::findOrFail($id);
        $member->delete(); // Ini otomatis jadi Soft Delete kalau di Model udah ada trait SoftDeletes

        return redirect()->back()->with('success', 'Member moved to trash!');
    }

    // FUNGSI BARU: Buat balikin data dari Trash
    public function restore(string $id)
    {
        // Cari data di sampah aja
        $member = Member::onlyTrashed()->findOrFail($id);

        // Kembalikan datanya
        $member->restore();

        // Optional: Kalau mau otomatis balik ke status published pas di-restore
        $member->update(['status' => 'published']);

        return redirect()->back()->with('success', 'Member restored successfully!');
    }
}
