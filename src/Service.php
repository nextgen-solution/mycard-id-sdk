<?php

namespace NextgenSolution\MyCardIDSDK;

use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use RuntimeException;

class Service
{
    /**
     * Guzzle instance.
     *
     * @var Client
     */
    protected $guzzle;

    /**
     * Create a new MyCard instance.
     *
     * @param Client $guzzle
     */
    public function __construct(Client $guzzle)
    {
        $this->guzzle = $guzzle;
    }

    /**
     * Fetch token from cache or request new one.
     *
     * @param string $scopes
     * @return void
     */
    public function fetchClientCredentialsToken($scopes = '')
    {
        return $this->fetchToken(
            'client_credentials',
            Config::get('services.mycard.client_id'),
            Config::get('services.mycard.client_secret'),
            $scopes
        );
    }

    /**
     * Remove token in cache.
     *
     * @param string $scopes
     * @return void
     */
    public function revokeClientCredentialsToken($scopes = '')
    {
        $this->revokeToken(
            'client_credentials',
            Config::get('services.mycard.client_id'),
            Config::get('services.mycard.client_secret'),
            $scopes
        );
    }

    /**
     * View user's profile by given token.
     *
     * @param string $token
     * @return array
     */
    public function viewProfile($token)
    {
        $url = Config::get('services.mycard.api_url') . '/v1/me';
        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];

        try {
            $response = $this->guzzle->get($url, [
                'headers' => $headers,
            ]);
        } catch (GuzzleException $e) {
            throw new RuntimeException('Unexpected error during cURL.', 0, $e);
        }

        return json_decode($response->getBody(), true);
    }

    /**
     * Fetch token from cache or request new one.
     *
     * @param string $grantType
     * @param string $clientID
     * @param string $clientSecret
     * @param string $scope
     * @param array $extra
     * @return string
     */
    private function fetchToken($grantType, $clientID, $clientSecret, $scope = '', array $extra = [])
    {
        $name = $this->generateCacheName(__METHOD__, func_get_args());

        if (!Cache::has($name)) {
            $token = $this->requestToken($grantType, $clientID, $clientSecret, $scope, $extra);
            $expiresAt = Carbon::now()->addSeconds($token['expires_in']);

            Cache::put($name, $token['access_token'], $expiresAt);
        }

        return Cache::get($name);
    }

    /**
     * Remove token in cache.
     *
     * @param string $grantType
     * @param string $clientID
     * @param string $clientSecret
     * @param string $scope
     * @param array $extra
     * @return void
     */
    private function revokeToken($grantType, $clientID, $clientSecret, $scope = '', array $extra = [])
    {
        $name = $this->generateCacheName(__METHOD__, func_get_args());

        Cache::forget($name);
    }

    /**
     * Request a token from OAuth server.
     *
     * @param string $grantType
     * @param string $clientID
     * @param string $clientSecret
     * @param string $scope
     * @param array $extra
     * @return array
     */
    private function requestToken($grantType, $clientID, $clientSecret, $scope = '', array $extra = [])
    {
        $url = Config::get('services.mycard.auth_url') . '/oauth/token';
        $headers = [
            'Accept' => 'application/json',
        ];
        $formParams = array_merge($extra, [
            'grant_type' => $grantType,
            'client_id' => $clientID,
            'client_secret' => $clientSecret,
            'scope' => $scope,
        ]);

        try {
            $response = $this->guzzle->post($url, [
                'headers' => $headers,
                'form_params' => $formParams,
            ]);
        } catch (GuzzleException $e) {
            throw new RuntimeException('Unexpected error during cURL.', 0, $e);
        }

        return json_decode($response->getBody(), true);
    }

    /**
     * Generate cache name by given data.
     *
     * @param array $data
     * @return string
     */
    private function generateCacheName(...$data)
    {
        return md5(json_encode($data));
    }
}
