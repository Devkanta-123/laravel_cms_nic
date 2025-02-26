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

        Schema::table('page_section', function (Blueprint $table) {
            $table->dropColumn(['created_at', 'updated_at', 'hindi_name', 'khasi_name', 'other_name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('page_section', function (Blueprint $table) {
            $table->timestamps(); 
            $table->string('hindi_name')->nullable();
            $table->string('khasi_name')->nullable();
            $table->string('other_bname')->nullable();
        });
    }
};
