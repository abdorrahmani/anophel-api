<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use OpenApi\Annotations as OA;

class CategoryController extends Controller
{

    /**
     * @return AnonymousResourceCollection
     *
     * @OA\Get(
     *       path="/api/v1/article/categories",
     *       summary="Get a list of article categories",
     *       description="get a list of article categories",
     *       tags={"ArticleCategories"},
     *
     *       @OA\Response(response=200, description="Successful operation"),
     *       @OA\Response(response=400, description="Invalid request")
     *   )
     *
     */
    public function index(): AnonymousResourceCollection
    {
        return CategoryResource::collection(Category::all());
    }



}
