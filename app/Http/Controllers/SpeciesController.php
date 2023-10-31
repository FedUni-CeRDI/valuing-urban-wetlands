<?php

namespace App\Http\Controllers;

use App\Services\SpeciesService;
use GuzzleHttp\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use JsonException;

class SpeciesController extends Controller
{

    public function __construct() {}

    //
    public function getSpeciesCountByWkt(Request $request, SpeciesService $speciesService): JsonResponse
    {
        $validated = $request->validate([
            'states' => ['required', 'string'],
            'wkt' => ['required', 'string'],
        ]);

        $client = new Client([
            'base_uri' => 'https://api.ala.org.au',
        ]);

        $response = $client->get('/occurrences/mapping/species', [
            'params' => [
                'fq' => 'class:Aves',
                'wkt' => $validated['wkt'],
            ],
        ]);

        try {
            $birdsInGeometry = json_decode($response->getBody()->getContents(), false, 512, JSON_THROW_ON_ERROR);
            $waterbirds = collect($speciesService->getWaterbirdList());
            $waterbirdTaxon = $waterbirds->pluck('scientific_name');

            $waterbirdsInGeometry = collect($birdsInGeometry)->filter(function ($bird) use ($waterbirdTaxon) {
                return $waterbirdTaxon->contains($bird->name);
            });

            $states = explode(',', $validated['states']);

            $threatenedSpecies = $speciesService->getThreatenedSpeciesByStates($states, true);

            return response()->json([
                'waterbirds' => $waterbirdsInGeometry,
                'threatenedSpecies' => $threatenedSpecies,
                'birds' => $birdsInGeometry,
            ]);
        } catch (JsonException $e) {
            Log::error(__FUNCTION__ . ': Unable to parse JSON response', ['message' => $e->getMessage()]);
        }

        return response()->json([]);
    }

}
