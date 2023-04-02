<?php

namespace App\Http\Controllers;

use App\Models\Datasource;
use Illuminate\Http\Request;

class DatasourceController extends Controller
{
    public function index(Request $request)
    {
        $datasources = Datasource::where('name', 'like', '%' . $request->input('q') . '%')->get();

        return response()->json([
            'data' => $datasources,
        ]);
    }
}
