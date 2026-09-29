<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('colleges', 'slug')) {
            Schema::table('colleges', function (Blueprint $table) {
                $table->string('slug')->nullable()->after('name_en');
            });
        }

        // Populate slug for existing colleges
        $colleges = DB::table('colleges')->get();
        foreach ($colleges as $college) {
            if (empty($college->name_en)) {
                continue;
            }
            $slug = Str::slug($college->name_en);
            // Handle duplicates if any by appending id
            $exists = DB::table('colleges')->where('slug', $slug)->where('id', '!=', $college->id)->exists();
            if ($exists) {
                $slug = $slug . '-' . $college->id;
            }
            DB::table('colleges')->where('id', $college->id)->update(['slug' => $slug]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('colleges', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
