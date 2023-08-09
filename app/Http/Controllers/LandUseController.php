<?php

namespace App\Http\Controllers;

use App\Services\LandUseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LandUseController extends Controller
{
    public function getPlanningZones(LandUseService $landUseService, Request $request): JsonResponse
    {
        return response()->json(
            $landUseService->getVicmapPlanningZones($request->wkt)
        );
    }

    public function getPlanningOverlays(LandUseService $landUseService, Request $request): JsonResponse
    {
        return response()->json(
            $landUseService->getVicmapPlanningOverlays($request->wkt)
        );
    }

    public function getCatchmentLandUse(LandUseService $landUseService, Request $request): JsonResponse
    {
        return response()->json(
            $landUseService->getCatchmentLandUse($request->wkt)
        );
    }

    public function getVluisPropertyClassification(LandUseService $landUseService, Request $request): JsonResponse
    {
        return response()->json(
            $landUseService->getVluisPropertyClassification($request->wkt)
        );
    }

    public function getVluisLandUse(LandUseService $landUseService, Request $request): JsonResponse
    {
        return response()->json(
            $landUseService->getVluisLandUse($request->wkt)
        );
    }

    public function getVluisLandCover(LandUseService $landUseService, Request $request): JsonResponse
    {
        return response()->json(
            $landUseService->getVluisLandCover($request->wkt)
        );
    }
}
