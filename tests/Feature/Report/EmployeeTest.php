<?php

namespace Tests\Feature\Report;

use App\Models\Appointment;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EmployeeTest extends TestCase
{
    use RefreshDatabase;

    private array $chelsfieldZipcodes;

    public function setUp(): void
    {
        parent::setUp();

        (new DatabaseSeeder())->run();
    }

    public function test_the_admin_can_see_all_employee_and_their_current_appointment_with_time_they_will_be_free()
    {
        $this->withoutExceptionHandling();

        $startedAppointments = Appointment::factory(2)->started()->create();
        $endedAppointments = Appointment::factory(2)->ended()->create();

        $admin = User::factory()->admin()->create();
        $this->be($admin);
        $this->assertTrue(auth()->user()->isAdmin());

        $this->getJson(route('employee-index'))
            ->assertJsonFragment([
                "id" => $startedAppointments->first()->employee()->first()->id,
                "name" => $startedAppointments->first()->employee()->first()->name,
                "family" => $startedAppointments->first()->employee()->first()->family,
                "email" => $startedAppointments->first()->employee()->first()->email,
                "phone" => $startedAppointments->first()->employee()->first()->phone,
            ])->assertJsonFragment([
                "id" => $startedAppointments->last()->employee()->first()->id,
                "name" => $startedAppointments->last()->employee()->first()->name,
                "family" => $startedAppointments->last()->employee()->first()->family,
                "email" => $startedAppointments->last()->employee()->first()->email,
                "phone" => $startedAppointments->last()->employee()->first()->phone,
            ])->assertJsonFragment([
                "id" => $endedAppointments->first()->employee()->first()->id,
                "name" => $endedAppointments->first()->employee()->first()->name,
                "family" => $endedAppointments->first()->employee()->first()->family,
                "email" => $endedAppointments->first()->employee()->first()->email,
                "phone" => $endedAppointments->first()->employee()->first()->phone,
            ])->assertJsonFragment([
                "id" => $endedAppointments->last()->employee()->first()->id,
                "name" => $endedAppointments->last()->employee()->first()->name,
                "family" => $endedAppointments->last()->employee()->first()->family,
                "email" => $endedAppointments->last()->employee()->first()->email,
                "phone" => $endedAppointments->last()->employee()->first()->phone,
            ])->assertJsonFragment([
                "id" => $startedAppointments->first()->id,
                'expected_date_time' => $startedAppointments->first()->date,
                'visited_start_time' => $startedAppointments->first()->start_time,
                'distance_estimated_time' => (string)$startedAppointments->first()->distance_estimated_time,
                'visited_end_time' => $startedAppointments->first()->end_time,
                'probable_employee_free_time' => $startedAppointments->first()->probableEmployeeFreeTime(),
            ])->assertJsonFragment([
                "id" => $startedAppointments->last()->id,
                'expected_date_time' => $startedAppointments->last()->date,
                'visited_start_time' => $startedAppointments->last()->start_time,
                'distance_estimated_time' => (string)$startedAppointments->last()->distance_estimated_time,
                'visited_end_time' => $startedAppointments->last()->end_time,
                'probable_employee_free_time' => $startedAppointments->last()->probableEmployeeFreeTime(),
            ]);
    }
}
