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
            'start_time' => null,
            'end_time' => null
        ];
    }
}
