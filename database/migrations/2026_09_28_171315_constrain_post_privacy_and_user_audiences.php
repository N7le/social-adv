<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {


        Schema::table('users', function (Blueprint $table) {
            $table->enum('posts_audience', ['public', 'private'])->default('public')->after('remember_token')->change();
            $table->enum('request_audience', ['everyone', 'friends_of_friends'])->default('everyone')->after('posts_audience')->change();
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->enum('privacy', ['public', 'private'])->default('public')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('posts_audience')->default('public')->after('remember_token')->change();
            $table->string('request_audience')->default('everyone')->after('posts_audience')->change();
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->enum('privacy', ['public', 'private', 'friends'])->default('public')->change();
        });
    }
};
