<?php

namespace App\Helpers;

use App\Helpers\Enums\ArticleDatasourceType;

class ArticleDatasourceData
{
    public function __construct(
        public string $datasourceType,
        public string $title,
        public string $sourceUrl,
        public ?string $urlToImage,
        public string $publishedAt,
        public ?string $content,
        public ?string $sourceName,
        public ?array $authors,
        public ?array $categories,
    )
    {
    }
}
