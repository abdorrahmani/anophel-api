<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ArticlesResource;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use OpenApi\Annotations as OA;

class ArticlesController extends Controller
{

    /**
     * @return AnonymousResourceCollection
     *
     * @OA\Get(
     *       path="/api/v1/articles",
     *       summary="Get a list of articles",
     *       description="get a list of articles with using pagination",
     *       tags={"articles"},
     *
     *       @OA\Response(response=200, description="Successful operation"),
     *       @OA\Response(response=400, description="Invalid request")
     *   )
     *
     */
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


    /**
     * @param Article $article
     * @return ArticlesResource
     *
     * @OA\Get(
     *       path="/api/v1/articles/{id}",
     *       summary="Get a list of articles",
     *       description="get a list of articles with using pagination",
     *       tags={"articles"},
     *@OA\Parameter(
     *           name="id",
     *           in="path",
     *           description="article's id",
     *           required=true,
     *           @OA\Schema(type="integer")
     *       ),
     *       @OA\Response(response=200, description="Successful operation"),
     *       @OA\Response(response=400, description="Invalid request")
     *   )
     */
    public function show(Article $article): ArticlesResource
    {
        return response(new ArticlesResource($article->load('user')), 200);
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
