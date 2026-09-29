<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\AcademicCourse;
use App\Models\AcademicProgram;

class AcademicCourseFactory extends Factory
{
    protected $model = AcademicCourse::class;

    public function definition(): array
    {
        return [
            'program_id' => AcademicProgram::factory(),
            'course_title' => 'مادة ' . $this->faker->unique()->word,
            'course_code' => strtoupper($this->faker->unique()->lexify('???-###')),
            'course_hours' => (string)$this->faker->numberBetween(2, 4),
            'course_desc' => $this->faker->sentence,
            'course_file' => null,
            'year' => $this->faker->numberBetween(1, 5),
            'semester' => $this->faker->numberBetween(1, 10),
            'lang' => $this->faker->numberBetween(1, 2)
        ];
    }
}
