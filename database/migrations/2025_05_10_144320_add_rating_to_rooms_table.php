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
        Schema::table('rooms', function (Blueprint $table) {
            $table->decimal('rating_sum', 10, 2)->default(0)->after('is_available');
            $table->unsignedInteger('rating_count')->default(0)->after('rating_sum');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->dropColumn(['rating_sum', 'rating_count']);
        });
    }
};
