<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Contracts\RepositoryInterface;

class EloquentUserRepository extends EloquentBaseRepository
{
    protected $model = User::class;
}
