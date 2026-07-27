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
        Schema::create('academic_courses', function (Blueprint $table) {
            $table->id('course_id');
            $table->unsignedBigInteger('program_id');
            $table->foreign('program_id')->references('id')->on('academic_programs')->onDelete('cascade');
            $table->integer('year');
            $table->integer('semester');
            $table->integer('lang')->default(1);
            $table->string('course_title', 400);
            $table->string('course_code', 400);
            $table->string('course_hours', 100);
            $table->string('course_desc', 600)->nullable();
            $table->string('course_file')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academic_courses');
    }
};
