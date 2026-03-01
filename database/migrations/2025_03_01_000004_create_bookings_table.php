<?php

use App\Enums\BookingStatus;
use App\Enums\PaymentStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('car_type_id')->constrained();
            $table->date('booking_date');
            $table->time('booking_time');
            $table->unsignedInteger('total_price');
            $table->unsignedInteger('booking_fee');
            $table->enum('payment_status', array_column(PaymentStatus::cases(), 'value'))->default(PaymentStatus::UNPAID->value);
            $table->enum('booking_status', array_column(BookingStatus::cases(), 'value'))->default(BookingStatus::PENDING_PAYMENT->value);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
