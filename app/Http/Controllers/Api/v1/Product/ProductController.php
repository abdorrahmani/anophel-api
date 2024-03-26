<?php

namespace App\Http\Controllers\Api\v1\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ArticlesResource;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\ProductFeature;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use JsonException;
use OpenApi\Annotations as OA;

class ProductController extends Controller
{
    /**
     * @return AnonymousResourceCollection
     *
     * @OA\Get(
     *       path="/api/v1/products",
     *       summary="Get a list of products",
     *       description="get a list of products",
     *       tags={"Products"},
     *
     *       @OA\Response(response=200, description="Successful operation"),
     *       @OA\Response(response=400, description="Invalid request")
     *   )
     *
     */
    public function index(): AnonymousResourceCollection
    {
        return ProductResource::collection(Product::with(['category' , 'brand','features.product_features'])->get());
    }

    /**
     * @OA\Post(
     *      path="/api/v1/products",
     *      operationId="storeProduct",
     *      tags={"Products"},
     *      summary="Create a new product",
     *      description="Creates a new product with the provided details.",
     *     @OA\RequestBody(
     *         required=true,
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="description", type="string"),
     *                 @OA\Property(property="slug", type="string", maxLength=15),
     *                 @OA\Property(property="price", type="number", format="float"),
     *                 @OA\Property(property="image", type="string", format="binary"),
     *                 @OA\Property(property="category_id", type="integer"),
     *                 @OA\Property(property="brand_id", type="integer"),
     *               @OA\Property(
     *                      property="features",
     *                      type="array",
     *                      @OA\Items(
     *                          type="object",
     *                          @OA\Property(
     *                              property="feature_id",
     *                              type="integer",
     *                              description="ID of the feature"
     *                          ),
     *                          @OA\Property(
     *                              property="value",
     *                              type="string",
     *                              description="Value of the feature"
     *                          )
     *                      ),
     *                      description="Array of product features (optional)"
     *                  ),
     *              )
     *          )
     *     ),
     *      @OA\Response(
     *          response=201,
     *          description="Product created successfully",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Product created successfully")
     *          )
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Validation error",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="The given data was invalid."),
     *              @OA\Property(property="errors", type="object", example={"title": {"The title field is required."}} )
     *          )
     *      )
     * )
     * @throws JsonException
     */
    public function store(ProductRequest $request): JsonResponse
    {
        $validatedData = $request->validated();

        // Handle image upload
        if ($request->hasFile('image')) {
            // Get the uploaded file
            $image = $request->file('image');

            // Upload image and get filename
            $filename = $this->uploadImage($image);

            // Add the filename to the validated data
            $validatedData['image'] = $filename;
        }

        // Create the product
        $product = Product::create($validatedData);

        // Sync features if provided
        if ($request->has('features')) {
            foreach (json_decode($request->features, true, 512, JSON_THROW_ON_ERROR) as $feature) {
                $productFeature = new ProductFeature();
                $productFeature->product_id = $product->id;
                $productFeature->feature_id = $feature['feature_id'];
                $productFeature->value = $feature['value'];
                $productFeature->save();
            }
        }

        return response()->json(['message' => 'Product created successfully'], 201);
    }

    /**
     * @OA\Get(
     *      path="/api/v1/products/{id}",
     *      operationId="showProduct",
     *      tags={"Products"},
     *      summary="Get a single product",
     *      description="Retrieves details of a single product.",
     *      @OA\Parameter(
     *          name="id",
     *          description="Product ID",
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
     *          description="Product not found",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Product not found"),
     *          )
     *      ),
     * )
     */
    public function show(Product $product): Response
    {
        return response(['data' => new ProductResource($product->load(['category', 'features.product_features','brand']))], 200);
    }

    /**
     * @OA\Post(
     *      path="/api/v1/products/{id}",
     *      operationId="updateProduct",
     *      tags={"Products"},
     *      summary="Update a product",
     *      description="Updates details of an existing product.",
     *      @OA\Parameter(
     *          name="id",
     *          description="Product ID",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                @OA\Property(property="_method", type="string", example="PATCH"),
     *                @OA\Property(property="name", type="string"),
     *                @OA\Property(property="description", type="string"),
     *                @OA\Property(property="slug", type="string", maxLength=15),
     *                @OA\Property(property="price", type="number", format="float"),
     *                @OA\Property(property="image", type="string", format="binary"),
     *                @OA\Property(property="category_id", type="integer"),
     *                @OA\Property(property="brand_id", type="integer"),
     *                @OA\Property(
     *                      property="features",
     *                      type="string",
     *                      description="JSON string representing an array of features. Example: [{""feature_id"": 1, ""value"": ""string""}]"
     *                  ),
     *
     *             )
     *         )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Product updated successfully",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Product updated successfully")
     *          )
     *      ),
     *          @OA\Response(
     *          response=400,
     *          description="Invalid JSON format",
     *          @OA\JsonContent(
     *              @OA\Property(property="error", type="string", example="Invalid JSON format for features")
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Product not found",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Product not found")
     *          )
     *      ),
     * )
     * @throws JsonException
     */
    public function update(ProductRequest $request, Product $product): JsonResponse
    {
        $validatedData = $request->validated();

        // Handle image upload
        if ($request->hasFile('image')) {
            // Get the uploaded file
            $image = $request->file('image');

            // Upload image and get filename
            $filename = $this->uploadImage($image, $product->image);

            // Update the image filename in the validated data
            $validatedData['image'] = $filename;
        }
        // Update the product with validated data

        $product->update($validatedData);

        // Sync features if provided
        if ($request->has('features')) {
            // Decode JSON string for features into an array
            $featuresArray = json_decode($request->features, true, 512, JSON_THROW_ON_ERROR);
            if ($featuresArray !== null) {
                foreach ($featuresArray as $feature) {
                    $productFeature = ProductFeature::whereProductId($product->id)
                        ->whereFeatureId($feature['feature_id'])
                        ->first();
                    $productFeature->update(
                        ['feature_id' => $feature['feature_id'], 'value' => $feature['value']]
                    );
                }
            } else {
                // Handle invalid JSON format
                return response()->json(['error' => 'Invalid JSON format for features'], 400);
            }
        }

        // Return a success response
        return response()->json(['message' => 'Product updated successfully'], 200);
    }

    public function destroy(Product $product): JsonResponse
    {
        //        $product->delete();

        return response()->json( 'Product deleted successfully',204);
    }

    // Function to handle image upload and filename generation
    private function uploadImage(UploadedFile $image, ?string $existingFilename = null): string
    {
        // Generate a unique filename
        $filename = uniqid('', true) . '.' . $image->getClientOriginalExtension();

        // Store the image in the storage folder
        Storage::disk('public')->putFileAs('images', $image, $filename);

        // Delete the previous image if exists
        if ($existingFilename) {
            Storage::disk('public')->delete('images/' . $existingFilename);
        }

        return $filename;
    }

}
