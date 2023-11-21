<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function getProfile(Request $request)
    {
        $customer = $request->user()->customer()->first()->only(['id', 'first_name', 'last_name', 'email', 'phone_number']);
        return response()->json([
            'status' => true,
            'message' => 'User profile',
            'data' =>
                [
                    "user" => $customer
                ]
        ]);
    }
    public function changePassword(Request $request)
    {
        $customer = $request->user();
        $validate = Validator::make(['password' => $request->password], [
            'password' => ['required', 'string', 'min:8', 'confirmed']
        ]);
        if ($validate->fails()) {
            return response()->json([
                'code' =>400,
                'message' => 'Change password failed',
                'data' =>
                    [
                        "errors" => $validate->errors()
                    ]
            ]);
        }
        $customer->password = bcrypt($request->password);
        $customer->save();
        return response()->json([
            'status' => true,
            'message' => 'Change password successfully',
            'data' =>
                [
                    "user" => $customer
                ]
        ]);
    }
}
