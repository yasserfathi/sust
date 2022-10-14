<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category_page_user', function (Blueprint $table) {
            $table->foreignId('category_page_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->unique(['category_page_id', 'user_id']);
            $table->unsignedBigInteger('auth_id');
            $table->foreign('auth_id')->references('id')->on('users');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->nullable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('category_page_user');
    }
};
