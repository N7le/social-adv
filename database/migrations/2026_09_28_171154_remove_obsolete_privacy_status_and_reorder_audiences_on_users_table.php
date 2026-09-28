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
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('privacy_status');
            $table->string('posts_audience')->default('public')->after('remember_token')->change();
            $table->string('request_audience')->default('everyone')->after('posts_audience')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('privacy_status', ['public', 'private'])->default('public')->after('cover_photo');
            $table->string('posts_audience')->default('public')->after('updated_at')->change();
            $table->string('request_audience')->default('everyone')->after('posts_audience')->change();
        });
    }
};
