<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreCategoryRequest;
use App\Http\Requests\Api\V1\UpdateCategoryRequest;
use App\Models\Category;
use App\Traits\ApiResponses;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use AuthorizesRequests;
    use ApiResponses;

    public function index(Request $request)
    {
        return Category::select(['id', 'name'])
            ->where('user_id', $request->user()->id)
            ->get();
    }

    public function store(StoreCategoryRequest $request): JsonResponse
    {

        return $this->ok('category created successfully.');
    }

    public function show(Category $category)
    {

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
