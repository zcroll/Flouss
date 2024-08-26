<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\JobName;
use App\Models\WorkEnvironment;

class WorkEnvironmentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = WorkEnvironment::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'description' => $this->faker->text(),
            'score' => $this->faker->numberBetween(-10000, 10000),
            'lang' => $this->faker->randomElement(["fr","en"]),
            'job_name_id' => JobName::factory(),
        ];
    }
}
