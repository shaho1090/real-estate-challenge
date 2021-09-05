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
        $landlordUser = User::factory()->landlord()->hasHomes(3)->create();

        $this->be($landlordUser);

        $this->assertAuthenticated();

        $this->getJson(route('landlord-home-index'))
            ->assertStatus(200)
            ->assertJsonFragment([
                "title" => $landlordUser->homes()->first()->title,
                "purpose" => $landlordUser->homes()->first()->purpose,
                "zip_code" => $landlordUser->homes()->first()->zip_code,
                "address" => $landlordUser->homes()->first()->address,
                "price" => $landlordUser->homes()->first()->price,
                "bedrooms" => $landlordUser->homes()->first()->bedrooms,
                "bathrooms" => $landlordUser->homes()->first()->bathrooms,
                "m_two" => $landlordUser->homes()->first()->m_two,
                "price_m_two" => $landlordUser->homes()->first()->price_m_two,
            ])->assertJsonFragment([
                "title" => $landlordUser->homes()->get()->last()->title,
                "purpose" => $landlordUser->homes()->get()->last()->purpose,
                "zip_code" => $landlordUser->homes()->get()->last()->zip_code,
                "address" => $landlordUser->homes()->get()->last()->address,
                "price" => $landlordUser->homes()->get()->last()->price,
                "bedrooms" => $landlordUser->homes()->get()->last()->bedrooms,
                "bathrooms" => $landlordUser->homes()->get()->last()->bathrooms,
                "m_two" => $landlordUser->homes()->get()->last()->m_two,
                "price_m_two" => $landlordUser->homes()->get()->last()->price_m_two,
            ]);
    }

    public function test_the_landlord_can_see_a_home_belongs_to_him()
    {
        $landlordUser = User::factory()->landlord()->hasHomes(3)->create();

        $this->be($landlordUser);

        $this->assertAuthenticated();

        $this->getJson(route('landlord-home-show',$landlordUser->homes()->get()->last()))
            ->assertStatus(200)
            ->assertJsonFragment([
                "title" => $landlordUser->homes()->get()->last()->title,
                "purpose" => $landlordUser->homes()->get()->last()->purpose,
                "zip_code" => $landlordUser->homes()->get()->last()->zip_code,
                "address" => $landlordUser->homes()->get()->last()->address,
                "price" => $landlordUser->homes()->get()->last()->price,
                "bedrooms" => $landlordUser->homes()->get()->last()->bedrooms,
                "bathrooms" => $landlordUser->homes()->get()->last()->bathrooms,
                "m_two" => $landlordUser->homes()->get()->last()->m_two,
                "price_m_two" => $landlordUser->homes()->get()->last()->price_m_two,
            ]);
    }

    public function test_a_landlord_can_not_see_other_landlord_s_home()
    {
        $landlordUser = User::factory()->landlord()->hasHomes(3)->create();
        $landlordUserTwo = User::factory()->landlord()->hasHomes(3)->create();

        $this->be($landlordUser);

        $this->assertAuthenticated();

        $this->getJson(route('landlord-home-show',$landlordUserTwo->homes()->get()->last()))
            ->assertStatus(403)
            ->assertJsonFragment([
                "message" => "This action is unauthorized."
            ])->assertJsonMissing([
                "title" => $landlordUser->homes()->get()->last()->title,
                "address" => $landlordUser->homes()->get()->last()->address,
            ]);
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
            'm_two' => rand(70, 500),
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

    public function test_the_landlord_can_update_the_title_of_a_home()
    {
        $landlordUser = User::factory()->landlord()->hasHomes(2)->create();
        $home = $landlordUser->homes()->first();

        $this->be($landlordUser);

        $this->assertAuthenticated();

        $homeUpdateData['title'] = 'a new title for the home';

        $this->assertNotSame($home['title'], $homeUpdateData['title']);

        $this->patchJson(route('landlord-home-update',$home),$homeUpdateData)
            ->assertStatus(200);

        $home->refresh();

        $this->assertSame($home['title'], $homeUpdateData['title']);
    }

    public function test_the_landlord_can_update_the_address_of_a_home()
    {
        $landlordUser = User::factory()->landlord()->hasHomes(2)->create();
        $home = $landlordUser->homes()->first();

        $this->be($landlordUser);

        $this->assertAuthenticated();

        $homeUpdateData['address'] = 'a new address for the home';

        $this->assertNotSame($home['address'], $homeUpdateData['address']);

        $this->patchJson(route('landlord-home-update',$home),$homeUpdateData)
            ->assertStatus(200);

        $home->refresh();

        $this->assertSame($home['address'], $homeUpdateData['address']);
    }

    public function test_the_landlord_can_update_the_type_of_a_home()
    {
        $landlordUser = User::factory()->landlord()->hasHomes(2)->create();
        $home = $landlordUser->homes()->first();

        $this->be($landlordUser);

        $this->assertAuthenticated();

        $homeTypes = HomeType::query()->get();

        $homeTypes->forget($home->type_id);

        $homeUpdateData['type_id'] = (integer)$homeTypes->random()->id;

        $this->assertNotSame((integer)$home->type_id, $homeUpdateData);

        $this->patchJson(route('landlord-home-update',$home),$homeUpdateData)
            ->assertStatus(200);

        $home->refresh();

        $this->assertSame((integer)$home->type_id, $homeUpdateData['type_id']);
    }

    public function test_the_landlord_can_update_the_price_of_a_home()
    {
        $landlordUser = User::factory()->landlord()->hasHomes(2)->create();
        $home = $landlordUser->homes()->first();

        $this->be($landlordUser);

        $this->assertAuthenticated();

        $homeUpdateData['price'] = rand(550000,1000000);

        $this->assertNotSame((integer)$home->price, $homeUpdateData);

        $this->patchJson(route('landlord-home-update',$home),$homeUpdateData)
            ->assertStatus(200);

        $home->refresh();

        $this->assertSame((integer)$home->price, $homeUpdateData['price']);
    }

    public function test_the_landlord_can_update_the_bedrooms_of_a_home()
    {
        $landlordUser = User::factory()->landlord()->hasHomes(2)->create();
        $this->be($landlordUser);
        $this->assertAuthenticated();

        $home = $landlordUser->homes()->first();
        $bedrooms = collect(['1', '2', '3', '4', '+4']);
        $bedrooms->forget($home->bedrooms);
        $homeUpdateData['bedrooms'] = $bedrooms->random();

        $this->assertNotSame((integer)$home->price, $homeUpdateData);

        $this->patchJson(route('landlord-home-update',$home),$homeUpdateData)
            ->assertStatus(200);

        $home->refresh();

        $this->assertSame($home->bedrooms, $homeUpdateData['bedrooms']);
    }

    public function test_the_landlord_can_update_the_bathrooms_of_a_home()
    {
        $landlordUser = User::factory()->landlord()->hasHomes(2)->create();
        $this->be($landlordUser);
        $this->assertAuthenticated();

        $home = $landlordUser->homes()->first();
        $bathrooms = collect(['1', '2', '3', '+3']);
        $bathrooms->forget($home->bathrooms);
        $homeUpdateData['bathrooms'] = $bathrooms->random();

        $this->assertNotSame((integer)$home->price, $homeUpdateData);

        $this->patchJson(route('landlord-home-update',$home),$homeUpdateData)
            ->assertStatus(200);

        $home->refresh();

        $this->assertSame($home->bathrooms, $homeUpdateData['bathrooms']);
    }

    public function test_the_landlord_can_update_the_condition_of_a_home()
    {
        $landlordUser = User::factory()->landlord()->hasHomes(2)->create();
        $home = $landlordUser->homes()->first();

        $this->be($landlordUser);

        $this->assertAuthenticated();

        $homeConditions = HomeCondition::query()->get();

        $homeConditions->forget($home->condition_id);

        $homeUpdateData['condition_id'] = (integer)$homeConditions->random()->id;

        $this->assertNotSame((integer)$home->condition_id, $homeUpdateData);

        $this->patchJson(route('landlord-home-update',$home),$homeUpdateData)
            ->assertStatus(200);

        $home->refresh();

        $this->assertSame((integer)$home->condition_id, $homeUpdateData['condition_id']);
    }

    public function test_the_landlord_can_update_the_m_two_field_of_a_home()
    {
        $landlordUser = User::factory()->landlord()->hasHomes(2)->create();
        $home = $landlordUser->homes()->first();

        $this->be($landlordUser);

        $this->assertAuthenticated();

        $homeUpdateData['m_two'] = rand(500,1000);

        $this->assertNotSame((integer)$home->m_two, $homeUpdateData);

        $this->patchJson(route('landlord-home-update',$home),$homeUpdateData)
            ->assertStatus(200);

        $home->refresh();

        $this->assertSame((integer)$home->m_two, $homeUpdateData['m_two']);
    }

    public function test_the_landlord_can_update_the_price_m_two_field_of_a_home()
    {
        $landlordUser = User::factory()->landlord()->hasHomes(2)->create();
        $home = $landlordUser->homes()->first();

        $this->be($landlordUser);

        $this->assertAuthenticated();

        $homeUpdateData['price_m_two'] = rand(500,1000);

        $this->assertNotSame((integer)$home->price_m_two, $homeUpdateData);

        $this->patchJson(route('landlord-home-update',$home),$homeUpdateData)
            ->assertStatus(200);

        $home->refresh();

        $this->assertSame((integer)$home->price_m_two, $homeUpdateData['price_m_two']);
    }
}
