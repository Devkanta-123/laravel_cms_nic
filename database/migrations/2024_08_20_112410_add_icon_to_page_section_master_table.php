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
        Schema::table('page_section_master', function (Blueprint $table) {
            $table->string('icon')->nullable()->after('page_section_name'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('page_section_master', function (Blueprint $table) {
            $table->dropColumn('icon');
        });
    }
};
