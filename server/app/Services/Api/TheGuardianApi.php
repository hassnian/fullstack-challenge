<?php

namespace App\Services\Api;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class TheGuardianApi
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param string $query
     * @param int $page
     * @param int $pageSize max is 50
     * @return array
     * @throws Exception
     */
    public function getSearch(string $query = '', int $page = 1, int $pageSize = 50 ): array {
        try {

            $options = [
                'query' => [
                    'q' => $query,
                    'page' => $page,
                    'page-size' => $pageSize,
                    'show-fields' => 'thumbnail',
                    'show-tags' => 'contributor,series',
                ]
            ];

            $response = $this->client->get('/search', $this->getOptionsWithDefaultConfig($options));

            $response = json_decode($response->getBody()->getContents(), true);

            return $response['response']['results'] ?? [];
        } catch (GuzzleException $e) {
            throw new Exception($e->getMessage());
        }
    }

    private function getOptionsWithDefaultConfig(array $options = []): array {
        return array_merge_recursive($options, $this->client->getConfig('defaults'));
    }
}
