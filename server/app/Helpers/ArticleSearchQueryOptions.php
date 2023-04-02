<?php

namespace App\Helpers;

class ArticleSearchQueryOptions
{

    public function __construct(
        public int $page = 1,
        public int $limit = 10,
        public ?string $query = '',
        public ?array $publishedAt = [],
        public ?array $categories = [],
        public ?array $datasources = [],
        public ?array $authors = [],
        public bool $allFiltersRequired = true,
    )
    {

    }

}