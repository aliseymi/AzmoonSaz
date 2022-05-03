<?php

use Carbon\Carbon;
use App\Consts\QuestionStatus;
use App\Consts\AnswerSheetsStatus;
use Laravel\Lumen\Testing\TestCase as BaseTestCase;
use App\Repositories\Contracts\QuizRepositoryInterface;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use App\Repositories\Contracts\QuestionRepositoryInterface;
use App\Repositories\Contracts\AnswerSheetRepositoryInterface;

abstract class TestCase extends BaseTestCase
{
    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        return require __DIR__ . '/../bootstrap/app.php';
    }


    protected function createCategories(int $count = 1): array
    {
        $categoryRepository = $this->app->make(CategoryRepositoryInterface::class);

        $categories = [];

        foreach (range(0, $count) as $item) {
            $categories[] = $categoryRepository->create([
                'name' => 'new category',
                'slug' => 'new-category'
            ]);
        }

        return $categories;
    }

    protected function createQuiz(int $count = 1, array $data = []): array
    {
        $category = $this->createCategories()[0];

        $quizRepository = $this->app->make(QuizRepositoryInterface::class);

        $start_date = Carbon::now()->addDay();

        $duration = Carbon::now()->addDay();

        $quizData = empty($data) ? [
            'category_id' => $category->getId(),
            'title' => 'quiz 1',
            'description' => 'this is a test quiz',
            'start_date' => $start_date,
            'duration' => $duration->addMinutes(60),
            'is_active' => true
        ] : $data;

        $quizzes = [];

        foreach (range(0, $count) as $item) {
            $quizzes[] = $quizRepository->create($quizData);
        }

        return $quizzes;
    }

    protected function createQuestion(int $count = 1, array $data = []): array
    {
        $quiz = $this->createQuiz()[0];

        $questionRepository = $this->app->make(QuestionRepositoryInterface::class);

        $questionData = empty($data) ? [
            'title' => 'What is PHP?',
            'options' => json_encode([
                1 => ['text' => 'PHP is a car', 'is_correct' => 0],
                2 => ['text' => 'PHP is a programming language', 'is_correct' => 1],
                3 => ['text' => 'PHP is an animal', 'is_correct' => 0],
                4 => ['text' => 'PHP is a toy', 'is_correct' => 0]
            ]),
            'is_active' => QuestionStatus::ACTIVE,
            'score' => 5,
            'quiz_id' => $quiz->getId()
        ] : $data;

        $questions = [];

        foreach (range(0, $count) as $item) {
            $questions[] = $questionRepository->create($questionData);
        }

        return $questions;
    }

    protected function createAnswerSheet(int $count = 1, array $data = []): array
    {
        $quiz = $this->createQuiz()[0];

        $answerSheetRepository = $this->app->make(AnswerSheetRepositoryInterface::class);

        $answerSheetData = empty($data) ? [
            'quiz_id' => $quiz->getId(),
            'answers' => json_encode([
                1 => 3,
                2 => 1,
                3 => 2
            ]),
            'status' => AnswerSheetsStatus::PASSED,
            'score' => 10,
            'finished_at' => Carbon::now()
        ] : $data;

        $answerSheets = [];

        foreach (range(0, $count) as $item) {
            $answerSheets[] = $answerSheetRepository->create($answerSheetData);
        }

        return $answerSheets;
    }
}
