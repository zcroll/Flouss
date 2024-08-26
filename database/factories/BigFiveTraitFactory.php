<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\BigFiveTrait;
use App\Models\JobName;

class BigFiveTraitFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BigFiveTrait::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'description' => $this->faker->text(),
            'openness' => $this->faker->randomFloat(2, 0, 999.99),
            'conscientiousness' => $this->faker->randomFloat(2, 0, 999.99),
            'extraversion' => $this->faker->randomFloat(2, 0, 999.99),
            'agreeableness' => $this->faker->randomFloat(2, 0, 999.99),
            'neuroticism' => $this->faker->randomFloat(2, 0, 999.99),
            'lang' => $this->faker->randomElement(["fr","en"]),
            'job_name_id' => JobName::factory(),
        ];
    }
}
