<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\API\Contracts\APIController;
use App\Repositories\Contracts\AnswerSheetRepositoryInterface;
use App\Repositories\Contracts\QuizRepositoryInterface;
use Illuminate\Http\Request;

class AnswerSheetController extends APIController
{
    public function __construct(private AnswerSheetRepositoryInterface $answerSheetRepository,
                                private QuizRepositoryInterface $quizRepository)
    {
    }

    public function index(Request $request)
    {
        $this->validate($request, [
            'page' => 'required|numeric',
            'pagesize' => 'nullable|numeric',
            'search' => 'nullable|string' 
        ]);

        $answerSheets = $this->answerSheetRepository->paginate($request->search, $request->page, $request->pagesize ?? 10, [
            'quiz_id', 'answers', 'status', 'score', 'finished_at'
        ]);

        return $this->respondSuccess('آزمون ها', $answerSheets);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'quiz_id' => 'required|numeric',
            'answers' => 'required|json',
            'status' => 'required|numeric',
            'score' => 'required|numeric',
            'finished_at' => 'required|date'
        ]);

        if(!$this->quizRepository->find($request->quiz_id)){
            return $this->respondNotFound('ازمون یافت نشد');
        }   

        $createdAnswerSheet = $this->answerSheetRepository->create([
            'quiz_id' => $request->quiz_id,
            'answers' => $request->answers,
            'status' => $request->status,
            'score' => $request->score,
            'finished_at' => $request->finished_at
        ]);

        return $this->respondCreated('پاسخنامه ایجاد شد', [
            'quiz_id' => $createdAnswerSheet->getQuizId(),
            'answers' => json_encode($createdAnswerSheet->getAnswers()),
            'status' => $createdAnswerSheet->getStatus(),
            'score' => $createdAnswerSheet->getScore(),
            'finished_at' => $createdAnswerSheet->getFinishedAt()
        ]);
    }

    public function delete(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|numeric'
        ]);

        if(!$this->answerSheetRepository->find($request->id)){
            return $this->respondNotFound('پاسخنامه یافت نشد');
        }

        if(!$this->answerSheetRepository->delete($request->id)){
            return $this->respondInternalError('پاسخنامه حذف نشد');
        }

        return $this->respondSuccess('پاسخنامه حذف شد');
    }
}