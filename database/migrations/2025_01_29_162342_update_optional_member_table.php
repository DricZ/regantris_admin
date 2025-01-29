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
        Schema::table('members', function (Blueprint $table) {
            $table->decimal('nominal_room', 10, 2)->nullable()->change();
            $table->decimal('nominal_resto', 10, 2)->nullable()->change();
            $table->decimal('nominal_laundry', 10, 2)->nullable()->change();
            $table->decimal('nominal_transport', 10, 2)->nullable()->change();
            $table->decimal('nominal_spa', 10, 2)->nullable()->change();
            $table->decimal('nominal_other', 10, 2)->nullable()->change();
        });
    }
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->decimal('nominal_room', 10, 2)->nullable(false)->change();
            $table->decimal('nominal_resto', 10, 2)->nullable(false)->change();
            $table->decimal('nominal_laundry', 10, 2)->nullable(false)->change();
            $table->decimal('nominal_transport', 10, 2)->nullable(false)->change();
            $table->decimal('nominal_spa', 10, 2)->nullable(false)->change();
            $table->decimal('nominal_other', 10, 2)->nullable(false)->change();
        });
    }
};
