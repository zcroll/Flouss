<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\JobInfo;

class JobInfoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = JobInfo::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'slug' => $this->faker->slug(),
            'eucation_level' => $this->faker->word(),
            'score' => $this->faker->word(),
            'education_level' => $this->faker->word(),
            'salary' => $this->faker->word(),
            'satisfaction' => $this->faker->word(),
            'satisfaction_Raw' => $this->faker->word(),
            'image' => $this->faker->word(),
        ];
    }
}
