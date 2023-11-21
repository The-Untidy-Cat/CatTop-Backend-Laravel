<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Employee;
use App\Rules\ExistedEmail;
use Illuminate\Http\Request;
use App\Mail\ResetPassword;
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
            'email' => ['required', 'string', 'email', 'max:255', new ExistedEmail()],
        ]);

        if ($validator->fails()) {
            return new JsonResponse(['code' => 400, 'message' => 'Thông tin không hợp lệ', 'errors' => $validator->errors()], 400);
        }

        // $check = DB::table('password_reset_tokens')->where([
        //     ['email', $request->all()['email']],
        // ]);

        // if ($check->exists()) {
        //     $difference = Carbon::now()->diffInSeconds($check->first()->created_at);
        //     if ($difference <= env('OTP_MAX_AGE', 300)) {
        //         return new JsonResponse(['code' => 400, 'message' => "Gửi yêu cầu quá nhiều lần. Vui lòng thử lại sau"], 400);
        //     }
        // }

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
                        'message' => "Kiểm tra email để lấy mã OTP",
                        'data' => [
                            'max_age' => env('OTP_MAX_AGE', 300),
                        ]
                    ],
                    200
                );
            }
        } else {
            return new JsonResponse(
                [
                    'code' => 400,
                    'message' => "Địa chỉ email không tồn tại"
                ],
                400
            );
        }
    }
    public function verifyPin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email', 'max:255'],
            'token' => ['required', 'regex:/^[0-9]{6}$/']
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
            if ($difference > env('OTP_MAX_AGE', 300)) {
                return new JsonResponse(['code' => 400, 'message' => "Mã OTP đã hết hạn"], 400);
            }

            // $delete = DB::table('password_reset_tokens')->where([
            //     ['email', $request->all()['email']],
            //     ['token', $request->all()['token']],
            // ])->delete();

            return new JsonResponse(
                [
                    'code' => 200,
                    'message' => "Mã OTP hợp lệ"
                ],
                200
            );
        } else {
            return new JsonResponse(
                [
                    'code' => 400,
                    'message' => "Mã OTP không hợp lệ"
                ],
                401
            );
        }
    }

}
