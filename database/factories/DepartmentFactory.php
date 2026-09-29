<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Department;
use App\Models\College;

class DepartmentFactory extends Factory
{
    protected $model = Department::class;

    public function definition(): array
    {
        return [
            'name' => 'قسم ' . $this->faker->unique()->word,
            'name_en' => $this->faker->unique()->words(3, true) . ' Department',
            'keywords' => $this->faker->words(5, true),
            'description' => $this->faker->paragraph,
            'keywords_ar' => 'كلمة، مفتاحية، تجريبية',
            'description_ar' => 'وصف تجريبي للقسم باللغة العربية',
            'college_id' => College::factory(),
            'school_id' => null,
            'active' => 1,
            'user_id' => 1,
        ];
    }
}
