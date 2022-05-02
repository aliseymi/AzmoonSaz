<?php

namespace App\Repositories\Eloquent;

use App\Entities\Question\EloquentQuestionEntity;
use App\Entities\Question\QuestionEntity;
use App\Models\Question;
use App\Repositories\Contracts\QuestionRepositoryInterface;

class EloquentQuestionRepository extends EloquentBaseRepository implements QuestionRepositoryInterface
{
    protected $model = Question::class;

    public function create(array $data): QuestionEntity
    {
        $createdQuestion = parent::create($data);

        return new EloquentQuestionEntity($createdQuestion);
    }
}