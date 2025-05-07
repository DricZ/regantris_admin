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
        Schema::table('redeem_log', function (Blueprint $table) {
            $table->string('model_type')->after('code');
            $table->renameColumn('transaction_id', 'model_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('redeem_log', function (Blueprint $table) {
            $table->dropColumn('model_type');
            $table->renameColumn('model_id', 'transaction_id');
        });
    }
};
