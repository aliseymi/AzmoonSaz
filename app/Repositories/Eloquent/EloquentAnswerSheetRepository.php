<?php

namespace App\Repositories\Eloquent;

use App\Entities\AnswerSheet\AnswerSheetEntity;
use App\Entities\AnswerSheet\EloquentAnswerSheetEntity;
use App\Models\AnswerSheet;
use App\Repositories\Contracts\AnswerSheetRepositoryInterface;

class EloquentAnswerSheetRepository extends EloquentBaseRepository implements AnswerSheetRepositoryInterface
{
    protected $model = AnswerSheet::class;

    public function create(array $data): AnswerSheetEntity
    {
        $createdAnswerSheet = parent::create($data);

        return new EloquentAnswerSheetEntity($createdAnswerSheet);
    }
}