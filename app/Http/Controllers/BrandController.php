<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use App\Http\Controllers\DatabaseController as DB;

class BrandController extends Controller
{
    public function index(Request $request)
    {
        $db = new DB;
        $data = $db->query($request, "brands", ["id", "name", "description", "image"]);
        return response()->json(["data" => $data, "code" => 200], 200);
    }
    public function store()
    {
        $brand = new Brand;
        $brand->name = request("name");
        $brand->description = request("description");
        $brand->image = request("image");
        $brand->slug = request("slug");
        if (request("parent_id") != null) {
            $brand->parent_id = request("parent_id");
        }
        $brand->save();
        return response()->json(["data" => $brand, "code" => 201], 201);
    }
    public function show($id)
    {
        $brand = Brand::find($id);
        if (is_null($brand)) {
            return response()->json([
                "code" => 404,
                "message" => "Not found"
            ], 404);
        }
        return response()->json([
            "code" => 200,
            "data" => $brand
        ], 200);
    }
    public function update($id, Request $request)
    {
        $brand = Brand::find($id);
        if (is_null($brand)) {
            return response()->json([
                "code" => 404,
                "message" => "Not found"
            ], 404);
        }
        if (request("name") != null)
            $brand->name = request("name");
        if (request("description") != null)
            $brand->description = request("description");
        if (request("image") != null)
            $brand->image = request("image");
        if (request("slug") != null)
            $brand->slug = request("slug");
        if (request("parent_id") != null)
            $brand->parent_id = request("parent_id");
        if (request("status") != null)
            $brand->status = request("status");
        $brand->save();
        return response()->json([
            "code" => 200,
            "data" => $brand
        ], 200);
    }
    public function destroy($id)
    {
        $brand = Brand::find($id);
        if (is_null($brand)) {
            return response()->json([
                "code" => 404,
                "message" => "Not found"
            ], 404);
        }
        $brand->delete();
        return response()->json([
            "code" => 204,
            "message" => "Deleted"
        ], 204);
    }
}
