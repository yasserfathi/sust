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
        $collegePage = DB::table('category_pages')->where('title', 'اضافة كلية')->first();
        if ($collegePage) {
            $schoolPage = DB::table('category_pages')->where('url', '/school')->first();
            if (!$schoolPage) {
                DB::table('category_pages')->insert([
                    'category_id' => $collegePage->category_id,
                    'title' => 'اضافة مدرسة',
                    'url' => '/school',
                    'active' => 1,
                    'user_id' => $collegePage->user_id,
                ]);
            }
        }

        $departmentPage = DB::table('category_pages')->where('title', 'اضافة قسم')->first();
        if ($departmentPage) {
            $sectionPage = DB::table('category_pages')->where('url', '/section')->first();
            if (!$sectionPage) {
                DB::table('category_pages')->insert([
                    'category_id' => $departmentPage->category_id,
                    'title' => 'اضافة شعبة',
                    'url' => '/section',
                    'active' => 1,
                    'user_id' => $departmentPage->user_id,
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('category_pages')->where('url', '/school')->delete();
        DB::table('category_pages')->where('url', '/section')->delete();
    }
};
