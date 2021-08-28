<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EndMyAppointmentController extends Controller
{
    /**
     * @throws Exception
     */
    public function update(Appointment $appointment): JsonResponse
    {
        if (auth()->id() !== (integer)$appointment->employee_id) {
            return response()->json([
                'success' => false,
            ], Response::HTTP_UNAUTHORIZED);
        }

        $appointment->end();

        return response()->json([
            'success' => true,
            'message' => 'The appointment started.'
        ], Response::HTTP_OK);
    }
}
