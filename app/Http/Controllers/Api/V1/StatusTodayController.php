<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Attraction;
use App\Services\OperatingStatusService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Controller status operasional harian.
 */
class StatusTodayController
{
    /**
     * Tampilkan status operasional hari ini.
     */
    public function index(Request $request, OperatingStatusService $service): JsonResponse
    {
        $attractionId = null;

        if ($request->filled('attraction_id')) {
            $attractionId = (int) $request->integer('attraction_id');
        }

        if ($request->filled('attraction_slug') && ! $attractionId) {
            $attraction = Attraction::query()->where('slug', $request->input('attraction_slug'))->first();
            $attractionId = $attraction?->id;
        }

        $status = $service->getStatusForToday($attractionId);

        return response()->json($status);
    }
}
