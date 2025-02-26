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
        Schema::create('menu', function (Blueprint $table) {
            $table->id();
            $table->string('menu_name');
            $table->tinyInteger('menu_master');
            $table->tinyInteger('order')->default(1);
            $table->bigInteger('parent');
            $table->tinyInteger('status')->default('1');
            $table->string('hindi_name')->nullable();
            $table->string('khasi_name')->nullable();
            $table->string('other_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu');
    }
};
