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
        Schema::table('posts', function (Blueprint $table) {
            $table->enum('privacy', ['public', 'private', 'friends'])->default('public')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('posts')->where('privacy', 'friends')->update(['privacy' => 'private']);

        Schema::table('posts', function (Blueprint $table) {
            $table->enum('privacy', ['public', 'private'])->default('public')->change();
        });
    }
};
