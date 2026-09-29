<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tablesAndColumns = [
            'users' => ['name', 'name_en'],
            'colleges' => ['name', 'name_en'],
            'albums' => ['title', 'title_en'],
            'academic_programs' => ['program_name', 'program_name_en'],
            'academic_courses' => ['course_title', 'course_code'],
            'departments' => ['name', 'name_en'],
            'sections' => ['name', 'name_en'],
            'schools' => ['name', 'name_en'],
            'pages' => ['title'],
            'category_pages' => ['title']
        ];

        foreach ($tablesAndColumns as $tableName => $columns) {
            if (Schema::hasTable($tableName)) {
                try {
                    Schema::table($tableName, function (Blueprint $table) use ($columns) {
                        $table->fullText($columns);
                    });
                } catch (\Illuminate\Database\QueryException $e) {
                    if (str_contains($e->getMessage(), 'Duplicate key name')) {
                        continue;
                    }
                    throw $e;
                }
            }
        }
    }

    public function down(): void
    {
        $tablesAndColumns = [
            'users' => ['name', 'name_en'],
            'colleges' => ['name', 'name_en'],
            'albums' => ['title', 'title_en'],
            'academic_programs' => ['program_name', 'program_name_en'],
            'academic_courses' => ['course_title', 'course_code'],
            'departments' => ['name', 'name_en'],
            'sections' => ['name', 'name_en'],
            'schools' => ['name', 'name_en'],
            'pages' => ['title'],
            'category_pages' => ['title']
        ];

        foreach ($tablesAndColumns as $tableName => $columns) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) use ($columns) {
                    $table->dropFullText([$columns]);
                });
            }
        }
    }
};
