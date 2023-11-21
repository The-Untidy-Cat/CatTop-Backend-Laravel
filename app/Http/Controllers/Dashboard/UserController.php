<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getProfile(Request $request)
    {
        $user = $request->user()->employee();
        return response()->json([
            'code' => 200,
            'message' => __('messages.get.success', ['name' => 'profile']),
            'data' => $request->user()->employee()->first(['first_name', 'last_name', 'email', 'phone_number', 'state'])
            // ->only(['id', 'first_name', 'last_name', 'email', 'phone_number', 'state'])
        ], 200);
    }
}
