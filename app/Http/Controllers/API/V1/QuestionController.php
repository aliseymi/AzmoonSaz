<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\API\Contracts\APIController;
use App\Repositories\Contracts\QuestionRepositoryInterface;
use App\Repositories\Contracts\QuizRepositoryInterface;
use Illuminate\Http\Request;

class QuestionController extends APIController
{
    public function __construct(private QuestionRepositoryInterface $questionRepository,
                                private QuizRepositoryInterface $quizRepository)
    {
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|string',
            'options' => 'required|json',
            'score' => 'required|numeric',
            'is_active' => 'required|numeric',
            'quiz_id' => 'required|numeric'
        ]);

        if(!$this->quizRepository->find($request->quiz_id)){
            return $this->respondForbidden('آزمون یافت نشد');
        }

        $question = $this->questionRepository->create([
            'title' => $request->title,
            'options' => $request->options,
            'score' => $request->score,
            'is_active' => $request->is_active,
            'quiz_id' => $request->quiz_id
        ]);

        return $this->respondCreated('سوال ایجاد شد', [
            'title' => $question->getTitle(),
            'options' => json_encode($question->getOptions()),
            'score' => $question->getScore(),
            'is_active' => $question->getIsActive(),
            'quiz_id' => $question->getQuizId()
        ]);
    }

    public function delete(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|numeric'
        ]);

        if(!$this->questionRepository->find($request->id)){
            return $this->respondNotFound('آزمون یافت نشد');
        }

        if(!$this->questionRepository->delete($request->id)){
            return $this->respondInternalError('آزمون حذف نشد');
        }

        return $this->respondSuccess('آزمون حذف شد');
    }
}