<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Models\Inbound;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

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

        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek   = Carbon::now()->endOfWeek();

        $stats = [
            'review'   => Inbound::where('status', 'review')
                ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
                ->count(),
            'approved' => Inbound::where('status', 'approved')
                ->whereBetween('approved_at', [$startOfWeek, $endOfWeek])
                ->count(),
            'rejected' => Inbound::where('status', 'rejected')
                ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
                ->count(),
        ];

        $query = Inbound::query()->where('status', $status);

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
        Log::info('updateStatus called', ['id' => $id, 'status' => $request->status]);

        return DB::transaction(function () use ($request, $id) {
            $inbound  = Inbound::findOrFail($id);
            $newStatus = $request->status;

            // Update status + approved_at
            $inbound->update([
                'status'      => $newStatus,
                'approved_at' => $newStatus === 'approved' ? now() : null,
            ]);

            if ($newStatus === 'approved') {
                Log::info('Creating member for: ' . $inbound->email);
                try {
                    Member::updateOrCreate(
                        ['email' => $inbound->email],
                        [
                            'name'           => $inbound->name,
                            'phone'          => $inbound->phone ?? null,
                            'company_name'   => $inbound->company_name ?? $inbound->company ?? '-',
                            'industry'       => $inbound->industry ?? null,
                            'position'       => $inbound->position ?? null,
                            'company_url'    => $inbound->company_url ?? null,
                            'linkedin_url'   => $inbound->linkedin_url ?? null,
                            'employee_size'  => $inbound->employee_size ?? null,
                            'annual_revenue' => $inbound->annual_revenue ?? null,
                            'message'        => $inbound->message ?? null,
                            'status'         => 'active',
                        ]
                    );
                    Log::info('Member created successfully');
                } catch (\Exception $e) {
                    Log::error('Member creation failed: ' . $e->getMessage());
                }
            }

            return response()->json(['success' => "Status berhasil diubah ke $newStatus"]);
        });
    }

    public function bulkApprove(Request $request)
    {
        $ids = $request->ids;
        if (!$ids) return response()->json(['error' => 'No data selected'], 400);

        DB::transaction(function () use ($ids) {
            $selectedInbounds = Inbound::whereIn('id', $ids)->get();

            foreach ($selectedInbounds as $inbound) {
                $inbound->update([
                    'status'      => 'approved',
                    'approved_at' => now(),
                ]);

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
                        'linkedin_url'  => $inbound->linkedin_url,
                        'employee_size' => $inbound->employee_size ?? null,
                        'annual_revenue' => $inbound->annual_revenue ?? null,
                        'message'       => $inbound->message ?? null,
                        'status'        => 'active',
                    ]
                );
            }
        });

        return response()->json(['success' => 'Selected inbound data has been approved successfully!']);
    }
}
