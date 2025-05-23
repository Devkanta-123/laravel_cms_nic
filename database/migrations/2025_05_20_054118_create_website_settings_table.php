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
         Schema::create('website_settings', function (Blueprint $table) {
        $table->id();
        $table->integer('archieve_duration')->nullable();
        $table->unsignedBigInteger('language_id')->nullable();
        $table->string('primary_color')->nullable();
        $table->string('secondary_color')->nullable();
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('website_settings');
    }
};
