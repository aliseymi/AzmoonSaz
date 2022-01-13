<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function store()
    {
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
