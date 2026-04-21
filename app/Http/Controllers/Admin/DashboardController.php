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

        // total member
        $totalMember = Member::count();

        // new member (7 hari kebelakang)
        $newMember = Member::where('created_at', '>=', now()->subWeek())->count();

        // Total Views (lebih cocok SUM daripada COUNT kalau ada kolom views)
        $totalViews = WebsiteView::count();

        // activity terbaru
        $recentActivities = ActivityLog::latest()
            ->take(5)
            ->get();

        // member baru
        $latestMembers = Member::latest()
            ->take(5)
            ->get();

        // member req
        
        $requestedCount = MemberRequest::where('status', 'review')->count();
        $approvedCount  = MemberRequest::where('status', 'approved')->count();
        $rejectedCount  = MemberRequest::where('status', 'rejected')->count();

        // return view

        return view('dashboard', [
            'totalMember'     => $totalMember,
            'newMember'       => $newMember,
            'totalViews'      => $totalViews,
            'recentActivities'=> $recentActivities,
            'latestMembers'   => $latestMembers,
            'requestedCount'  => $requestedCount,
            'approvedCount'   => $approvedCount,
            'rejectedCount'   => $rejectedCount,
        ]);
    }
}