<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('workshop_album_photo', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('workshop_id');
            $table->unsignedBigInteger('album_photo_id');
            $table->timestamps();
        });

        // Migrate data
        $workshops = DB::table('workshops')->get();
        foreach ($workshops as $workshop) {
            if (!empty($workshop->photos)) {
                $photoIds = array_filter(array_map('trim', explode(',', $workshop->photos)));
                foreach ($photoIds as $photoId) {
                    if (is_numeric($photoId)) {
                        $exists = DB::table('album_photos')->where('id', $photoId)->exists();
                        if ($exists) {
                            DB::table('workshop_album_photo')->insert([
                                'workshop_id' => $workshop->id,
                                'album_photo_id' => $photoId,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                        }
                    }
                }
            }
        }

        // Drop the photos column
        Schema::table('workshops', function (Blueprint $table) {
            $table->dropColumn('photos');
        });
    }

    public function down(): void
    {
        Schema::table('workshops', function (Blueprint $table) {
            $table->string('photos')->nullable();
        });

        $workshops = DB::table('workshops')->get();
        foreach ($workshops as $workshop) {
            $photos = DB::table('workshop_album_photo')
                        ->where('workshop_id', $workshop->id)
                        ->pluck('album_photo_id')
                        ->toArray();
            if (!empty($photos)) {
                DB::table('workshops')
                    ->where('id', $workshop->id)
                    ->update(['photos' => implode(',', $photos)]);
            }
        }

        Schema::dropIfExists('workshop_album_photo');
    }
};
