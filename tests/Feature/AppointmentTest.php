<?php

namespace Tests\Feature;

use App\Models\Home;
use App\Models\User;
use App\Models\UserType;
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

    public function test_the_admin_can_make_an_appointment()
    {
        $this->withoutExceptionHandling();

        $admin = User::factory()->admin()->create();

        $this->be($admin);

        $this->assertAuthenticated();

        $appointmentData = [
            'employee_id' => User::factory()->create()->id,
            'home_id' => Home::factory()->create()->id,
            'date' => Carbon::now()->toDateTimeString(),
        ];

        $this->postJson(route('create-appointment'), $appointmentData)
            ->assertStatus(200);

        $this->assertDatabaseHas('appointments', [
            'employee_id' => $appointmentData['employee_id'],
            'home_id' => $appointmentData['home_id'],
            'date' => Carbon::parse($appointmentData['date'])->toDateTimeString()
        ]);
    }

    public function test_the_employee_can_see_its_appointments()
    {
        $employee = User::factory()->employee()->hasAppointments(3)->create();
        $employeeTwo = User::factory()->employee()->hasAppointments(3)->create();

        $this->be($employee);

        $this->getJson(route('employee-my-appointments'))
            ->assertJsonFragment([
                "title" => $employee->appointments()->first()->home->title,
                "purpose" => $employee->appointments()->first()->home->purpose,
                "zip_code" => $employee->appointments()->first()->home->zip_code,
                "address" => $employee->appointments()->first()->home->address,
                "price" => $employee->appointments()->first()->home->price,
                "bedrooms" => $employee->appointments()->first()->home->bedrooms,
                "bathrooms" => $employee->appointments()->first()->home->bathrooms,
            ])
            ->assertJsonFragment([
                "title" => $employee->appointments()->get()->last()->home->title,
                "purpose" => $employee->appointments()->get()->last()->home->purpose,
                "zip_code" => $employee->appointments()->get()->last()->home->zip_code,
                "address" => $employee->appointments()->get()->last()->home->address,
                "price" => $employee->appointments()->get()->last()->home->price,
                "bedrooms" => $employee->appointments()->get()->last()->home->bedrooms,
                "bathrooms" => $employee->appointments()->get()->last()->home->bathrooms,
            ])
            ->assertJsonMissing([
                "title" => $employeeTwo->appointments()->first()->home->title,
                "zip_code" => $employeeTwo->appointments()->first()->home->zip_code,
                "address" => $employeeTwo->appointments()->first()->home->address,
            ])
            ->assertJsonMissing([
                "title" => $employeeTwo->appointments()->get()->last()->home->title,
                "zip_code" => $employeeTwo->appointments()->get()->last()->home->zip_code,
                "address" => $employeeTwo->appointments()->get()->last()->home->address,
            ]);
    }

    public function test_the_non_admin_users_can_not_create_an_appointment()
    {
//        $this->withoutExceptionHandling();

        $employee = User::factory()->employee()->create();
        $customer = User::factory()->customer()->create();

        $this->be($employee);

        $appointmentData = [
            'employee_id' => User::factory()->create()->id,
            'home_id' => Home::factory()->create()->id,
            'date' => Carbon::now()->toDateTimeString(),
        ];

        $this->postJson(route('create-appointment'), $appointmentData)
            ->assertJsonFragment([
                "message" =>"This action is unauthorized."
            ])
            ->assertStatus(403);

        $this->be($customer);

        $this->assertEquals(UserType::customer()->id,auth()->user()->type_id);

        $this->postJson(route('create-appointment'), $appointmentData)
            ->assertJsonFragment([
                "message" =>"This action is unauthorized."
            ])
            ->assertStatus(403);
    }

}
