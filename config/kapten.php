<?php

return [
    'booking_fee_percent' => env('BOOKING_FEE_PERCENT', 10),
    'business_hour_start' => env('BUSINESS_HOUR_START', '09:00'),
    'business_hour_end' => env('BUSINESS_HOUR_END', '18:00'),
    'slot_duration_minutes' => env('SLOT_DURATION_MINUTES', 30),
    'buffer_minutes' => env('BUFFER_MINUTES', 15),
    'payment_provider' => env('PAYMENT_PROVIDER', 'mock'),
];
