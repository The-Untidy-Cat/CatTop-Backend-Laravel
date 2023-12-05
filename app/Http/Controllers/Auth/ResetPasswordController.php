<?php

namespace App\Http\Controllers\Auth;

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
            'password' => ['required', 'string', 'min:8'],
            'token' => ['required', 'regex:/^[0-9]{6}$/']
        ]);

        if ($validator->fails()) {
            return new JsonResponse(['code' => 400, 'message' => 'Thông tin không hợp lệ', 'errors' => $validator->errors()], 400);
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
            $customer = Customer::where('email', $request->all()['email'])->exists();
            $employee = Employee::where('email', $request->all()['email'])->exists();

            if ($customer) {
                $user = Customer::where('email', $request->all()['email'])->first()->user()->first();
            } else if ($employee) {
                $user = Employee::where('email', $request->all()['email'])->first()->user()->first();
            }

            if ($user) {
                $user->update([
                    'password' => password_hash(str($request->password)->toString(), PASSWORD_DEFAULT)
                ]);
                DB::table('password_reset_tokens')->where([
                    ['email', $request->all()['email']],
                    ['token', $request->all()['token']],
                ])->delete();
                return new JsonResponse(
                    [
                        'code' => 200,
                        'message' => "Cập nhật mật khẩu thành công",
                    ],
                    200
                );
            } else {
                return new JsonResponse(
                    [
                        'code' => 400,
                        'message' => "Không tìm thấy người dùng",
                    ],
                    400
                );
            }
        } else {
            return new JsonResponse(
                [
                    'code' => 400,
                    'message' => "Mã OTP không hợp lệ",
                ],
                401
            );
        }

    }

}
