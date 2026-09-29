<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tablesAndColumns = [
            'workshops' => ['title'],
            'ads' => ['title'],
            'news' => ['title']
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
            'workshops' => ['title'],
            'ads' => ['title'],
            'news' => ['title']
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
