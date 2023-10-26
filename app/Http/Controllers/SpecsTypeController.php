<?php

namespace App\Http\Controllers;

use App\Models\SpecsType;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SpecsTypeController extends Controller
{
    public function index()
    {
        $specsTypes = SpecsType::all();
        return response()->json([
            "code" => 200,
            "data" => $specsTypes
        ], 200);
    }
    public function store(Request $request)
    {
        $specType = new SpecsType();
        $specType->name = $request->name;
        $specType->id = $request->id;
        $specType->slug = $request->slug;
        $specType->description = $request->description;
        $specType->save();
        return response()->json([
            "code" => 201,
            "data" => $specType
        ], 201);
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
                "message" => "Not found"
            ], 404);
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
            "code" => 204,
            "message" => "Specs type deleted"
        ], 204);
    }
}
