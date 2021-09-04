<?php

namespace Database\Factories;

use App\Models\Home;
use App\Models\HomeCondition;
use App\Models\HomeType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class HomeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Home::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $zipCodes = [
            'BR66EN',
            'BR66ER',
            'BR67PU',
            'BR67QA',
            'BR67RA'
        ];

        return [
            'title' => $this->faker->sentence,
            'purpose' => 'sell',
            'zip_code'=> $zipCodes[array_rand($zipCodes)],
            'address' => $this->faker->address,
            'price' => rand(1000,500000),
            'bedrooms' => (string)rand(0,8),
            'bathrooms' => (string)rand(1,5),
            'm_two' => rand(40,200),
            'price_m_two' => rand(100,50000),
            'landlord_id' => User::factory()->create()->id,
            'type_id' => HomeType::query()->find(rand(1,4))->id,
            'condition_id'=> HomeCondition::query()->find(rand(1,3))->id
        ];
    }
}
