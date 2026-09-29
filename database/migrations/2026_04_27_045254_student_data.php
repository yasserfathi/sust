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
        if (!Schema::hasTable('student_data')) {
            Schema::create('student_data', function (Blueprint $table) {
                $table->id();
                $table->string('university_number')->unique();
                $table->string('full_name');
                $table->string('college');
                $table->string('department');
                $table->string('semester');
                $table->string('academic_year');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_data');
    }
};
