<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function getProfile(Request $request)
    {

        $customer = $request->user()->customer()->first();
        $employee = $request->user()->employee()->first();
        try {
            return response()->json([
                'code' => 200,
                'message' => 'Get profile success',
                'data' => [
                    'user' => $customer ? ($customer->only([
                        "first_name",
                        "last_name",
                        "email",
                        "phone_number",
                        "date_of_birth",
                        "gender"
                    ])) : ($employee ? $employee->only([
                            "first_name",
                            "last_name",
                            "email",
                            "phone_number",
                            "date_of_birth",
                            "gender"
                        ]) : [
                            "first_name" => null,
                            "last_name" => null,
                            "email" => null,
                            "phone_number" => null,
                            "date_of_birth" => null,
                            "gender" => null
                        ])
                ],
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'code' => 500,
                'message' => 'Get profile failed',
                'data' => null,
            ], 500);
        }

    }
}
