<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Http\Resources\AppointmentCollection;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class MyAppointmentController extends Controller
{
    public function index(): JsonResponse
    {
        $appointments = auth()->user()->getAppointments();

        return response()->json([
            'success' => true,
            'data' => new AppointmentCollection($appointments)
        ], Response::HTTP_OK);
    }

}
