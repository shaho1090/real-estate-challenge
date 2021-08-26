<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\WithFaker;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, withFaker;

    public function signIn()
    {
        $userData = [
            'name' => $this->faker->name,
            'family' => $this->faker->lastName,
            'phone' => $this->faker->phoneNumber,
            'email' => $this->faker->email,
            'password' => $this->faker->password,
            'address' => $this->faker->address,
        ];

        $this->postJson(route('register'), $userData);

        $this->postJson(route('login'),[
            'email' => $userData['email'],
            'password' => $userData['password']
        ]);
    }
}
