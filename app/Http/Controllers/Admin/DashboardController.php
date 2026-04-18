<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request) 
    {
        // collect data dari database
        $totalMember = User::count();
        $newMember = User::whereDate('created_at', today())->count();
        $totalViews = 1340; // Ini bisa dikembangkan nanti jika ada tabel tracking view

        // send data ke tampilan (view)
        return view('admin.dashboard', compact('totalMember', 'newMember', 'totalViews'));
    }
}