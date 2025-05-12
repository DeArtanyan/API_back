<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('room_id')->constrained()->cascadeOnDelete();
            $table->date('check_in_date');
            $table->date('check_out_date');
            $table->integer('guests_count');
            $table->decimal('total_cost', 10, 2);
            $table->string('booking_status')->default('confirmed');
            $table->text('special_requests')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['check_in_date', 'check_out_date']);
            $table->index('booking_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('bookings');
    }
};
