<?php

namespace App\Http\Controllers;

use App\Http\Resources\AppointmentResource;
use App\Models\Appointment;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AppointmentController extends Controller
{
    public function index()
    {

    }

    /**
     * @throws Exception
     */
    public function store(Request $request): JsonResponse
    {
       $appointment = (new Appointment())->createNewAppointment($request->toArray());

        return response()->json([
            'success' => true,
            'message' => 'The appointment has been created successfully!',
            'data' => new AppointmentResource($appointment)
        ], Response::HTTP_OK);
    }
}
