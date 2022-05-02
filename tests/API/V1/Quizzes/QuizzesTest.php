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

    public function test_ensure_that_we_can_create_new_quiz()
    {
        $category = $this->createCategories()[0];

        $start_date = Carbon::now()->addDay();

        $quizData = [
            'category_id' => $category->getId(),
            'title' => 'new quiz',
            'description' => 'this is a new test',
            'start_date' => $start_date->format('Y-m-d H:i:s'),
            'duration' => $start_date->addMinutes(60),
            'is_active' => true
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
                'duration',
                'is_active'
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

    public function test_ensure_that_we_can_get_quizzes()
    {
        $this->createQuiz(30);

        $pagesize = 3;

        $response = $this->call('get', 'api/v1/quizzes', [
            'page' => 1,
            'pagesize' => $pagesize
        ]);

        $data = json_decode($response->getContent(), true);

        $this->assertCount($pagesize, $data['data']);

        $this->assertEquals(200, $response->status());

        $this->seeJsonStructure([
            'success',
            'message',
            'data'
        ]);
    }

    public function test_ensure_that_we_can_get_filtered_quizzes()
    {
        $start_date = Carbon::now()->addDay();

        $duration = Carbon::now()->addDay()->addMinutes(30);

        $this->createQuiz(30, [
            'title' => 'specific quiz',
            'description' => 'this is a specific quiz',
            'start_date' => $start_date,
            'duration' => $duration
        ]);

        $pagesize = 3;

        $searchKey = 'specific quiz';

        $response = $this->call('get', 'api/v1/quizzes', [
            'page' => 1,
            'pagesize' => $pagesize,
            'search' => $searchKey
        ]);

        $data = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->status());

        foreach($data['data'] as $quiz){
            $this->assertEquals($quiz['title'], $searchKey);
        }

        $this->seeJsonStructure([
            'success',
            'message',
            'data'
        ]);
    }

    public function test_ensure_that_we_can_update_a_quiz()
    {
        $quiz = $this->createQuiz()[0];

        $start_date = Carbon::now()->addDay();

        $duration = Carbon::now()->addDay()->addMinutes(30);

        $quizData = [
            'id' => $quiz->getId(),
            'category_id' => $quiz->getCategoryId(),
            'title' => 'updated quiz',
            'description' => 'this is the updated quiz',
            'start_date' => $start_date,
            'duration' => $duration,
            'is_active' => false
        ];

        $response = $this->call('PUT', 'api/v1/quizzes', $quizData);

        $data = json_decode($response->getContent(), true)['data'];

        $this->assertEquals(200, $response->getStatusCode());

        $this->assertEquals($quizData['title'], $data['title']);
        $this->assertEquals($quizData['description'], $data['description']);
        $this->assertEquals($quizData['is_active'], $data['is_active']);

        $this->seeJsonStructure([
            'success',
            'message',
            'data' => [
                'category_id',
                'title',
                'description',
                'start_date',
                'duration',
                'is_active'
            ]
        ]);
    }

    private function createQuiz(int $count = 1, array $data = []): array
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

