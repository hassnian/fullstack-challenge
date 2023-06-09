<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Helpers\Enums\ArticleDatasourceType;
use App\Models\Datasource;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->seedDatasources();

        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }

    private function seedDatasources()
    {
        Datasource::create([
            'name' => 'The New York Times',
            'datasource_id' => ArticleDatasourceType::THE_GUARDIAN,
        ]);

        Datasource::create([
            'name' => 'News API',
            'datasource_id' => ArticleDatasourceType::NEWS_API,
        ]);

        Datasource::create([
            'name' => 'New York Times',
            'datasource_id' => ArticleDatasourceType::NEW_YORK_TIMES,
        ]);
    }
}
