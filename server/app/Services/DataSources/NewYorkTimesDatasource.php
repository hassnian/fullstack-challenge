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
        $categories = collect($article['des_facet'] ?? [])->map(function ($category){
            return new ArticleCategoryData($category, $category);
        })->toArray();

        $persons = $article['byline']['person'] ?? [];

        $authors = collect($persons)->map(function ($person){
            return new ArticleAuthorData($person['firstname'] . '-' . $person['lastname'], $person['firstname'] . ' ' . $person['lastname']);
        })->toArray();

        $multimedia = $article['multimedia'] ?? [];
        $firstMultimedia = $multimedia[0] ?? [];
        $imageUrl = $firstMultimedia['url'] ?? '';


        $source = $article['source'];
        $title = $article['abstract'];
        $sourceUrl = $article['web_url'];
        $urlToImage =  $imageUrl === '' ? null : 'https://static01.nyt.com/' . $imageUrl;
        $publishedAt = new Carbon($article['pub_date']);
        $content = $article['snippet'];
        $categories = [];

        return new ArticleDatasourceData(ArticleDatasourceType::NEW_YORK_TIMES, $title, $sourceUrl, $urlToImage, $publishedAt, $content, $source, $authors, $categories);
    }

    public function getArticles($query = ''): array
    {
        try {

            $sections = $this->api->getSections();

            $sections = collect($sections)->map(function ($section){
                return $section['section'];
            })->toArray();

            $articles = [];

            foreach ($sections as $category) {
                // todo this will hit the api limit add it into a queue and process it
                try {
                 $articles = array_merge($articles, $this->getArticlesByCategory($category, $query));
                } catch (\Exception $e) {

                }
            }

            return $articles;
        } catch (\Exception $e) {
            dd($e->getMessage());
            return [];
        }

    }

    private function getArticlesByCategory(mixed $category, mixed $query)
    {
        $articles = $this->api->getArticles($query, 1 , 50, $category);

        $formattedArticles = collect($articles)->map(function ($article){
            return $this->getFormattedArticleDatasourceData($article);
        });

        return $formattedArticles->toArray();
    }

}
