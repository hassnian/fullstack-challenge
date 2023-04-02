<?php

namespace App\Observers;

use Elasticsearch\Client;

class ElasticsearchObserver {
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function saved($model): void
    {
        $model->indexOnElasticsearch($this->client);
    }

    public function deleted($model): void
    {
        $model->deleteOnElasticsearch($this->client);
    }

    public function updated($model): void
    {
        $model->updateOnElasticsearch($this->client);
    }
}