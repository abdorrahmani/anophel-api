<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ArticleRequest;
use App\Http\Resources\ArticlesResource;
use App\Models\Article;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
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
            'user_id' => auth()->id(), // Assuming user is authenticated
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
     *
     * @return Application|ResponseFactory|\Illuminate\Foundation\Application|Response
     * @OA\Get(
     *       path="/api/v1/articles/{article}",
     *       summary="Get a list of articles",
     *       description="get a list of articles with using pagination",
     *       tags={"Articles"},
     *@OA\Parameter(
     *           name="article",
     *           in="path",
     *           description="article's id",
     *           required=true,
     *           @OA\Schema(type="integer")
     *       ),
     *       @OA\Response(response=200, description="Successful operation"),
     *       @OA\Response(response=400, description="Invalid request")
     *   )
     */
    public function show(Article $article)
    {
        return response(new ArticlesResource($article->load('user')), 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  ArticleRequest  $request
     * @param  Article  $article
     * @return JsonResponse
     *
     * @OA\Post(
     *     path="/api/v1/articles/{article}",
     *     tags={"Articles"},
     *     summary="Update an existing article",
     *     operationId="updateArticle",
     *     @OA\Parameter(
     *         name="article",
     *         in="path",
     *         description="ID of the article to update",
     *         required=true,
     *        @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="_method", type="string", example="PATCH"),
     *                 @OA\Property(property="title", type="string", example="Updated Title"),
     *                 @OA\Property(property="slug", type="string", example="updated-title"),
     *                 @OA\Property(property="poster", type="string", format="binary"),
     *                 @OA\Property(property="body", type="string", example="This is an updated article body."),
     *                 @OA\Property(property="category_id", type="integer", example=1),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Article updated successfully",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Article not found",
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
 */

    public function update(ArticleRequest $request, Article $article): JsonResponse
    {
        // Validate the incoming request data
        $validatedData = $request->validated();
        // Update the article's data
        if ($request->hasFile('poster')) {
            // Handle file upload for the article's poster
            $posterPath = $request->file('poster')->store('posters', 'public');
            // Update poster path if a new poster is uploaded
            $article->poster = $posterPath;
        }

        $article->title = $validatedData['title'];
        $article->slug = $validatedData['slug'];
        $article->body = $validatedData['body'];
        $article->save();

        // Sync categories with the article
        $article->categories()->sync($validatedData['category_id']);

        // Return a success response
        return response()->json(['message' => 'Article updated successfully'], 200);
    }

    public function destroy(Article $articles): JsonResponse
    {
        return response()->json($articles);
    }
}
