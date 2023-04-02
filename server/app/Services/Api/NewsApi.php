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
    public function getTopHeadlines($query = '') {
        try {

            $response = $this->client->get('/v2/top-headlines', [
                'query' => [
                    'q' => $query,
                    'sortBy' => 'publishedAt',
                    'country' => 'us',
                    'pageSize' => 100,
                ]
            ]);


            if ($response->getStatusCode() !== 200) {
                throw new Exception('News API request failed');
            }

            $response = json_decode($response->getBody()->getContents(), true);

            return $response['articles'] ?? [];
        } catch (GuzzleException $e) {
            throw new Exception($e->getMessage());
        }

    }
}
