<?php

namespace Tests\Feature;

use App\Models\HomeCondition;
use App\Models\HomeType;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeTest extends TestCase
{
    use RefreshDatabase;

    private array $chelsfieldZipcodes;

    public function setUp(): void
    {
        parent::setUp();

        (new DatabaseSeeder())->run();

        $this->chelsfieldZipcodes = [
            'BR66EN',
            'BR66ER',
            'BR67PU',
            'BR67QA',
            'BR67RA'
        ];
    }

    public function test_a_landlord_can_create_a_home()
    {
        $this->withoutExceptionHandling();

        $this->signIn();

        $this->assertAuthenticated();

        $homeData = [
            'zip_code' => $this->chelsfieldZipcodes[array_rand($this->chelsfieldZipcodes)],
            'purpose' => 'rent',
            'title' => $this->faker->title,
            'type_id' => HomeType::query()->find(rand(1, 5))->id,
            'price' => rand(1000, 1000000),
            'bedrooms' => '3',
            'bathrooms' => '2',
            'condition_id' => HomeCondition::query()->find(rand(1, 3))->id,
            'm_two' => rand(70, 500),
            'price_m_two' => rand(1, 1000),
            'address' => $this->faker->address,
        ];

        $this->postJson(route('landlord-home-create'), $homeData)
            ->assertJsonFragment([
                "message" => "The home was created successfully"
            ])->assertStatus(200);
    }
}
