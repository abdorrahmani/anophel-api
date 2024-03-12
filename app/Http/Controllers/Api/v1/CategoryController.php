<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\categoryResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        return categoryResource::collection(Category::all());
    }

    public function store(Request $request)
    {
        $data = $request->validate([

        ]);

        return new categoryResource(Category::create($data));
    }

    public function show(category $category)
    {
        return new categoryResource($category);
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate([

        ]);

        $category->update($data);

        return new categoryResource($category);
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return response()->json();
    }
}
