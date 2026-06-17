<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubscriptionController extends Controller
{
    /**
     * Menampilkan daftar utama subscription dengan filter tab & pencarian
     */
    public function index(Request $request)
    {
        $status = $request->get('status', 'published');
        $search = $request->get('search');

        // 1. Hitung Data untuk Counter di Tab-Filters
        $counts = DB::table('subscriptions')
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        // 2. Query Ambil Data Utama
        $query = DB::table('subscriptions');

        // Filter berdasarkan Tab Status
        if ($status !== 'all') {
            $query->where('status', $status);
        }

        // Filter berdasarkan Kolom Pencarian (Search)
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('company_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $subscriptions = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        // Format data tabs agar terbaca oleh komponen blade custom-tabs
        $tabs = [
            ['label' => 'Published', 'value' => 'published', 'count' => $counts['published'] ?? 0],
            ['label' => 'Draft', 'value' => 'draft', 'count' => $counts['draft'] ?? 0],
            ['label' => 'Archived', 'value' => 'archived', 'count' => $counts['archived'] ?? 0],
            ['label' => 'Trash', 'value' => 'trash', 'count' => $counts['trash'] ?? 0],
        ];

        return view('crm.subscription', compact('subscriptions', 'tabs'));
    }

    /**
     * Aksi Mengubah Status Aplikasi (Active / Deactive) via Dropdown
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'sub_status' => 'required|in:active,deactive'
        ]);

        DB::table('subscriptions')
            ->where('id', $id)
            ->update([
                'sub_status' => $request->sub_status,
                'updated_at' => now()
            ]);

        return redirect()->back()->with('success', 'Subscription status updated successfully!');
    }

    /**
     * Aksi memindahkan data dari tab biasa masuk ke dalam Trash
     */
    public function destroy($id)
    {
        DB::table('subscriptions')
            ->where('id', $id)
            ->update([
                'status' => 'trash',
                'updated_at' => now()
            ]);

        return redirect()->back()->with('success', 'Subscription moved to Trash successfully!');
    }

    /**
     * Aksi mengembalikan data dari Trash ke status Published kembali
     */
    public function restore($id)
    {
        DB::table('subscriptions')
            ->where('id', $id)
            ->update([
                'status' => 'published',
                'updated_at' => now()
            ]);

        return redirect()->route('subscription.index', ['status' => 'published'])
            ->with('success', 'Subscription restored successfully!');
    }

    /**
     * Aksi menghapus data secara permanen (Lenyap dari Database)
     */
    public function forceDelete($id)
    {
        DB::table('subscriptions')
            ->where('id', $id)
            ->delete();

        return redirect()->route('subscription.index', ['status' => 'trash'])
            ->with('success', 'Subscription permanently deleted!');
    }

    // Aksi menyimpan perubahan data detail (Name, Image, Link, Dates)
    public function updateDetail(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'partner_link' => 'nullable|url',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'partner_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048' // Max 2MB
        ]);

        $updateData = [
            'name' => $request->name,
            'transaction_url' => $request->partner_link,
            // Opsional: Jika di DB kamu fieldnya pake datetime/string, kita buat default tanggalnya aman
            'created_at' => $request->start_date ? \Carbon\Carbon::parse($request->start_date) : now(),
            'updated_at' => now()
        ];

        // Proses upload file gambar beneran jika user milih file baru
        if ($request->hasFile('partner_image')) {
            $file = $request->file('partner_image');
            $fileName = time() . '_' . $file->getClientOriginalName();
            // File bakal masuk ke folder: public/uploads/partners/
            $file->move(public_path('uploads/partners'), $fileName);

            // Simpan nama filenya ke database (sesuaikan nama kolom gambar kamu di DB, misal 'image')
            // $updateData['image'] = $fileName; 
        }

        DB::table('subscriptions')->where('id', $id)->update($updateData);

        return redirect()->back()->with('success', 'Subscription detail updated successfully!');
    }
}