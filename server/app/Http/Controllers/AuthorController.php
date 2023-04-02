<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    public function index(Request $request)
    {
        $authors = Author::where('name', 'like', '%' . $request->input('q') . '%')->paginate(10);

        return response()->json($authors);
    }
}
