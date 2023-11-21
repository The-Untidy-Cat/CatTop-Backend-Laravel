<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Rules\ExistedDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SearchReadController extends Controller
{
    public function index(Request $request)
    {
        $db = new DatabaseController();
        $validate = Validator::make($request->all(), [
            "model" => ["required", "string", new ExistedDatabase],
            // ""
        ]);
        if ($validate->fails()) {
            return response()->json([
                "code" => 400,
                "message" => __('messages.validation.error'),
                "errors" => $validate->errors(),
            ], 400);
        }

        // $db->searchRead();
        return response()->json([], 200);
    }
}
