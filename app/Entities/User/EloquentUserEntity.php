<?php

namespace App\Entities\User;

use App\Models\User;

class EloquentUserEntity implements UserEntity
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getId(): int
    {
        return $this->user->id;
    }

    public function getName(): string
    {
        return $this->user->name;
    }

    public function getEmail(): string
    {
        return $this->user->email;
    }
}