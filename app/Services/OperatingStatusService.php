<?php

namespace App\Services;

use App\Models\Closure;
use App\Models\OperatingHour;
use Carbon\Carbon;
use Illuminate\Support\Collection;

/**
 * Service untuk menghitung status operasional hari ini.
 */
class OperatingStatusService
{
    /**
     * Ambil status operasional untuk hari ini dengan mempertimbangkan penutupan.
     */
    public function getStatusForToday(?int $attractionId = null): array
    {
        $now = Carbon::now();
        $dayOfWeek = (int) $now->dayOfWeek; // 0 = Ahad, 6 = Sabtu.

        $hours = $this->resolveOperatingHours($dayOfWeek, $attractionId);
        $closures = $this->resolveClosuresForToday($now, $attractionId);

        $openTime = $hours?->open_time ? Carbon::createFromFormat('H:i:s', $hours->open_time) : null;
        $closeTime = $hours?->close_time ? Carbon::createFromFormat('H:i:s', $hours->close_time) : null;

        $openNow = false;
        if ($hours && !$hours->is_closed && $openTime && $closeTime) {
            $openTimeInstance = (clone $openTime)->setDate($now->year, $now->month, $now->day);
            $closeTimeInstance = (clone $closeTime)->setDate($now->year, $now->month, $now->day);
            $openNow = $now->betweenIncluded($openTimeInstance, $closeTimeInstance);
        }

        $hasClosureNow = $closures->contains(function (Closure $closure) use ($now) {
            $start = Carbon::parse($closure->start_at);
            $end = $closure->end_at ? Carbon::parse($closure->end_at) : null;

            if ($end) {
                return $start->lte($now) && $end->gte($now);
            }

            return $start->lte($now);
        });

        return [
            'open_now' => $openNow && !$hasClosureNow,
            'open_time' => $openTime?->format('H:i'),
            'close_time' => $closeTime?->format('H:i'),
            'closures_today' => $closures->map(function (Closure $closure) {
                return [
                    'id' => $closure->id,
                    'reason' => $closure->reason,
                    'start_at' => Carbon::parse($closure->start_at)->toIso8601String(),
                    'end_at' => $closure->end_at ? Carbon::parse($closure->end_at)->toIso8601String() : null,
                    'attraction_name' => $closure->attraction?->name,
                ];
            })->values()->all(),
        ];
    }

    /**
     * Tentukan jadwal operasional yang relevan untuk hari ini.
     */
    protected function resolveOperatingHours(int $dayOfWeek, ?int $attractionId = null): ?OperatingHour
    {
        if ($attractionId) {
            $hours = OperatingHour::query()
                ->where('day_of_week', $dayOfWeek)
                ->where('attraction_id', $attractionId)
                ->first();

            if ($hours) {
                return $hours;
            }
        }

        return OperatingHour::query()
            ->where('day_of_week', $dayOfWeek)
            ->whereNull('attraction_id')
            ->first();
    }

    /**
     * Ambil daftar penutupan yang aktif pada hari ini.
     */
    protected function resolveClosuresForToday(Carbon $now, ?int $attractionId = null): Collection
    {
        return Closure::query()
            ->with('attraction')
            ->where(function ($query) use ($now) {
                $query->whereDate('start_at', '<=', $now->toDateString())
                    ->where(function ($sub) use ($now) {
                        $sub->whereNull('end_at')
                            ->orWhereDate('end_at', '>=', $now->toDateString());
                    });
            })
            ->when($attractionId, function ($query) use ($attractionId) {
                $query->where(function ($sub) use ($attractionId) {
                    $sub->whereNull('attraction_id')
                        ->orWhere('attraction_id', $attractionId);
                });
            })
            ->get();
    }
}
