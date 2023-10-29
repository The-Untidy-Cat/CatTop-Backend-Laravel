<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function customerRegister(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'phone_number' => 'required|unique:customers',
            'email' => 'required|unique:customers',
            'username' => 'required|unique:users|min:6',
            'password' => 'required|min:6',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'status' => 400,
                'message' => 'Validation error',
                'errors' => $validate->errors()
            ], 400);
        }
        $user = User::create([
            'username' => $request->username,
            'password' => bcrypt($request->password),
        ]);
        $user->customer()->create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
        ]);
        $user->userRole()->create([
            'role_id' => 3,
        ]);
        return response()->json([
            'status' => 200,
            'message' => 'Register success',
            'data' => [
                'user' => $user,
            ]
        ], 200);
    }
    public function employeesBasicLogin(Request $request)
    {
        $credentials = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
        ]);

        if ($credentials->fails()) {
            return response()->json([
                'status' => 400,
                'message' => 'Validation error',
                'errors' => $credentials->errors()
            ], 400);
        }

        if (
            Auth::attempt([
                'username' => $request->username,
                'password' => $request->password
            ])
        ) {
            $role = Auth::user()->userRole()->whereIn('role_id', [1, 2, 4, 5, 6, 7, 8, 9])->get();

            if ($role->isEmpty()) {
                return response()->json([
                    'status' => 401,
                    'message' => 'You are not allowed to access this page'
                ], 401);
            }
            $token = Auth::user()->createToken('authToken')->plainTextToken;

            $cookie = cookie('auth_token', $token, 60 * 24 * 30, null, null, null, true, false, 'None'); // set the cookie for 7 days

            return response()->json([
                'status' => 200,
                'message' => 'Login success',
                'data' => [
                    'user' => Auth::user(),
                    'token' => $token
                ]
            ], 200)->withCookie($cookie);
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Invalid credentials',
            ], 401);
        }
    }
    public function customerBasicLogin(Request $request)
    {
        $credentials = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
        ]);

        if ($credentials->fails()) {
            return response()->json([
                'status' => 400,
                'message' => 'Validation error',
                'errors' => $credentials->errors()
            ], 400);
        }

        if (
            Auth::attempt([
                'username' => $request->username,
                'password' => $request->password
            ])
        ) {
            $role = Auth::user()->userRole()->whereIn('role_id', [3])->get();

            if ($role->isEmpty()) {
                return response()->json([
                    'status' => 401,
                    'message' => 'You are not allowed to access this page'
                ], 401);
            }
            $token = Auth::user()->createToken('authToken')->plainTextToken;

            $cookie = cookie('auth_token', $token, 60 * 24 * 30, null, null, null, true, false, 'None'); // set the cookie for 7 days

            return response()->json([
                'status' => 200,
                'message' => 'Login success',
                'data' => [
                    'user' => Auth::user()->customer()->first()->only([
                        "first_name",
                        "last_name",
                        "email",
                        "phone_number",
                        "date_of_birth",
                        "gender"
                    ]),
                ]
            ], 200)->withCookie($cookie);
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Invalid credentials',
            ], 401);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            "status" => 200,
            "message" => "Logout success"
        ])
        ->withCookie(cookie('auth_token', null, -1));
    }
}
