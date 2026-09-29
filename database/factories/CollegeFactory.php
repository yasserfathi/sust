<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\College;
use Illuminate\Support\Str;

class CollegeFactory extends Factory
{
    protected $model = College::class;

    public function definition(): array
    {
        $nameEn = $this->faker->unique()->words(3, true) . ' College';
        return [
            'name' => 'كلية ' . $this->faker->unique()->word,
            'name_en' => $nameEn,
            'slug' => Str::slug($nameEn),
            'college_type' => 'college',
            'active' => 1,
            'user_id' => 1,
            'logo' => null,
            'logo_en' => null,
            'banner' => null,
        ];
    }
}
