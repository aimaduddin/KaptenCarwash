<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function show($id): View
    {
        $booking = Booking::with(['user', 'carType', 'services'])->findOrFail($id);
        
        return view('pages.book-success', compact('booking'));
    }
}
