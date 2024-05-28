<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Filters\V1\CategoryFilter;
use App\Http\Requests\V1\StoreCategoryRequest;
use App\Http\Requests\V1\UpdateCategoryRequest;
use App\Http\Resources\V1\CategoryResource;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CategoryController extends ApiController
{
    public function index(CategoryFilter $filter): JsonResponse|AnonymousResourceCollection
    {
        return CategoryResource::collection(
            Category::setFilters($filter)
                ->filter()
                ->cachedPaginated()
        );
    }

    public function store(StoreCategoryRequest $request): JsonResponse
    {

        return $this->ok('category created successfully.');
    }

    public function show(CategoryFilter $filter, int $categoryId): CategoryResource
    {
        return new CategoryResource(
            Category::setFilters($filter)
                ->setIdentifier($categoryId)
                ->filter()
                ->cachedFirstOfFail()
        );
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
