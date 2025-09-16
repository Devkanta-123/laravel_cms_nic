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
        Schema::create('map', function (Blueprint $table) {
            $table->id();
            $table->decimal('lat', 10, 7);   // Latitude (e.g. 25.5651200)
            $table->decimal('lng', 10, 7);   // Longitude (e.g. 91.8835340)
            $table->integer('zoom')->default(14); // Default zoom level
            $table->string('place_name')->nullable(); // Optional place name / label
            $table->string('rejected_remarks')->nullable();
            $table->unsignedBigInteger('publisher_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();  // bigint
            $table->string('application_id')->nullable();
             $table->char('flag', 1)->default('N');    
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('map');
    }
};
