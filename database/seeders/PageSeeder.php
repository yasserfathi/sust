<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\College;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1. Global Page for HomeController (About Us)
        // This corresponds to HomeController::about() -> where('title', 'about_us')
        DB::table('pages')->updateOrInsert(
            ['title' => 'about_us', 'lang' => 1],
            [
                'detail' => '<p>Welcome to the University. This is the main About Us content.</p>',
                'detail_portion' => 'Welcome to the University.',
                'keywords' => 'university, about us',
                'img' => 'uploads/about_us.jpg',
                'file' => 'uploads/about_us.pdf',
                'college_id' => null, // Assuming global pages are not linked to a specific college
                'auth_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // 2. College Specific Pages for CollegeArController
        // We fetch all colleges to ensure every college has these pages
        $colleges = College::all();

        $pageTitles = ['about', 'dean_message', 'activities', 'vision_mission_objectives'];

        foreach ($colleges as $college) {
            foreach ($pageTitles as $title) {
                DB::table('pages')->updateOrInsert(
                    ['title' => $title, 'college_id' => $college->id, 'lang' => 2],
                    [
                        'detail' => "<p>This is the <strong>$title</strong> content for " . ($college->name_en ?? 'this college') . ".</p>",
                        'detail_portion' => "This is the $title content.",
                        'keywords' => 'keywords',
                        'img' => 'uploads/default.jpg',
                        'file' => 'uploads/default.pdf',
                        'auth_id' => 1,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
        }
    }
}