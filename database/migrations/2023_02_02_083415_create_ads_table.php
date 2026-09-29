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
        if (!Schema::hasTable('ads')) {
            Schema::create('ads', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('college_id');
                $table->foreign('college_id')->references('id')->on('colleges');
                $table->string('title');
                $table->boolean('lang')->nullable()->default(1);
                $table->date('ad_date');
                $table->string('detail_portion')->nullable();
                $table->text('detail')->nullable();
                $table->string('keywords');
                $table->string('photos')->nullable();
                $table->string('file')->nullable();
                $table->boolean('active')->nullable()->default(1);
                $table->string('duration');
                $table->boolean('priority')->nullable()->default(0);
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
        Schema::dropIfExists('ads');
    }
};
