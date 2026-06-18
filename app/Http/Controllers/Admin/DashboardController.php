<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\WebsiteView;
use App\Models\ActivityLog;
use App\Models\Inbound;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <-- BAGIAN YANG KURANG: Ditambahkan biar VS Code gak garis merah
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // 1. STATS RECAP
        $totalMember = Member::count();
        $newMember = Member::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        $totalViews = WebsiteView::count();

        // 2. AMBIL DATA MEMBER TERBARU
        $latestMembers = Member::latest()
            ->take(5)
            ->get();

        // 3. REAL-TIME MONTHLY RECAP
        $monthlyLabels = [];
        $monthlyData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthlyLabels[] = $month->format('M');
            $monthlyData[] = Member::whereMonth('created_at', $month->month)
                ->whereYear('created_at', $month->year)
                ->count();
        }

        // 4. RECENT ACTIVITY
        $userId = Auth::id();

        $recentActivities = ActivityLog::where('user_id', $userId)
            ->latest()
            ->take(10)
            ->get();

        // 5. MEMBER REQUESTS
        // BAGIAN YANG SALAH: Sudah diganti total dari MemberRequest:: menjadi Member:: karena mau ambil data dari tabel database
        $requestedCount = Member::where('status', 'review')->count();
        $approvedCount  = Member::where('status', 'approved')->count();
        $rejectedCount  = Member::where('status', 'rejected')->count();

        return view('dashboard', [
            'totalMember'      => $totalMember,
            'newMember'        => $newMember,
            'totalViews'       => $totalViews,
            'recentActivities' => $recentActivities,
            'latestMembers'    => $latestMembers,
            'requestedCount'   => $requestedCount,
            'approvedCount'    => $approvedCount,
            'rejectedCount'    => $rejectedCount,
            'monthlyLabels'    => $monthlyLabels,
            'monthlyData'      => $monthlyData,
        ]);
    }
}