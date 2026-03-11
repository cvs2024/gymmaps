<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('locations', function (Blueprint $table) {
            $table->string('google_place_id')->nullable()->after('logo_url')->index();
            $table->text('opening_hours_json')->nullable()->after('google_place_id');
            $table->timestamp('opening_hours_updated_at')->nullable()->after('opening_hours_json');
        });
    }

    public function down(): void
    {
        Schema::table('locations', function (Blueprint $table) {
            $table->dropIndex(['google_place_id']);
            $table->dropColumn([
                'google_place_id',
                'opening_hours_json',
                'opening_hours_updated_at',
            ]);
        });
    }
};
