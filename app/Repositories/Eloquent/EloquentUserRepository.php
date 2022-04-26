<?php

namespace App\Repositories\Eloquent;

use App\Entities\User\EloquentUserEntity;
use App\Entities\User\UserEntity;
use App\Models\User;
use App\Repositories\Contracts\RepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;

class EloquentUserRepository extends EloquentBaseRepository implements UserRepositoryInterface
{
    protected $model = User::class;

    public function create(array $data): UserEntity
    {
        $user = parent::create($data);

        return new EloquentUserEntity($user);
    }
}
