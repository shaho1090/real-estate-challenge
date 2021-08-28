<?php

namespace Database\Factories;

use App\Models\Home;
use App\Models\HomeCondition;
use App\Models\HomeType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

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
        return [
            'title' => $this->faker->title,
            'purpose' => 'sell',
            'zip_code'=> $this->faker->word,
            'address' => $this->faker->address,
            'price' => rand(1000,500000),
            'bedrooms' => (string)rand(0,8),
            'bathrooms' => (string)rand(1,5),
            'm_two' => rand(40,200),
            'price_m_two' => rand(100,50000),
            'customer_id' => User::factory()->create()->id,
            'type_id' => HomeType::query()->find(rand(1,4))->id,
            'condition_id'=> HomeCondition::query()->find(rand(1,3))->id
        ];
    }
}
