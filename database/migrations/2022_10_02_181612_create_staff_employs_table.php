<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('staff_employs')) {
            Schema::create('staff_employs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('department_id');
                $table->foreign('department_id')->references('id')->on('departments');
                $table->string('job_title')->nullable();
                $table->string('rank')->nullable();
                $table->date('hire_date')->nullable();
                $table->string('specialty')->nullable();
                $table->string('subspecialty')->nullable();
                $table->string('specialty_en')->nullable();
                $table->string('subspecialty_en')->nullable();
                $table->unsignedBigInteger('user_id');
                $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('staff_employs');
    }
};
