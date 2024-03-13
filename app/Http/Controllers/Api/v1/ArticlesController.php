<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ArticlesResource;
use App\Models\Article;
use Illuminate\Http\Request;

class ArticlesController extends Controller
{
    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return ArticlesResource::collection(Article::with(["user"])->latest()->paginate(15));
    }

//    public function store(Request $request)
//    {
//        $data = $request->validate([
//
//        ]);
//
//        return new ArticlesResource(Article::create($data));
//    }

    public function show(Article $article): ArticlesResource
    {
        return new ArticlesResource($article);
    }

//    public function update(Request $request, Article $articles)
//    {
//        $data = $request->validate([
//
//        ]);
//
//        $articles->update($data);
//
//        return new ArticlesResource($articles);
//    }

//    public function destroy(Article $articles)
//    {
//        $articles->delete();
//
//        return response()->json();
//    }
}
