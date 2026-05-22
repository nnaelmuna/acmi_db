<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\WebsiteView;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HistoryController extends Controller
{
   public function index()
{
    $monthlyRecap = [];
    
    // Looping untuk 6 bulan terakhir
    for ($i = 0; $i < 6; $i++) {
        $date = now()->subMonths($i);
        
        // ngitung total member SAMPAI bulan tersebut
        $totalMember = \App\Models\Member::where('created_at', '<=', $date->endOfMonth())->count();
        
        // ngitung member BARU hanya di bulan tersebut
        $newMember = \App\Models\Member::whereMonth('created_at', $date->month)
                                       ->whereYear('created_at', $date->year)
                                       ->count();
        
        // ngitung total views di bulan tersebut
        $views = \App\Models\WebsiteView::whereMonth('created_at', $date->month)
                                        ->whereYear('created_at', $date->year)
                                        ->count();

        $monthlyRecap[] = [
            'month' => $date->translatedFormat('F Y'), // Contoh: Mei 2026
            'total_member' => $totalMember,
            'new_member' => $newMember,
            'views' => $views,
        ];
    }

    return view('history', compact('monthlyRecap'));
}
}