<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\JobInfo;
use App\Models\JobInfoType;

class JobInfoTypeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = JobInfoType::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'type_name' => $this->faker->text(),
            'type_description' => $this->faker->text(),
            'job_info_id' => JobInfo::factory(),
        ];
    }
}
