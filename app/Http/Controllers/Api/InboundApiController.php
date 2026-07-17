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
            'birth_date'          => $request->birth_date,
            'gender'              => $request->gender,
            'domicile'            => $request->domicile,
            'address'             => $request->address,
            'shirt_size'          => $request->shirt_size,
            'company'             => $request->company,
            'company_name'        => $request->company,
            'position'            => $request->position,
            'industry'            => $request->industry,
            'company_url'         => $request->company_url,
            'company_address'     => $request->company_address,
            'business_detail'     => $request->business_detail,
            'linkedin_url'        => $request->linkedin_url,
            'instagram'           => $request->instagram,
            'tiktok'              => $request->tiktok,
            'facebook'            => $request->facebook,
            'employee_size'       => $request->employee_size,
            'annual_revenue'      => $request->revenue ?? $request->annual_revenue,
            'motivation_referral' => $request->message,
            'message'             => $request->message,
            'ceo_mm_batch'        => $request->ceo_mm_batch,
            'status'              => 'review',
        ]);

        return response()->json(['success' => true, 'data' => $inbound]);
    }
}