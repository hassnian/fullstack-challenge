<?php

namespace App\Providers;

use App\Interfaces\ArticleSearchRepository;
use App\Repository\ElasticsearchArticleRepository;
use App\Repository\EloquentArticleSearchRepository;
use App\Services\Api\NewsApi;
use App\Services\DataSources\NewsApiDatasource;
use Elasticsearch\Client as ElasticsearchClient;
use Elasticsearch\ClientBuilder;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Foundation\Application;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(NewsApiDatasource::class, function (Application $application) {
            return new NewsApiDatasource();
        });

        $this->bindElasticsearchClient();

        $this->app->bind(ArticleSearchRepository::class, function (Application $application) {
            if (config('services.search.driver') === 'elasticsearch') {
                return new ElasticsearchArticleRepository(
                    $application->make(ElasticsearchClient::class)
                );
            }

            return new EloquentArticleSearchRepository();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }

    private function bindElasticsearchClient(): void
    {
        $this->app->bind(ElasticsearchClient::class, function (Application $application) {
            return ClientBuilder::create()
                ->setHosts(config('services.search.elastic_search.hosts'))
                ->build();
        });
    }
}
