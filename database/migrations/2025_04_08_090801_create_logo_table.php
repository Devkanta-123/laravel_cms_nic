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
       Schema::create('logo', function (Blueprint $table) {
    $table->id(); // bigint NOT NULL
    $table->string('image', 255); // character varying(255) NOT NULL
    $table->integer('menu_id');   // integer NOT NULL

    $table->timestamp('created_at')->nullable();
    $table->timestamp('updated_at')->nullable();

    $table->unsignedBigInteger('user_id')->nullable();  // bigint
    $table->char('flag', 1)->default('N');              // character(1) DEFAULT 'N'
    $table->smallInteger('role_id')->nullable();        // smallint
    $table->string('rejected_remarks')->nullable();     // character varying
    $table->integer('publisher_id')->nullable();        // integer
    $table->string('application_id')->nullable();       // character varying
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logo');
    }
};
