<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase, withFaker;

    /**
     * @var array
     */
    private $userData;

    public function registerAUser()
    {
        $this->userData = [
            'name' => $this->faker->name,
            'email' => $this->faker->email(),
            'password' => $this->faker->password,
        ];

        $this->postJson(route('register'), $this->userData);
    }

    public function test_a_user_can_register()
    {
//        $this->withoutExceptionHandling();

        $userData = [
            'name' => $this->faker->name,
            'email' => $this->faker->email(),
            'password' => $this->faker->password,
        ];

        $this->postJson(route('register'), $userData)
            ->assertJsonFragment([
                "success" => true,
                "message" => "User created successfully"
            ])->assertJsonFragment([
                "name" => $userData['name'],
                "email" => $userData['email'],
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
