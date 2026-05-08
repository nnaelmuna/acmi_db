<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Http\Request;

class MemberController extends Controller
{
public function index(Request $request)
{
    $industries = ['Software', 'Energi', 'FnB', 'Manufaktur', 'Properti', 'Fintech'];
    
    // 1. Mulai Query
    $query = \App\Models\Member::query()->where('status', 'active');

    // 2. Terapkan Filter (Industri & Search)
    if ($request->filled('industry') && $request->industry != 'Semua') {
        $query->where('industry', $request->industry);
    }

    if ($request->filled('search')) {
        $query->where(function($q) use ($request) {
            $q->where('name', 'like', '%' . $request->search . '%')
              ->orWhere('company_name', 'like', '%' . $request->search . '%');
        });
    }

    // 3. HITUNG TOTAL BERDASARKAN HASIL FILTER DI ATAS
    // Ini kuncinya biar angkanya nyesuain sama data yang tampil
    $totalMembers = $query->count(); 

    // 4. Ambil datanya
    $members = $query->latest()->get();

    // Hitung jumlah per kategori (untuk badge kategori lain)
    $counts = [];
    foreach($industries as $ind) {
        $counts[$ind] = \App\Models\Member::where('industry', $ind)->count();
    }

    return view('CRM.members', compact('members', 'industries', 'counts', 'totalMembers'));
}
   public function show(string $id)
{
    // Cari member, kalau gak ada kasih error 404
    $member = Member::findOrFail($id);
    return response()->json($member);
}

   public function update(Request $request,string $id)
{
    $member = Member::findOrFail($id);
    
    // Validasi dikit biar keren
    $request->validate([
        'name' => 'required',
        'email' => 'required|email',
    ]);

    $member->update($request->all());

    return redirect()->back()->with('success', 'Member data updated successfully!');
}

    public function destroy(string $id)
    {
        $member = Member::findOrFail($id);
        $member->delete();

        return redirect()->back()->with('success', 'Member deleted successfully!');
    }
}