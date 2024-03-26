<?php

namespace App\Http\Controllers\Api\v1\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\FeatureRequest;
use App\Models\Feature;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class FeatureController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/v1/features",
     *      operationId="getFeatures",
     *      tags={"Features"},
     *      summary="Get all features",
     *      description="Returns a list of all features.",
     *       @OA\Response(response=200, description="Successful operation"),
     *       @OA\Response(response=400, description="Invalid request")
     * )
     */
    public function index(): JsonResponse
    {
        return Response::json(Feature::all());
    }

    /**
     * @OA\Post(
     *      path="/api/v1/features",
     *      operationId="storeBrand",
     *      tags={"Features"},
     *      summary="Create a new features",
     *      description="Creates a new features with the provided details.",
     *      @OA\RequestBody(
     *          required=true,
     *          description="features data",
     *          @OA\JsonContent(
     *              required={"name","description"},
     *              @OA\Property(property="name", type="string"),
     *              @OA\Property(property="type", type="string"),
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Feature created successfully",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Feature created successfully")
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
    public function store(FeatureRequest $request): JsonResponse
    {
        Feature::create($request->validated());

        return Response::json(['message' => 'Feature created successfully'], 201);
    }

    /**
     * @OA\Get(
     *      path="/api/v1/features/{id}",
     *      operationId="showFeatures",
     *      tags={"Features"},
     *      summary="Get a single feature",
     *      description="Retrieves details of a single feature.",
     *      @OA\Parameter(
     *          name="id",
     *          description="Feature ID",
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
     *          description="Feature not found",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Features not found"),
     *          )
     *      ),
     * )
     */
    public function show(Feature $feature): JsonResponse
    {
        return response()->json(['data' => $feature], ResponseAlias::HTTP_OK);
    }

    /**
     * @OA\Post(
     *      path="/api/v1/features/{id}",
     *      operationId="updateFeature",
     *      tags={"Features"},
     *      summary="Update a features",
     *      description="Updates details of an existing feature.",
     *      @OA\Parameter(
     *          name="id",
     *          description="feature ID",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          description="feature data",
     *          @OA\JsonContent(
     *              required={"name","type"},
     *              @OA\Property(property="_method", type="string", example="PATCH"),
     *              @OA\Property(property="name", type="string"),
     *              @OA\Property(property="type", type="string"),
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Feature updated successfully",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Feature updated successfully")
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

    public function update(FeatureRequest $request, Feature $feature): JsonResponse
    {
        $feature->update($request->validated());
        return Response::json(['message' => 'Feature updated successfully'], 200);
    }

}
