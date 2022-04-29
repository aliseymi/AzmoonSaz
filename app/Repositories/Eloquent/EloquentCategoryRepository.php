<?php

namespace App\Repositories\Eloquent;

use App\Entities\Category\CategoryEntity;
use App\Entities\Category\EloquentCategoryEntity;
use App\Models\Category;
use App\Repositories\Contracts\CategoryRepositoryInterface;

class EloquentCategoryRepository extends EloquentBaseRepository implements CategoryRepositoryInterface
{
    protected $model = Category::class;

    public function create(array $data): CategoryEntity
    {
        $createdCategory = parent::create($data);

        return new EloquentCategoryEntity($createdCategory);
    }
}