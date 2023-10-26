<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DatabaseController extends Controller
{
    public function query(Request $request, $table, array $column, array $conditions = [])
    {
        $data = DB::table($table)->select($column);
        if ($request->has("search")) {

            $data = $data->where("name", "like", "%" . $request->search . "%");
        }
        foreach ($conditions as $condition) {
            if (isset($condition["column"]) && isset($condition["operator"]) && isset($condition["value"]))
                $data = $data->where($condition["column"], $condition["operator"], $condition["value"]);
        }
        $length = $data->count();
        $offset = $request->has("offset") ? $request->offset : 0;
        $limit = $request->has("limit") ? $request->limit : 10;
        $data = $data->offset($offset)->limit($limit)->get();
        return ["limit" => $limit, "offset" => $offset, "length" => $length, "result" => $data];
    }
}
