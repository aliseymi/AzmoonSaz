<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\API\Contracts\APIController;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use Illuminate\Http\Request;

class CategoryController extends APIController
{
    public function __construct(private CategoryRepositoryInterface $categoryRepository)
    {
        
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|min:3|max:255',
            'slug' => 'required|string|min:3|max:255'
        ]);

        $createdCategory = $this->categoryRepository->create([
            'name' => $request->name,
            'slug' => $request->slug
        ]);

        return $this->respondCreated('دسته‌بندی ایجاد شد', [
            'name' => $createdCategory->getName(),
            'slug' => $createdCategory->getSlug()
        ]);
    }
}