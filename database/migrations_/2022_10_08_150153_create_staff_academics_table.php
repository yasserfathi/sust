<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff_academics', function (Blueprint $table) {
            $table->id();
            $table->boolean('lang')->nullable()->default(1);
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedBigInteger('auth_id');
            $table->foreign('auth_id')->references('id')->on('users');
            $table->string('item');
            $table->string('item_val');
            $table->text('keywords');
            $table->string('url')->nullable();
            $table->string('img')->nullable();
            $table->string('thumb_img')->nullable();
            $table->string('detail')->nullable();
            $table->string('file')->nullable();
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
        Schema::dropIfExists('staff_academics');
    }
};
