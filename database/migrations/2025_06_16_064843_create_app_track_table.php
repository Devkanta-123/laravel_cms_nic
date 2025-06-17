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
        Schema::create('app_track', function (Blueprint $table) {
        $table->id(); // auto-increment primary key
        $table->unsignedBigInteger('menu_id');
        $table->unsignedBigInteger('page_section_master_id');
        $table->unsignedBigInteger('user_from')->nullable();
        $table->string('user_from_name')->nullable();
        $table->unsignedBigInteger('user_to')->nullable();
        $table->string('user_to_name')->nullable();
        $table->text('remarks')->nullable();
        $table->string('flag', 50)->nullable();
        $table->string('action', 50)->nullable();
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_track');
    }
};
