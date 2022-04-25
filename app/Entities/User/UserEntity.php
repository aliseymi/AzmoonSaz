<?php

namespace App\Entities\User;

interface UserEntity
{
    public function getId(): int;

    public function getName(): string;

    public function getEmail(): string;
}