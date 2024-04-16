<?php

namespace App\Console\Commands;

use App\Services\Ala\OccurrenceService;
use App\Traits\PrefixedLogger;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use League\Csv\Reader;
use JsonException;

class FetchFrogInfo extends Command
{

    use PrefixedLogger;

    private array $stateLookup = [
        'Australian Capital Territory' => 'act',
        'New South Wales' => 'nsw',
        'Northern Territory' => 'nt',
        'Queensland' => 'qld',
        'South Australia' => 'sa',
        'Tasmania' => 'tas',
        'Victoria' => 'vic',
        'Western Australia' => 'wa',
    ];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'frog:info';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch and cache frog species info';

    /**
     * @var \GuzzleHttp\Client
     */
    private Client $client;

    public function __construct()
    {
        parent::__construct();

        $this->setLoggerPrefix(basename(__CLASS__));

        $this->client = new Client([
            'base_uri' => 'https://api.ala.org.au',
        ]);
    }

    /**
     * Execute the console command.
     * @see https://api.ala.org.au/occurrences/occurrences/facets/download?facets=names_and_lsid&lookup=true&count=true&lists=true&fq=species_group%3AAmphibians
     */
    public function handle(OccurrenceService $occurrenceService)
    {
        $response = $occurrenceService->getFacetDownload('names_and_lsid', 'class:Amphibia');

        if (is_string($response)) {
            $csv = Reader::createFromString($response);
            $csv->setHeaderOffset(0);
            $records = array_values(iterator_to_array($csv->getRecords()));
            $frogs = [];
            foreach ($records as $record) {
                [$speciesName, $guid, $commonName, $kingdom, $family] = explode('|', $record['names_and_lsid']);
                $frog = [
                    'scientific_name' => $speciesName,
                    'guid' => $guid,
                    'common_name' => $commonName ?: null,
                ];
                $tmpArray= array();

                try {
                    array_push($tmpArray, $this->parseConversationStatusString($record['Australian Capital Territory : Conservation Status']));
                    array_push($tmpArray, $this->parseConversationStatusString($record['South Australia : Conservation Status']));
                    array_push($tmpArray, $this->parseConversationStatusString($record['Tasmania : Conservation Status']));
                    array_push($tmpArray, $this->parseConversationStatusString($record['Queensland : Conservation Status']));
                    array_push($tmpArray, $this->parseConversationStatusString($record['EPBC Act Threatened Species']));
                    array_push($tmpArray, $this->parseConversationStatusString($record['Victoria : Conservation Status']));
                    array_push($tmpArray, $this->parseConversationStatusString($record['Northern Territory : Conservation Status']));
                    array_push($tmpArray, $this->parseConversationStatusString($record['New South Wales : Conservation Status']));
                    array_push($tmpArray, $this->parseConversationStatusString($record['Western Australia : Conservation status']));
                    array_push($tmpArray, $this->parseConversationStatusString($record['Western Australia : Conservation status: Priority 4']));
                    array_push($tmpArray, $this->parseConversationStatusString($record['Western Australia : Conservation status: Priority 3']));
                    array_push($tmpArray, $this->parseConversationStatusString($record['Western Australia : Conservation status: Priority 1']));
                }

                catch(JsonException $e) {
                    $this->log('error', 'Unable to parse frog Info list response', [$e->getMessage()]);
                }
                $tmpArray=array_filter($tmpArray);
                $frog['conservation'] = ($tmpArray);
                $frogs[] = $frog;
            }

            return Storage::put('frog-species-info-cache.json', json_encode($frogs));
        }
    }

    /**
     * Parse concatenated string of vulnerability statuses into nested area
     * Sample: Australian Capital Territory : Conservation Status | EPBC Act Threatened Species | New South Wales : Conservation Status
     *
     * @param string $statusString
     *
     * @return array|null
     */
    private function parseConversationStatusString(string $statusString): ?array
    {
        $payload = [];
        if (!empty($statusString)) {
            $statusParts = str_contains($statusString, '|') ?
                explode('|', $statusString) : [$statusString];

            foreach ($statusParts as $part) {
                if (str_contains($part, ':')) {
                    [$state, $value] = explode(':', $part);
                    $stateAbbreviation = $this->stateLookup[trim($state)];
                    $payload[$stateAbbreviation] = true;
                } else {
                    $payload['aus'] = trim($part);
                }
            }
        }
        return $payload;
    }

}
