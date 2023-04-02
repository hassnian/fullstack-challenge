<?php

namespace App\Interfaces;

use App\Helpers\ArticleSearchQueryOptions;
use Illuminate\Database\Eloquent\Collection;

interface ArticleSearchRepository
{
    public function search(ArticleSearchQueryOptions $articleSearchQueryOptions): Collection;
}