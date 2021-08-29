<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\UserType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
            'family' => $this->faker->lastName,
            'phone' => $this->faker->phoneNumber,
            'password' => $this->faker->password,
            'address' => $this->faker->address,
            'type_id' => UserType::employee()->id
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return Factory
     */
    public function unverified(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }

    public function admin(): UserFactory
    {
        return $this->state(function (array $attributes) {
            return [
                'type_id' => UserType::query()->where('title','admin')->first()->id,
            ];
        });
    }

    public function employee(): UserFactory
    {
        return $this->state(function (array $attributes) {
            return [
                'type_id' => UserType::query()->where('title','employee')->first()->id,
            ];
        });
    }

    public function landlord(): UserFactory
    {
        return $this->state(function (array $attributes) {
            return [
                'type_id' => UserType::query()->where('title','landlord')->first()->id,
            ];
        });
    }
}
