<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MemberRequestController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name'          => 'required|string|max:255',
                'email'         => 'required|email|max:255',
                'phone'         => 'nullable|string|max:50',
                'company_name'  => 'nullable|string|max:255',
                'industry'      => 'nullable|string|max:255',
                'position'      => 'nullable|string|max:255',
                'linkedin_url'  => 'nullable|string|max:255',
                'company_url'   => 'nullable|string|max:255',
                'employee_size' => 'nullable|string|max:255',
                'revenue'       => 'nullable|string|max:255',
                'message'       => 'nullable|string',
            ]);

            $validated['status'] = 'draft'; // default draft dulu

            Member::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Formulir berhasil dikirim!'
            ]);
        } catch (\Exception $e) {
            Log::error('Member request gagal: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal: ' . $e->getMessage(),
                'errors'  => []
            ], 500);
        }
    }
}
