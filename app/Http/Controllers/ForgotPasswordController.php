<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Mail\ResetPassword;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ForgotPasswordController extends Controller
{
    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email', 'max:255'],
        ]);

        if ($validator->fails()) {
            return new JsonResponse(['code' => 400, 'message' => $validator->errors()], 422);
        }

        $customer = Customer::where('email', $request->all()['email'])->exists();
        $employee = Employee::where('email', $request->all()['email'])->exists();

        if ($customer) {
            $user = Customer::where('email', $request->all()['email'])->first()->user()->first();
        } else if ($employee) {
            $user = Employee::where('email', $request->all()['email'])->first()->user()->first();
        }
        if ($user) {
            $verify2 = DB::table('password_reset_tokens')->where([
                ['email', $request->all()['email']]
            ]);

            if ($verify2->exists()) {
                $verify2->delete();
            }

            $token = random_int(100000, 999999);
            $password_reset = DB::table('password_reset_tokens')->insert([
                'email' => $request->all()['email'],
                'token' => $token,
                'created_at' => Carbon::now()
            ]);

            if ($password_reset) {
                Mail::to($request->all()['email'])->send(new ResetPassword($token));

                return new JsonResponse(
                    [
                        'code' => 200,
                        'message' => "Please check your email for a 6 digit pin"
                    ],
                    200
                );
            }
        } else {
            return new JsonResponse(
                [
                    'code' => 400,
                    'message' => "This email does not exist"
                ],
                400
            );
        }
    }
    public function verifyPin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email', 'max:255'],
            'token' => ['required'],
        ]);

        if ($validator->fails()) {
            return new JsonResponse(['code' => 400, 'message' => $validator->errors()], 422);
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

            // $delete = DB::table('password_reset_tokens')->where([
            //     ['email', $request->all()['email']],
            //     ['token', $request->all()['token']],
            // ])->delete();

            return new JsonResponse(
                [
                    'code' => 200,
                    'message' => "You can now reset your password"
                ],
                200
            );
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
