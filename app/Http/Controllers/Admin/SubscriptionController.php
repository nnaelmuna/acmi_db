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
        $query = DB::table('subscriptions')
            ->leftJoin('members', 'subscriptions.email', '=', 'members.email')
            ->select('subscriptions.*', 'members.company_url as partner_link');

        // Filter berdasarkan Tab Status
        if ($status !== 'all') {
            $query->where('subscriptions.status', $status);
        }

        // Filter berdasarkan Kolom Pencarian (Search)
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('subscriptions.name', 'like', "%{$search}%")
                    ->orWhere('subscriptions.company_name', 'like', "%{$search}%")
                    ->orWhere('subscriptions.email', 'like', "%{$search}%");
            });
        }

        $subscriptions = $query->orderBy('subscriptions.id', 'desc')->paginate(10)->withQueryString();

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
            'sub_status' => 'required|in:active,deactive,unactive'
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
            'partner_link' => 'nullable|string|max:255',
            'transaction_image' => 'nullable|file|mimes:jpeg,png,jpg,webp,pdf|max:2048',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
        ]);

        $subscription = DB::table('subscriptions')->where('id', $id)->first();
        if ($subscription) {
            $memberExists = DB::table('members')->where('email', $subscription->email)->exists();
            if ($memberExists) {
                DB::table('members')
                    ->where('email', $subscription->email)
                    ->update(['company_url' => $request->partner_link]);
            } else {
                DB::table('members')->insert([
                    'name' => $subscription->name,
                    'email' => $subscription->email,
                    'phone' => $subscription->phone,
                    'company_name' => $subscription->company_name,
                    'industry' => $subscription->industry,
                    'position' => $subscription->business_model,
                    'company_url' => $request->partner_link,
                    'status' => 'published',
                    'sub_status' => 'active',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        $updateData = [
            'name' => $request->name,
            // Opsional: Jika di DB kamu fieldnya pake datetime/string, kita buat default tanggalnya aman
            'created_at' => $request->start_date ? \Carbon\Carbon::parse($request->start_date) : now(),
            'updated_at' => now()
        ];

        if ($request->hasFile('transaction_image')) {
            $file2 = $request->file('transaction_image');
            $fileName2 = time() . '_tr_' . $file2->getClientOriginalName();
            $file2->move(public_path('uploads/transactions'), $fileName2);
            $updateData['transaction_image'] = $fileName2;
        }

        DB::table('subscriptions')->where('id', $id)->update($updateData);

        return redirect()->back()->with('success', 'Subscription detail updated successfully!');
    }
}