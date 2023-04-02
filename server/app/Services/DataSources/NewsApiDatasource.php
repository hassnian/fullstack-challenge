<?php

namespace App\Services\DataSources;

use App\Helpers\ArticleAuthorData;
use App\Helpers\ArticleCategoryData;
use App\Helpers\ArticleDatasourceData;
use App\Helpers\Enums\ArticleDatasourceType;
use App\Interfaces\ArticleDatasource;
use App\Services\Api\NewsApi;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Str;

class NewsApiDatasource implements ArticleDatasource
{

    private NewsApi $api;

    private array $categories = [
        'business',
        'entertainment',
        'general',
        'health',
        'science',
        'sports',
        'technology'
    ];

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
        $articles = [];

        foreach ($this->categories as $category) {
            $articles = array_merge($articles, $this->getArticlesByCategory($category, $query));
        }

        return $articles;
    }

    private function getArticlesByCategory(mixed $category, mixed $query): array
    {

        try {
            $articles = $this->api->getTopHeadlines($query, $category);

            $formattedArticles = collect($articles)->map(function ($article) use ($category) {

                $authors = collect(explode(',', $article['author']) ?? [])
                    ->map(function ($author) {
                        return trim($author);
                    })
                    ->map(function ($author) {
                    return new ArticleAuthorData(Str::slug($author), $author);
                })->toArray();

                $categories = [
                    new ArticleCategoryData(Str::slug($category), $category)
                ];

                $source = $article['source']['name'] ?? null;
                $title = $article['title'];
                $sourceUrl = $article['url'];
                $urlToImage = $article['urlToImage'];
                $publishedAt = new Carbon($article['publishedAt']);
                $content = $article['content'] ?? $article['description'] ?? null;

                return new ArticleDatasourceData(ArticleDatasourceType::NEWS_API, $title, $sourceUrl, $urlToImage, $publishedAt, $content, $source, $authors, $categories);
            });

            return $formattedArticles->toArray();
        } catch (\Exception $e) {
            dd($e->getMessage());
            return [];
        }

    }
}
