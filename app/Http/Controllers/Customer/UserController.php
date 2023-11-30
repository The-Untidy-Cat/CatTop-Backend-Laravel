<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Rules\PhoneNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $customer = $request->user()->customer()->first()->only(['id', 'first_name', 'last_name', 'email', 'phone_number']);
        $cart = Cart::where([['customer_id', '=', $request->user()->customer()->first()->id]]);
        $cart = $cart->with([
            'variant:id,name,product_id,sale_price,discount,standard_price,image',
            'variant.product:id,name,slug,image',
        ])->get();
        return response()->json([
            'code' => 200,
            'message' => 'User profile',
            'data' =>
                [
                    "user" => $customer,
                    'cart' => $cart
                ]
        ]);
    }
    public function changePassword(Request $request)
    {
        $customer = $request->user();
        $validate = Validator::make(['password' => $request->password], [
            'password' => ['required', 'string', 'min:8']
        ]);
        if ($validate->fails()) {
            return response()->json([
                'code' => 400,
                'message' => 'Change password failed',
                'data' =>
                    [
                        "errors" => $validate->errors()
                    ]
            ], 400);
        }
        if (bcrypt($request->password) == $customer->password) {
            return response()->json([
                'code' => 400,
                'message' => __('messages.validation.error'),
                "errors" => [
                    "password" => [
                        "Password must be different from the old password"
                    ]
                ]
            ], 400);
        }
        $customer->password = bcrypt($request->password);
        $customer->save();
        return response()->json([
            'status' => true,
            'message' => __('messages.update.success', ['name' => 'Password']),
            'data' =>
                [
                    "user" => $customer
                ]
        ]);
    }
    public function update(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'first_name' => ['regex:/[a-zA-ZÀÁÂÃÈÉÊÌÍÒÓÔÕÙÚĂĐĨŨƠàáâãèéêìíòóôõùúăđĩũơƯĂẠẢẤẦẨẪẬẮẰẲẴẶẸẺẼỀỀỂưăạảấầẩẫậắằẳẵặẹẻẽềềểỄỆỈỊỌỎỐỒỔỖỘỚỜỞỠỢỤỦỨỪễệỉịọỏốồổỗộớờởỡợụủứừỬỮỰỲỴÝỶỸửữựỳỵỷỹ]+/u'],
            'last_name' => ['regex:/^[a-zA-ZÀÁÂÃÈÉÊÌÍÒÓÔÕÙÚĂĐĨŨƠàáâãèéêìíòóôõùúăđĩũơƯĂẠẢẤẦẨẪẬẮẰẲẴẶẸẺẼỀỀỂưăạảấầẩẫậắằẳẵặẹẻẽềềểỄỆỈỊỌỎỐỒỔỖỘỚỜỞỠỢỤỦỨỪễệỉịọỏốồổỗộớờởỡợụủứừỬỮỰỲỴÝỶỸửữựỳỵỷỹ]*$/u'],
            'phone_number' => [new PhoneNumber()],
            'email' => 'unique:customers|unique:employees',
            'date_of_birth' => 'date',
            'gender' => 'in:0,1',
        ]);
        if ($validate->fails()) {
            return response()->json([
                'code' => 400,
                'message' => __('messages.validation.error'),
                "errors" => $validate->errors()
            ], 400);
        }
        $customer = $request->user()->customer()->first();
        $customer->first_name = $request->first_name;
        $customer->last_name = $request->last_name;
        $customer->phone_number = $request->phone_number;
        $customer->email = $request->email;
        $customer->save();
        return response()->json([
            'status' => true,
            'message' => __('messages.update.success', ['name' => 'Profile']),
            'data' =>
                [
                    "user" => $customer
                ]
        ]);
    }
}
