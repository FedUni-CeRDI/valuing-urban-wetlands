<?php

namespace App\Http\Controllers;

use App\Services\SpeciesService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function index(): View
    {
        return view('map');
    }

    public function config(): JsonResponse
    {
        return response()->json([
            'geoserver_base_url' => config('aurin.geoserver_base_url')
        ]);
    }

    public function speciesInfo(SpeciesService $speciesService): JsonResponse
    {
        return new JsonResponse($speciesService->getSpeciesList());
    }

    public function alaWaterbirdCounts(SpeciesService $speciesService, Request $request): JsonResponse
    {
        $waterbirds = $speciesService->getWaterbirdsInArea($request->get('wkt'));
        return new JsonResponse($waterbirds);
    }
}
