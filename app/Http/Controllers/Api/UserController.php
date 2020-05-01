<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UserController extends Controller
{
   
    public function register(UserRequest $request){
        
        $name=$request->name;
        $email=$request->email;
        $password=$request->password;

        $user=User::create([
            'name'=>$name,
            'email'=>$email,
            'password'=>bcrypt($password),
        ]);

        $token=$user->createToken($request->name)->accessToken;

        return response()->json([
            'statusCode' => '200',
            'message'=> 'successfully',
            'data'=>$user,
            'token'=>$token
        ]);
    }

    public function login(Request $request)
    {
        
        $email=$request->email;
        $password=$request->password;

        $login=['email'=>$email,'password'=>$password];
 
      
        if (Auth::attempt($login, false, false)) {
           $user=Auth::user();
        
           $token=$user->createToken($user->name)->accessToken;
           return response()->json([
            'statusCode' => '200',
            'message'=> 'successfully',
            'data'=>$user,
            'token'=>$token
        ]);
        }

        return response()->json([
            'status' => '500',
            'message'=> 'failed',
            'data'=>[
                'error'=>'email and Password incorrect'
            ],
           
        ]);
    }









    public function logout(Request $res)
    {
      if (Auth::user()) {
        $user = Auth::user()->token();
        $user->revoke();

        return response()->json([
          'statusCode' => '200',
          'message' => 'Logout successfully'
      ]);
      }else {
        return response()->json([
          'statusCode' => '500',
          'message' => 'Unable to Logout'
        ]);
      }
     }
    
}
