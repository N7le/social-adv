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
            $table->string('username')->unique()->after('name');
            $table->string('bio', 500)->nullable()->after('email');
            $table->string('work', 50)->nullable()->after('bio');
            $table->string('education', 50)->nullable()->after('work');
            $table->string('city', 100)->nullable()->after('education');
            $table->string('website')->nullable()->after('city');
            $table->date('birthday')->after('website');
            $table->string('profile_photo')->nullable()->after('birthday');
            $table->string('cover_photo')->nullable()->after('profile_photo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'username',
                'bio',
                'work',
                'education',
                'city',
                'website',
                'birthday',
                'profile_photo',
                'cover_photo',
            ]);
        });
    }
};
