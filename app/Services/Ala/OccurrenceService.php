<?php
/**
 * User: Scott Limmer
 * Date: 5/04/2023
 */

namespace App\Services\Ala;

use App\Traits\PrefixedLogger;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use JsonException;
use PhpParser\Node\Expr\Cast\Object_;

class OccurrenceService
{
    use PrefixedLogger;

    private Client $client;
    private string $bearer;

    public function __construct(AuthService $authService)
    {
        $this->setLoggerPrefix(basename(__CLASS__));
        $this->client = new Client(['base_uri' => 'https://api.ala.org.au/occurrences/']);
        $this->bearer = $authService->getBearerToken();
    }

    public function getFacetCountsBySpeciesConceptInLocation(string $taxonConceptId, string $wkt, string $facet, ?Carbon $startDate, ?Carbon $endDate): array
    {

        $startDate = $startDate ? $startDate->toIso8601ZuluString() : '*';
        $endDate = $endDate ? $endDate->toIso8601ZuluString() : '*';

        //https://biocache.ala.org.au/occurrences/search?q=taxa%3A%22japanese%20snipe%22&qualityProfile=ALA&qc=-_nest_parent_%3A*&fq=taxon_name%3A%22Gallinago+%28Gallinago%29+hardwickii%22
        try {
            $qid = $this->cacheSpatialQuery([
                'fq' => 'taxon_name:"Gallinago (Gallinago) hardwickii"',
                'wkt' => $wkt,
                'q' => "occurrence_date:[$startDate TO $endDate]"
            ]);

            $response = $this->executeQuery('chart', [
                'query' => [
                    'q' => "qid:$qid",
                    'x' => $facet,
                    'qualityProfile' => 'ALA',
                ]
            ]);
            return $response->data[0]->data;

        } catch (GuzzleException|JsonException $e) {
            $this->log('error', $e->getMessage(), [
                'function: ' . __FUNCTION__
            ]);

            return [];
        }

        //chart?
        //q=lsid%3Ahttps://biodiversity.org.au/afd/taxa/5c1957dc-0780-47cb-9c89-0498340c1e62%20AND%20occurrence_date%3A[2010-01-01T00%3A00%3A00Z%20TO%202019-12-31T23%3A59%3A59Z]
        //&qualityProfile=ALA
        //&x=year

    }


    public function getBirdsInArea(string $wkt): array
    {

        try {
            $qid = $this->cacheSpatialQuery([
                'fq' => 'class:Aves',
                'wkt' => $wkt
            ]);

            $response =  $this->executeQuery('mapping/species', [
                'query' => [
                    'flimit' => 1000,
                    'q' => 'qid:' . $qid
                ]
            ]);

            return $response;


        } catch (GuzzleException|JsonException $e) {
            $this->log('error', $e->getMessage(), [
                'function: ' . __FUNCTION__
            ]);

            return [];
        }
    }

    /**
     * @throws GuzzleException
     * @throws JsonException
     */
    private function cacheSpatialQuery(array $query): string
    {

        return $this->executeQuery(
            'qid',
            [
                'headers' => [
                    'Authorization' => "Bearer $this->bearer"
                ],
                'form_params' => $query
            ],
            'POST'
        );
    }

    /**
     * @throws GuzzleException
     * @throws JsonException
     */
    private function executeQuery(string $url, array $options = [], $method = 'GET'): array|object|string
    {
        $response = $this->client->request($method, $url, $options);

        $contents = $response->getBody()->getContents();

        if (str_contains($response->getHeader('Content-Type')[0], 'application/json')) {
            $contents = json_decode($contents, null, 512, JSON_THROW_ON_ERROR);
        }

        return $contents;
    }


}
