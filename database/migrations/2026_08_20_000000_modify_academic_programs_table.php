<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('academic_programs', function (Blueprint $table) {
            if (Schema::hasColumn('academic_programs', 'credit_hours')) {
                $table->dropColumn('credit_hours');
            }
            if (!Schema::hasColumn('academic_programs', 'NOOFYEARSNO')) {
                $table->integer('NOOFYEARSNO')->nullable()->after('program_name_en');
            }
            if (!Schema::hasColumn('academic_programs', 'NOOFSEM')) {
                $table->integer('NOOFSEM')->nullable()->after('NOOFYEARSNO');
            }
        });
    }

    public function down(): void
    {
        Schema::table('academic_programs', function (Blueprint $table) {
            if (Schema::hasColumn('academic_programs', 'NOOFYEARSNO')) {
                $table->dropColumn('NOOFYEARSNO');
            }
            if (Schema::hasColumn('academic_programs', 'NOOFSEM')) {
                $table->dropColumn('NOOFSEM');
            }
            if (!Schema::hasColumn('academic_programs', 'credit_hours')) {
                $table->string('credit_hours')->nullable()->after('program_name_en');
            }
        });
    }
};
