<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Http\Resources\AppointmentCollection;
use App\Http\Resources\AppointmentResource;
use App\Models\Appointment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Validation\UnauthorizedException;
use Symfony\Component\HttpFoundation\Response;

class MyAppointmentController extends Controller
{
    public function index(): JsonResponse
    {
        $appointments = auth()->user()->getEmployeeAppointments();

        return response()->json([
            'success' => true,
            'data' => new AppointmentCollection($appointments)
        ], Response::HTTP_OK);
    }

    /**
     * @param Appointment $appointment
     * @return JsonResponse
     */
    public function show(Appointment $appointment): JsonResponse
    {
        if ((integer)$appointment->employee_id !== auth()->user()->id) {
            return response()->json([
                'success' => false,
            ], Response::HTTP_UNAUTHORIZED);
        }

        return response()->json([
            'success' => true,
            'data' => new AppointmentResource($appointment)
        ], Response::HTTP_OK);
    }

}
