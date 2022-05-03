<?php

namespace App\Repositories\Eloquent;

use App\Entities\Question\EloquentQuestionEntity;
use App\Entities\Question\QuestionEntity;
use App\Models\Question;
use App\Repositories\Contracts\QuestionRepositoryInterface;
use Exception;

class EloquentQuestionRepository extends EloquentBaseRepository implements QuestionRepositoryInterface
{
    protected $model = Question::class;

    public function create(array $data): QuestionEntity
    {
        $createdQuestion = parent::create($data);

        return new EloquentQuestionEntity($createdQuestion);
    }

    public function update(int $id, array $data): QuestionEntity
    {
        if(!parent::update($id, $data)){
            throw new Exception('سوال بروزرسانی نشد');
        }

        return new EloquentQuestionEntity(parent::find($id));
    }
}