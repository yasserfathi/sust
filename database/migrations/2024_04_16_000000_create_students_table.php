<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        if (!Schema::hasTable('students')) {
            Schema::create('students', function (Blueprint $table) {
                $table->id();
                $table->string('username')->unique(); // For Moodle login ID
                $table->string('name')->nullable();
                $table->string('email')->unique()->nullable(); // Nullable initially
                $table->timestamp('email_verified_at')->nullable();
                $table->string('moodle_token')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('students');
    }
};