<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\JobInfo;
use App\Models\PersonalityDetails;

class PersonalityDetailsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PersonalityDetails::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'career_name' => $this->faker->word(),
            'description' => $this->faker->text(),
            'trait_score' => $this->faker->word(),
            'trait_type' => $this->faker->word(),
            'job_info_id' => JobInfo::factory(),
        ];
    }
}
