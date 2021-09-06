<?php

namespace Database\Factories;

use App\Models\Appointment;
use App\Models\Home;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class AppointmentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Appointment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'employee_id' => User::factory()->employee()->create()->id,
            'home_id' => Home::factory()->create()->id,
            'customer_id' => User::factory()->customer()->create()->id,
            'date' => Carbon::now()->toDateTimeString(),
            'distance_estimated_time' => rand(5,120),
            'start_time' => null,
            'end_time' => null
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return Factory
     */
    public function started(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'start_time' => Carbon::now()->toDateTimeString(),
            ];
        });
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return Factory
     */
    public function ended(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'start_time' => Carbon::now()->subMinutes(75)->toDateTimeString(),
                'end_time' => Carbon::now()->toDateTimeString(),
            ];
        });
    }


}
