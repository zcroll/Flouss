<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\HollandCodeTrait;
use App\Models\JobName;

class HollandCodeTraitFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = HollandCodeTrait::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'description' => $this->faker->text(),
            'realistic' => $this->faker->randomFloat(2, 0, 999.99),
            'investigative' => $this->faker->randomFloat(2, 0, 999.99),
            'artistic' => $this->faker->randomFloat(2, 0, 999.99),
            'social' => $this->faker->randomFloat(2, 0, 999.99),
            'enterprising' => $this->faker->randomFloat(2, 0, 999.99),
            'conventional' => $this->faker->randomFloat(2, 0, 999.99),
            'lang' => $this->faker->randomElement(["fr","en"]),
            'job_name_id' => JobName::factory(),
        ];
    }
}
