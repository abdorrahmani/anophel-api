<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ArticleRequest;
use App\Http\Resources\ArticlesResource;
use App\Models\Article;
use Illuminate\Http\JsonResponse;
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
     *       tags={"Articles"},
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  ArticleRequest  $request
     * @return JsonResponse
     *
     * @OA\Post(
     *     path="/api/v1/articles",
     *     tags={"Articles"},
     *     summary="Create a new article",
     *     operationId="storeArticle",
     *     @OA\RequestBody(
     *         required=true,
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  @OA\Property(property="title", type="string", example="Sample Title"),
     *                  @OA\Property(property="slug", type="string", example="sample-title"),
     *                  @OA\Property(property="poster", type="string", format="binary"),
     *                  @OA\Property(property="body", type="string", example="This is a sample article body."),
     *                  @OA\Property(property="category_id", type="integer", example=1),
     *                  @OA\Property(property="token", type="string", example="YOUR_JWT_TOKEN_HERE")
     *              )
     *          )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Article created successfully",
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(property="errors", type="object", example={"title": {"The title field is required."}})
     *         )
     *     ),
     *     security={{"bearerAuth": {}}}
     * )
     *
     * @OA\SecurityScheme(
     *      securityScheme="bearerAuth",
     *      type="http",
     *      scheme="bearer",
     *      bearerFormat="JWT"
     *  )
     */
    public function store(ArticleRequest $request): JsonResponse
    {
        $validatedData = $request->validated();

        // Handle file upload for the article's poster
        $posterPath = $request->file('poster')->store('posters', 'public');

        // Create the article
        $article = Article::create([
            'user_id' => auth()->user()->id, // Assuming user is authenticated
            'title' => $validatedData['title'],
            'slug' => $validatedData['slug'],
            'poster' => $posterPath,
            'body' => $validatedData['body'],
        ]);

        // Attach categories to the article
        $article->categories()->attach($validatedData['category_id']);

        // Return a success response
        return response()->json(['message' => 'Article created successfully'], 201);
    }


    /**
     * @param Article $article
     * @return ArticlesResource
     *
     * @OA\Get(
     *       path="/api/v1/articles/{id}",
     *       summary="Get a list of articles",
     *       description="get a list of articles with using pagination",
     *       tags={"Articles"},
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
