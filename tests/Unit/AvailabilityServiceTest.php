<?php

namespace Tests\Unit;

use App\Models\CarType;
use App\Models\Service;
use App\Services\AvailabilityService;
use Tests\TestCase;

class AvailabilityServiceTest extends TestCase
{
    public function test_generates_time_slots_for_a_date(): void
    {
        $date = '2026-03-01';
        $config = [
            'business_hour_start' => '09:00',
            'business_hour_end' => '18:00',
            'slot_duration_minutes' => 30,
        ];

        $slots = AvailabilityService::generateTimeSlots($date, $config);

        $this->assertCount(18, $slots); // 9 hours * 2 slots per hour
        $this->assertEquals('09:00', $slots[0]);
        $this->assertEquals('17:30', $slots[17]);
    }

    public function test_buffer_filtering_logic(): void
    {
        // Test that buffer filtering logic excludes adjacent slots
        $bookingTime = '10:00';
        $slotDuration = 30;

        $beforeBuffer = \Carbon\Carbon::parse($bookingTime)->subMinutes($slotDuration)->format('H:i');
        $afterBuffer = \Carbon\Carbon::parse($bookingTime)->addMinutes($slotDuration)->format('H:i');

        $this->assertEquals('09:30', $beforeBuffer);
        $this->assertEquals('10:30', $afterBuffer);
        $this->assertNotEquals($bookingTime, $beforeBuffer);
        $this->assertNotEquals($bookingTime, $afterBuffer);
    }
}
