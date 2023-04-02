<?php

namespace App\Services\Api;

use Illuminate\Support\Facades\Http;

class NewYorkTimesApi
{
    private mixed $api_key;
    private mixed $api_url;

    public function __construct()
    {
        $this->api_url = config('services.ny_times_api.url');
        $this->api_key = config('services.ny_times_api.key');
    }

    /**
     * @throws \Exception
     */
    public function getArticles($query = '') {
        try {
            $response = Http::get($this->api_url.'/articlesearch.json', [
                'api-key' => $this->api_key,
                'q' => $query,
            ]);

            if ($response['status'] !== 'ok') {
                throw new \Exception('News API request failed');
            }

            $response = $response->json();

            return $response['articles'] ?? [];
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

    }
}
