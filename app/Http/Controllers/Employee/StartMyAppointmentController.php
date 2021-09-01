<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Http\Requests\AppointmentStartRequest;
use App\Models\Appointment;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StartMyAppointmentController extends Controller
{
    /**
     * @throws Exception
     */
    public function update(Appointment $appointment, AppointmentStartRequest $request): JsonResponse
    {
        if (auth()->id() !== (integer)$appointment->employee_id) {
            return response()->json([
                'success' => false,
            ], Response::HTTP_UNAUTHORIZED);
        }

        $appointment->start($request->input('origin_zipcode'));

        return response()->json([
            'success' => true,
            'message' => 'The appointment started.'
        ], Response::HTTP_OK);
    }
}
