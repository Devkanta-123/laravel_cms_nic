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
        Schema::create('logo', function (Blueprint $table) {
            $table->id();
            $table->string('image');
            $table->integer('menu_id');
            $table->string('rejected_remarks'); // Store one file path per row
            $table->unsignedBigInteger('publisher_id'); // Store one file path per row
            $table->string('application_id', 30)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logo');
    }
};
