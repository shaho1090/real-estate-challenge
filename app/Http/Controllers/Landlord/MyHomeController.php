<?php

namespace App\Http\Controllers\Landlord;

use App\Http\Controllers\Controller;
use App\Http\Requests\HomeStoreRequest;
use App\Http\Resources\HomeCollection;
use App\Http\Resources\HomeResource;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class MyHomeController extends Controller
{
    public function index(): JsonResponse
    {
        $homes = auth()->user()->homes;

        return response()->json([
            'success' => true,
            'data' => new HomeCollection($homes)
        ]);

    }

    public function store(HomeStoreRequest $request): JsonResponse
    {
       $home = auth()->user()->createHome($request->toArray());

        return response()->json([
            'success' => true,
            'message' => 'The home was created successfully',
            'data' => new HomeResource($home)
        ], Response::HTTP_OK);
    }
}
