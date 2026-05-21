<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Models\Inbound;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class InboundController extends Controller
{
    public function index(Request $request)
    {
        $query = Inbound::query();

        $status = $request->get('status', 'review');
        $query->where('status', $status);

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('company_name', 'like', "%{$search}%")
                    ->orWhere('industry', 'like', "%{$search}%")
                    ->orWhere('position', 'like', "%{$search}%");
            });
        }

        $inbounds = $query->latest()->paginate(10)->withQueryString();

        $stats = [
            'review' => Inbound::where('status', 'review')->count(),
            'approved' => Inbound::where('status', 'approved')->count(),
            'rejected' => Inbound::where('status', 'rejected')->count(),
        ];

        $diffs = [
            'review' => Inbound::where('status', 'review')->whereDate('created_at', Carbon::today())->count()
                - Inbound::where('status', 'review')->whereDate('created_at', Carbon::yesterday())->count(),

            'approved' => Inbound::where('status', 'approved')->whereDate('created_at', Carbon::today())->count()
                - Inbound::where('status', 'approved')->whereDate('created_at', Carbon::yesterday())->count(),

            'rejected' => Inbound::where('status', 'rejected')->whereDate('created_at', Carbon::today())->count()
                - Inbound::where('status', 'rejected')->whereDate('created_at', Carbon::yesterday())->count(),
        ];

        return view('crm.inbound', compact('inbounds', 'stats', 'diffs'));
    }

    public function show($id)
    {
        $inbound = Inbound::findOrFail($id);
        return response()->json($inbound);
    }

    public function updateStatus(Request $request, $id)
    {
        return DB::transaction(function () use ($request, $id) {
            $inbound = Inbound::findOrFail($id);
            $newStatus = $request->status;

            $inbound->update(['status' => $newStatus]);

            if ($newStatus === 'approved') {
                Member::updateOrCreate(
                    ['email' => $inbound->email],
                    [
                        'name'          => $inbound->name,
                        'phone'         => $inbound->phone,
                        'company_name'  => $inbound->company_name ?? $inbound->company ?? '-',
                        'industry'      => $inbound->industry ?? '-',
                        'position'      => $inbound->position ?? '-',
                        'company_url'   => $inbound->company_url,
                        'status'        => 'active',
                    ]
                );
            }

            return response()->json(['success' => "Status has been changed to $newStatus"]);
        });
    }

    public function bulkApprove(Request $request)
    {
        $ids = $request->ids;
        if (!$ids) return response()->json(['error' => 'No data selected'], 400);

        DB::transaction(function () use ($ids) {
            $selectedInbounds = Inbound::whereIn('id', $ids)->get();

            foreach ($selectedInbounds as $inbound) {
                $inbound->update(['status' => 'approved']);

                // Create member
                Member::updateOrCreate(
                    ['email' => $inbound->email],
                    [
                        'name'          => $inbound->name,
                        'phone'         => $inbound->phone,
                        'company_name'  => $inbound->company_name ?? $inbound->company ?? '-',
                        'industry'      => $inbound->industry ?? '-',
                        'position'      => $inbound->position ?? '-',
                        'company_url'   => $inbound->company_url,
                        'linkedin_url'   => $inbound->company_url,
                        'status'        => 'active',
                    ]
                );
            }
        });

        return response()->json(['success' => 'Selected inbound data has been approved successfully!']);
    }
}
