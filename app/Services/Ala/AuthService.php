<?php
/**
 * User: Scott Limmer
 * Date: 5/04/2023
 */

namespace App\Services\Ala;

use App\Traits\PrefixedLogger;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class AuthService
{
    use PrefixedLogger;

    private Client $client;

    public function __construct()
    {
        $this->setLoggerPrefix(basename(__CLASS__));
        $this->client = new Client(['base_uri' => 'https://auth.ala.org.au/cas/oidc/']);
    }

    public function getBearerToken(): string
    {
        if ($this->tokenCacheIsValid()) {
            if ($this->tokenIsExpired()) {
                $this->log('debug', 'Token is expired. Refreshing');
                $this->refreshAccessToken();
            }
        } else {
            $this->log('debug', 'Token missing or invalid. Generating');
            $this->generateAccessToken();
        }

        $token = Cache::get('ala_token');

        return $token->access_token;
    }

    private function tokenCacheIsValid(): bool
    {
        return Cache::has('ala_token') && Cache::has('ala_token_refresh_token') && Cache::has('ala_token_created_at');
    }

    private function tokenIsExpired(): bool
    {
        $token = Cache::get('ala_token');
        $tokenExpiry = Cache::get('ala_token_created_at') + $token->expires_in;

        return Carbon::now()->greaterThan(Carbon::createFromTimestampUTC($tokenExpiry));
    }

    private function cacheAccessToken(Response $response): void
    {
        $token = json_decode($response->getBody()->getContents());
        // Refreshing an existing token does not issue a new refresh_token
        if (isset($token->refresh_token)) {
            Cache::put('ala_token_refresh_token', $token->refresh_token);
        }
        Cache::put('ala_token', $token);
        Cache::put('ala_token_created_at', Carbon::now()->format('U'));
    }

    public function generateAccessToken(): void
    {
        try {
            $response = $this->client->post('oidcAccessToken', [
                'auth' => [
                    config('aurin.ala.api.client_id'),
                    config('aurin.ala.api.client_secret'),
                ],
                'form_params' => [
                    'grant_type' => 'client_credentials',
                ],
            ]);

            $this->cacheAccessToken($response);
        } catch (GuzzleException $e) {
            $this->log('error', $e->getMessage(), [
                'function: '.__FUNCTION__,
            ]);
        }
    }

    private function refreshAccessToken(): void
    {
        $refreshToken = Cache::get('ala_token_refresh_token');

        try {
            $response = $this->client->post('oidcAccessToken', [
                'auth' => [
                    config('aurin.ala.api.client_id'),
                    config('aurin.ala.api.client_secret'),
                ],
                'form_params' => [
                    'grant_type' => 'refresh_token',
                    'refresh_token' => $refreshToken,
                    'client_id' => config('aurin.ala.api.client_id'),
                    'client_secret' => config('aurin.ala.api.client_secret'),
                ],
            ]);

            $this->cacheAccessToken($response);
        } catch (GuzzleException $e) {
            $this->log('error', $e->getMessage(), [
                'function: '.__FUNCTION__,
            ]);
            // Likely refresh token has expired
            $this->generateAccessToken();
        }
    }
}
