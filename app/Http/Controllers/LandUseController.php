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
            $landUseService->getVicmapPlanningZones($request->feature)
        );
    }

    public function getPlanningOverlays(LandUseService $landUseService, Request $request): JsonResponse
    {
        return response()->json(
            $landUseService->getVicmapPlanningOverlays($request->feature)
        );
    }

    public function getCatchmentLandUse(LandUseService $landUseService, Request $request): JsonResponse
    {
        return response()->json(
            $landUseService->getCatchmentLandUse($request->feature)
        );
    }

    public function getVluisPropertyClassification(LandUseService $landUseService, Request $request): JsonResponse
    {
        return response()->json(
            $landUseService->getVluisPropertyClassification($request->feature)
        );
    }

    public function getVluisLandUse(LandUseService $landUseService, Request $request): JsonResponse
    {
        return response()->json(
            $landUseService->getVluisLandUse($request->feature)
        );
    }

    public function getVluisLandCover(LandUseService $landUseService, Request $request): JsonResponse
    {
        return response()->json(
            $landUseService->getVluisLandCover($request->feature)
        );
    }
}
