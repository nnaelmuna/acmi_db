<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Inbound;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class InboundApiController extends Controller
{
    public function store(Request $request)
    {
        Log::info('Inbound request:', $request->all());
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
        ]);

        $inbound = Inbound::create([
            'name'                => $request->name,
            'email'               => $request->email,
            'phone'               => $request->phone,
            'company'             => $request->company,
            'company_name'        => $request->company,
            'position'            => $request->position,
            'industry'            => $request->industry,
            'company_url'         => $request->company_url,
            'linkedin_url'        => $request->linkedin_url,
            'employee_size'       => $request->employee_size,
            'annual_revenue'      => $request->revenue ?? $request->annual_revenue,
            'motivation_referral' => $request->message,
            'message'             => $request->message,
            'status'              => 'review',
        ]);

        return response()->json(['success' => true, 'data' => $inbound]);
    }
}