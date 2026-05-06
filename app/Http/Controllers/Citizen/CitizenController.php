<?php

namespace App\Http\Controllers\Citizen;

use App\Http\Controllers\Controller;
use App\Models\Request as CitizenRequest;
use App\Models\Status;
use Illuminate\Support\Facades\Auth;

class CitizenController extends Controller
{
    public function dashboard()
    {
        $userId = Auth::id();

        $requests = CitizenRequest::query()
            ->with(['status', 'service.office', 'service.serviceCategory'])
            ->where('user_id', $userId)
            ->latest()
            ->take(5)
            ->get();

        $totalRequests = CitizenRequest::query()->where('user_id', $userId)->count();
        $pendingStatus = Status::query()->where('name', 'Pending')->first();
        $inReviewStatus = Status::query()->where('name', 'In Review')->first();
        $completedStatus = Status::query()->where('name', 'Completed')->first();

        $pendingCount = $pendingStatus
            ? CitizenRequest::query()->where('user_id', $userId)->where('status_id', $pendingStatus->id)->count()
            : 0;
        $inReviewCount = $inReviewStatus
            ? CitizenRequest::query()->where('user_id', $userId)->where('status_id', $inReviewStatus->id)->count()
            : 0;
        $completedCount = $completedStatus
            ? CitizenRequest::query()->where('user_id', $userId)->where('status_id', $completedStatus->id)->count()
            : 0;

        return view('citizen.dashboard.index', compact(
            'requests',
            'totalRequests',
            'pendingCount',
            'inReviewCount',
            'completedCount'
        ));
    }
}
