<?php
/**
 * User: Scott Limmer
 * Date: 5/04/2023
 */

namespace App\Services;

use App\Traits\PrefixedLogger;
use Illuminate\Support\Facades\Storage;

class SpeciesService
{
    use PrefixedLogger;

    protected string $logPrefix = 'SpeciesService';

    private AlaService $ala;

    public function __construct(AlaService $alaService)
    {
        $this->ala = $alaService;
    }

    public function getThreatenedSpeciesByStates(array|string $states, $include_national = true): array
    {
        $stateAbbreviations = [
            'Victoria' => 'vic',
            'New South Wales' => 'nsw',
            'Queensland' => 'qld',
            'South Australia' => 'sa',
            'Western Australia' => 'wa',
            'Tasmania' => 'tas',
            'Northern Territory' => 'nt',
            'Australian Capital Territory' => 'act'
        ];

        if (is_string($states)) {
            $states = [$states];
        }
        // Validate states supplied are those we expect. Drop anything else
        $states = collect($states)->filter(function ($state) use ($stateAbbreviations) {
            return $stateAbbreviations[$state] ?? false;
        });

        $speciesList = collect($this->getSpeciesList());

        $threatenedSpecies = $speciesList->filter(function ($specie) use ($states, $stateAbbreviations, $include_national) {
            $threatened = $include_national ? (bool)$specie->conservation_aus : false;
            foreach ($states as $state) {
                $threatened = (bool)$specie->{'conservation_' . $stateAbbreviations[$state]} ?? $threatened;
            }

            return $threatened;
        });

        return array_values($threatenedSpecies->toArray());
    }

    /**
     * @return array<Object>
     */
    public function getSpeciesList(): array
    {
        return json_decode(Storage::get('species-info-cache.json'));
    }

    public function getWaterbirdsInArea(string $wkt)
    {
        $this->log('debug', $wkt);
        $birdsInArea = $this->ala->getBirdsInArea($wkt);
       # $this->log('debug', print_r($birdsInArea, true));
        $this->log('debug', count($birdsInArea));
        $waterbirds = collect($this->getSpeciesList());
        $waterbirdGuids = $waterbirds->pluck('guid')->toArray();

        $waterbirdsInArea = collect($birdsInArea)->filter(function ($bird) use ($waterbirdGuids) {
            if (property_exists($bird, 'guid')) {
                return in_array($bird->guid, $waterbirdGuids);
            }

            return false;
        })->map(function ($bird) use ($waterbirds) {
            $waterbird = $waterbirds->first(function ($wb) use ($bird) {
                return $bird->guid == $wb->guid;
            });
            $bird->common_names = [$bird->commonName, $waterbird->common_name];
            unset($bird->name);
            unset($bird->commonName);
            unset($waterbird->common_name);

            return array_merge((array)$bird, (array)$waterbird);
        })->values();

        return $waterbirdsInArea;
    }
}
