<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('ad_album_photo')) {
            Schema::create('ad_album_photo', function (Blueprint $table) {
                $table->id();
    
                // Relationship with ads table
                $table->foreignId('ad_id')
                    ->constrained('ads')
                    ->cascadeOnDelete();
    
                // Relationship with album_photos table
                $table->foreignId('album_photo_id')
                    ->constrained('album_photos')
                    ->cascadeOnDelete();
    
                $table->unique(['ad_id', 'album_photo_id']);
                $table->timestamps();
                $table->softDeletes();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ad_album_photo');
    }
};
