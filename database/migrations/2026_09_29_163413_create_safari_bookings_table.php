<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('safari_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->uuid('reference')->unique();
            $table->string('package_name');
            $table->date('travel_date');
            $table->unsignedSmallInteger('travellers');
            $table->decimal('amount', 12, 2);
            $table->string('currency', 3)->default('UGX');
            $table->string('phone_number');
            $table->string('payment_method');
            $table->string('payment_status')->default('pending');
            $table->string('booking_status')->default('pending');
            $table->string('pesapal_tracking_id')->nullable()->index();
            $table->text('pesapal_redirect_url')->nullable();
            $table->json('payment_details')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'booking_status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('safari_bookings');
    }
};
