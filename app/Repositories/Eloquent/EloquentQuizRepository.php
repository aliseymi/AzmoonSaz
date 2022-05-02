<?php

namespace App\Repositories\Eloquent;

use App\Entities\Quiz\EloquentQuizEntity;
use App\Entities\Quiz\QuizEntity;
use App\Models\Quiz;
use App\Repositories\Contracts\QuizRepositoryInterface;
use Exception;

class EloquentQuizRepository extends EloquentBaseRepository implements QuizRepositoryInterface
{
    protected $model = Quiz::class;

    public function create(array $data): QuizEntity
    {
        $createdQuiz = parent::create($data);

        return new EloquentQuizEntity($createdQuiz);
    }

    public function update(int $id, array $data): QuizEntity
    {
        if(!parent::update($id, $data)){
            throw new Exception('آزمون بروزرسانی نشد');
        }

        return new EloquentQuizEntity(parent::find($id));
    }
}