<?php

namespace App\Http\Controllers;

use App\Models\Datasource;
use Illuminate\Http\Request;

class DatasourceController extends Controller
{
    public function index(Request $request)
    {
        $datasources = Datasource::where('name', 'like', '%' . $request->input('q') . '%')->paginate(10);

        return response()->json($datasources);
    }
}
