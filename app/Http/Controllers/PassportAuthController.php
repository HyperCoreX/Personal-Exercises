<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class PassportAuthController extends Controller
{
    
    public function register(Request $request){
        $this->validate($request, [
            'name' => 'required|min:4',
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        $token = $user->createToken('laravelAuthApp')->accessToken;

        return response()->json([
            'message' => 'register success',
            'data' => [
                'user' => $user,
                'token' => $token
            ],
            'status' => 200,

            ], 200);
    }
    /* login */

    public function login(Request $request){
        $data = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if(auth()->attempt($data)) {
            $token = auth()->user()->createToken('LaravelAuthApp')->accessToken;
            return response()->json([
                'message' => 'login success',
                'data'=> [
                    'user' =>$data,
                    'token' => $token
                ],
                'status' => 200,

                ], 200);
        } else{
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }

}