<?php

namespace App\Http\Controllers;

use App\Helpers\ArticleSearchQueryOptions;
use App\Helpers\Enums\ArticleDatasourceType;
use App\Http\Resources\ArticleResource;
use App\Interfaces\ArticleSearchRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ArticleController extends Controller
{
    public function index(Request $request ,ArticleSearchRepository $articleSearchRepository)
    {
        $query = request('q') ?? '';

        $categories = collect(explode(',', request('categories')))->map(fn ($category) => (int) $category)->filter(fn ($category) => $category > 0);
        $datasources = collect(explode(',', request('datasources')))->map(fn ($datasource) => $datasource)->filter(fn ($datasource) => $datasource !== '');
        $publishedAt = collect(explode(':', request('published_at')))->filter(fn ($publishedAt) => $publishedAt !== '')->map(fn ($publishedAt) => Carbon::parse($publishedAt));
        $authors = collect(explode(',', request('authors')))->map(fn ($author) => $author)->filter(fn ($author) => $author !== '');
        $page = request('page') ?? 1;
        $perPage = request('per_page') ?? 10;

       $request->validate([
            'q' => 'nullable|string',
            'categories' => 'exists:categories,id',
            'datasources' => ['nullable', Rule::in([
                ArticleDatasourceType::NEW_YORK_TIMES,
                ArticleDatasourceType::THE_GUARDIAN,
                ArticleDatasourceType::NEW_YORK_TIMES,
            ])],
            'authors' => 'exists:authors,id',
            'page' => 'nullable|integer',
            'per_page' => 'nullable|integer'
        ]);


        $articleSearchQueryOptions = new ArticleSearchQueryOptions($page, $perPage, $query,
            $publishedAt->toArray(),
            $categories->toArray(),
            $datasources->toArray(),
            $authors->toArray()
        );

        $articles = $articleSearchRepository->search($articleSearchQueryOptions);

        return response()->json([
                'status' => 'success',
                'data' => ArticleResource::collection($articles)
            ]);
    }
}
