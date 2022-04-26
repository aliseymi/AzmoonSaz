<?php

namespace App\Repositories\Json;

use App\Entities\User\JsonUserEntity;
use App\Entities\User\UserEntity;
use App\Repositories\Contracts\RepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;

class JsonUserRepository extends JsonBaseRepository implements UserRepositoryInterface
{
    protected $userJson = 'users.json';

    public function create(array $data): UserEntity
    {
        $newUser = parent::create($data);

        return new JsonUserEntity($newUser);
    }
}
