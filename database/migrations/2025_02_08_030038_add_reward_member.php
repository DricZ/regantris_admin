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
        Schema::table(table: 'members', callback: function (Blueprint $table) {
            $table->double('gained_reward')->after('poin')->default(0);
            $table->double('claimed_reward')->after('gained_reward')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('members', callback: function (Blueprint $table) {
            $table->dropColumn(columns: 'gained_reward');
            $table->dropColumn(columns: 'claimed_reward');
        });
    }
};