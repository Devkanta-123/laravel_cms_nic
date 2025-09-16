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
        Schema::create('faqs', function (Blueprint $table) {
            $table->id(); // Primary key (auto-increment)
            $table->string('english_title_question'); // English title question
            $table->text('english_answer'); // English answer
            $table->integer('order')->default(0); // Order of display
            $table->string('rejected_remarks')->nullable();
            $table->unsignedBigInteger('publisher_id')->nullable();
            $table->string('application_id')->nullable();
            $table->timestamps(); // Created at & Updated at timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faqs');
    }
};
