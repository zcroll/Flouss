<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\JobName;
use App\Models\JobType;

class JobTypeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = JobType::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'intro' => $this->faker->word(),
            'body' => $this->faker->word(),
            'lang' => $this->faker->randomElement(["fr","en"]),
            'job_name_id' => JobName::factory(),
        ];
    }
}
