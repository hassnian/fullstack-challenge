<?php

namespace App\Services\DataSources;

use App\Helpers\ArticleAuthorData;
use App\Helpers\ArticleCategoryData;
use App\Helpers\ArticleDatasourceData;
use App\Helpers\Enums\ArticleDatasourceType;
use App\Interfaces\ArticleDatasource;
use App\Models\Article;
use App\Services\Api\TheGuardianApi;
use Carbon\Carbon;
use GuzzleHttp\Client;

class TheGuardianDatasource implements ArticleDatasource
{

    private TheGuardianApi $api;

    public function __construct()
    {
        $this->api = new TheGuardianApi(
            new Client([
                'base_uri' => config('services.the_guardian.url'),
                'defaults' => [
                    'query' => [
                        'api-key' => config('services.the_guardian.key'),
                    ]
                ]
            ])
        );
    }

    private function getFormattedArticleDatasourceData($article): ArticleDatasourceData {
        $tags = $article['tags'] ?? [];

        $authors = collect($tags)->filter(function ($tag){
            return $tag['type'] === 'contributor';
        })->map(function ($tag){
            return new ArticleAuthorData($tag['id'], $tag['webTitle']);
        })->toArray();

        $series = collect($tags)->filter(function ($tag){
            return $tag['type'] === 'series';
        })->map(function ($item){
            return new ArticleCategoryData($item['sectionId'], $item['sectionName']);
        })->toArray();

        $source = 'The Guardian';
        $title = $article['webTitle'];
        $sourceUrl = $article['webUrl'];
        $urlToImage = $article['fields']['thumbnail'] ?? null;
        $publishedAt = new Carbon($article['webPublicationDate']);
        $content = null;
        $categories = $series;

        return new ArticleDatasourceData(ArticleDatasourceType::THE_GUARDIAN, $title, $sourceUrl, $urlToImage, $publishedAt, $content, $source, $authors, $categories);
    }

    public function getArticles($query = ''): array
    {
        try {

           $articles = $this->api->getSearch($query);

           $formattedArticles = collect($articles)->map(function ($article){
               return $this->getFormattedArticleDatasourceData($article);
           });

           return $formattedArticles->toArray();
        } catch (\Exception $e) {
            dd($e->getMessage());
            return [];
        }

    }

}
