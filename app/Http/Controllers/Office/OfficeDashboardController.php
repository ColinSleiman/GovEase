<?php

namespace App\Http\Controllers\Office;

use App\Http\Controllers\Controller;
use App\Models\Request as ServiceRequest;
use App\Models\Status;
use Illuminate\Support\Facades\Auth;

class OfficeDashboardController extends Controller
{
    // Office portal dashboard controller (Blade pages only).
    public function index()
    {
        $officeId = Auth::user()?->office_id;
        abort_if(!$officeId, 403);

        $baseQuery = ServiceRequest::query()
            ->whereHas('service', function ($query) use ($officeId) {
                $query->where('office_id', $officeId);
            });

        $statusCounts = [];
        $statuses = Status::query()->get(['id', 'name']);
        foreach ($statuses as $status) {
            $statusCounts[strtolower($status->name)] = (clone $baseQuery)
                ->where('status_id', $status->id)
                ->count();
        }

        $recentRequests = (clone $baseQuery)
            ->with(['user', 'service.serviceCategory', 'status'])
            ->latest()
            ->take(8)
            ->get();

        return view('office.dashboard', [
            'totalRequests' => (clone $baseQuery)->count(),
            'pendingCount' => $statusCounts['pending'] ?? 0,
            'inReviewCount' => $statusCounts['in review'] ?? 0,
            'missingDocumentsCount' => $statusCounts['missing documents'] ?? 0,
            'approvedCount' => $statusCounts['approved'] ?? 0,
            'rejectedCount' => $statusCounts['rejected'] ?? 0,
            'completedCount' => $statusCounts['completed'] ?? 0,
            'recentRequests' => $recentRequests,
        ]);
    }
}
