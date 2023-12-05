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
        $customer = $request->user()->customer()->first()->only(['id', 'first_name', 'last_name', 'email', 'phone_number', 'gender', 'date_of_birth']);
        $cart = Cart::where([['customer_id', '=', $request->user()->customer()->first()->id]]);
        $cart = $cart->with([
            'variant:id,name,product_id,sale_price,discount,standard_price,image,state',
            'variant.product:id,name,slug,image,state',
        ])->get();
        return response()->json([
            'code' => 200,
            'message' => 'User profile',
            'data' =>
                [
                    "user" => [...$customer, "username" => $request->user()->username],
                    'cart' => $cart
                ]
        ]);
    }
    public function changePassword(Request $request)
    {
        $customer = $request->user();
        $validate = Validator::make($request->all(), [
            'new_password' => ['required', 'string', 'min:8'],
            'old_password' => ['required']
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
        if (
            password_hash(str($request->old_password)->toString(), PASSWORD_DEFAULT) != $customer->password
        ) {
            return response()->json([
                'code' => 400,
                'message' => __('messages.validation.error'),
                "errors" => [
                    "old_password" => [
                        __('messages.user.password.wrong')
                    ]
                ]
            ], 400);
        }
        if (password_hash(str($request->new_password)->toString(), PASSWORD_DEFAULT) == $customer->password) {
            return response()->json([
                'code' => 400,
                'message' => __('messages.validation.error'),
                "errors" => [
                    "new_password" => [
                        __('messages.user.password.duplicate')
                    ]
                ]
            ], 400);
        }
        $customer->password = password_hash(str($request->new_password)->toString(), PASSWORD_DEFAULT);
        $customer->save();
        return response()->json([
            'status' => true,
            'message' => __('messages.update.success', ['name' => 'Password']),
            'data' =>
                [
                    "user" => [...$customer, "username" => $request->user()->username]
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
        $customer->fill($request->all());
        $customer->save();
        return response()->json([
            'status' => true,
            'message' => __('messages.update.success', ['name' => 'Profile']),
            'data' =>
                [
                    "user" => $customer->only(['id', 'first_name', 'last_name', 'email', 'phone_number', 'gender', 'date_of_birth'])
                ]
        ]);
    }
}
