<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TimeLog>
 */
class TimeLogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $start_time = fake()->dateTimeThisYear();
        $end_time = strtotime('+1 Hour', $start_time->getTimestamp());;
        return [
            'start' => $start_time,
            'end' => date('Y-m-d H:i:s', $end_time),
            'user_id' => User::factory(),
            'project_id' => Project::factory()
        ];
    }
}
