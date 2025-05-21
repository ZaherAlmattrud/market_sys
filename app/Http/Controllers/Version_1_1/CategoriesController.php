<?php

namespace App\Http\Controllers\Version_1_1;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    //


    public function getAllCategoriesForList(){


        $categores = Category::all(['id', 'name']);
        return response()->json($categores);

    }

    public function getAll()
    {
        $data = [];
        return response()->json($data);
    }

    public function get($id)
    {

        $data = [];
        return response()->json($data);
    }

    public function create(Request $request)
    {

        $data = [];
        return response()->json($data);
    }

    public function update(Request $request, $id)
    {

        $data = [];
        return response()->json($data);
    }

    public function delete($id)
    {

        $data = [];
        return response()->json($data);
    }
}
