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
        Schema::create('latest_news', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('type');
            $table->string('file')->nullable();
            $table->string('link')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->string('order');
            $table->tinyInteger('status');
            $table->string('hindi_title')->nullable();
            $table->string('khasi_title')->nullable();
            $table->string('other_title')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('latest_news');
    }
};
