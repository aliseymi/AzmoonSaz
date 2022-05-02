<?php

use Carbon\Carbon;
use Laravel\Lumen\Testing\TestCase as BaseTestCase;
use App\Repositories\Contracts\QuizRepositoryInterface;
use App\Repositories\Contracts\CategoryRepositoryInterface;

abstract class TestCase extends BaseTestCase
{
    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        return require __DIR__.'/../bootstrap/app.php';
    }

    
    protected function createCategories(int $count = 1): array
    {
        $categoryRepository = $this->app->make(CategoryRepositoryInterface::class);

        $categories = [];

        foreach(range(0, $count) as $item){
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

        foreach(range(0 ,$count) as $item){
            $quizzes[] = $quizRepository->create($quizData);
        }

        return $quizzes;
    }
}
