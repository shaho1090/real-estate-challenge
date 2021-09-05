<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\HomeCollection;
use App\Http\Resources\HomeResource;
use App\Models\Home;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends Controller
{
    public function index(): JsonResponse
    {
        if(!auth()->user()->isAdmin()){
            return response()->json([
                "message" => "This action is unauthorized."
            ], Response::HTTP_UNAUTHORIZED);
        }

        return response()->json([
            'success' => true,
            'data' => new HomeCollection(Home::query()->get())
        ], Response::HTTP_OK);
    }

    public function show(Home $home): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => new HomeResource($home)
        ], Response::HTTP_OK);
    }
}
