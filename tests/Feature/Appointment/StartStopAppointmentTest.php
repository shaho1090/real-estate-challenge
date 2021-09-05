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

    public function test_the_employee_can_start_time_of_its_appointment()
    {
        $employee = User::factory()->employee()->hasEmployeeAppointments(3)->create();
        $appointment = $employee->employeeAppointments()->get()->last();

        $this->be($employee);

        $this->assertNull($appointment->start_time);

        $this->patchJson(route('start-my-appointment', $appointment),[
            'origin' => 'office'
        ])
            ->assertStatus(200);

        $appointment->refresh();

        $this->assertNotNull($appointment->start_time);
    }

    public function test_the_employee_can_not_start_the_appointment_of_others()
    {
        $employee = User::factory()->employee()->hasEmployeeAppointments(3)->create();
        $employeeTwo = User::factory()->employee()->hasEmployeeAppointments(3)->create();

        $appointment = $employeeTwo->employeeAppointments()->get()->last();

        $this->be($employee);

        $this->assertNull($appointment->start_time);

        $this->patchJson(route('start-my-appointment', $appointment),[
            'origin' => 'office'
        ])
            ->assertStatus(401);

        $appointment->refresh();

        $this->assertNull($appointment->start_time);
    }

    public function test_the_employee_can_end_its_appointment()
    {
        $this->withoutExceptionHandling();

        $employee = User::factory()->employee()->hasEmployeeAppointments(3)->create();
        $appointment = $employee->employeeAppointments()->get()->last();

        $this->be($employee);

        $appointment->start('office');

        $this->assertNull($appointment->end_time);

        $this->patchJson(route('end-my-appointment', $appointment))
            ->assertStatus(200);

        $appointment->refresh();

        $this->assertNotNull($appointment->end_time);
    }

    public function
    test_when_an_employee_start_the_appointment_the_estimated_time_is_calculated_based_on_office_zip_code_or_latest_appointment()
    {
       $this->withoutExceptionHandling();

        $employee = User::factory()->employee()->hasEmployeeAppointments(2)->create();
        $firstAppointment = $employee->employeeAppointments()->first();
        $lastAppointment = $employee->employeeAppointments()->get()->last();

        $this->be($employee);

        $this->patchJson(route('start-my-appointment', $firstAppointment), [
            'origin' => 'office'
        ])
            ->assertStatus(200);


        $firstAppointment->refresh();

        $this->assertNotNull($firstAppointment->distance_estimated_time);

        $firstAppointment->end();

        $this->patchJson(route('start-my-appointment', $lastAppointment), [
            'origin' => 'previous_appointment'
        ])
            ->assertStatus(200);

        $lastAppointment->refresh();

        $this->assertNotNull($lastAppointment->distance_estimated_time);
    }
}
