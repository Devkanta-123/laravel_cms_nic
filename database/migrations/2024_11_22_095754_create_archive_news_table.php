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
        Schema::create('archive_news', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->string('file'); // 'file' column
            $table->string('link')->nullable(); // 'link' column
            $table->string('type'); // 'type' column
            $table->string('title')->nullable(); // 'title' column
            $table->integer('order'); // 'order' column (could be used for sorting)
            $table->boolean('status'); // 'status' column (to indicate active or inactive)
            $table->string('hindi_title')->nullable(); // 'hindi_title' column, nullable in case it's not available
            $table->string('khasi_title')->nullable(); // 'khasi_title' column, nullable
            $table->string('other_title')->nullable(); // 'other_title' column, nullable
            $table->timestamps(); // 'created_at' and 'updated_at' columns automatically managed by Laravel
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('archive_news');
    }
};
