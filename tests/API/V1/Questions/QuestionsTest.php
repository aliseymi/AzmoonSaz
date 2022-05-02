<?php

namespace API\V1\Questions;

use App\Consts\QuestionStatus;
use TestCase;

class QuestionsTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('migrate:refresh');
    }

    public function test_ensure_that_we_can_create_a_new_question()
    {
        $quiz = $this->createQuiz()[0];

        $questionData = [
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
        ];

        $response = $this->call('POST', 'api/v1/questions', $questionData);

        $responseData = json_decode($response->getContent(), true)['data'];

        $this->assertEquals(201, $response->getStatusCode());

        $this->assertEquals($questionData['title'], $responseData['title']);
        $this->assertEquals($questionData['options'], $responseData['options']);
        $this->assertEquals($questionData['is_active'], $responseData['is_active']);
        $this->assertEquals($questionData['score'], $responseData['score']);
        $this->assertEquals($questionData['quiz_id'], $responseData['quiz_id']);

        $this->seeJsonStructure([
            'success',
            'message',
            'data' => [
                'title',
                'options',
                'is_active',
                'score',
                'quiz_id'
            ]
        ]);
    }

    public function test_ensure_that_we_can_delete_a_question()
    {
        $question = $this->createQuestion()[0];
        
        $response = $this->call('delete', 'api/v1/questions', [
            'id' => $question->getId()
        ]);

        $this->assertEquals(200, $response->getStatusCode());

        $this->seeJsonStructure([
            'success',
            'message',
            'data'
        ]);
    }

    public function test_ensure_that_we_can_get_questions()
    {
        $this->createQuestion(30);

        $pagesize = 3;

        $response = $this->call('GET', 'api/v1/questions', [
            'page' => 1,
            'pagesize' => $pagesize,
        ]);

        $responseData = json_decode($response->getContent(), true)['data'];

        $this->assertEquals(200, $response->getStatusCode());

        $this->assertCount($pagesize, $responseData);

        $this->seeJsonStructure([
            'success',
            'message',
            'data'
        ]);
    }

    public function test_ensure_that_we_can_get_filtered_questions()
    {
        $quiz = $this->createQuiz()[0];

        $this->createQuestion(30, [
            'title' => 'What is Golang?',
            'options' => json_encode([
                1 => ['text' => 'Golang is a car', 'is_correct' => 0],
                2 => ['text' => 'Golang is a programming language', 'is_correct' => 1],
                3 => ['text' => 'Golang is an animal', 'is_correct' => 0],
                4 => ['text' => 'Golang is a toy', 'is_correct' => 0]
            ]),
            'is_active' => QuestionStatus::ACTIVE,
            'score' => 10,
            'quiz_id' => $quiz->getId()
        ]);

        $pagesize = 3;

        $searchKey = 'What is Golang?';

        $response = $this->call('GET', 'api/v1/questions', [
            'page' => 1,
            'pagesize' => $pagesize,
            'search' => $searchKey
        ]);

        $responseData = json_decode($response->getContent(), true)['data'];

        $this->assertEquals(200, $response->getStatusCode());

        foreach($responseData as $question){
            $this->assertEquals($searchKey, $question['title']);
        }

        $this->seeJsonStructure([
            'success',
            'message',
            'data'
        ]);
    }
}