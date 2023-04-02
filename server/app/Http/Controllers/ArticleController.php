<?php

namespace App\Http\Controllers;

use App\Helpers\ArticleSearchQueryOptions;
use App\Http\Resources\ArticleResource;
use App\Interfaces\ArticleSearchRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index(Request $request ,ArticleSearchRepository $articleSearchRepository)
    {
        $query = request('q') ?? '';

        $categories = collect(explode(',', request('categories')))->map(fn ($category) => (int) $category)->filter(fn ($category) => $category > 0);
        $datasources = collect(explode(',', request('datasources')))->map(fn ($datasource) => $datasource)->filter(fn ($datasource) => $datasource !== '');
        $publishedAt = collect(explode(':', request('published_at')))->filter(fn ($publishedAt) => $publishedAt !== '')->map(fn ($publishedAt) => Carbon::parse($publishedAt));
        $authors = collect(explode(',', request('authors')))->map(fn ($author) => $author)->filter(fn ($author) => $author !== '');

        //        $request->validate([
//            'q' => 'required|string',
//            'categories' => 'present|array|exists:categories,id',
//            'datasources' => 'present|array|exists:datasources,id',
//        ]);


        $articleSearchQueryOptions = new ArticleSearchQueryOptions(1, 10, $query,
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
