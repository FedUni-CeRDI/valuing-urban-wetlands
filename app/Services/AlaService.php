<?php
/**
 * User: Scott Limmer
 * Date: 5/04/2023
 */

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use JsonException;

class AlaService
{
    private Client $client;

    public function __construct()
    {
        $this->client = new Client(['base_uri' => 'https://api.ala.org.au']);

    }

    public function getBirdsInArea(string $wkt)
    {
        try {
            $content = $this->executeQuery('/occurrences/mapping/species', [
                'query' => [
                    'fq' => 'class:Aves',
                    'wkt' => $wkt,
                    'flimit' => 500
                ]
            ]);

            return $content;
        } catch (\Exception $e) {
            // TODO:
        }

    }

    /**
     * @throws GuzzleException
     * @throws JsonException
     */
    private function executeQuery(string $url, array $options = []): array|object
    {
        $response = $this->client->get($url, $options);
        $contents = $response->getBody()->getContents();

        return json_decode($contents, null, 512, JSON_THROW_ON_ERROR);
    }

}
