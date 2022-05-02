<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\API\Contracts\APIController;
use App\Repositories\Contracts\QuizRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;

class QuizController extends APIController
{
    public function __construct(private QuizRepositoryInterface $quizRepository)
    {
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'category_id' => 'required|numeric',
            'title' => 'required|string',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'duration' => 'required|date'
        ]);
        
        $start_date = Carbon::parse($request->start_date);

        $duration = Carbon::parse($request->duration);


        if($start_date->timestamp > $duration->timestamp){
            return $this->respondInvalidValidation('مدت زمان آزمون باید زمانی بزرگ تر از تاریخ شروع آزمون باشد');
        }

        $createdQuiz = $this->quizRepository->create([
            'category_id' => $request->category_id,
            'title' => $request->title,
            'description' => $request->description,
            'start_date' => $start_date,
            'duration' => $duration
        ]);

        return $this->respondCreated('آزمون با موفقیت ایجاد شد', [
            'category_id' => $createdQuiz->getCategoryId(),
            'title' => $createdQuiz->getTitle(),
            'description' => $createdQuiz->getDescription(),
            'start_date' => $createdQuiz->getStartDate(),
            'duration' => $createdQuiz->getDuration()
        ]);
    }

    public function delete(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|numeric'
        ]);

        if(!$this->quizRepository->find($request->id)){
            return $this->respondNotFound('آزمون یافت نشد');
        }

        if(!$this->quizRepository->delete($request->id)){
            return $this->respondInternalError('آزمون حذف نشد');
        }

        return $this->respondSuccess('آزمون حذف شد');
    }
}