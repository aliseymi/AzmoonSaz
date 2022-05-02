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

    public function index(Request $request)
    {
        $this->validate($request, [
            'search' => 'nullable|string',
            'page' => 'required|numeric',
            'pagesize' => 'nullable|numeric'
        ]);

        $users = $this->userRepository->paginate($request->search, $request->page, $request->pagesize ?? 10, ['full_name', 'email', 'mobile']);

        return $this->respondSuccess('کاربران', $users);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'full_name' => 'required|string|min:3|max:255',
            'email' => 'required|string|email',
            'mobile' => 'required|string|digits:11|regex:/^09\d{9}$/',
            'password' => 'required|string'
        ]);

        $user = $this->userRepository->create(
            [
                'full_name' => $request->full_name,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'password' => app('hash')->make($request->password)
            ]
        );

        return $this->respondCreated('کاربر با موفقیت ایجاد شد', [
            'full_name' => $user->getFullName(),
            'email' => $user->getEmail(),
            'mobile' => $user->getMobile(),
            'password' => $user->getPassword()
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

        try {
            $user = $this->userRepository->update($request->id, [
                'full_name' => $request->full_name,
                'email' => $request->email,
                'mobile' => $request->mobile
            ]);
        } catch (\Exception $e) {
            return $this->respondInternalError('کاربر بروزرسانی نشد');
        }

        return $this->respondSuccess('کاربر با موفقیت بروزرسانی شد', [
            'full_name' => $user->getFullName(),
            'email' => $user->getEmail(),
            'mobile' => $user->getMobile()
        ]);
    }

    public function updatePassword(Request $request)
    {
        $this->validate($request, [
            'id' => 'required',
            'password' => 'min:6|required_with:password_repeat|same:password_repeat',
            'password_repeat' => 'min:6'
        ]);

        try {
            $user = $this->userRepository->update($request->id, [
                'password' => app('hash')->make($request->password)
            ]);
        } catch (\Exception $e) {
            return $this->respondInternalError('کاربر بروزرسانی نشد');
        }

        return $this->respondSuccess('رمزعبور با موفقیت بروزرسانی شد', [
            'full_name' => $user->getFullName(),
            'email' => $user->getEmail(),
            'mobile' => $user->getMobile()
        ]);
    }

    public function delete(Request $request)
    {
        $this->validate($request, [
            'id' => 'required'
        ]);

        if(!$this->userRepository->find($request->id)){
            return $this->respondNotFound('کاربر وجود ندارد');
        }


        if(!$this->userRepository->delete($request->id)){
            return $this->respondInternalError('خطایی وجود دارد لطفا مجددا تلاش فرمایید');
        }

        return $this->respondSuccess('کاربر با موفقیت حذف شد');
    }
}
