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
        Schema::create('paragraph', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('menu_id');
            $table->bigInteger('page_section_id');
            $table->string('title');
            $table->text('description');
            $table->text('new_description');
            $table->string('meta_title');
            $table->string('meta_description');
            $table->string('meta_keywords');
            $table->tinyInteger('status');
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
        Schema::dropIfExists('paragraph');
    }
};
