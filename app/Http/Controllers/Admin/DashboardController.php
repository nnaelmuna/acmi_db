<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\MemberRequest;
use App\Models\WebsiteView;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $totalMember = Member::count();

        $newMember = Member::whereDate('created_at', today())->count();

        $totalViews = WebsiteView::count();

        $recentActivities = ActivityLog::latest()->take(5)->get();

        $latestMembers = Member::latest()->take(5)->get();

        $requestedCount = MemberRequest::where('status', 'review')->count();
        $approvedCount = MemberRequest::where('status', 'approved')->count();
        $rejectedCount = MemberRequest::where('status', 'rejected')->count();

        return view('admin.dashboard', compact(
            'totalMember',
            'newMember',
            'totalViews',
            'recentActivities',
            'latestMembers',
            'requestedCount',
            'approvedCount',
            'rejectedCount'
        ));
    }
}