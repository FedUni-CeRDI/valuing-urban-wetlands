<?php
/**
 * User: Scott Limmer
 * Date: 5/04/2023
 */

namespace App\Services;

use App\Services\Ala\OccurrenceService;
use App\Traits\PrefixedLogger;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SpeciesService
{
    use PrefixedLogger;

    private OccurrenceService $alaOccurrence;

    public function __construct(OccurrenceService $occurrencesService)
    {
        $this->setLoggerPrefix(basename(__CLASS__));
        $this->alaOccurrence = $occurrencesService;
    }

    public function getLathamsSnipeCountsInAreaByYear(string $wkt)
    {
        return $this->alaOccurrence->getFacetCountsBySpeciesConceptInLocation(
            config('aurin.ala.taxon.lathams_snipe'),
            $wkt,
            'year',
            Carbon::create(2010, 1, 1, 0, 0, 0),
            Carbon::create(2019, 12, 31, 23, 59, 59)
        );
    }

    public function getThreatenedSpeciesByStates(array|string $states, bool $include_national = true): array
    {
        $stateAbbreviations = [
            'Victoria' => 'vic',
            'New South Wales' => 'nsw',
            'Queensland' => 'qld',
            'South Australia' => 'sa',
            'Western Australia' => 'wa',
            'Tasmania' => 'tas',
            'Northern Territory' => 'nt',
            'Australian Capital Territory' => 'act',
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
            $threatened = $include_national && $specie->conservation_aus;
            foreach ($states as $state) {
                $threatened = (bool) $specie->{'conservation_'.$stateAbbreviations[$state]} ?? $threatened;
            }

            return $threatened;
        });

        return array_values($threatenedSpecies->toArray());
    }

    /**
     * @return array<object>
     */
    public function getSpeciesList(): array
    {
        $speciesList = null;

        $json = Storage::get('species-info-cache.json');

        if (is_string($json)) {
            $speciesList = json_decode($json);
        }
        if (!is_array($speciesList) || count($speciesList) === 0) {
            $speciesList = [];
            Log::error('Waterbird species cache has 0 entries. Re-run php artisan species:info');
        }
        return $speciesList;
    }

    public function getFrogsInArea(string $wkt)
    {
        $frogsInArea = $this->alaOccurrence->getFrogsInArea($wkt);

        return $frogsInArea;
    }


    public function getWaterbirdsInArea(string $wkt)
    {
        $birdsInArea = $this->alaOccurrence->getBirdsInArea($wkt);
        $waterbirds = collect($this->getSpeciesList());
        $waterbirdGuids = $waterbirds->pluck('guid')->toArray();

        // Check if retrieved bird exists in our list of waterbirds
        $waterbirdsInArea = collect($birdsInArea)->filter(function ($bird) use ($waterbirdGuids) {
            return property_exists($bird, 'guid') && in_array($bird->guid, $waterbirdGuids);
        });

        $waterbirdsInArea = $waterbirdsInArea->map(function ($bird) use ($waterbirds) {
            // Get our metadata for this waterbird
            $waterbird = $waterbirds->first(fn ($wb) => $bird->guid == $wb->guid);
            $bird = array_merge((array) $bird, (array) $waterbird);

            $bird['common_names'] = array_unique(array_merge((array) $bird['common_name'], [$bird['commonName']]));

            $removeKeys = ['name', 'commonName', 'common_name', 'kingdom', 'rank', 'family'];
            $bird = array_diff_key($bird, array_flip($removeKeys));

            return $bird;
        });

        return $waterbirdsInArea->values();
    }
}
