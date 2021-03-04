<?php

namespace App\Http\Controllers\Auth;

use App\Events\UserCreated;
use App\Repositories\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers, UserActivateChannel;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $rules = [
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:wz_users',
            'password' => 'required|string|min:6|confirmed',
        ];

        if (config('wizard.register_invitation')) {
            $rules['invitation_code'] = 'required|string|max:255|invitation_code';
        }

        return Validator::make($data, $rules);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     *
     * @return \App\Repositories\User
     */
    protected function create(array $data)
    {
        $needActivate = config('wizard.need_activate');
        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => bcrypt($data['password']),
            'role'     => User::ROLE_NORMAL,
            'status'   => $needActivate ? User::STATUS_NONE : User::STATUS_ACTIVATED,
        ]);

        // 如果创建的用户是系统中第一个用户，则自动设置其为管理员
        if ((int)$user->id === 1) {
            $user->role = User::ROLE_ADMIN;
            $user->save();
        }

        // 注册后发送激活邮件
        if ($needActivate) {
            $this->sendUserActivateEmail($user);
        }

        event(new UserCreated($user));

        return $user;
    }

}
