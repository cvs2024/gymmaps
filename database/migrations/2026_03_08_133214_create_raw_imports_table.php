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
        Schema::create('raw_imports', function (Blueprint $table) {
            $table->id();
            $table->string('source')->index();
            $table->string('external_id')->nullable()->index();
            $table->string('import_hash')->unique();
            $table->json('payload');
            $table->string('status')->default('pending')->index();
            $table->text('error_message')->nullable();
            $table->timestamp('processed_at')->nullable()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('raw_imports');
    }
};
