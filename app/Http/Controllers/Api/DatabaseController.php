<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DatabaseController extends Controller
{
    public function query(Request $request, $table, array $column)
    {
        $data = DB::table($table)->select($column);
        if ($request->has("search")) {
            $data = $data->where("name", "like", "%" . $request->search . "%");
        }
        if ($request->has("conditions")) {
            foreach ($request->conditions as $condition) {
                if (gettype($condition[3]) == "array") {
                    foreach ($condition[3] as $value) {
                        $data = $data->where($condition[0], $condition[1], $value);
                    }
                }
            }
        }
        $length = $data->count();
        $offset = $request->has("offset") ? $request->offset : 0;
        $limit = $request->has("limit") ? $request->limit : 10;
        $data = $data->offset($offset)->limit($limit)->get();
        return ["limit" => $limit, "offset" => $offset, "length" => $length, "result" => $data];
    }

    public function searchRead(string $model, array $conditions, array $attributes, array $with = [], int $offset = 0, int $limit = 20)
    {
        $data = app("App\\Models\\$model");
        $whereOperator = "&&";
        foreach ($conditions as $condition) {
            if (gettype($condition) == "string") {
                switch ($condition) {
                    case "||":
                        $whereOperator = "||";
                        break;
                    case "&&":
                    default:
                        $whereOperator = "&&";
                        break;
                }
            } else {
                switch ($whereOperator) {
                    case "||":
                        $data = $data->orWhere($condition[0], $condition[1], $condition[2]);
                        break;
                    case "&&":
                    default:
                        $data = $data->where($condition[0], $condition[1], $condition[2]);
                        break;
                }
            }
        }

        $offset = isset($offset) && $offset > 0 ? $offset : 0;
        $limit = isset($limit) && $limit > 0 ? $limit : 20;
        $records = $data->offset($offset)->limit($limit);
        if (isset($with)){
            $records = $records->with($with);
        }
        $records = $records->get($attributes);
        return [
            "records" => $records,
            "limit"=> $limit,
            "offset"=> $offset,
            "length"=> $data->count()
        ];
    }

}
