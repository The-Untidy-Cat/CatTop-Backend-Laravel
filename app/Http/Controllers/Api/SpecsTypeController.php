<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SpecsType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SpecsTypeController extends Controller
{
    public function index(Request $request)
    {
        $database = new DatabaseController;
        $specsTypes = $database->query($request, "specs_types", ["id", "name", "slug", "description"]);
        return response()->json([
            "code" => 200,
            "data" => $specsTypes
        ], 200);
    }
    public function store(Request $request)
    {
        $specType = new SpecsType();
        $validator = $specType->validate($request->all());
        if ($validator->fails()) {
            return response()->json([
                "code" => 400,
                "data" => $validator->errors()
            ], 400);
        }
        $specType->name = $request->name;
        $specType->id = $request->id;
        $specType->slug = $request->slug;
        $specType->description = $request->description;
        $specType->save();
        return response()->json([
            "code" => 200,
            "data" => $specType
        ], 200);
    }
    public function show($id)
    {
        $specType = SpecsType::find($id);
        if (is_null($specType)) {
            return response()->json([
                "code" => 404,
                "message" => "Specs type not found"
            ], 404);
        }
        return response()->json([
            "code" => 200,
            "data" => $specType
        ], 200);
    }
    public function update(Request $request, $id)
    {
        $specType = SpecsType::find($id);
        if (is_null($specType)) {
            return response()->json([
                "code" => 404,
                "message" => "Not founda"
            ], 404);
        }
        $specType->name = $request->name ?? $specType->name;
        $specType->id = $request->id ?? $specType->id;
        $specType->slug = $request->slug ?? $specType->slug;
        $specType->description = $request->description ?? $specType->description;
        $specType->save();
        return response()->json([
            "code" => 200,
            "data" => $specType
        ], 200);
    }
    public function destroy($id)
    {
        $specType = SpecsType::find($id);
        if (is_null($specType)) {
            return response()->json([
                "code" => 404,
                "message" => "Specs type not found"
            ], 404);
        }
        $specType->delete();
        return response()->json([
            "code" => 200,
            "message" => "Specs type deleted"
        ], 200);
    }
}
