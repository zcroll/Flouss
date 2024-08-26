<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\JobName;
use App\Models\Workplace;

class WorkplaceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Workplace::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'body' => $this->faker->text(),
            'lang' => $this->faker->randomElement(["fr","en"]),
            'job_name_id' => JobName::factory(),
        ];
    }
}
