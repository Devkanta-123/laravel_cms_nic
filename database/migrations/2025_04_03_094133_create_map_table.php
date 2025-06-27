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
            $table->text('iframe'); // To store iFrame content
            $table->string('rejected_remarks')->nullable();
            $table->unsignedBigInteger('publisher_id')->nullable();
            $table->string('application_id')->nullable();
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
