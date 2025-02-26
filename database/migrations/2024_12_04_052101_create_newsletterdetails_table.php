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
        Schema::create('newsletterdetails', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->unsignedBigInteger('newsletterid'); // Foreign key reference to newsletters table
            $table->unsignedInteger('menu_id')->nullable(); // Optional field for menu_id
            $table->unsignedInteger('page_section_id')->nullable(); // Optional field for page_section_id
            $table->string('english_title')->nullable(); // Nullable English title
            $table->string('hindi_title')->nullable(); // Nullable Hindi title
            $table->string('khasi_title')->nullable(); // Nullable Khasi title
            $table->date('fromdate')->nullable(); // Optional from date
            $table->date('todate')->nullable(); // Optional to date
            $table->date('date')->nullable(); // Optional date
            $table->string('filepath')->nullable(); // Optional file path
            $table->timestamps(); // Automatically adds created_at and updated_at

            // Define foreign key constraint
            // $table->foreign('newsletterid')->references('id')->on('newsletters')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('newsletterdetails');
    }
};
