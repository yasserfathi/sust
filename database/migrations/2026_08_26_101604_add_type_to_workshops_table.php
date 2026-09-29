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
        if (!Schema::hasColumn('workshops', 'type')) {
            Schema::table('workshops', function (Blueprint $table) {
                $table->tinyInteger('type')->default(1)->after('title')->comment('1: ورشة عمل (Workshop), 2: مؤتمر (Conference), 3: سمنار (Seminar)');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('workshops', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};
