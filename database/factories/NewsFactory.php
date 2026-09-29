<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\News;
use App\Models\College;

class NewsFactory extends Factory
{
    protected $model = News::class;

    public function definition(): array
    {
        return [
            'college_id' => College::factory(),
            'title' => $this->faker->unique()->sentence,
            'slug' => $this->faker->unique()->slug,
            'lang' => $this->faker->numberBetween(1, 2),
            'news_date' => $this->faker->date('Y-m-d'),
            'detail_portion' => $this->faker->paragraph,
            'detail' => $this->faker->text,
            'keywords' => $this->faker->words(3, true),
            'active' => 1,
            'priority' => 0,
            'file' => null,
            'auth_id' => 1
        ];
    }
}
