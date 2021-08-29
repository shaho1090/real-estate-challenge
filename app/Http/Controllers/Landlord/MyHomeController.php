<?php

namespace App\Http\Controllers\Landlord;

use App\Http\Controllers\Controller;
use App\Http\Resources\HomeResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MyHomeController extends Controller
{
    public function store(Request $request): JsonResponse
    {
       $home = auth()->user()->createHome($request->toArray());

        return response()->json([
            'success' => true,
            'message' => 'The home was created successfully',
            'data' => new HomeResource($home)
        ], Response::HTTP_OK);
    }
}
