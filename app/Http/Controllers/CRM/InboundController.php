<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Models\Inbound;
use Illuminate\Http\Request;

class InboundController extends Controller
{
    public function index(Request $request)
    {
        // 1. Inisialisasi Query
        $query = Inbound::query()->where('status', 'requested');

        // 2. Fitur Search (Nama, Perusahaan, atau Email)
        if ($request->has('search') && $request->search != '') {
            $query->search($request->search);
        }

        // 3. Fitur Filter Status (Approved, Review, Rejected, Requested)
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // 4. Ambil data dengan pagination
        $inbounds = $query->latest()->paginate(10);

        // 5. Data untuk Approval Status Card (Header)
        $stats = [
            'requested' => Inbound::where('status', 'requested')->count(),
            'approved' => Inbound::where('status', 'approved')->count(),
            'rejected' => Inbound::where('status', 'rejected')->count(),
        ];

        return view('crm.inbound', compact('inbounds', 'stats'));
    }

    public function approve(string $id)
{
    // 1. Cari data di tabel Inbound
    $inbound = \App\Models\Inbound::findOrFail($id);

    // 2. Copy data ke tabel Member
    \App\Models\Member::create([
        'name'          => $inbound->name,
        'email'         => $inbound->email,
        'phone'         => $inbound->phone,
        'company_name'  => $inbound->company_name,
        'industry'      => $inbound->industry,
        'position'      => $inbound->position,
        'company_url'   => $inbound->company_url, // Pastikan di Inbound juga ada kolom ini
        'status'        => 'active', // Langsung active di tabel Member
    ]);

    // 3. Update status di tabel Inbound jadi approved biar ilang dari list 'Requested'
    $inbound->update(['status' => 'approved']);

    return redirect()->back()->with('success', 'Member approved and moved to CRM!');
}

    /**
     * Detail Pop-up Modal (Mengambil data satuan untuk modal)
     * Digunakan untuk "Lo-fi Pop-Out Detail" (Screenshot 2026-05-02 at 16.43.00.jpg)
     */
    public function show($id)
    {
        $inbound = Inbound::findOrFail($id);
        return response()->json($inbound);
    }

    /**
     * Update Status via Tombol Action (Approve/Reject)
     */
    public function updateStatus(Request $request, $id)
    {
        $inbound = Inbound::findOrFail($id);
        $inbound->update([
            'status' => $request->status // 'approved', 'rejected', atau 'review'
        ]);

        return back()->with('success', "Status pendaftar berhasil diubah ke {$request->status}!");
    }

    /**
     * Bulk Action: Approve All yang dipilih
     */
    public function bulkApprove(Request $request)
    {
        $ids = $request->ids; // Array ID dari checkbox
        if (!$ids) {
            return response()->json(['error' => 'Tidak ada data yang dipilih'], 400);
        }

        Inbound::whereIn('id', $ids)->update(['status' => 'approved']);

        return response()->json(['success' => 'Semua pendaftar terpilih berhasil disetujui!']);
    }
}