<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('workshops', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('title');
        });

        // Generate slugs for existing records
        $workshops = DB::table('workshops')->get();
        foreach ($workshops as $workshop) {
            if (empty($workshop->slug)) {
                $slug = Str::slug($workshop->title, '-');
                // Ensure unique slug
                $originalSlug = $slug;
                $count = 1;
                while (DB::table('workshops')->where('slug', $slug)->where('id', '!=', $workshop->id)->exists()) {
                    $slug = $originalSlug . '-' . $count;
                    $count++;
                }
                DB::table('workshops')
                    ->where('id', $workshop->id)
                    ->update(['slug' => $slug]);
            }
        }
    }

    public function down(): void
    {
        Schema::table('workshops', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
