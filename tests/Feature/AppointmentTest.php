<?php

namespace Tests\Feature;

use App\Models\Home;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use Tests\TestCase;
use Tymon\JWTAuth\JWTAuth;

class AppointmentTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var string[]
     */
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

    public function test_an_employee_can_make_an_appointment()
    {
        $this->withoutExceptionHandling();

        $admin = User::factory()->admin()->create();

//        \Tymon\JWTAuth\Facades\JWTAuth::fromUser($admin);

        $this->be($admin);

        $this->assertAuthenticated();

        $appointmentData = [
            'employee_id' => User::factory()->create()->id,
            'home_id' => Home::factory()->create()->id,
            'date' => Carbon::now()->toDateTimeString(),
        ];

        $this->postJson(route('create-appointment'), $appointmentData)->dump()
            ->assertStatus(200);
//            ->assertJsonFragment([
//                "employee" => $appointmentData['employee_id'],
//                "home_id" => $appointmentData['home_id'],
//                "date" => Carbon::parse($appointmentData['date'])->toDateTimeString()
//            ]);

        $this->assertDatabaseHas('appointments', [
            'employee_id' => $appointmentData['employee_id'],
            'home_id' => $appointmentData['home_id'],
            'date' => Carbon::parse($appointmentData['date'])->toDateTimeString()
        ]);
    }

}
