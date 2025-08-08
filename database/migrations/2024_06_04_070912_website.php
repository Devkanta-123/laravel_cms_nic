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
        Schema::create('website', function (Blueprint $table) {
            $table->id();
            $table->string('website_name');
            $table->string('website_department');
            $table->string('website_short');
            $table->string('logo');
            $table->string('favicon')->nullable();
            $table->string('title');
            $table->text('description');
            $table->string('hindi_name')->nullable();
            $table->string('khasi_name')->nullable();
            $table->string('other_name')->nullable();
            $table->string('hindi_department')->nullable();
            $table->string('khasi_department')->nullable();
            $table->string('other_department')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('website');
    }
};
