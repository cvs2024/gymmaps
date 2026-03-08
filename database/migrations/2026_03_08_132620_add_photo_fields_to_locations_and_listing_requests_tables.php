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
        Schema::table('locations', function (Blueprint $table) {
            $table->string('photo_url')->nullable()->after('phone');
        });

        Schema::table('listing_requests', function (Blueprint $table) {
            $table->string('photo_path')->nullable()->after('message');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('locations', function (Blueprint $table) {
            $table->dropColumn('photo_url');
        });

        Schema::table('listing_requests', function (Blueprint $table) {
            $table->dropColumn('photo_path');
        });
    }
};
