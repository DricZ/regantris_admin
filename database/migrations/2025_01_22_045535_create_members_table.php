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
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->double("nominal_room")->default(0);
            $table->double("nominal_resto")->default(0);
            $table->double("nominal_laundry")->default(0);
            $table->double("nominal_transport")->default(0);
            $table->double("nominal_spa")->default(0);
            $table->double("nominal_other")->default(0);
            $table->double("total_nominal")->default(0);
            $table->double("poin")->default(0);
            $table->enum('tier', ['Urban', 'City Slicker', 'Metropolis', 'Explorer'])->default('Urban');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
