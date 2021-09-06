<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\EmployeeCollection;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EmployeeController extends Controller
{
    /**
     * @throws Exception
     */
    public function index(): \Illuminate\Http\JsonResponse
    {
        $employees = (new User)->getAllEmployees();

        return response()->json([
            'success' => true,
            'data' => new EmployeeCollection($employees)
        ], Response::HTTP_OK);
    }
}
