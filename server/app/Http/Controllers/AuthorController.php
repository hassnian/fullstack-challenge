<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    public function index(Request $request)
    {
        $authors = Author::where('name', 'like', '%' . $request->input('q') . '%')->get();

        return response()->json([
            'data' => $authors,
        ]);
    }
}
