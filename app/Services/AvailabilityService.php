<?php

namespace App\Services;

use App\Enums\BookingStatus;
use App\Models\Booking;
use Carbon\Carbon;

class AvailabilityService
{
    public static function generateTimeSlots(string $date, array $config): array
    {
        try {
            $start = Carbon::createFromFormat('Y-m-d H:i', $date.' '.$config['business_hour_start']);
            $end = Carbon::createFromFormat('Y-m-d H:i', $date.' '.$config['business_hour_end']);
        } catch (\Throwable) {
            return [];
        }

        if (! $start || ! $end) {
            return [];
        }

        $slotDuration = $config['slot_duration_minutes'];

        $slots = [];
        $current = $start->copy();

        while ($current->lt($end)) {
            $slots[] = $current->format('H:i');
            $current->addMinutes($slotDuration);
        }

        return $slots;
    }

    public static function getAvailableSlots(string $date, array $config): array
    {
        $slots = self::generateTimeSlots($date, $config);

        $bookings = Booking::where('booking_date', $date)
            ->whereIn('booking_status', [BookingStatus::CONFIRMED, BookingStatus::IN_PROGRESS, BookingStatus::PENDING_PAYMENT])
            ->get();

        $bufferMinutes = $config['buffer_minutes'] ?? 0;
        $slotDuration = $config['slot_duration_minutes'] ?? 30;

        foreach ($bookings as $booking) {
            $bookingTimeStr = $booking->booking_time;
            $beforeBufferTimeStr = Carbon::parse($bookingTimeStr)->subMinutes($slotDuration)->format('H:i');
            $afterBufferTimeStr = Carbon::parse($bookingTimeStr)->addMinutes($slotDuration)->format('H:i');

            // Filter out the booked time slot and buffer
            $slots = array_values(array_filter($slots, function ($slot) use ($bookingTimeStr, $beforeBufferTimeStr, $afterBufferTimeStr) {
                return $slot !== $bookingTimeStr && $slot !== $beforeBufferTimeStr && $slot !== $afterBufferTimeStr;
            }));
        }

        return $slots;
    }
}
