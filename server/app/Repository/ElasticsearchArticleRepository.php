<?php

namespace App\Repository;

use App\Helpers\ArticleSearchQueryOptions;
use App\Helpers\ElastisearchQueryBuilderHelper;
use App\Interfaces\ArticleSearchRepository;
use App\Models\Article;
use Illuminate\Database\Eloquent\Collection;
use Elasticsearch\Client;
use Illuminate\Support\Arr;

class ElasticsearchArticleRepository implements ArticleSearchRepository
{

    private Client $elasticsearch;

    public function __construct(Client $elasticSearchClient)
    {
        $this->elasticsearch = $elasticSearchClient;
    }

    /**
     * @param ArticleSearchQueryOptions $articleSearchQueryOptions
     * @return array
     */
    private function getBody(ArticleSearchQueryOptions $articleSearchQueryOptions): array
    {
        $query = $articleSearchQueryOptions->query ?? null;
        $publishedAt = $articleSearchQueryOptions->publishedAt ?? [];
        $datasources = $articleSearchQueryOptions->datasources ?? [];
        $categories = $articleSearchQueryOptions->categories ?? [];
        $authors = $articleSearchQueryOptions->authors ?? [];
        $pageSize = $articleSearchQueryOptions->pageSize ?? 10;
        $page = $articleSearchQueryOptions->page ?? 1;

        $body = collect();

        // pagination
        $from = ($page - 1) * $pageSize;
        $body->put('from', $from);
        $body->put('size', $pageSize);

        // sort
        $body->put('sort', [
            ElastisearchQueryBuilderHelper::getSortBy('published_at', 'desc')
        ]);

        // query
        $mustOrShouldQuery = collect();

        list($hasPublishedAt, $fromDate, $toDate) = $this->getPublishedAtDates($publishedAt);

        if ($hasPublishedAt) {
            $mustOrShouldQuery->push(ElastisearchQueryBuilderHelper::getRangeQuery('published_at', $fromDate, $toDate));
        }

        if ($query) {
            $mustOrShouldQuery->push(ElastisearchQueryBuilderHelper::getMultiMatchQuery($query, ['title^5', 'content']));
        }

        if ($this->hasDatasources($datasources)) {
            $mustOrShouldQuery->push(ElastisearchQueryBuilderHelper::getTermsQuery('datasource', $datasources));
        }

        if ($this->hasCategories($categories)) {
            $mustOrShouldQuery->push(ElastisearchQueryBuilderHelper::getNestedPathQuery('categories',
                ElastisearchQueryBuilderHelper::getBooleanQuery(
                    ElastisearchQueryBuilderHelper::getMustQuery([
                           ElastisearchQueryBuilderHelper::getTermsQuery('categories.id', $categories)
                    ])
                )
            ));
        }

        if ($this->hasAuthors($authors)) {
            $mustOrShouldQuery->push(ElastisearchQueryBuilderHelper::getNestedPathQuery('authors',
                ElastisearchQueryBuilderHelper::getBooleanQuery(
                    ElastisearchQueryBuilderHelper::getMustQuery([
                        ElastisearchQueryBuilderHelper::getTermsQuery('authors.id', $authors)
                    ])
                )
            ));
        }

        $body->put('query', ElastisearchQueryBuilderHelper::getBoolQuery('should', $mustOrShouldQuery->toArray()));

        return $body->toArray();
    }

    /**
     * @param array|null $publishedAt
     * @return array
     */
    public function getPublishedAtDates(?array $publishedAt): array
    {
        $hasPublishedAt = $publishedAt && $publishedAt[0] ?? false;
        $fromDate = $publishedAt[0] ?? null;
        $toDate = $publishedAt[1] ?? false;
        $toDate = $toDate ?: $fromDate;
        return array($hasPublishedAt, $fromDate, $toDate);
    }

    private function searchOnElasticsearch(array $body): array
    {
        $model = new Article;

        return $this->elasticsearch->search([
            'index' => $model->getSearchIndex(),
            'type' => '_doc',
            'body' => $body,
        ]);
    }


    private function getArticlesCollection(array $items)
    {
        $ids = Arr::pluck($items['hits']['hits'], '_id');

        return Article::findMany($ids);
    }


    public function search(ArticleSearchQueryOptions $articleSearchQueryOptions): Collection
    {
        $body = $this->getBody($articleSearchQueryOptions);

        $items = $this->searchOnElasticsearch($body);

        return $this->getArticlesCollection($items);
    }

    private function hasDatasources(array $datasources): bool
    {
        return count($datasources) > 0;
    }

    private function hasCategories(array $categories): bool
    {
        return count($categories) > 0;
    }

    private function hasAuthors(array $authors)
    {
        return count($authors) > 0;
    }
}