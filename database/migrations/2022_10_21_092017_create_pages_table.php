<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('pages')) {
            Schema::create('pages', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('college_id')->nullable();
                $table->foreign('college_id')->references('id')->on('colleges');
                $table->string('title');
                $table->smallInteger('lang');
                $table->string('detail_portion');
                $table->text('detail');
                $table->string('keywords')->nullable();
                $table->string('img');
                $table->string('thumb_img')->nullable();
                $table->string('file')->nullable();
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
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pages');
    }
};
