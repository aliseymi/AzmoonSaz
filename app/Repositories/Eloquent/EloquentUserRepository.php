<?php

namespace App\Repositories\Eloquent;

use App\Entities\User\EloquentUserEntity;
use App\Entities\User\UserEntity;
use App\Models\User;
use App\Repositories\Contracts\RepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use Exception;

class EloquentUserRepository extends EloquentBaseRepository implements UserRepositoryInterface
{
    protected $model = User::class;

    public function create(array $data): UserEntity
    {
        $user = parent::create($data);

        return new EloquentUserEntity($user);
    }

    public function update(int $id, array $data): UserEntity
    {
        if(!parent::update($id, $data)){
            throw new Exception('کاربر بروزرسانی نشد');
        }

        return new EloquentUserEntity(parent::find($id));
    }
}
