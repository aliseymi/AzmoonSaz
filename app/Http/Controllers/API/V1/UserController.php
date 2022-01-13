<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\UserRepositoryInterface;

class UserController extends Controller
{
    public function __construct(private UserRepositoryInterface $userRepository)
    {
    }

    public function store()
    {
//        $this->userRepository->create();

        return response()->json([
            'success' => true,
            'message' => 'کاربر با موفقیت ایجاد شد',
            'data' => [
                'fullname' => 'aliseymi',
                'email' => 'ali@gmail.com',
                'mobile' => '09121234567',
                'password' => '123456'
            ]
        ])->setStatusCode(201);
    }
}
