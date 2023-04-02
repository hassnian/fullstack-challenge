<?php

namespace App\Console\Commands;

use App\Models\Article;
use Elasticsearch\Client;
use Illuminate\Console\Command;

class IndexModels extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:index-models';

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

        $client = app(Client::class);

        $client->indices()->create(Article::getElasticSearchMappings());
    }
}
