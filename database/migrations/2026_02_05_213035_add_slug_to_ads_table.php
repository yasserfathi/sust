<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('ads', 'slug')) {
            Schema::table('ads', function (Blueprint $table) {
                $table->string('slug')->nullable()->after('title');
            });
        }

        // Seed existing slugs
        $ads = DB::table('ads')->get();
        foreach ($ads as $ad) {
            DB::table('ads')
                ->where('id', $ad->id)
                ->update(['slug' => Str::slug($ad->title)]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ads', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
