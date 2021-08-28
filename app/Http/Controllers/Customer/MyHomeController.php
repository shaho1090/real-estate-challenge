<?php

namespace App\Http\Controllers;

use App\Http\Resources\HomeResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends Controller
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
