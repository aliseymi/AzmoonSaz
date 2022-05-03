<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\API\Contracts\APIController;
use App\Repositories\Contracts\QuizRepositoryInterface;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class QuizController extends APIController
{
    public function __construct(private QuizRepositoryInterface $quizRepository)
    {
    }

    public function index(Request $request)
    {
        $this->validate($request, [
            'search' => 'nullable|string',
            'page' => 'required|numeric',
            'pagesize' => 'nullable|numeric'
        ]);

        $quizzes = $this->quizRepository->paginate($request->search, $request->page, $request->pagesize ?? 10, ['title', 'description', 'start_date', 'duration']);

        return $this->respondSuccess('آزمون ها', $quizzes);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'category_id' => 'required|numeric|exists:categories,id',
            'title' => 'required|string',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'duration' => 'required|date',
            'is_active' => 'required|boolean'
        ]);

        $start_date = Carbon::parse($request->start_date);

        $duration = Carbon::parse($request->duration);


        if ($start_date->timestamp > $duration->timestamp) {
            return $this->respondInvalidValidation('مدت زمان آزمون باید زمانی بزرگ تر از تاریخ شروع آزمون باشد');
        }

        $createdQuiz = $this->quizRepository->create([
            'category_id' => $request->category_id,
            'title' => $request->title,
            'description' => $request->description,
            'start_date' => $start_date,
            'duration' => $duration,
            'is_active' => $request->is_active
        ]);

        return $this->respondCreated('آزمون با موفقیت ایجاد شد', [
            'category_id' => $createdQuiz->getCategoryId(),
            'title' => $createdQuiz->getTitle(),
            'description' => $createdQuiz->getDescription(),
            'start_date' => $createdQuiz->getStartDate(),
            'duration' => $createdQuiz->getDuration(),
            'is_active' => $createdQuiz->getIsActive()
        ]);
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|numeric|exists:quizzes',
            'category_id' => 'required|numeric',
            'title' => 'required|string',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'duration' => 'required|date',
            'is_active' => 'required|boolean'
        ]);

        $start_date = Carbon::parse($request->start_date);

        $duration = Carbon::parse($request->duration);


        if ($start_date->timestamp > $duration->timestamp) {
            return $this->respondInvalidValidation('مدت زمان آزمون باید زمانی بزرگ تر از تاریخ شروع آزمون باشد');
        }

        try {
            $updatedQuiz = $this->quizRepository->update($request->id, [
                'category_id' => $request->category_id,
                'title' => $request->title,
                'description' => $request->description,
                'start_date' => $start_date,
                'duration' => $duration,
                'is_active' => $request->is_active
            ]);
        } catch (Exception $e) {
            return $this->respondInternalError('آزمون بروزرسانی نشد');
        }

        return $this->respondSuccess('آزمون بروزرسانی شد', [
            'category_id' => $updatedQuiz->getCategoryId(),
            'title' => $updatedQuiz->getTitle(),
            'description' => $updatedQuiz->getDescription(),
            'start_date' => $updatedQuiz->getStartDate(),
            'duration' => $updatedQuiz->getDuration(),
            'is_active' => $updatedQuiz->getIsActive()
        ]);
    }

    public function delete(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|numeric'
        ]);

        if (!$this->quizRepository->find($request->id)) {
            return $this->respondNotFound('آزمون یافت نشد');
        }

        if (!$this->quizRepository->delete($request->id)) {
            return $this->respondInternalError('آزمون حذف نشد');
        }

        return $this->respondSuccess('آزمون حذف شد');
    }
}
