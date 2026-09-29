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
        if (Schema::hasColumn('staff_academics', 'type')) {
            Schema::table('staff_academics', function (Blueprint $table) {
                $table->tinyInteger('type')->nullable()->default(null)->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('staff_academics', function (Blueprint $table) {
            $table->tinyInteger('type')->nullable()->default(1)->change();
        });
    }
};
