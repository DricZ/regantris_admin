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
        Schema::table(table: 'users', callback: function (Blueprint $table) {
            $table->bigInteger(column: 'hotel_id')->after('id')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', callback: function (Blueprint $table) {
            $table->dropColumn(columns: 'hotel_id');
        });
    }
};
