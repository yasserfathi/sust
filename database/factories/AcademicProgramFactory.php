<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\AcademicProgram;
use App\Models\Department;

class AcademicProgramFactory extends Factory
{
    protected $model = AcademicProgram::class;

    public function definition(): array
    {
        return [
            'department_id' => Department::factory(),
            'program_type' => $this->faker->numberBetween(1, 4),
            'program_name' => 'برنامج ' . $this->faker->unique()->word,
            'program_name_en' => $this->faker->unique()->words(3, true) . ' Program',
            'NOOFYEARSNO' => $this->faker->numberBetween(2, 5),
            'NOOFSEM' => $this->faker->numberBetween(4, 10),
            'file' => null,
            'active' => 1,
            'user_id' => 1,
        ];
    }
}
