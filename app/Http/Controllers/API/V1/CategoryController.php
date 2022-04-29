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

    public function delete(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|numeric'
        ]);

        if(!$this->categoryRepository->find($request->id)){
            return $this->respondNotFound('دسته‌بندی وجود ندارد');
        }

        if(!$this->categoryRepository->delete($request->id)){
            return $this->respondInternalError('خطایی وجود دارد لطفا مجددا تلاش کنید');
        }

        return $this->respondSuccess('دسته‌بندی با موفقیت حذف شد');
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|numeric',
            'name' => 'required|string|min:3|max:255',
            'slug' => 'required|string|min:3|max:255'
        ]);

        try {
            $updatedCategory = $this->categoryRepository->update($request->id, [
                'name' => $request->name,
                'slug' => $request->slug
            ]);
        } catch (\Exception $e) {
            return $this->respondInternalError('دسته‌بندی بروزرسانی نشد');
        }

        return $this->respondSuccess('دسته‌بندی با موفقیت بروزرسانی شد', [
            'name' => $updatedCategory->getName(),
            'slug' => $updatedCategory->getSlug()
        ]);
    }
}