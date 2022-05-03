<?php

namespace API\V1\AnswerSheets;

use App\Consts\AnswerSheetsStatus;
use Carbon\Carbon;
use TestCase;

class AnswerSheetsTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('migrate:refresh');
    }

    public function test_ensure_that_we_can_create_an_answer_sheet()
    {
        $quiz = $this->createQuiz()[0];

        $answerSheetData = [
            'quiz_id' => $quiz->getId(),
            'answers' => json_encode([
                1 => 3,
                2 => 1,
                3 => 2
            ]),
            'status' => AnswerSheetsStatus::PASSED,
            'score' => 10,
            'finished_at' => Carbon::now()
        ];

        $response = $this->call('POST', 'api/v1/answer-sheets', $answerSheetData);

        $responseData = json_decode($response->getContent(), true)['data'];

        $this->assertEquals(201, $response->getStatusCode());

        $this->assertJson($responseData['answers']);

        $this->seeInDatabase('answer_sheets', $answerSheetData);

        $this->assertEquals($answerSheetData['quiz_id'], $responseData['quiz_id']);
        $this->assertEquals($answerSheetData['answers'], $responseData['answers']);
        $this->assertEquals($answerSheetData['status'], $responseData['status']);
        $this->assertEquals($answerSheetData['score'], $responseData['score']);
        $this->assertEquals($answerSheetData['finished_at'], Carbon::parse($responseData['finished_at']));

        $this->seeJsonStructure([
            'success',
            'message',
            'data' => [
                'quiz_id',
                'answers',
                'status',
                'score',
                'finished_at'
            ]
        ]);
    }

    public function test_ensure_that_we_can_delete_an_answer_sheet()
    {
        $answerSheet = $this->createAnswerSheet()[0];

        $response = $this->call('delete', 'api/v1/answer-sheets', [
            'id' => $answerSheet->getId()
        ]);

        $this->assertEquals(200, $response->getStatusCode());

        $this->seeJsonStructure([
            'success',
            'message',
            'data'
        ]);
    }
}
