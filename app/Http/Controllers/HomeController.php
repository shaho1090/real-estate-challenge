<?php

namespace App\Http\Controllers;

use App\Http\Resources\HomePublicCollection;
use App\Models\Home;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends Controller
{
    public function index(): \Illuminate\Http\JsonResponse
    {
        $homes = (new Home())->getAll();

        return response()->json([
            'success' => true,
            'data' => new HomePublicCollection(Home::query()->get())
        ], Response::HTTP_OK);
    }
}
