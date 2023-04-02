<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::where('name', 'like', '%' . $request->input('q') . '%')->get();

        return response()->json([
            'data' => $categories,
        ]);
    }
}
