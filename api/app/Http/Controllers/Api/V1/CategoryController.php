<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\StoreCategoryRequest;
use App\Http\Requests\Api\V1\UpdateCategoryRequest;
use App\Http\Resources\Api\V1\CategoryResource;
use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CategoryController extends ApiController
{
    public function __construct(private readonly CategoryService $categoryService)
    {
        parent::__construct(Category::listRelationships());
    }

    public function index(Request $request): JsonResponse|AnonymousResourceCollection
    {
        [$values, $perPage] = $this->getValues($request);

        $list = $this->categoryService->getList(auth()->id(), $values, $perPage);
        if ($list->isEmpty()) {
            return $this->error('no categories found', 404);
        }

        return CategoryResource::collection($list);
    }

    public function store(StoreCategoryRequest $request): JsonResponse
    {

        return $this->ok('category created successfully.');
    }

    public function show(Request $request, int $categoryId): JsonResponse|CategoryResource
    {
        [$values, ] = $this->getValues($request);

        $category = $this->categoryService->getCategory($categoryId, auth()->id(), $values);
        if ($category === null) {
            return $this->error('category found', 404);
        }

        return new CategoryResource($category);
    }

    public function update(UpdateCategoryRequest $request, Category $category): JsonResponse
    {

        return $this->ok('category updated successfully.');
    }

    public function destroy(Category $category): JsonResponse
    {
        $this->authorize('delete', $category);

        $category->delete();

        return $this->ok('category deleted successfully.');
    }
}
