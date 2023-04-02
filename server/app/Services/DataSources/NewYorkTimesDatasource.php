<?php

namespace App\Services\DataSources;

use App\Helpers\ArticleAuthorData;
use App\Helpers\ArticleCategoryData;
use App\Helpers\ArticleDatasourceData;
use App\Helpers\Enums\ArticleDatasourceType;
use App\Interfaces\ArticleDatasource;
use App\Models\Article;
use App\Services\Api\NewYorkTimesApi;
use App\Services\Api\TheGuardianApi;
use Carbon\Carbon;
use GuzzleHttp\Client;

class NewYorkTimesDatasource implements ArticleDatasource
{

    private NewYorkTimesApi $api;

    public function __construct(NewYorkTimesApi $newYorkTimesApi)
    {
        $this->api = $newYorkTimesApi;
    }

    private function getFormattedArticleDatasourceData($article): ArticleDatasourceData {
        $category = $article['section_name'] ?? null;

        $categories = [];

        if ($category !== null) {
            $categories[] = new ArticleCategoryData($category, $category);
        }

        $persons = $article['byline']['person'] ?? [];

        $authors = collect($persons)->map(function ($person){
            return new ArticleAuthorData($person['firstname'] . '-' . $person['lastname'], $person['firstname'] . ' ' . $person['lastname']);
        })->toArray();

        $multimedia = $article['multimedia'] ?? [];
        $firstMultimedia = $multimedia[0] ?? [];
        $imageUrl = $firstMultimedia['url'] ?? '';

        $source = $article['source'] ?? 'New York Times';
        $title = $article['abstract'] ?? $article['headline']['main'];
        $sourceUrl = $article['web_url'];
        $urlToImage =  $imageUrl === '' ? null : 'https://static01.nyt.com/' . $imageUrl;
        $publishedAt = new Carbon($article['pub_date']);
        $content = $article['snippet'];
        $categories = [];

        return new ArticleDatasourceData(ArticleDatasourceType::NEW_YORK_TIMES, $title, $sourceUrl, $urlToImage, $publishedAt, $content, $source, $authors, $categories);
    }

    public function getArticles($query = ''): array
    {
        $pages = 10;

        try {
            $articles = [];

            for ($i = 0; $i < $pages; $i++) {
                $articles = array_merge($articles, $this->api->getArticles($query, $i));
            }

            $formattedArticles = collect($articles)->map(function ($article){
                return $this->getFormattedArticleDatasourceData($article);
            });

            dd($formattedArticles->toArray());

            return $formattedArticles->toArray();
        } catch (\Exception $e) {
            dd($e->getMessage());
            return [];
        }

    }
}
