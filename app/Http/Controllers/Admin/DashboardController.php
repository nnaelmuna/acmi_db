<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\MemberRequest;
use App\Models\WebsiteView;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Carbon\Carbon;

use function Illuminate\Support\weeks;

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

    // 2. AMBIL DATA MEMBER TERBARU (Ini yang tadi hilang)
    $latestMembers = Member::latest()
        ->take(5)
        ->get();

    // 3. REAL-TIME MONTHLY RECAP (Untuk Grafik)
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
    $recentActivities = ActivityLog::latest()
        ->take(10)
        ->get();

    // 5. MEMBER REQUESTS
    $requestedCount = MemberRequest::where('status', 'review')->count();
    $approvedCount  = MemberRequest::where('status', 'approved')->count();
    $rejectedCount  = MemberRequest::where('status', 'rejected')->count();

    // Pastikan semua variabel di bawah ini masuk ke return view
    return view('dashboard', [
        'totalMember'      => $totalMember,
        'newMember'        => $newMember,
        'totalViews'       => $totalViews,
        'recentActivities' => $recentActivities,
        'latestMembers'    => $latestMembers, // Sekarang variabel ini sudah ada isinya
        'requestedCount'   => $requestedCount,
        'approvedCount'    => $approvedCount,
        'rejectedCount'    => $rejectedCount,
        'monthlyLabels'    => $monthlyLabels,
        'monthlyData'      => $monthlyData,
    ]);
}
}