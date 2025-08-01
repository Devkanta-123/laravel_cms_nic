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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id');
            $table->string('title');
            $table->date('date');
            $table->string('file'); // Store one file path per row
            $table->string('rejected_remarks'); // Store one file path per row
            $table->unsignedBigInteger('publisher_id'); // Store one file path per row
            $table->string('application_id', 30)->nullable();
            $table->unsignedBigInteger('ispinned', 1)->nullable();
            $table->timestamps();
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
