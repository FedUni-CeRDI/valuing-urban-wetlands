<?php

namespace App\Console\Commands;

use App\Traits\PrefixedLogger;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use JsonException;
use Monolog\Logger;

class FetchSpeciesInfo extends Command
{
    use PrefixedLogger;

    protected string $logPrefix = 'FetchSpeciesInfo';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'species:info';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch and cache species info';
    private Client $client;

    public function __construct()
    {
        parent::__construct();

        $this->client = new Client([
            'base_uri' => 'https://api.ala.org.au'
        ]);;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $guids = $this->fetchWaterbirdGuids();
        $speciesData = $this->fetchSpeciesData($guids);
        $this->writeSpeciesData($speciesData);

        return Command::SUCCESS;
    }

    /**
     * @return array<string>
     */
    public function fetchWaterbirdGuids(): array
    {
        $url = sprintf('/specieslist/ws/speciesList/%s/taxa', config('aurin.waterbird_list_id'));
        $guids = [];
        try {
            $guids = $this->executeQuery($url);
            if (empty($guids)) {
                $this->log('warning', 'Waterbird list is empty');
            }
        } catch (JsonException $e) {
            $this->log('error', 'Unable to parse waterbird list response', [$e->getMessage()]);
        } catch (GuzzleException $e) {
            $this->log('error', 'Unable to fetch waterbird list', [$e->getMessage()]);
        }

        return $guids;
    }

    /**
     * @throws GuzzleException
     * @throws JsonException
     */
    private function executeQuery($url): array|object
    {
        $response = $this->client->get($url);
        $contents = $response->getBody()->getContents();

        return json_decode($contents, null, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * @param array $guids
     *
     * @return array<array>
     */
    private function fetchSpeciesData(array $guids): array
    {
        $species = [];

        foreach ($guids as $guid) {
            $specie = [];
            $url = sprintf('/species/species/%s', urlencode($guid));

            try {
                $speciesInfo = $this->executeQuery($url);
                if (empty($speciesInfo)) {
                    $this->log('warning', 'No species info found', ['guid' => $guid]);

                    continue;
                }

                $specie['guid'] = $guid;
                $specie['scientific_name'] = $speciesInfo->taxonConcept->nameString;
                $specie['common_name'] = $speciesInfo->commonNames[0]?->nameString;
                $specie['conservation_aus'] = $speciesInfo->conservationStatuses?->AUS?->status ?? null;
                $specie['conservation_vic'] = $speciesInfo->conservationStatuses?->VIC?->status ?? null;

                $species[] = $specie;
            } catch (JsonException $e) {
                $this->log('error', 'Unable to parse waterbird list response', [$e->getMessage()]);
            } catch (GuzzleException $e) {
                $this->log('error', 'Unable to fetch species info', ['exception' => $e->getMessage(), 'guid' => $guid]);
            } catch (\Exception $e) {
                $this->log('error', $e->getMessage(), ['guid' => $guid]);
            }
        }

        return $species;
    }

    /**
     * @param array $speciesData
     *
     * @return bool
     */
    private function writeSpeciesData(array $speciesData): bool
    {
        return Storage::put('species-info-cache.json', json_encode($speciesData));
    }
}
