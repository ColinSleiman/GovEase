<?php

namespace App\Http\Controllers\Office;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OfficeReviewController extends Controller
{
    public function index(Request $request)
    {
        $officeId = (int) Auth::user()?->office_id;
        abort_if(!$officeId, 403);

        $query = Review::query()
            ->with(['user', 'service.serviceCategory', 'request'])
            ->where('office_id', $officeId)
            ->latest();

        if ($request->filled('service_id')) {
            $query->where('service_id', $request->integer('service_id'));
        }

        if ($request->filled('rating')) {
            $query->where('rating', $request->integer('rating'));
        }

        if ($request->filled('search')) {
            $search = trim((string) $request->input('search'));
            $query->where(function ($searchQuery) use ($search) {
                $searchQuery
                    ->where('comment', 'like', '%' . $search . '%')
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery
                            ->whereRaw("CONCAT(firstName, ' ', lastName) like ?", ['%' . $search . '%'])
                            ->orWhere('email', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('service', function ($serviceQuery) use ($search) {
                        $serviceQuery->where('name', 'like', '%' . $search . '%');
                    });
            });
        }

        $rows = $query->paginate(10)->withQueryString();

        return view('office.reviews.index', [
            'rows' => $rows,
            'services' => Service::query()
                ->where('office_id', $officeId)
                ->orderBy('name')
                ->get(['id', 'name']),
            'filters' => [
                'service_id' => $request->input('service_id'),
                'rating' => $request->input('rating'),
                'search' => $request->input('search'),
            ],
        ]);
    }
}
