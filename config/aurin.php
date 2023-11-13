<?php
/**
 * User: Scott Limmer
 * Date: 27/03/2023
 */

return [
    'geoserver_base_url' => env('GEOSERVER_BASE_URL', 'https://geo.cerdi.edu.au'),
    'waterbird_list_id' => env('AURIN_WATERBIRD_LIST_ID', 'dr21343'),
    'ala' => [
        'api' => [
            'client_id' => env('ALA_API_CLIENT_ID'),
            'client_secret' => env('ALA_API_CLIENT_SECRET'),
        ],
        'taxon' => [
            'lathams_snipe' => env('ALA_TAXON_CONCEPT_LATHAMS_SNIPE', 'https://biodiversity.org.au/afd/taxa/5c1957dc-0780-47cb-9c89-0498340c1e62'),
        ],
    ],
    // Wetland buffer radius in metres
    'wetland_buffer' => 350,
    'google_analytics_key' => env('GOOGLE_ANALYTICS_KEY')
];
