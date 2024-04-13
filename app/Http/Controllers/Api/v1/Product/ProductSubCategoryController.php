<?php

namespace App\Http\Controllers\Api\v1\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductSubCategoryRequest;
use App\Http\Resources\ProductSubCategoryResource;
use App\Models\ProductSubCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class ProductSubCategoryController extends Controller
{
    /**
     * @return AnonymousResourceCollection
     *
     * @OA\Get(
     *       path="/api/v1/products/sub-categories",
     *       summary="Get a list of Product sub categories",
     *       description="get a list of product sub categories",
     *       tags={"Product SubCategories"},
     *
     *       @OA\Response(response=200, description="Successful operation"),
     *       @OA\Response(response=400, description="Invalid request")
     *   )
     *
     */
    public function index(): AnonymousResourceCollection
    {
        return ProductSubCategoryResource::collection(ProductSubCategory::latest()->get());
    }

    /**
     * @OA\Post(
     *      path="/api/v1/products/sub-categories",
     *      operationId="storeProductSubCategory",
     *      tags={"Product SubCategories"},
     *      summary="Create a new product sub category",
     *      description="Creates a new product sub category with the provided details.",
     *      @OA\RequestBody(
     *          required=true,
     *          description="Product sub Category data",
     *          @OA\JsonContent(
     *              required={"name", "category_id", "slug"},
     *              @OA\Property(property="name", type="string", maxLength=100),
     *              @OA\Property(property="category_id", type="string",example="1"),
     *              @OA\Property(property="slug", type="string", maxLength=100),
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Product SubCategories created successfully",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="SubCategories created successfully")
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
    public function store(ProductSubCategoryRequest $request): JsonResponse
    {
        ProductSubCategory::create($request->validated());

        return response()->json(['message' => 'SubCategory created successfully'], 201);
    }

    /**
     * @OA\Get(
     *      path="/api/v1/products/sub-categories/{id}",
     *      operationId="showProductSubCategories",
     *      tags={"Product SubCategories"},
     *      summary="Get a single SubCategories",
     *      description="Retrieves details of a single sub categories.",
     *      @OA\Parameter(
     *          name="id",
     *          description="Sub Category ID",
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
     *          description="SubCategory not found",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="SubCategory not found"),
     *          )
     *      ),
     * )
     */
    public function show(ProductSubCategory $subCategory): JsonResponse
    {
        return response()->json(['data' => new ProductSubCategoryResource($subCategory)], ResponseAlias::HTTP_OK);
    }

    /**
     * @OA\Post(
     *      path="/api/v1/products/sub-categories/{id}",
     *      operationId="updateProductSubCategory",
     *      tags={"Product SubCategories"},
     *      summary="Update a product sub category",
     *      description="Updates details of an existing product sub category.",
     *      @OA\Parameter(
     *          name="id",
     *          description="Product sub Category ID",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          description="Product sub Category data",
     *          @OA\JsonContent(
     *              required={"name", "category_id", "slug"},
     *              @OA\Property(property="_method", type="string", example="PATCH"),
     *              @OA\Property(property="name", type="string", maxLength=255),
     *              @OA\Property(property="category_id", type="string"),
     *              @OA\Property(property="slug", type="string", maxLength=15),
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Sub Category updated successfully",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Sub Category updated successfully")
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Sub Category not found",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Sub Category not found")
     *          )
     *      ),
     * )
     */
    public function update(ProductSubCategoryRequest $request, ProductSubCategory $subCategory): JsonResponse
    {
        $subCategory->update($request->validated());

        return response()->json(['message' => 'Sub category updated successfully'], 200);
    }

    public function destroy(): JsonResponse
    {
        return response()->json();
    }
}
