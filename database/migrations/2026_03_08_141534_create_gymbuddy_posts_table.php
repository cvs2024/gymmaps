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
        Schema::create('gymbuddy_posts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('sport');
            $table->string('days_per_week');
            $table->string('address')->nullable();
            $table->string('postcode')->nullable();
            $table->string('city');
            $table->string('gender_preference')->nullable();
            $table->text('about_you');
            $table->text('search_message');
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gymbuddy_posts');
    }
};
