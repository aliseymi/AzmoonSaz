<?php 

namespace API\V1\Quizzes;

use App\Repositories\Contracts\QuizRepositoryInterface;
use Carbon\Carbon;
use TestCase;

class QuizzesTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('migrate:refresh');
    }

    public function test_ensure_that_we_can_crate_new_quiz()
    {
        $category = $this->createCategories()[0];

        $start_date = Carbon::now()->addDay();

        $quizData = [
            'category_id' => $category->getId(),
            'title' => 'new quiz',
            'description' => 'this is a new test',
            'start_date' => $start_date->format('Y-m-d H:i:s'),
            'duration' => $start_date->addMinutes(60)
        ];

        $response = $this->call('POST', 'api/v1/quizzes', $quizData);

        $quizData['start_date'] = $start_date->format('Y-m-d');

        $createdQuiz = json_decode($response->getContent(), true)['data'];

        $this->assertEquals(201, $response->getStatusCode());

        $this->assertEquals($quizData['title'], $createdQuiz['title']);

        $this->seeInDatabase('quizzes', $quizData);

        $this->seeJsonStructure([
            'success',
            'message',
            'data' => [
                'category_id',
                'title',
                'description',
                'start_date',
                'duration'
            ]
        ]);
    }

    public function test_ensure_that_we_can_delete_a_quiz()
    {
        $quiz = $this->createQuiz()[0];

        $response = $this->call('delete', 'api/v1/quizzes', [
            'id' => $quiz->getId()
        ]);

        $this->assertEquals(200, $response->getStatusCode());

        $this->seeJsonStructure([
            'success',
            'message',
            'data'
        ]);
    }

    private function createQuiz(int $count = 1): array
    {
        $category = $this->createCategories()[0];

        $quizRepository = $this->app->make(QuizRepositoryInterface::class);

        $start_date = Carbon::now()->addDay();

        $duration = Carbon::now()->addDay();

        $quizData = [
            'category_id' => $category->getId(),
            'title' => 'quiz 1',
            'description' => 'this is a test quiz',
            'start_date' => $start_date,
            'duration' => $duration->addMinutes(60)
        ];

        $quizzes = [];

        foreach(range(0 ,$count) as $item){
            $quizzes[] = $quizRepository->create($quizData);
        }

        return $quizzes;
    }
}

