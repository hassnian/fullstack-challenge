<?php

namespace App\Services\Api;

use GuzzleHttp\Client;

class NewYorkTimesApi
{
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @throws \Exception
     */
    public function getArticles($query = '', $page = 1) {
        try {
            $options = [
                'query' => [
                    'q' => $query,
                    'page' => $page,
                ]
            ];

            $response = $this->client->get('/svc/search/v2/articlesearch.json', $this->getOptionsWithDefaultConfig($options));

            $response = json_decode($response->getBody()->getContents(), true);

            return $response['response']['docs'] ?? [];
        } catch (\Exception $e) {
            dd($e->getMessage());
            throw new \Exception($e->getMessage());
        }
    }

    private function getOptionsWithDefaultConfig(array $options): array
    {
        return array_merge_recursive($options, $this->client->getConfig('defaults'));
    }
}
