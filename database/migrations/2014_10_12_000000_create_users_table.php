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
        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('name_en')->nullable();
                $table->string('univ_no')->nullable();
                $table->string('email')->unique();
                $table->tinyInteger('role')->default(1);
                $table->string('phone')->nullable();
                $table->string('img')->nullable();
                $table->string('thumb_img')->nullable();
                $table->boolean('active')->nullable()->default(0);
                $table->unsignedBigInteger('auth_id')->nullable();
                $table->foreign('auth_id')->references('id')->on('users');
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->rememberToken();
                $table->timestamps();
                $table->softDeletes();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
