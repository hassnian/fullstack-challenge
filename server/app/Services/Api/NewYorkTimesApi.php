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
    public function getArticles($query = '', $page = 1, $pageSize = 50, $section = 'all-sections') {
        try {
            $options = [
                'query' => [
                    'q' => $query,
                    'page' => $page,
                    'fq' => 'section_name:("' . $section . '")',
                ]
            ];

            $response = $this->client->get('/svc/search/v2/articlesearch.json', $this->getOptionsWithDefaultConfig($options));

            $response = json_decode($response->getBody()->getContents(), true);

            return $response['response']['docs'] ?? [];
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }


    public function getSections($query = '', $page = 1, $pageSize = 50)
    {
        $options = [
            'query' => [
                'q' => $query,
            ]
        ];

        $response = $this->client->get('/svc/news/v3/content/section-list.json', $this->getOptionsWithDefaultConfig($options));

        $response = json_decode($response->getBody()->getContents(), true);

        return $response['results'] ?? [];
    }


    private function getOptionsWithDefaultConfig(array $options): array
    {
        return array_merge_recursive($options, $this->client->getConfig('defaults'));
    }
}
