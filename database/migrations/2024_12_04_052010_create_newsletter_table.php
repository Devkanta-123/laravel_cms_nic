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
        Schema::create('newsletter', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->unsignedInteger('menu_id'); // Unsigned integer for foreign key compatibility
            $table->unsignedInteger('page_section_id'); // Unsigned integer for foreign key compatibility
            $table->string('english_title')->nullable(); // Nullable title in English
            $table->string('hindi_title')->nullable(); // Nullable title in Hindi
            $table->string('khasi_title')->nullable(); // Nullable title in Khasi
            $table->date('date'); // Date of newsletter
            $table->timestamps(); // Automatically adds created_at and updated_at
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('newsletter');
    }
};
