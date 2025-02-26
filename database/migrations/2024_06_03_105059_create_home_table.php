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
        Schema::create('home', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('description');
            $table->string('more_link')->nullable();
            $table->string('image')->nullable();
            $table->string('image_decsription')->nullable();
            $table->string('hindi_title')->nullable();
            $table->string('khasi_title')->nullable();
            $table->string('other_title')->nullable();
            $table->string('hindi_description')->nullable();
            $table->string('khasi_description')->nullable();
            $table->string('other_description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('home');
    }
};
