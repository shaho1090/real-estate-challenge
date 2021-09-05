<?php

namespace Tests\Feature;

use App\Models\Home;
use App\Models\User;
use App\Models\UserType;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

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
            'employee_id' => User::factory()->employee()->create()->id,
            'home_id' => Home::factory()->create()->id,
            'customer_id' => User::factory()->customer()->create()->id,
            'date' => Carbon::now()->toDateTimeString(),
        ];

        $this->postJson(route('create-appointment'), $appointmentData)
            ->assertStatus(200);

        $this->assertDatabaseHas('appointments', [
            'employee_id' => $appointmentData['employee_id'],
            'home_id' => $appointmentData['home_id'],
            'customer_id' => $appointmentData['customer_id'],
            'date' => Carbon::parse($appointmentData['date'])->toDateTimeString()
        ]);
    }

    public function test_the_employee_can_see_its_appointments()
    {
        $employee = User::factory()->employee()->hasEmployeeAppointments(3)->create();
        $employeeTwo = User::factory()->employee()->hasEmployeeAppointments(3)->create();

        $this->be($employee);

        $this->getJson(route('employee-my-appointments'))
            ->assertJsonFragment([
                "title" => $employee->employeeAppointments()->first()->home->title,
                "purpose" => $employee->employeeAppointments()->first()->home->purpose,
                "zip_code" => $employee->employeeAppointments()->first()->home->zip_code,
                "address" => $employee->employeeAppointments()->first()->home->address,
                "price" => $employee->employeeAppointments()->first()->home->price,
                "bedrooms" => $employee->employeeAppointments()->first()->home->bedrooms,
                "bathrooms" => $employee->employeeAppointments()->first()->home->bathrooms,
            ])
            ->assertJsonFragment([
                "title" => $employee->employeeAppointments()->get()->last()->home->title,
                "purpose" => $employee->employeeAppointments()->get()->last()->home->purpose,
                "zip_code" => $employee->employeeAppointments()->get()->last()->home->zip_code,
                "address" => $employee->employeeAppointments()->get()->last()->home->address,
                "price" => $employee->employeeAppointments()->get()->last()->home->price,
                "bedrooms" => $employee->employeeAppointments()->get()->last()->home->bedrooms,
                "bathrooms" => $employee->employeeAppointments()->get()->last()->home->bathrooms,
            ])
            ->assertJsonMissing([
                "title" => $employeeTwo->employeeAppointments()->first()->home->title,
                "address" => $employeeTwo->employeeAppointments()->first()->home->address,
            ])
            ->assertJsonMissing([
                "title" => $employeeTwo->employeeAppointments()->get()->last()->home->title,
                "address" => $employeeTwo->employeeAppointments()->get()->last()->home->address,
            ]);
    }

    public function test_the_non_admin_users_can_not_create_an_appointment()
    {
        $employee = User::factory()->employee()->create();
        $landlord = User::factory()->landlord()->create();

        $this->be($employee);

        $appointmentData = [
            'employee_id' => User::factory()->create()->id,
            'home_id' => Home::factory()->create()->id,
            'date' => Carbon::now()->toDateTimeString(),
        ];

        $this->postJson(route('create-appointment'), $appointmentData)
            ->assertJsonFragment([
                "message" => "This action is unauthorized."
            ])
            ->assertStatus(403);

        $this->be($landlord);

        $this->assertEquals(UserType::landlord()->id, auth()->user()->type_id);

        $this->postJson(route('create-appointment'), $appointmentData)
            ->assertJsonFragment([
                "message" => "This action is unauthorized."
            ])
            ->assertStatus(403);
    }

    public function test_employee_can_see_an_appointment()
    {
        $this->withoutExceptionHandling();

        $employee = User::factory()->employee()->hasEmployeeAppointments(5)->create();

        $this->be($employee);

        $appointment = $employee->employeeAppointments()->first();

        $this->getJson(route('employee-my-appointment', $appointment))
            ->assertStatus(200)
            ->assertJsonFragment([
                "name" => $employee->name,
                "family" => $employee->family,
                "email" => $employee->email,
            ])
            ->assertJsonFragment([
                "title" => $appointment->home->title,
                "purpose" => $appointment->home->purpose,
                "zip_code" => $appointment->home->zip_code,
                "address" => $appointment->home->address,
                "price" => $appointment->home->price,
                "bedrooms" => $appointment->home->bedrooms,
                "bathrooms" => $appointment->home->bathrooms,
                "m_two" => $appointment->home->m_two,
                "price_m_two" => $appointment->home->price_m_two,
            ])
            ->assertJsonFragment([
                'expected_date_time' => Carbon::parse($appointment->date)->toDateTimeString()
            ]);
    }

    public function test_the_employee_can_not_see_an_appointment_of_other_employee()
    {
        $this->withoutExceptionHandling();

        $employee = User::factory()->employee()->hasEmployeeAppointments(3)->create();
        $employeeTwo = User::factory()->employee()->hasEmployeeAppointments(3)->create();

        $this->be($employee);

        $employeeTwoAppointment = $employeeTwo->employeeAppointments()->first();

        $this->getJson(route('employee-my-appointment', $employeeTwoAppointment))
            ->assertUnauthorized()
            ->assertStatus(401);
    }

//    public function test_a_customer_can_store_request_for_
}
