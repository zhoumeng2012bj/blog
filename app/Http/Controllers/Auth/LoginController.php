<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;


class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */


    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }
    public function index(){
        //验证用户名密码，通过
        if (Auth::attempt(Input::get())) {
          // return redirect(Input::get('returnurl').'?sso='.Auth::user()->remember_token);
            return [
                'msg'=>'success!',
                'code'=>200,
                'user'=>Auth::user(),
            ];
        }else{//验证失败返回前端，提示用户名密码错误
            return [
                'msg'=>'账户或者密码错误!',
                'code'=>201,
            ];
        }
    }
    public function logout(){
        return Auth::logout();
    }

    //生成token
    public  function  createToken($username){
        return md5($username.getdate());
    }
}
