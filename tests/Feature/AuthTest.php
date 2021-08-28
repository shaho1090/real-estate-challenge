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

    public function registerAUser()
    {
        $this->userData = [
            'name' => $this->faker->name,
            'family' => $this->faker->lastName,
            'phone' => $this->faker->phoneNumber,
            'email' => $this->faker->email,
            'password' => $this->faker->password,
            'address' => $this->faker->address,
        ];

        $this->postJson(route('register'), $this->userData);
    }

    public function test_a_user_can_register()
    {
        $this->withoutExceptionHandling();

        $userData = [
            'name' => $this->faker->name,
            'family' => $this->faker->lastName,
            'phone' => $this->faker->phoneNumber,
            'email' => $this->faker->email,
            'password' => $this->faker->password,
            'address' => $this->faker->address,
        ];

        $this->postJson(route('register'), $userData)
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
            'type_id' => UserType::customer()->id
        ]);
    }

    public function test_the_user_can_login_and_get_jwt_token()
    {
        $this->registerAUser();

        $this->assertNotNull(User::first());

        $this->postJson(route('login'),[
            'email' => $this->userData['email'],
            'password' => $this->userData['password']
        ])->assertSeeText('access_token', 'barear');

        $this->assertAuthenticated();
    }
}
