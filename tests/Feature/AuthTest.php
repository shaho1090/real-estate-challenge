<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\UserType;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var array
     */
    private array $userData;

    public function setUp(): void
    {
        parent::setUp();

        (new DatabaseSeeder())->run();
    }

    public function registerALandlordUser()
    {
        $this->userData = [
            'name' => $this->faker->name,
            'family' => $this->faker->lastName,
            'phone' => $this->faker->phoneNumber,
            'email' => $this->faker->email,
            'password' => $this->faker->password,
            'address' => $this->faker->address,
        ];

        $this->postJson(route('landlord-register'), $this->userData);
    }

    public function registerACustomerUser()
    {
        $this->userData = [
            'name' => $this->faker->name,
            'family' => $this->faker->lastName,
            'phone' => $this->faker->phoneNumber,
            'email' => $this->faker->email,
            'password' => $this->faker->password,
            'address' => $this->faker->address,
        ];

        $this->postJson(route('customer-register'), $this->userData);
    }

    /**
     * @throws \Exception
     */
    public function test_a_landlord_can_register()
    {
        $userData = [
            'name' => $this->faker->name,
            'family' => $this->faker->lastName,
            'phone' => $this->faker->phoneNumber,
            'email' => $this->faker->email,
            'password' => $this->faker->password,
            'address' => $this->faker->address,
        ];

        $this->postJson(route('landlord-register'), $userData)
            ->assertJsonFragment([
                "success" => true,
                "message" => "User created successfully"
            ])->assertJsonFragment([
                "name" => $userData['name'],
                "email" => $userData['email'],
            ]);

        $this->assertDatabaseHas('users',[
            'name' => $userData['name'],
            'family' => $userData['family'],
            'email' => $userData['email'],
            'phone' => $userData['phone'],
            'address' => $userData['address'],
            'type_id' => UserType::landlord()->id
        ]);
    }

    public function test_a_landlord_user_can_login_and_get_jwt_token()
    {
        $this->registerALandlordUser();

        $this->assertNotNull(User::query()->first());

        $this->postJson(route('login'),[
            'email' => $this->userData['email'],
            'password' => $this->userData['password']
        ])->assertSeeText('access_token', 'barear');

        $this->assertAuthenticated();
    }

    public function test_a_customer_user_can_login_and_get_jwt_token()
    {
        $this->registerACustomerUser();

        $this->assertNotNull(User::query()->first());

        $this->postJson(route('login'),[
            'email' => $this->userData['email'],
            'password' => $this->userData['password']
        ])->assertSeeText('access_token', 'barear');

        $this->assertAuthenticated();
    }
}
