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
       Schema::create('news_album_photo', function (Blueprint $table) {
        $table->id();
        
        // ربط مع جدول الأخبار
        $table->foreignId('news_id')
              ->constrained('news')
              ->cascadeOnDelete();

        // ربط مع جدول الصور
        $table->foreignId('album_photo_id')
              ->constrained('album_photos')
              ->cascadeOnDelete();

        $table->unique(['news_id', 'album_photo_id']);
        $table->timestamps();
        $table->softDeletes();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news_album_photo');
    }
};
