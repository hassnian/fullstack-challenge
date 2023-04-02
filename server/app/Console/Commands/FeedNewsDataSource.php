<?php

namespace App\Console\Commands;

use App\Services\ArticleDatasourceService;
use App\Services\DataSources\NewsApiDatasource;
use App\Services\DataSources\NewYorkTimesDatasource;
use App\Services\DataSources\TheGuardianDatasource;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;

class FeedNewsDataSource extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:feed-news-data-source';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $articleDataSource = new ArticleDatasourceService([
            App::make(NewsApiDatasource::class),
            App::make(TheGuardianDatasource::class),
//            App::make(NewYorkTimesDatasource::class)
        ]);


        $articleDataSource->feedArticleData();
    }
}
