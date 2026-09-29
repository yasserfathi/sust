<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    public function up()
    {
        if (!Schema::hasTable('schools')) {
            Schema::create('schools', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('college_id');
                $table->foreign('college_id')->references('id')->on('colleges');
                $table->string('name');
                $table->string('name_en');
                $table->text('keywords')->nullable();
                $table->text('description')->nullable();
                $table->text('keywords_ar')->nullable();
                $table->text('description_ar')->nullable();
                $table->boolean('active')->nullable()->default(1);
                $table->unsignedBigInteger('user_id');
                $table->foreign('user_id')->references('id')->on('users');
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('updated_at')->nullable();
                $table->softDeletes();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('schools');
    }
};
