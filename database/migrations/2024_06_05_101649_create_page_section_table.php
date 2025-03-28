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
        Schema::create('page_section', function (Blueprint $table) {
            $table->id();
            $table->integer('menu_id');
            $table->string('page_section_name');
            $table->bigInteger('page_section_id');
            $table->tinyInteger('order')->default(1);
            $table->tinyInteger('status')->default('1');
            $table->string('hindi_name')->nullable();
            $table->string('khasi_name')->nullable();
            $table->string('other_name')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_section');
    }
};
