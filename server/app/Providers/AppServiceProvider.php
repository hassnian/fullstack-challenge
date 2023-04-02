<?php

namespace App\Providers;

use App\Interfaces\ArticleSearchRepository;
use App\Repository\ElasticsearchArticleRepository;
use App\Repository\EloquentArticleSearchRepository;
use App\Services\Api\NewsApi;
use App\Services\Api\NewYorkTimesApi;
use App\Services\Api\TheGuardianApi;
use App\Services\DataSources\NewsApiDatasource;
use App\Services\DataSources\NewYorkTimesDatasource;
use App\Services\DataSources\TheGuardianDatasource;
use Elasticsearch\Client as ElasticsearchClient;
use Elasticsearch\ClientBuilder;
use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Foundation\Application;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->bindDatasources();

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

    /**
     * @return void
     */
    public function bindDatasources(): void
    {
        $this->app->singleton(NewsApiDatasource::class, function (Application $application) {
            return new NewsApiDatasource(
                new NewsApi(
                    new Client([
                        'base_uri' => config('services.news_api.url'),
                        'defaults' => [
                            'query' => [
                                'apiKey' => config('services.news_api.key'),
                            ]
                        ]
                    ])
                )
            );
        });

        $this->app->singleton(TheGuardianDatasource::class, function (Application $application) {
            return new TheGuardianDatasource(new TheGuardianApi(
                new Client([
                    'base_uri' => config('services.the_guardian.url'),
                    'defaults' => [
                        'query' => [
                            'api-key' => config('services.the_guardian.key'),
                        ]
                    ]
                ])
            ));
        });

        $this->app->singleton(NewYorkTimesDatasource::class, function (Application $application) {
            return new NewYorkTimesDatasource(
                new NewYorkTimesApi(
                    new Client([
                        'base_uri' => config('services.new_york_times.url'),
                        'defaults' => [
                            'query' => [
                                'api-key' => config('services.new_york_times.key'),
                            ]
                        ]
                    ])
                )
            );
        });
    }
}
