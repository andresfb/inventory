<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\V1\StoreCategoryRequest;
use App\Http\Requests\V1\UpdateCategoryRequest;
use App\Http\Resources\V1\CategoryResource;
use App\Models\Category;
use App\Services\CategoryService;
use App\Validators\V1\CategoryValidator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CategoryController extends ApiController
{
    public function __construct(private readonly CategoryService $categoryService)
    {
        parent::__construct(Category::listRelationships());
    }

    public function index(CategoryValidator $validator): JsonResponse|AnonymousResourceCollection
    {
        return CategoryResource::collection(
            $this->categoryService->getList($validator)
        );
    }

    public function store(StoreCategoryRequest $request): JsonResponse
    {

        return $this->ok('category created successfully.');
    }

    public function show(CategoryValidator $validator, int $categoryId): JsonResponse|CategoryResource
    {
        $category = $this->categoryService->getCategory($categoryId, $validator);
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
