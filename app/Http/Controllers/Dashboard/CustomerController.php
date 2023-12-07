<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\CustomerState;
use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DatabaseController;
use App\Mail\CustomerCreated;
use App\Models\Customer;
use App\Models\User;
use App\Rules\PhoneNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $customers = DatabaseController::searchRead(
            "Customer",
            [],
            ["id", "first_name", "last_name", "state", "phone_number", "email"],
            [],
            [],
            ['*'],
            $request->offset ? $request->offset : 0,
            $request->limit ? $request->limit : 10
        );
        return response()->json([
            'code' => 200,
            'message' => __('messages.list.success', ['name' => 'customers']),
            'data' => $customers
        ], 200);
    }
    public function store(Request $request)
    {
        $customer = new Customer();
        $validate = $customer->validate($request->all());
        if ($validate->fails()) {
            return response()->json([
                'code' => 400,
                'message' => __('messages.validation.error'),
                'errors' => $validate->errors()
            ], 400);
        }
        $customer->state = CustomerState::ACTIVE;
        $password = md5(rand());
        $customer->user()->create([
            'username' => $request->phone_number,
            'password' => password_hash(str($password)->toString(), PASSWORD_DEFAULT)
        ]);
        $user = User::where('username', $request->phone_number)->first();
        $customer->user_id = $user->id;
        $user->userRole()->create([
            'role_id' => UserRole::CUSTOMER->value
        ]);
        $customer->fill($request->all());
        $customer->save();
        Mail::to($request->email)->send(new CustomerCreated($request->phone_number, $password));
        return response()->json([
            'code' => 200,
            'message' => __('messages.create.success', ['name' => 'customer']),
            'data' => $customer
        ], 200);
    }
    public function show(Request $request, $id)
    {
        $validate = Validator::make(
            ['id' => $id],
            [
                'id' => 'required|exists:customers,id'
            ]
        );
        if ($validate->fails()) {
            return response()->json([
                'code' => 400,
                'message' => __('messages.validation.error'),
                'errors' => $validate->errors()
            ], 400);
        }
        $customer = Customer::find($id)->load('orders')
        ->only([
            'id',
            'first_name',
            'last_name',
            'phone_number',
            'date_of_birth',
            'gender',
            'state',
            'user_id',
            'email',
            'orders'
        ]);
        return response()->json([
            'code' => 200,
            'message' => __('messages.get.success'),
            'data' => $customer
        ], 200);
    }
    public function update(Request $request, $id){
        $validate = Validator::make(
            ['id' => $id],
            [
                'id' => 'required|exists:customers,id'
            ]
        );
        if ($validate->fails()) {
            return response()->json([
                'code' => 400,
                'message' => __('messages.validation.error'),
                'errors' => $validate->errors()
            ], 400);
        }
        $customer = Customer::find($id);
        $validate = Validator::make($request->all(), [
            "first_name" => "string",
            "last_name" => "string",
            "phone_number" => ["required",new PhoneNumber],
            "date_of_birth" => "date",
            "gender" => "in:0,1",
            "email" => "email|unique:customers,email",
        ]);
        if ($validate->fails()) {
            return response()->json([
                'code' => 400,
                'message' => __('messages.validation.error'),
                'errors' => $validate->errors()
            ], 400);
        }
        $customer->fill($request->all());
        $customer->save();
        return response()->json([
            'code' => 200,
            'message' => __('messages.update.success', ['name' => 'customer']),
            'data' => $customer
        ], 200);
    }
}
