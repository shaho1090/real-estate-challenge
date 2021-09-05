<?php

namespace Tests\Feature;

use App\Models\HomeCondition;
use App\Models\HomeType;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
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
//        $this->withoutExceptionHandling();

        $landlordUser = User::factory()->landlord()->create();

        $this->be($landlordUser);

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

    public function test_the_landlord_can_see_all_its_homes()
    {
        //        $this->withoutExceptionHandling();

        $landlordUser = User::factory()->landlord()->hasHomes(3)->create();

        $this->be($landlordUser);

        $this->assertAuthenticated();

        $this->getJson(route('landlord-home-index'))->dump();
//            ])->assertStatus(200);
    }

    public function test_zip_code_is_required_for_creating_home()
    {
        $landlordUser = User::factory()->landlord()->create();

        $this->be($landlordUser);

        $this->assertAuthenticated();

        $homeData = [
            'zip_code' => '',
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
                "message" => "The given data was invalid.",
                "errors" => [
                    "zip_code" => [
                        0 => "The zip code field is required."
                    ]
                ]]);
    }

    public function test_the_purpose_field_is_required_and_should_be_rent_or_sell()
    {
        $landlordUser = User::factory()->landlord()->create();

        $this->be($landlordUser);

        $this->assertAuthenticated();

        $homeData = [
            'zip_code' => $this->chelsfieldZipcodes[array_rand($this->chelsfieldZipcodes)],
            'purpose' => '',
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
                "message" => "The given data was invalid.",
                "errors" => [
                    "purpose" => [
                        0 => "The purpose field is required."
                    ]
                ]
            ]);

        $homeData['purpose'] = 'foo';

        $this->postJson(route('landlord-home-create'), $homeData)
            ->assertJsonFragment([
                "message" => "The given data was invalid.",
                "errors" => [
                    "purpose" => [
                        0 => "The selected purpose is invalid."
                    ]
                ]
            ]);

        $homeData['purpose'] = 'rent';

        $this->postJson(route('landlord-home-create'), $homeData)
            ->assertStatus(200);

        $homeData['purpose'] = 'sell';

        $this->postJson(route('landlord-home-create'), $homeData)
            ->assertStatus(200);
    }

    public function test_the_title_field_is_required_and_should_be_string_with_length_limitation()
    {
        $landlordUser = User::factory()->landlord()->create();

        $this->be($landlordUser);

        $this->assertAuthenticated();

        $homeData = [
            'zip_code' => $this->chelsfieldZipcodes[array_rand($this->chelsfieldZipcodes)],
            'purpose' => 'sell',
            'title' => '',
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
                "message" => "The given data was invalid.",
                "errors" => [
                    "title" => [
                        0 => "The title field is required."
                    ]
                ]
            ]);

        $homeData['title'] = 1232;

        $this->postJson(route('landlord-home-create'), $homeData)
            ->assertJsonFragment([
                "message" => "The given data was invalid.",
                "errors" => [
                    "title" => [
                        0 => "The title must be a string."
                    ]
                ]
            ]);

        $homeData['title'] = 'ab';

        $this->postJson(route('landlord-home-create'), $homeData)
            ->assertJsonFragment([
                "message" => "The given data was invalid.",
                "errors" => [
                    "title" => [
                        0 => "The title must be at least 3 characters."
                    ]
                ]
            ]);

        $homeData['title'] = Str::random(251);

        $this->postJson(route('landlord-home-create'), $homeData)
            ->assertJsonFragment([
                "message" => "The given data was invalid.",
                "errors" => [
                    "title" => [
                        0 => "The title must not be greater than 250 characters."
                    ]
                ]
            ]);
    }

    public function test_home_type_id_is_required_and_should_be_valid_id_in_home_types_table()
    {
        $landlordUser = User::factory()->landlord()->create();

        $this->be($landlordUser);

        $this->assertAuthenticated();

        $homeData = [
            'zip_code' => $this->chelsfieldZipcodes[array_rand($this->chelsfieldZipcodes)],
            'purpose' => 'sell',
            'title' => 'a title just for fun',
            'type_id' => '',//HomeType::query()->find(rand(1, 5))->id,
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
                "message" => "The given data was invalid.",
                "errors" => [
                    "type_id" => [
                        0 => "The type id field is required."
                    ]
                ]
            ]);

        $homeData['type_id'] = (HomeType::query()->get()->last()->id + 1);

        $this->postJson(route('landlord-home-create'), $homeData)
            ->assertJsonFragment([
                "message" => "The given data was invalid.",
                "errors" => [
                    "type_id" => [
                        0 => "The selected type id is invalid."
                    ]
                ]
            ]);

        $homeData['type_id'] = HomeType::query()->get()->last()->id;

        $this->postJson(route('landlord-home-create'), $homeData)
            ->assertStatus(200);
    }

    public function test_the_price_field_is_required_and_should_be_numeric()
    {
        $landlordUser = User::factory()->landlord()->create();

        $this->be($landlordUser);

        $this->assertAuthenticated();

        $homeData = [
            'zip_code' => $this->chelsfieldZipcodes[array_rand($this->chelsfieldZipcodes)],
            'purpose' => 'sell',
            'title' => 'a title just for fun',
            'type_id' => HomeType::query()->find(rand(1, 5))->id,
            'price' => '',
            'bedrooms' => '3',
            'bathrooms' => '2',
            'condition_id' => HomeCondition::query()->find(rand(1, 3))->id,
            'm_two' => rand(70, 500),
            'price_m_two' => rand(1, 1000),
            'address' => $this->faker->address,
        ];

        $this->postJson(route('landlord-home-create'), $homeData)
            ->assertJsonFragment([
                "message" => "The given data was invalid.",
                "errors" => [
                    "price" => [
                        0 => "The price field is required."
                    ]
                ]
            ]);

        $homeData['price'] = '45sd';

        $this->postJson(route('landlord-home-create'), $homeData)
            ->assertJsonFragment([
                "message" => "The given data was invalid.",
                "errors" => [
                    "price" => [
                        0 => "The price must be an integer."
                    ]
                ]
            ]);

        $homeData['price'] = 100000;

        $this->postJson(route('landlord-home-create'), $homeData)
            ->assertStatus(200);
    }

    public function test_the_bedrooms_field_is_required_and_should_be_selected_correctly()
    {
        $landlordUser = User::factory()->landlord()->create();

        $this->be($landlordUser);

        $this->assertAuthenticated();

        $homeData = [
            'zip_code' => $this->chelsfieldZipcodes[array_rand($this->chelsfieldZipcodes)],
            'purpose' => 'sell',
            'title' => 'a title just for fun',
            'type_id' => HomeType::query()->find(rand(1, 5))->id,
            'price' => 100000,
            'bedrooms' => '',
            'bathrooms' => '2',
            'condition_id' => HomeCondition::query()->find(rand(1, 3))->id,
            'm_two' => rand(70, 500),
            'price_m_two' => rand(1, 1000),
            'address' => $this->faker->address,
        ];

        $this->postJson(route('landlord-home-create'), $homeData)
            ->assertJsonFragment([
                "message" => "The given data was invalid.",
                "errors" => [
                    "bedrooms" => [
                        0 => "The bedrooms field is required."
                    ]
                ]
            ]);

        $homeData['bedrooms'] = '5';

        $this->postJson(route('landlord-home-create'), $homeData)
            ->assertJsonFragment([
                "message" => "The given data was invalid.",
                "errors" => [
                    "bedrooms" => [
                        0 => "The selected bedrooms is invalid."
                    ]
                ]
            ]);

        $homeData['bedrooms'] = '1';
        $this->postJson(route('landlord-home-create'), $homeData)
            ->assertStatus(200);

        $homeData['bedrooms'] = '2';
        $this->postJson(route('landlord-home-create'), $homeData)
            ->assertStatus(200);

        $homeData['bedrooms'] = '3';
        $this->postJson(route('landlord-home-create'), $homeData)
            ->assertStatus(200);

        $homeData['bedrooms'] = '4';
        $this->postJson(route('landlord-home-create'), $homeData)
            ->assertStatus(200);

        $homeData['bedrooms'] = '+4';
        $this->postJson(route('landlord-home-create'), $homeData)
            ->assertStatus(200);
    }

    public function test_the_bathrooms_field_is_required_and_should_be_selected_correctly()
    {
        $landlordUser = User::factory()->landlord()->create();

        $this->be($landlordUser);

        $this->assertAuthenticated();

        $homeData = [
            'zip_code' => $this->chelsfieldZipcodes[array_rand($this->chelsfieldZipcodes)],
            'purpose' => 'sell',
            'title' => 'a title just for fun',
            'type_id' => HomeType::query()->find(rand(1, 5))->id,
            'price' => 100000,
            'bedrooms' => '3',
            'bathrooms' => '',
            'condition_id' => HomeCondition::query()->find(rand(1, 3))->id,
            'm_two' => rand(70, 500),
            'price_m_two' => rand(1, 1000),
            'address' => $this->faker->address,
        ];

        $this->postJson(route('landlord-home-create'), $homeData)
            ->assertJsonFragment([
                "message" => "The given data was invalid.",
                "errors" => [
                    "bathrooms" => [
                        0 => "The bathrooms field is required."
                    ]
                ]
            ]);

        $homeData['bathrooms'] = '4';

        $this->postJson(route('landlord-home-create'), $homeData)
            ->assertJsonFragment([
                "message" => "The given data was invalid.",
                "errors" => [
                    "bathrooms" => [
                        0 => "The selected bathrooms is invalid."
                    ]
                ]
            ]);

        $homeData['bathrooms'] = '1';
        $this->postJson(route('landlord-home-create'), $homeData)
            ->assertStatus(200);

        $homeData['bathrooms'] = '2';
        $this->postJson(route('landlord-home-create'), $homeData)
            ->assertStatus(200);

        $homeData['bathrooms'] = '3';
        $this->postJson(route('landlord-home-create'), $homeData)
            ->assertStatus(200);

        $homeData['bathrooms'] = '+3';
        $this->postJson(route('landlord-home-create'), $homeData)
            ->assertStatus(200);
    }

    public function test_the_condition_id_is_required_and_should_be_selected_from_home_conditions_table()
    {
        $landlordUser = User::factory()->landlord()->create();

        $this->be($landlordUser);

        $this->assertAuthenticated();

        $homeData = [
            'zip_code' => $this->chelsfieldZipcodes[array_rand($this->chelsfieldZipcodes)],
            'purpose' => 'sell',
            'title' => 'a title just for fun',
            'type_id' => HomeType::query()->find(rand(1, 5))->id,
            'price' => 100000,
            'bedrooms' => '3',
            'bathrooms' => '2',
            'condition_id' => null,
            'm_two' => rand(70, 500),
            'price_m_two' => rand(1, 1000),
            'address' => $this->faker->address,
        ];

        $this->postJson(route('landlord-home-create'), $homeData)
            ->assertJsonFragment([
                "message" => "The given data was invalid.",
                "errors" => [
                    "condition_id" => [
                        0 => "The condition id field is required."
                    ]
                ]
            ]);

        $homeData['condition_id'] = (HomeCondition::query()->get()->last()->id + 1);

        $this->postJson(route('landlord-home-create'), $homeData)
            ->assertJsonFragment([
                "message" => "The given data was invalid.",
                "errors" => [
                    "condition_id" => [
                        0 => "The selected condition id is invalid."
                    ]
                ]
            ]);

        $homeData['condition_id'] = HomeCondition::query()->find(rand(1, 3))->id;
        $this->postJson(route('landlord-home-create'), $homeData)
            ->assertStatus(200);
    }

    public function test_the_m_two_field_should_be_integer()
    {
        $landlordUser = User::factory()->landlord()->create();

        $this->be($landlordUser);

        $this->assertAuthenticated();

        $homeData = [
            'zip_code' => $this->chelsfieldZipcodes[array_rand($this->chelsfieldZipcodes)],
            'purpose' => 'sell',
            'title' => 'a title just for fun',
            'type_id' => HomeType::query()->find(rand(1, 5))->id,
            'price' => 100000,
            'bedrooms' => '3',
            'bathrooms' => '2',
            'condition_id' => HomeCondition::query()->find(rand(1, 3))->id,
            'm_two' => '5464sdf',
            'price_m_two' => rand(1, 1000),
            'address' => $this->faker->address,
        ];

        $this->postJson(route('landlord-home-create'), $homeData)
            ->assertJsonFragment([
                "message" => "The given data was invalid.",
                "errors" => [
                    "m_two" => [
                        0 => "The m two must be an integer."
                    ]
                ]
            ]);
    }

    public function test_the_m_two_price_field_should_be_integer()
    {
        $landlordUser = User::factory()->landlord()->create();

        $this->be($landlordUser);

        $this->assertAuthenticated();

        $homeData = [
            'zip_code' => $this->chelsfieldZipcodes[array_rand($this->chelsfieldZipcodes)],
            'purpose' => 'sell',
            'title' => 'a title just for fun',
            'type_id' => HomeType::query()->find(rand(1, 5))->id,
            'price' => 100000,
            'bedrooms' => '3',
            'bathrooms' => '2',
            'condition_id' => HomeCondition::query()->find(rand(1, 3))->id,
            'm_two' =>  rand(70, 500),
            'price_m_two' => '4564sdfs',
            'address' => $this->faker->address,
        ];

        $this->postJson(route('landlord-home-create'), $homeData)
            ->assertJsonFragment([
                "message" => "The given data was invalid.",
                "errors" => [
                    "price_m_two" => [
                        0 => "The price m two must be an integer."
                    ]
                ]
            ]);
    }
}
