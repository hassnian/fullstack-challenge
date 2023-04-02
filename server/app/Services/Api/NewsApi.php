<?php

namespace App\Services\Api;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class NewsApi
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @throws Exception
     */
    public function getTopHeadlines($query = '', string $category = 'general') {
        try {

            $options =  [
                'query' => [
                    'q' => $query,
                    'sortBy' => 'publishedAt',
                    'country' => 'us',
                    'pageSize' => 100,
                    'category' => $category,
                ]
            ];

            $response = $this->client->get('/v2/top-headlines', $this->getOptionsWithDefaultConfig($options));

            $response = json_decode($response->getBody()->getContents(), true);

            return $response['articles'] ?? [];
        } catch (GuzzleException $e) {
            throw new Exception($e->getMessage());
        }

    }

    private function getOptionsWithDefaultConfig(array $options)
    {
        return array_merge_recursive($options, $this->client->getConfig('defaults'));
    }
}
