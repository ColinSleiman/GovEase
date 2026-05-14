<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\Office;
use Carbon\Carbon;

class AppointmentSlotService
{
    public function availableSlots(Office $office, string $date, ?int $ignoreAppointmentId = null): array
    {
        [$openingTime, $closingTime] = $this->workingHoursRange($office->working_hours);

        $start = Carbon::createFromFormat('h:i A', $openingTime);
        $end = Carbon::createFromFormat('h:i A', $closingTime);

        if ($end->lessThanOrEqualTo($start)) {
            $end = Carbon::createFromFormat('h:i A', '05:00 PM');
            $start = Carbon::createFromFormat('h:i A', '08:00 AM');
        }

        $bookedSlots = Appointment::query()
            ->where('office_id', $office->id)
            ->whereDate('appointment_date', $date)
            ->when($ignoreAppointmentId, fn ($query) => $query->where('id', '!=', $ignoreAppointmentId))
            ->pluck('appointment_time')
            ->map(fn ($time) => Carbon::parse($time)->format('H:i:s'))
            ->all();

        $slots = [];
        $cursor = $start->copy();

        while ($cursor->lessThan($end)) {
            $slotValue = $cursor->format('H:i:s');
            if (!in_array($slotValue, $bookedSlots, true)) {
                $slots[] = [
                    'value' => $slotValue,
                    'label' => $cursor->format('h:i A'),
                ];
            }

            $cursor->addMinutes(30);
        }

        return $slots;
    }

    public function isWithinWorkingHours(Office $office, string $time): bool
    {
        [$openingTime, $closingTime] = $this->workingHoursRange($office->working_hours);

        $slot = Carbon::createFromFormat('H:i:s', $time);
        $start = Carbon::createFromFormat('h:i A', $openingTime);
        $end = Carbon::createFromFormat('h:i A', $closingTime);

        return $slot->greaterThanOrEqualTo($start) && $slot->lessThan($end);
    }

    private function workingHoursRange(?string $workingHours): array
    {
        if (!$workingHours || !str_contains($workingHours, ' - ')) {
            return ['08:00 AM', '05:00 PM'];
        }

        [$openingTime, $closingTime] = explode(' - ', $workingHours, 2);

        return [$openingTime ?: '08:00 AM', $closingTime ?: '05:00 PM'];
    }
}
