<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Register a user.
     * 
     * @group Auth
     * @unauthenticated
     * @response 
     * {
     *     "user": {
     *         "name": "juan el user",
     *         "email": "juan@juan.com",
     *         "updated_at": "2023-03-12T12:23:42.000000Z",
     *         "created_at": "2023-03-12T12:23:42.000000Z",
     *         "id": 1
     *     },
     *     "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiOTM0YTU4YTY0YTM3ZGRhYjllZjhhYzg0YTY1MWZmYjU2MWM4OWM1OGY1Zjk5ZWZmOTA2MTMwNTcxNTZkNWM3NjFkYzAxYjJlNTdjNmMxMjYiLCJpYXQiOjE2Nzg2MjM4MjIuMzEwNTQ1LCJuYmYiOjE2Nzg2MjM4MjIuMzEwNTU1LCJleHAiOjE3MTAyNDYyMjIuMjk2MzQsInN1YiI6IjEiLCJzY29wZXMiOltdfQ.jVAx_YLYnxKGlU99V7gaEmUvUdzkwguMCkNDaa6dHkR4I7XlrZYeQL_jQPS_M23pbrt9P1L1ToMxYD29a0VR3XhfQGkk9-P2gF94cAty2qCbWBMqa-D0yH-_FWJ_EQU8GuztZW8NK6SL_76dhJOOmrDe71kJydG2i0CEJLqFjhZlYGXVGs99rF_TwiiQ5dirRxvHd_O3d8ReU-HjA6c-Ud0Wom9tO_12CAJIrgUD4M8KIshPsBL1yCyKjr4Oax5n7MQqIrNEwY3M_Zlrdcs_Ps86FOtVHQD8mHjd93bI04dyvw-TbT8xuvub48u27OaFEgQcnuYMP4vcjkaKu2ez7WBRf6QsGCuJR0LW-mSB1z6nUrMj5cTgt1as3YkHE15JlCpJtHXIWRhuidABtc8txei7Gm-joq8qll7dUOYRT_r2P9Bn5bQcKFZ-IdxoYr9CfuSFLOBBlJuh9464S_892mSCcEPv107adlPjLqcbPWqujGXPGr5Q277kln6x46W-ThPyb4T01PfKgeI3oyBx_ZtZkXpli13fd4pmb4qsCS22PfudVufYyxdn2--CcaevWWjSh-B6Sc34Qz9l7-E5LvZxEYUalQl8BfgJfYI9hVfwT7c8K1XHKZvP5eTnTHrxN0F-KsFDxBuXLMk5Kjpp2dB6mQop5UiE8AHxQfuwlSg"
     * }
     */
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

    /**
     * Login a user.
     * 
     * @group Auth
     * @unauthenticated
     * @response 
     * {
     *     "user": {
     *         "id": 1,
     *         "name": "juan el user",
     *         "email": "juan@juan.com",
     *         "email_verified_at": null,
     *         "created_at": "2023-03-12T12:23:42.000000Z",
     *         "updated_at": "2023-03-12T12:23:42.000000Z"
     *     },
     *     "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiNjM0ZmQzMjY5ZTc4YzRlNTZlNDAxN2NhNzBiZGEzMzk0ZTc0OWVkNDI4NTVhNmQxNGQzZjljNWJmMGIxN2VjMjA2ZDQ2YjhhZTA4Y2FkM2IiLCJpYXQiOjE2Nzg2MjU0ODcuOTM2MTc0LCJuYmYiOjE2Nzg2MjU0ODcuOTM2MTg0LCJleHAiOjE3MTAyNDc4ODcuOTIzNjM5LCJzdWIiOiIxIiwic2NvcGVzIjpbXX0.Nl04ZzdjGMLaRcLtvaXkAh-p4_-JMIBBxVMGwV48p9glhDyhGhjUV5y1aTHxw8SIrkXJur_fcFs0JeYSjpBvYeMskT68Pe2wTLKtQjGYPG_JRJoOGPOWJ_37hb99XydVGRVRHqul5ce0ClkETTYYteVTYIWxfig1ivZPArwndYBcurupk9yCR3DlFoFwL7669_tfn_9Sk7tabz83D5tHBsQJuZAXHupNMVDZQ20b0MzaktRhrXMvpzyAwu-kt-UNCcDxPwNrNep7yAll9pHyKW8ezVJbwRpRx1oq_7E2MlYYTypLJpyKd_gAD6F-miff-68mw5PJCpTB2mrVr7fY2uZr-Mdv-dbwc4UCXJnoM0Cq3LzFdgmTYwDn7XVagIwO-M2CoSaRjpOzxh0Y4EHL69beJP3Qw_BVmzf6U-Njxq9A540zrxS-ZhvoOmUlq9pubVNQoni5oE4kbM9uwWmiECzVZt624HGwmNDKbnAM8nOrfIvnWhF4fUI3-1e7hAUPk1sdvwPhQeBZ0aTaH7O5ermOTgUmhwI0OtzC9jw7-lc8trJe-k2Ft9yNxMp4g_Mk_jU-7VhpwNrabX8pddzzZpBVB_ha9O0tjZ2t1Lr0TBmcRtQyt01y543UL7UBIrE8fg4gV72sMS4j1cR47E04b_E1H9PS4i0g2Zo5bLTgWjw"
     * }
     */
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
