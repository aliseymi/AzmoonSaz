<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\API\Contracts\APIController;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Http\Request;

class UserController extends APIController
{
    public function __construct(private UserRepositoryInterface $userRepository)
    {
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'full_name' => 'required|string|min:3|max:255',
            'email' => 'required|string|email',
            'mobile' => 'required|string|digits:11|regex:/^09\d{9}$/',
            'password' => 'required|string'
        ]);

        $this->userRepository->create(
            [
                'full_name' => $request->full_name,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'password' => app('hash')->make($request->password)
            ]
        );

        return $this->respondCreated('کاربر با موفقیت ایجاد شد', [
            'full_name' => $request->full_name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'password' => $request->password
        ]);
    }

    public function updateInfo(Request $request)
    {
        $this->validate($request, [
            'id' => 'required',
            'full_name' => 'required|string|min:3|max:255',
            'email' => 'required|string|email',
            'mobile' => 'required|string|digits:11|regex:/^09\d{9}$/',
        ]);

        $this->userRepository->update($request->id, [
            'full_name' => $request->full_name,
            'email' => $request->email,
            'mobile' => $request->mobile
        ]);

        return $this->respondSuccess('کاربر با موفقیت بروزرسانی شد', [
            'full_name' => $request->full_name,
            'email' => $request->email,
            'mobile' => $request->mobile
        ]);
    }

    public function updatePassword(Request $request)
    {
        $this->validate($request, [
            'id' => 'required',
            'password' => 'min:6|required_with:password_repeat|same:password_repeat',
            'password_repeat' => 'min:6'
        ]);

        $this->userRepository->update($request->id, [
            'password' => app('hash')->make($request->password)
        ]);

        return $this->respondSuccess('رمزعبور با موفقیت بروزرسانی شد', [
            'full_name' => $request->full_name,
            'email' => $request->email,
            'mobile' => $request->mobile
        ]);
    }
}
