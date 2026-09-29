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
        if (Schema::hasTable('university_official_ranks')) {
            Schema::rename('university_official_ranks', 'head_administrative_positions');
        }

        Schema::table('head_administrative_positions', function (Blueprint $table) {
            if (Schema::hasColumn('head_administrative_positions', 'name')) {
                $table->dropColumn('name');
            }
            if (Schema::hasColumn('head_administrative_positions', 'name_ar')) {
                $table->dropColumn('name_ar');
            }
            if (Schema::hasColumn('head_administrative_positions', 'name_en')) {
                $table->dropColumn('name_en');
            }
            if (!Schema::hasColumn('head_administrative_positions', 'administrative_positions_id')) {
                $table->unsignedBigInteger('administrative_positions_id')->nullable()->after('id');
                $table->foreign('administrative_positions_id', 'head_admin_pos_admin_pos_id_fk')->references('id')->on('administrative_positions')->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('head_administrative_positions', function (Blueprint $table) {
            $table->dropForeign('head_admin_pos_admin_pos_id_fk');
            $table->dropColumn('administrative_positions_id');
            
            $table->string('name')->nullable();
            $table->string('name_ar')->nullable();
        });

        Schema::rename('head_administrative_positions', 'university_official_ranks');
    }
};
