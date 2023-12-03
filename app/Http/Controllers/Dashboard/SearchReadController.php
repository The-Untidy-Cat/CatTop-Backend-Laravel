<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Controllers\DatabaseController;
use App\Rules\ExistedDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SearchReadController extends Controller
{
    public function index(Request $request)
    {
        $validate = Validator::make($request->all(), [
            "model" => ["required", "string", new ExistedDatabase()],
            "domain" => ["array"],
            "fields" => ["required", "array"],
            "offset" => ["integer", "min:0"],
            "limit" => ["integer", "min:1"],
        ]);
        if ($validate->fails()) {
            return response()->json([
                "code" => 400,
                "message" => __('messages.validation.error'),
                "errors" => $validate->errors(),
            ], 400);
        }

        $result = DatabaseController::searchRead(
            $request->model,
            $request->domain ?? [],
            $request->fields ?? [],
            [],
            [],
            ['*'],
            $request->offset ?? 0,
            $request->limit ?? 10,
            $request->order_by ?? null,
            $request->order ?? null
        );
        return response()->json([
            "code" => 200,
            "message" => __('messages.list.success', ['name' => $request->model]),
            "data" => $result
        ], 200);
    }
}
