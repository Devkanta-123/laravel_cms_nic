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
        Schema::create('carousel', function (Blueprint $table) {
            $table->id();
            $table->string('image');
            $table->string('type');
            $table->string('link');
            $table->string('title');
            $table->string('alt');
            $table->integer('order');
            $table->tinyInteger('status');
            $table->string('hindi_title')->nullable();
            $table->string('khasi_title')->nullable();
            $table->string('other_title')->nullable();
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
        Schema::dropIfExists('carousel');
    }
};
