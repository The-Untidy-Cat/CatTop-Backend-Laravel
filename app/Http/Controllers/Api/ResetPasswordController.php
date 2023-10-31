<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ResetPasswordController extends Controller
{
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'token' => ['required']
        ]);

        if ($validator->fails()) {
            return new JsonResponse(['code' => 422, 'message' => $validator->errors()], 422);
        }
        $check = DB::table('password_reset_tokens')->where([
            ['email', $request->all()['email']],
            ['token', $request->all()['token']],
        ]);

        if ($check->exists()) {
            $difference = Carbon::now()->diffInSeconds($check->first()->created_at);
            if ($difference > 3600) {
                return new JsonResponse(['code' => 400, 'message' => "Token Expired"], 400);
            }
            $customer = Customer::where('email', $request->all()['email'])->exists();
            $employee = Employee::where('email', $request->all()['email'])->exists();

            if ($customer) {
                $user = Customer::where('email', $request->all()['email'])->first()->user()->first();
            } else if ($employee) {
                $user = Employee::where('email', $request->all()['email'])->first()->user()->first();
            }

            if ($user) {
                $user->update([
                    'password' => bcrypt($request->password)
                ]);
                DB::table('password_reset_tokens')->where([
                    ['email', $request->all()['email']],
                    ['token', $request->all()['token']],
                ])->delete();
                return new JsonResponse(
                    [
                        'code' => 200,
                        'message' => "Your password has been reset",
                    ],
                    200
                );
            } else {
                return new JsonResponse(
                    [
                        'code' => 400,
                        'message' => "User not found",
                    ],
                    400
                );
            }
        } else {
            return new JsonResponse(
                [
                    'code' => 400,
                    'message' => "Invalid token"
                ],
                401
            );
        }

    }

}
