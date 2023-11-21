<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\CustomerState;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DatabaseController;
use App\Mail\CustomerCreated;
use App\Models\Customer;
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
        $customer->fill($request->all());
        $customer->state = CustomerState::ACTIVE;
        $customer->save();
        $password = md5(rand());
        $customer->user()->create([
            'username' => $request->phone_number,
            'password' => bcrypt(
                $password
            ),
        ]);
        Mail::to($request->email)->send(new CustomerCreated($request->phone_number, $password));
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
        $customer = Customer::find($id)->first([
            'id',
            'first_name',
            'last_name',
            'phone_number',
            'date_of_birth',
            'gender',
            'state',
            'user_id',
            'email',
        ])->with(['orders:id,created_at']);
        return response()->json([
            'code' => 200,
            'message' => __('messages.get.success'),
            'data' => $customer
        ], 200);
    }
}
