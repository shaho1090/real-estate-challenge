<?php

namespace App\Http\Controllers\Landlord;

use App\Http\Controllers\Controller;
use App\Http\Requests\HomeStoreRequest;
use App\Http\Requests\HomeUpdateRequest;
use App\Http\Resources\HomeCollection;
use App\Http\Resources\HomeResource;
use App\Models\Home;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Symfony\Component\HttpFoundation\Response;

class MyHomeController extends Controller
{
    public function index(): JsonResponse
    {
        $homes = auth()->user()->homes;

        return response()->json([
            'data' => new HomeCollection($homes)
        ]);

    }

    public function show(Home $home): JsonResponse
    {
        return response()->json([
            'data' => new HomeResource($home)
        ]);
    }

    public function store(HomeStoreRequest $request): JsonResponse
    {
       $home = auth()->user()->createNewHome($request->toArray());

        return response()->json([
            'success' => true,
            'message' => 'The home was created successfully',
            'data' => new HomeResource($home)
        ], Response::HTTP_OK);
    }

    public function update(Home $home, HomeUpdateRequest $request): JsonResponse
    {
        $home->update($request->toArray());

        $home->refresh();

        return response()->json([
            'success' => true,
            'message' => 'The home was updated successfully',
            'data' => new HomeResource($home)
        ], Response::HTTP_OK);
    }
}
