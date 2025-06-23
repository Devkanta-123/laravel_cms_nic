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
        Schema::create('cards', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('menu_id');
            $table->bigInteger('page_section_id');
            $table->string('card_title');
            $table->string('card_description')->nullable();
            $table->string('image')->nullable();
            $table->string('more_link')->nullable();
            $table->string('order');
            $table->smallInteger('status');
            $table->string('hindi_title')->nullable();
            $table->string('khasi_title')->nullable();
            $table->string('other_title')->nullable();
            $table->string('hindi_description')->nullable();
            $table->string('khasi_description')->nullable();
            $table->string('other_description')->nullable();
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
        Schema::dropIfExists('cards');
    }
};
