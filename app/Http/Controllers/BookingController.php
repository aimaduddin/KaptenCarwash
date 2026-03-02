<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function show($id): View
    {
        $booking = Booking::with(['user', 'carType', 'services'])->findOrFail($id);

        return view('pages.book-success', compact('booking'));
    }

    public function downloadReceipt($id)
    {
        $booking = Booking::with(['user', 'carType', 'services'])->findOrFail($id);

        if (Auth::check()) {
            if ($booking->user_id !== Auth::id()) {
                abort(403, 'Unauthorized access to this receipt.');
            }
        } else {
            $sessionBookings = session('guest_bookings', []);
            if (! in_array($id, $sessionBookings)) {
                abort(403, 'Unauthorized access to this receipt.');
            }
        }

        $filename = 'receipt-KW-'.str_pad($booking->id, 6, '0', STR_PAD_LEFT).'.pdf';

        $html = view('receipt', compact('booking'))->render();

        $dompdf = new \Dompdf\Dompdf;
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4');
        $dompdf->render();

        return $dompdf->stream($filename, ['Attachment' => true]);
    }
}
