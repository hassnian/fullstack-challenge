<?php

namespace App\Services\DataSources;

use App\Helpers\ArticleDatasourceData;
use App\Helpers\Enums\ArticleDatasourceType;
use App\Interfaces\ArticleDatasource;
use App\Services\Api\NewsApi;
use Carbon\Carbon;
use GuzzleHttp\Client;

class NewsApiDatasource implements ArticleDatasource
{

    private NewsApi $api;

    public function __construct()
    {
        $this->api = new NewsApi(
            new Client([
                'base_uri' => config('services.news_api.url'),
                'headers' => [
                    'X-Api-Key' => config('services.news_api.key'),
                ],
            ])
        );
    }

    public function getArticles($query = ''): array
    {
        try {
           $articles = $this->api->getTopHeadlines($query);

           $formattedArticles = collect($articles)->map(function ($article){
               $source = $article['source']['name'] ?? null;
               $author = $article['author'];
               $title = $article['title'];
               $sourceUrl = $article['url'];
               $urlToImage = $article['urlToImage'];
               $publishedAt = new Carbon($article['publishedAt']);
               $content = $article['content'] ?? $article['description'] ?? null;

               return new ArticleDatasourceData(ArticleDatasourceType::NEWS_API, $title, $sourceUrl, $urlToImage, $publishedAt, $content, $source, $author, null);
           });

           return $formattedArticles->toArray();
        } catch (\Exception $e) {
            dd($e->getMessage());
            return [];
        }
    }
}
