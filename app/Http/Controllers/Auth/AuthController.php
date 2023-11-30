<?php

namespace App\Http\Controllers\Auth;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Rules\PhoneNumber;
use App\Rules\Username;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function customerRegister(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'first_name' => ['required', 'regex:/[a-zA-ZÀÁÂÃÈÉÊÌÍÒÓÔÕÙÚĂĐĨŨƠàáâãèéêìíòóôõùúăđĩũơƯĂẠẢẤẦẨẪẬẮẰẲẴẶẸẺẼỀỀỂưăạảấầẩẫậắằẳẵặẹẻẽềềểỄỆỈỊỌỎỐỒỔỖỘỚỜỞỠỢỤỦỨỪễệỉịọỏốồổỗộớờởỡợụủứừỬỮỰỲỴÝỶỸửữựỳỵỷỹ]+/u'],
            // regex for vietnamese name
            'last_name' => ['required', 'regex:/^[a-zA-ZÀÁÂÃÈÉÊÌÍÒÓÔÕÙÚĂĐĨŨƠàáâãèéêìíòóôõùúăđĩũơƯĂẠẢẤẦẨẪẬẮẰẲẴẶẸẺẼỀỀỂưăạảấầẩẫậắằẳẵặẹẻẽềềểỄỆỈỊỌỎỐỒỔỖỘỚỜỞỠỢỤỦỨỪễệỉịọỏốồổỗộớờởỡợụủứừỬỮỰỲỴÝỶỸửữựỳỵỷỹ]*$/u'],
            'phone_number' => ['required', new PhoneNumber()],
            'email' => 'required|unique:customers|unique:employees',
            'username' => ['required', 'unique:users', new Username()],
            'password' => 'required|min:6',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'code' => 400,
                'message' => __('messages.register.failed'),
                'errors' => $validate->errors()
            ], 400);
        }
        $user = User::create([
            'username' => $request->username,
            'password' => bcrypt($request->password),
        ]);
        $user->customer()->create([
            'first_name' => trim(strtoupper($request->first_name)),
            'last_name' => trim(strtoupper($request->last_name)),
            'phone_number' => $request->phone_number,
            'email' => $request->email,
        ]);
        $user->userRole()->create([
            'role_id' => UserRole::CUSTOMER,
        ]);
        return response()->json([
            'code' => 200,
            'message' => __('messages.register.success'),
            'data' => [
                'user' => $user->customer()->first([
                    'first_name',
                    'last_name',
                    'phone_number',
                    'email'
                ]),
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
                'code' => 400,
                'message' => __('messages.validation.error'),
                'errors' => $credentials->errors()
            ], 400);
        }

        if (
            Auth::attempt([
                'username' => $request->username,
                'password' => $request->password
            ])
        ) {
            // echo (UserRole::ADMIN->value);
            // echo Auth::user()->userRole()->first()->role_id;
            $role = Auth::user()->userRole()->whereIn('role_id', [UserRole::ADMIN, UserRole::SELLER])->get();

            if ($role->isEmpty()) {
                return response()->json([
                    'code' => 401,
                    'message' => __('messages.login.forbidden')
                ], 401);
            }
            $token = Auth::user()->createToken('authToken')->plainTextToken;

            $cookie = cookie('auth_token', $token, 60 * 24 * 30, null, null, null, true, false); // set the cookie for 7 days

            return response()->json([
                'code' => 200,
                'message' => __('messages.login.success'),
                'data' => [
                    'user' => Auth::user()->employee()->first([
                        "first_name",
                        "last_name",
                        "email",
                        "phone_number",
                        "date_of_birth",
                        "gender"
                    ]),
                    // 'token' => $token
                ]
            ], 200)->withCookie($cookie);
        } else {
            return response()->json([
                'code' => 401,
                'message' => __('messages.login.invalid')
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
                'code' => 400,
                'message' => __('messages.validation.error'),
                'errors' => $credentials->errors()
            ], 400);
        }

        if (
            Auth::attempt([
                'username' => $request->username,
                'password' => $request->password
            ])
        ) {
            $role = Auth::user()->userRole()->whereIn('role_id', [UserRole::CUSTOMER])->get();

            if ($role->isEmpty()) {
                return response()->json([
                    'code' => 401,
                    'message' => __('messages.login.forbidden')
                ], 401);
            }
            $token = Auth::user()->createToken('authToken')->plainTextToken;

            $cookie = cookie('auth_token', $token, 60 * 24 * 30, null, null, null, true, false); // set the cookie for 7 days

            return response()->json([
                'code' => 200,
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
                'code' => 401,
                'message' => __('messages.login.invalid'),
            ], 401);
        }
    }

    public function logout(Request $request)
    {
        // // Get user who requested the logout
        // $user = request()->user(); //or Auth::user()

        // // Revoke current user token
        // $user->tokens()->where('id', $user->currentAccessToken()->id)->delete();
        return response()->json([
            'code' => 200,
            "message" => __('messages.logout.success')
        ], 200)
            ->withCookie(cookie('auth_token', null, -1));
    }
}
