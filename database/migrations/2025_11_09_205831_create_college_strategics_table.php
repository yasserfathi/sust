<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('college_strategics')) {
            Schema::create('college_strategics', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('college_id')->nullable();
                $table->foreign('college_id')->references('id')->on('colleges');
                $table->smallInteger('lang');
                $table->text('vision');
                $table->text('mission');
                $table->text('goals');
                $table->string('keywords')->nullable();
                $table->unsignedBigInteger('auth_id');
                $table->foreign('auth_id')->references('id')->on('users');
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('updated_at')->nullable();
                $table->softDeletes();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('college_strategics');
    }
};
