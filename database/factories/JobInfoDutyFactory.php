<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\JobInfo;
use App\Models\JobInfoDuty;

class JobInfoDutyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = JobInfoDuty::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'duty_category' => $this->faker->text(),
            'duty_description' => $this->faker->text(),
            'job_info_id' => JobInfo::factory(),
        ];
    }
}
