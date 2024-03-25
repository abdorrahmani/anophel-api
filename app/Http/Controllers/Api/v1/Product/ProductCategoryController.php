<?php

namespace App\Http\Controllers\Api\v1\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductCategoryRequest;
use App\Http\Resources\ProductCategoryResource;
use App\Models\ProductCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class ProductCategoryController extends Controller
{
    /**
     * @return AnonymousResourceCollection
     *
     * @OA\Get(
     *       path="/api/v1/products/categories",
     *       summary="Get a list of Product Categories",
     *       description="get a list of product Categories",
     *       tags={"Product Categories"},
     *
     *       @OA\Response(response=200, description="Successful operation"),
     *       @OA\Response(response=400, description="Invalid request")
     *   )
     *
     */
    public function index(): AnonymousResourceCollection
    {
        return ProductCategoryResource::collection(ProductCategory::all());
    }

    /**
     * @OA\Post(
     *      path="/api/v1/products/categories",
     *      operationId="storeProductCategory",
     *      tags={"Product Categories"},
     *      summary="Create a new product category",
     *      description="Creates a new product category with the provided details.",
     *      @OA\RequestBody(
     *          required=true,
     *          description="Product Category data",
     *          @OA\JsonContent(
     *              required={"name", "description", "slug"},
     *              @OA\Property(property="name", type="string", maxLength=255),
     *              @OA\Property(property="description", type="string"),
     *              @OA\Property(property="slug", type="string", maxLength=15),
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Product category created successfully",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Category created successfully")
     *          )
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Validation error",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="The given data was invalid."),
     *              @OA\Property(property="errors", type="object", example={"name": {"The name field is required."}})
     *          )
     *      ),

     * )
     */
    public function store(ProductCategoryRequest $request): JsonResponse
    {
        ProductCategory::create($request->validated());

        return response()->json(['message' => 'Category created successfully'], 201);
    }

    /**
     * @OA\Get(
     *      path="/api/v1/products/categories/{id}",
     *      operationId="showProductCategories",
     *      tags={"Product Categories"},
     *      summary="Get a single category",
     *      description="Retrieves details of a single category.",
     *      @OA\Parameter(
     *          name="id",
     *          description="Category ID",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", example={"name": "name"} ),
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Category not found",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Category not found"),
     *          )
     *      ),
     * )
     */
    public function show(ProductCategory $category): JsonResponse
    {
        return response()->json(['data' => new ProductCategoryResource($category)], ResponseAlias::HTTP_OK);
    }

    /**
     * @OA\Post(
     *      path="/api/v1/products/categories/{id}",
     *      operationId="updateProductCategory",
     *      tags={"Product Categories"},
     *      summary="Update a product category",
     *      description="Updates details of an existing product category.",
     *      @OA\Parameter(
     *          name="id",
     *          description="Product Category ID",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          description="Product Category data",
     *          @OA\JsonContent(
     *              required={"name", "description", "slug"},
     *              @OA\Property(property="_method", type="string", example="PATCH"),
     *              @OA\Property(property="name", type="string", maxLength=255),
     *              @OA\Property(property="description", type="string"),
     *              @OA\Property(property="slug", type="string", maxLength=15),
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Category updated successfully",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Category updated successfully")
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Category not found",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Category not found")
     *          )
     *      ),
     * )
     */
    public function update(ProductCategoryRequest $request, ProductCategory $category): JsonResponse
    {
        $category->update($request->validated());

        // Return a success response
        return response()->json(['message' => 'Category updated successfully'], 200);
    }

    public function destroy(ProductCategory $category)
    {
//        $productCategory->delete();

        return response()->json();
    }
}
