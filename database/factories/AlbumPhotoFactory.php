<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\AlbumPhoto;

class AlbumPhotoFactory extends Factory
{
    protected $model = AlbumPhoto::class;

    public function definition(): array
    {
        return [
            'album_id' => 1, // Just a default, since we don't strictly test Albums here yet
            'user_id' => 1,
            'title' => $this->faker->word,
            'title_en' => $this->faker->word,
            'img' => 'dummy/img.jpg',
            'thumb_img' => 'dummy/thumb.jpg',
        ];
    }
}
