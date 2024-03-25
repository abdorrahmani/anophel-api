<?php

namespace App\Http\Controllers\Api\v1\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\BrandRequest;
use App\Models\Brand;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use OpenApi\Annotations as OA;

class BrandController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/v1/brands",
     *      operationId="getBrands",
     *      tags={"Brands"},
     *      summary="Get all brands",
     *      description="Returns a list of all brands.",
     *       @OA\Response(response=200, description="Successful operation"),
     *       @OA\Response(response=400, description="Invalid request")
     * )
     */
    public function index(): JsonResponse
    {
        return Response::json(['data' =>Brand::all()], 200);
    }

    /**
     * @OA\Post(
     *      path="/api/v1/brands",
     *      operationId="storeBrand",
     *      tags={"Brands"},
     *      summary="Create a new brand",
     *      description="Creates a new brand with the provided details.",
     *      @OA\RequestBody(
     *          required=true,
     *          description="Brand data",
     *          @OA\JsonContent(
     *              required={"name" ,"slug","description"},
     *              @OA\Property(property="name", type="string"),
     *              @OA\Property(property="slug", type="string"),
     *              @OA\Property(property="description", type="string"),
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Brand created successfully",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Brand created successfully")
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
    public function store(BrandRequest $request): JsonResponse
    {
        Brand::create($request->validated());
        return Response::json(['message' => 'Brand created successfully'], 201);
    }

    /**
     * @OA\Post(
     *      path="/api/v1/brands/{id}",
     *      operationId="updateBrand",
     *      tags={"Brands"},
     *      summary="Update a brand with POST method",
     *      description="Updates details of an existing brand.",
     *      @OA\Parameter(
     *          name="id",
     *          description="Brand ID",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          description="Brand data",
     *          @OA\JsonContent(
     *              required={"name","slug", "description"},
     *              @OA\Property(property="_method", type="string", example="PATCH"),
     *              @OA\Property(property="name", type="string"),
     *              @OA\Property(property="slug", type="string"),
     *              @OA\Property(property="description", type="string"),
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Brand updated successfully",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Brand updated successfully")
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
    public function update(BrandRequest $request, Brand $brand): JsonResponse
    {
        $brand->update($request->validated());
        return Response::json(['message' => 'Brand updated successfully'], 201);
    }
}
