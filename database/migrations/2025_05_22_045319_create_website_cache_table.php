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
       Schema::create('website_cache', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // e.g., 'carousel', 'gallery'
            $table->longText('data'); // Base64-encoded JSON
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('website_cache');
    }
};
