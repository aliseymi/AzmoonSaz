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

    /**
     * @OA\Get(
     *   description="Returns all categories",
     *   tags={"categories"},
     *   path="/api/v1/categories",
     *   
     *   @OA\Parameter(
     *     name="search",
     *     in="path",
     *     description="By passing this parameter you can filter the result",
     *     required=false,
     *     @OA\Schema(type="string")
     *   ),
     *   
     *   @OA\Parameter(
     *     name="page",
     *     in="path",
     *     description="By passing this param you can get the result of the page",
     *     required=true,
     *     @OA\Schema(type="numeric")
     *   ),
     *   
     *   @OA\Parameter(
     *     name="pagesize",
     *     in="path",
     *     description="By passing this param you choose the size of the page",
     *     required=false,
     *     @OA\Schema(type="numeric")
     *   ),
     *   
     *   @OA\Response(
     *     response=200,
     *     description="OK",
     *     @OA\JsonContent(
     *       @OA\Property(property="success", type="boolean", example="true"),
     *       
     *       @OA\Property(property="message", type="string", example="دسته‌بندی‌ها"),
     *       
     *       @OA\Property(
     *         property="data",
     *         type="array",
     *         @OA\Items(
     *           @OA\Property(property="name", type="string", example="category 1"),
     *           @OA\Property(property="slug", type="string", example="category-1"),
     *         )
     *       )
     *     )
     *   )
     * )
     */

    public function index(Request $request)
    {
        $this->validate($request, [
            'search' => 'nullable|string',
            'page' => 'required|numeric',
            'pagesize' => 'nullable|numeric'
        ]);

        $categories = $this->categoryRepository->paginate($request->search, $request->page, $request->pagesize ?? 10, [
            'name', 'slug'
        ]);

        return $this->respondSuccess('دسته‌بندی‌ها', $categories);
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