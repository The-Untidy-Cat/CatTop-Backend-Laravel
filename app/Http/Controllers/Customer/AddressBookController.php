<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\AddressBook;
use Illuminate\Http\Request;

class AddressBookController extends Controller
{
    public function index()
    {
        try {
            $customer = auth()->user()->customer()->first();
            $addressBooks = $customer->addressBooks()->get(['id', 'name', 'phone', 'address_line', 'province', 'district', 'ward']);
            return response()->json([
                'code' => 200,
                'message' => __('messages.list.success', ['name' => 'address book']),
                'data' => [
                    'address_books' => $addressBooks
                ]
            ], 200);
        } catch (\Exception $th) {
            return response()->json([
                'code' => 400,
                'message' => __('messages.list.failed', ['name' => 'address book']),
                'data' => [
                    'errors' => $th->getMessage()
                ]
            ], 400);
        }
    }
    public function store(Request $request)
    {
        $addressBook = new AddressBook();
        $addressBook->customer_id = auth()->user()->customer()->first()->id;
        $validate = $addressBook->validate([
            'customer_id' => $addressBook->customer_id,
            'name' => $request->name,
            'phone' => $request->phone,
            'address_line' => $request->address_line,
            'ward' => $request->ward,
            'district' => $request->district,
            'province' => $request->province
        ]);
        if ($validate->fails()) {
            return response()->json([
                'code' => 400,
                'message' => __('messages.validation.error'),
                'data' => [
                    'errors' => $validate->errors()
                ]
            ], 400);
        }
        $addressBook->fill($request->all());
        $addressBook->customer_id = auth()->user()->customer()->first()->id;
        $addressBook->save();
        return response()->json([
            'code' => 200,
            'message' => __('messages.create.success', ['name' => 'address book']),
            'data' => [
                'address_book' => $addressBook
            ]
        ], 200);
    }

    public function update(Request $request, $id) {
        $addressBook = new AddressBook();
        $addressBook = $addressBook->find($id);
        $request->merge(['customer_id' => auth()->user()->customer()->first()->id]);
        $validate = $addressBook->validate($request->all());
        if ($validate->fails()) {
            return response()->json([
                'code' => 400,
                'message' => __('messages.validation.error'),
                'data' => [
                    'errors' => $validate->errors()
                ]
            ], 400);
        }
        $addressBook->fill($request->all());
        $addressBook->save();
        return response()->json([
            'code' => 200,
            'message' => __('messages.update.success', ['name' => 'address book']),
            'data' => [
                'address_book' => $addressBook
            ]
        ], 200);
    }
}
