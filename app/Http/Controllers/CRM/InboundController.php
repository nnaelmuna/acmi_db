<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Models\Inbound;
use App\Models\Member; // Pastikan Model Member di-import
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Buat transaksi database

class InboundController extends Controller
{
    public function index(Request $request)
    {
        $query = Inbound::query();

        // 1. Default nampilin 'review' kalau baru buka halaman
        if (!$request->has('status')) {
            $query->where('status', 'review');
        }

        // 2. Filter Status (Sekarang nyari 'review', bukan 'requested')
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // 3. Fitur Search
        if ($request->has('search') && $request->search != '') {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('company_name', 'like', '%' . $request->search . '%');
            });
        }

        $inbounds = $query->latest()->paginate(10);

        // 4. Statistik (Pakai kunci 'review' biar pas sama Blade)
        $stats = [
            'review'   => Inbound::where('status', 'review')->count(),
            'approved' => Inbound::where('status', 'approved')->count(),
            'rejected' => Inbound::where('status', 'rejected')->count(),
        ];

        return view('crm.inbound', compact('inbounds', 'stats'));
    }

    public function show($id)
    {
        $inbound = Inbound::findOrFail($id);
        return response()->json($inbound);
    }

    /**
     * UPDATE STATUS (Ini yang dipake tombol centang & silang di Blade)
     */
    public function updateStatus(Request $request, $id)
    {
        // Gunakan Transaction biar aman
        return DB::transaction(function () use ($request, $id) {
            $inbound = Inbound::findOrFail($id);
            $newStatus = $request->status;

            // 1. Update status di tabel Inbound
            $inbound->update(['status' => $newStatus]);

            // 2. Kalau statusnya 'approved', otomatis bikin data di tabel Member
            if ($newStatus === 'approved') {
                // Cek dulu biar nggak duplikat di tabel Member berdasarkan email
                Member::updateOrCreate(
                    ['email' => $inbound->email],
                    [
                        'name'          => $inbound->name,
                        'phone'         => $inbound->phone,
                        'company_name'  => $inbound->company_name ?? $inbound->company ?? '-',
                        'industry'      => $inbound->industry ?? '-',
                        'position'      => $inbound->position ?? '-',
                        'company_url'   => $inbound->company_url,
                        'status'        => 'active',
                    ]
                );
            }

            return response()->json(['success' => "Status berhasil diubah ke $newStatus"]);
        });
    }

    /**
     * BULK ACTION (Approve all yang diceklis)
     */
    public function bulkApprove(Request $request)
    {
        $ids = $request->ids;
        if (!$ids) return response()->json(['error' => 'Tidak ada data dipilih'], 400);

        DB::transaction(function () use ($ids) {
            $selectedInbounds = Inbound::whereIn('id', $ids)->get();

            foreach ($selectedInbounds as $inbound) {
                // Update status inbound
                $inbound->update(['status' => 'approved']);

                // Create member
                Member::updateOrCreate(
                    ['email' => $inbound->email],
                    [
                        'name'          => $inbound->name,
                        'company_name'  => $inbound->company_name ?? $inbound->company ?? '-',
                        'industry'      => $inbound->industry ?? '-',
                        'position'      => $inbound->position ?? '-',
                        'status'        => 'active',
                    ]
                );
            }
        });

        return response()->json(['success' => 'Semua terpilih berhasil diapprove!']);
    }
}
