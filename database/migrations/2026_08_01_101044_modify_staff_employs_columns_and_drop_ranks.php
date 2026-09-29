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
        if (Schema::hasColumn('staff_employs', 'job_title') && !Schema::hasColumn('staff_employs', 'grade')) {
            Schema::table('staff_employs', function (Blueprint $table) {
                $table->renameColumn('job_title', 'grade');
            });
        }
        if (Schema::hasColumn('staff_employs', 'job_title_en') && !Schema::hasColumn('staff_employs', 'grade_en')) {
            Schema::table('staff_employs', function (Blueprint $table) {
                $table->renameColumn('job_title_en', 'grade_en');
            });
        }
        if (Schema::hasColumn('staff_employs', 'rank')) {
            Schema::table('staff_employs', function (Blueprint $table) {
                $table->dropColumn(['rank']);
            });
        }
        if (Schema::hasColumn('staff_employs', 'rank_en')) {
            Schema::table('staff_employs', function (Blueprint $table) {
                $table->dropColumn(['rank_en']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('staff_employs', function (Blueprint $table) {
            $table->renameColumn('grade', 'job_title');
            $table->renameColumn('grade_en', 'job_title_en');
            $table->string('rank')->nullable();
            $table->string('rank_en')->nullable();
        });
    }
};
