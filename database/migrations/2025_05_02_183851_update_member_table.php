<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('members', function (Blueprint $table) {
            // 1. Password
            if (! Schema::hasColumn('members', 'password')) {
                $table->string('password')->after('email');
            }

            // 2. Remember Me token
            if (! Schema::hasColumn('members', 'remember_token')) {
                $table->string('remember_token', 100)
                      ->nullable()
                      ->after('password');
            }

            // 3. Email verification timestamp
            if (! Schema::hasColumn('members', 'email_verified_at')) {
                $table->timestamp('email_verified_at')
                      ->nullable()
                      ->after('remember_token');
            }

            // 4. Unique indexes
            $table->unique('email');
            $table->unique('phone_number');
        });
    }

    public function down()
    {
        Schema::table('members', function (Blueprint $table) {
            // Hapus index dulu
            $table->dropUnique(['email']);
            $table->dropUnique(['phone_number']);

            // Lalu kolom auth
            $table->dropColumn(['email_verified_at', 'remember_token', 'password']);
        });
    }
};
