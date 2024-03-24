<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductCategoryRequest;
use App\Http\Resources\ProductCategoryResource;
use App\Models\ProductCategory;

class ProductCategoryController extends Controller
{
    public function index()
    {
        return ProductCategoryResource::collection(ProductCategory::all());
    }

    public function store(ProductCategoryRequest $request)
    {
        return new ProductCategoryResource(ProductCategory::create($request->validated()));
    }

    public function show(ProductCategory $productCategory)
    {
        return new ProductCategoryResource($productCategory);
    }

    public function update(ProductCategoryRequest $request, ProductCategory $productCategory)
    {
        $productCategory->update($request->validated());

        return new ProductCategoryResource($productCategory);
    }

    public function destroy(ProductCategory $productCategory)
    {
        $productCategory->delete();

        return response()->json();
    }
}
