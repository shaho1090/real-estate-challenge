<?php

namespace Tests\Feature\Home;

use App\Models\Home;
use App\Models\HomeCondition;
use App\Models\HomeType;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class HomeVisibilityTest extends TestCase
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

    public function test_the_admin_can_see_all_homes_created_by_landlords()
    {
        $landlordUser = User::factory()->landlord()->hasHomes(2)->create();
        $landlordUserTwo = User::factory()->landlord()->hasHomes(2)->create();
        $landlordUserThree = User::factory()->landlord()->hasHomes(2)->create();

        $admin =  User::factory()->admin()->create();
        $this->be($admin);
        $this->assertAuthenticated();
        $this->assertTrue(auth()->user()->isAdmin());

        $this->getJson(route('homes-index'))
            ->assertJsonFragment([
                "id" => $landlordUser->homes()->first()->id,
                "title" => $landlordUser->homes()->first()->title,
                "purpose" => $landlordUser->homes()->first()->purpose,
            ])->assertJsonFragment([
                "id" => $landlordUser->homes()->get()->last()->id,
                "title" => $landlordUser->homes()->get()->last()->title,
                "purpose" => $landlordUser->homes()->get()->last()->purpose,
            ])->assertJsonFragment([
                "id" => $landlordUserTwo->homes()->first()->id,
                "title" => $landlordUserTwo->homes()->first()->title,
                "purpose" => $landlordUserTwo->homes()->first()->purpose,
            ])->assertJsonFragment([
                "id" => $landlordUserTwo->homes()->get()->last()->id,
                "title" => $landlordUserTwo->homes()->get()->last()->title,
                "purpose" => $landlordUserTwo->homes()->get()->last()->purpose,
            ])->assertJsonFragment([
                "id" => $landlordUserThree->homes()->first()->id,
                "title" => $landlordUserThree->homes()->first()->title,
                "purpose" => $landlordUserThree->homes()->first()->purpose,
            ])->assertJsonFragment([
                "id" => $landlordUserThree->homes()->get()->last()->id,
                "title" => $landlordUserThree->homes()->get()->last()->title,
                "purpose" => $landlordUserThree->homes()->get()->last()->purpose,
            ]);
    }

    public function test_the_admin_can_see_a_single_home()
    {
        $landlordUser = User::factory()->landlord()->hasHomes(2)->create();
        $landlordUserTwo = User::factory()->landlord()->hasHomes(2)->create();

        $admin =  User::factory()->admin()->create();
        $this->be($admin);
        $this->assertAuthenticated();
        $this->assertTrue(auth()->user()->isAdmin());

        $homes = Home::all();
        $selectedHome = $homes->random();

        $this->getJson(route('home-show',$selectedHome))
            ->assertJsonFragment([
                "id" => $selectedHome->id,
                "title" => $selectedHome->title,
                "purpose" => $selectedHome->purpose,
            ]);
    }
}
