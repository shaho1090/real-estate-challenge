<?php

namespace Tests\Feature\Home;

use App\Models\HomeCondition;
use App\Models\HomeType;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class HomeUpdateTest extends TestCase
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
