n
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
        Schema::create('whos_who', function (Blueprint $table) {
            $table->id(); // auto-incrementing primary key
            $table->string('name'); // Name of the person
            $table->string('designation'); // Designation of the person
            $table->string('email')->nullable(); // Email of the person
            $table->string('contact')->nullable(); // Contact number
            $table->string('profile_image')->nullable(); // Optional: Profile image URL/path
            $table->unsignedBigInteger('level_id'); // Foreign key to a master levels table (e.g., state, district, block)
            $table->string('district_name', 255)->nullable();
            $table->string('block_name', 255)->nullable();
            $table->string('rejected_remarks'); // Store one file path per row
            $table->unsignedBigInteger('publisher_id'); // Store one file path per row
            $table->string('application_id', 30)->nullable();
            $table->timestamps(); // Laravel's default created_at and updated_at fields
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whos_who');
    }
};
