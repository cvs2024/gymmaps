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
            $table->string('source')->nullable()->after('photo_url')->index();
            $table->string('external_id')->nullable()->after('source')->index();
            $table->timestamp('imported_at')->nullable()->after('external_id');
            $table->timestamp('last_seen_at')->nullable()->after('imported_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('locations', function (Blueprint $table) {
            $table->dropColumn([
                'source',
                'external_id',
                'imported_at',
                'last_seen_at',
            ]);
        });
    }
};
