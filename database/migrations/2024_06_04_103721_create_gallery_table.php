<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('gallery', function (Blueprint $table) {
            $table->id();
            $table->integer('parent_id');
            $table->string('gallery_name');
            $table->string('gallery_image');
            $table->string('gallery_description');
            $table->string('link');
            $table->string('meta_title');
            $table->string('meta_description');
            $table->string('meta_keywords');
            $table->string('order');
            $table->smallInteger('status');
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
        Schema::dropIfExists('gallery');
    }
};
