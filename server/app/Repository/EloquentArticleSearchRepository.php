<?php

namespace App\Repository;

use App\Helpers\ArticleSearchQueryOptions;
use App\Interfaces\ArticleSearchRepository;
use App\Models\Article;
use Illuminate\Database\Eloquent\Collection;

class EloquentArticleSearchRepository implements ArticleSearchRepository
{

    public function search(ArticleSearchQueryOptions $articleSearchQueryOptions): Collection
    {
        $query = $articleSearchQueryOptions->query;

        return Article::where('title', 'like', "%$query%")
            ->orWhere('content', 'like', "%$query%")
            ->get();

    }
}