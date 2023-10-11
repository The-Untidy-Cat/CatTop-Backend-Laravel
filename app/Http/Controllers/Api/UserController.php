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
        return response()->json([
            'status' => 200,
            'message' => 'Get profile success',
            'data' => $customer->only([
                "first_name",
                "last_name",
                "email",
                "phone_number",
                "date_of_birth",
                "gender"
            ]) ?? $employee->only([
                            "first_name",
                            "last_name",
                            "email",
                            "phone_number",
                            "date_of_birth",
                            "gender"
                        ]),
        ], 200);
    }
}
