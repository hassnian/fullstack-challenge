<?php

namespace App\Services;

use App\Helpers\ArticleAuthorData;
use App\Helpers\ArticleCategoryData;
use App\Helpers\ArticleDatasourceData;
use App\Helpers\Enums\ArticleDatasourceType;
use App\Interfaces\ArticleDatasource;
use App\Models\Article;
use App\Models\Author;
use App\Models\Category;

class ArticleDatasourceService
{


    /**
     * @param array $datasources
     */
    public function __construct(array $datasources)
    {
        $this->datasources = $datasources;
    }

    private function getUniqueArticles(array $articles): array
    {
        $datasourceArticlesSourceUrls = collect($articles)->map(function (ArticleDatasourceData $article){
            return $article->sourceUrl;
        })->toArray();


        $uniqueArticles = Article::where('datasource', ArticleDatasourceType::THE_GUARDIAN)
            ->whereIn('source_url', $datasourceArticlesSourceUrls)
            ->get()
            ->map(function (Article $article){
                return $article->source_url;
            })->toArray();

        return  collect($articles)->filter(function (ArticleDatasourceData $article) use ($uniqueArticles){
            return !in_array($article->sourceUrl, $uniqueArticles);
        })->toArray();
    }

    private function saveArticles(array $datasourceArticles): void
    {
        collect($datasourceArticles)->each(function (ArticleDatasourceData $articleDatasourceData){
            /** @var Article $article */
            $article = Article::create([
                'datasource' => $articleDatasourceData->datasourceType,
                'title' => $articleDatasourceData->title,
                'source_url' => $articleDatasourceData->sourceUrl,
                'url_to_image' => $articleDatasourceData->urlToImage,
                'published_at' => $articleDatasourceData->publishedAt,
                'content' => $articleDatasourceData->content,
                'source' => $articleDatasourceData->sourceName,
            ]);


            collect($articleDatasourceData->authors)->each(function (ArticleAuthorData $author) use ($article){
                $authorExists = Author::where('slug', $author->id)->first();

                if ($authorExists) {
                    $article->authors()->attach($authorExists->id);
                    return;
                }

                $article->authors()->create([
                    'name' => $author->name,
                    'slug' => collect(explode('/', $author->id))->last()
                ]);
            });


            collect($articleDatasourceData->categories)->each(function (ArticleCategoryData $category) use ($article){

                $categoryExists = Category::where('slug', $category->id)->first();

                if ($categoryExists) {
                    $article->categories()->attach($categoryExists->id);
                    return;
                }

                $article->categories()->create([
                    'name' => $category->name,
                    'slug' => $category->id,
                ]);
            });

            $article->refresh();

            // since relations are not indexed on creation we need to reindex the article
            $article->forceReIndex();

            $article->save();
        });
    }

    public function feedArticleData(): void
    {
        $articles = collect($this->datasources)->map(function ($datasource) {
            /** @var ArticleDatasource $datasource */
            return $datasource->getArticles();
        })->flatten(1)->toArray();

//        $uniqueArticles = $this->getUniqueArticles($articles);

        $this->saveArticles($articles);
    }

}
