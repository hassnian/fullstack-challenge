<?php

namespace App\Helpers\Traits;


use App\Observers\ElasticsearchObserver;
use Elasticsearch\Client;

trait ElasticSearchSearchable
{
    public static function bootElasticSearchSearchable(): void
    {
        if (config('services.search.driver') === 'elasticsearch') {
            static::observe(ElasticsearchObserver::class);
        }
    }

    public function indexOnElasticsearch(Client $elasticsearchClient)
    {
        $elasticsearchClient->index([
            'index' => $this->getSearchIndex(),
            'type' => '_doc',
            'id' => $this->getKey(),
            'body' => $this->toSearchArray(),
        ]);
    }

    public function deleteOnElasticsearch(Client $elasticsearchClient)
    {
        $elasticsearchClient->delete([
            'index' => $this->getTable(),
            'type' => '_doc',
            'id' => $this->getKey(),
        ]);
    }

    public function updateOnElasticsearch(Client $elasticsearchClient)
    {
        $elasticsearchClient->update([
            'index' => $this->getTable(),
            'type' => '_doc',
            'id' => $this->getKey(),
            'body' => [
                'doc' => $this->toSearchArray(),
            ],
        ]);
    }

    /**
     * Get the index name for the model.
     * @return string
     */
    public function getSearchIndex(): string
    {
        return $this->getTable();
    }

    abstract public function toSearchArray(): array;
}