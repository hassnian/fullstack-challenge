<?php

namespace App\Http\Controllers;

use App\Helpers\ArticleSearchQueryOptions;
use App\Http\Resources\ArticleResource;
use App\Interfaces\ArticleSearchRepository;
use App\Models\User;
use Illuminate\Http\Request;

class UserPreferenceController extends Controller
{
    public function index(Request $request)
    {
        /** @var User $user */
        $user = $request->user();

        $userCategories = $user->categories()->get();
        $userAuthors = $user->authors()->get();
        $userSources = $user->datasources()->get();

        return response()->json([
            // TODO add resources
            'data' => [
                'categories' => $userCategories,
                'authors' => $userAuthors,
                'datasources' => $userSources,
            ],
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'categories' => 'present|array|exists:categories,id',
            'authors' => 'present|array|exists:authors,id',
            'datasources' => 'present|array|exists:datasources,id',
        ]);

        /** @var User $user */
        $user = $request->user();

        $user->categories()->sync($request->categories);
        $user->authors()->sync($request->authors);
        $user->datasources()->sync($request->datasources);


        return response()->json([
            'message' => 'User preferences updated',
        ]);
    }

    public function getUserFeed(ArticleSearchRepository $articleSearchRepository)
    {
        $user = auth()->user();

        $categories = $user->categories()->get()->pluck('id')->toArray();
        $authors = $user->authors()->get()->pluck('id')->toArray();
        $datasources = $user->datasources()->get()->pluck('datasource_id')->toArray();
        $page = request('page') ?? 1;
        $perPage = request('per_page') ?? 10;

        $articleSearchQueryOptions = new ArticleSearchQueryOptions(
            $page,
            $perPage,
            null,
            null,
            $categories,
            $datasources,
            $authors,
            false
        );

        $articles = $articleSearchRepository->search($articleSearchQueryOptions);

        return ArticleResource::collection($articles);
    }
}
