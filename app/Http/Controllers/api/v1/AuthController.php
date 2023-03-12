<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function signup(Request $request)
    {
        $requestData  = $request->validate([
            'name' => 'required|max:50',
            'email' => 'required|email|unique:users|max:50',
            'password' => 'required|confirmed'
        ]);

        $requestData['password'] = bcrypt($request->password);
        $user = User::create($requestData);
        $accessToken = $user->createToken('AuthToken')->accessToken;

        return response([
            'user' => $user,
            'access_token' => $accessToken
        ], 201);
    }
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'email|required',
            'password' => 'required'
        ]);

        if (!Auth::attempt($credentials)) {
            return response([
                'message' => 'Email or password is invalid'
            ], 401);
        }

        $user = Auth::user();
        $accessToken = $user->createToken('authToken')->accessToken;

        return response([
            'user' => $user,
            'access_token' => $accessToken
        ], 200);
    }

    public function list(Request $request)
    {
        $users = User::all();
        return response($users);
    }
}
