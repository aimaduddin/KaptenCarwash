<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt - Kapten Carwash</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: #fff;
            color: #000;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #000;
            padding-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #000;
        }
        .header p {
            margin: 5px 0 0;
            color: #666;
        }
        .success-icon {
            color: #22c55e;
            font-size: 48px;
            margin-bottom: 15px;
        }
        .section {
            margin-bottom: 20px;
        }
        .section h3 {
            margin: 0 0 10px;
            font-size: 14px;
            text-transform: uppercase;
            color: #666;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        .section p {
            margin: 5px 0;
            font-size: 14px;
        }
        .section p strong {
            font-weight: bold;
        }
        .booking-ref {
            font-size: 20px;
            font-family: 'Courier New', monospace;
            color: #000;
            font-weight: bold;
        }
        .services-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .services-list li {
            padding: 5px 0;
            border-bottom: 1px solid #eee;
        }
        .total-section {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #000;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 18px;
            font-weight: bold;
        }
        .total-amount {
            font-size: 24px;
            color: #000;
        }
        .fee-text {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Kapten Carwash</h1>
            <p>Booking Receipt</p>
        </div>

        <div class="section">
            <h3>Booking Reference</h3>
            <p class="booking-ref">{{ 'KW-' . str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}</p>
        </div>

        <div class="section">
            <h3>Customer Details</h3>
            <p><strong>Name:</strong> {{ $booking->customer_name ?? $booking->user?->name ?? 'N/A' }}</p>
            <p><strong>Phone:</strong> {{ $booking->customer_phone ?? $booking->user?->phone ?? 'N/A' }}</p>
            <p><strong>Car Plate:</strong> {{ $booking->car_plate ?? 'N/A' }}</p>
        </div>

        <div class="section">
            <h3>Booking Details</h3>
            <p><strong>Car Type:</strong> {{ $booking->carType->name }}</p>
            <p><strong>Date:</strong> {{ $booking->booking_date->format('F j, Y') }}</p>
            <p><strong>Time:</strong> {{ $booking->booking_time }}</p>
        </div>

        <div class="section">
            <h3>Services</h3>
            <ul class="services-list">
                @foreach ($booking->services as $service)
                    <li>{{ $service->name }}</li>
                @endforeach
            </ul>
        </div>

        <div class="total-section">
            <div class="total-row">
                <span>Total Paid</span>
                <span class="total-amount">RM {{ number_format($booking->total_price / 100, 2) }}</span>
            </div>
            <p class="fee-text">Includes RM {{ number_format($booking->booking_fee / 100, 2) }} booking fee</p>
        </div>

        <div class="footer">
            <p>Thank you for your business!</p>
            <p>Booking Date: {{ $booking->created_at->format('F j, Y \a\t g:i A') }}</p>
        </div>
    </div>
</body>
</html>
