<?php

namespace App\Entities\Quiz;

interface QuizEntity
{
    public function getId(): int;

    public function getCategoryId(): int;

    public function getTitle(): string;

    public function getDescription(): string;

    public function getStartDate(): string;

    public function getDuration(): string;
}