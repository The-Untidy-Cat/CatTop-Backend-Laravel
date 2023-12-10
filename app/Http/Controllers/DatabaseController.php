<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DatabaseController extends Controller
{
    public static function query(Request $request, $table, array $columns)
    {
        $data = DB::table($table)->select($columns);
        if ($request->has("search")) {
            $data = $data->where("name", "like", "%" . $request->search . "%");
        }
        if ($request->has("conditions")) {
            foreach ($request->conditions as $condition) {
                if (gettype($condition[2]) == "array") {
                    foreach ($condition[2] as $value) {
                        $data = $data->where($condition[0], $condition[1], $value);
                    }
                } else {
                    $data = $data->where($condition[0], $condition[1], $condition[2]);
                }
            }
        }
        $length = $data->count();
        $offset = $request->has("offset") ? $request->offset : 0;
        $limit = $request->has("limit") ? $request->limit : 10;
        $data = $data->offset($offset)->limit($limit)->get();
        return ["limit" => $limit, "offset" => $offset, "length" => $length, "result" => $data];
    }
    public static function searchRead(string $model, array $conditions, array $attributes, array $with = [], array $joins = [], array $count_column = ['*'], int $offset = 0, int $limit = 0, string $order_by = null, string $order = null)
    {
        $model = app("App\\Models\\$model");
        $data = $model;
        $whereOperator = "&&";
        $joinSide = "left";
        foreach ($joins as $join) {
            if (gettype($join) == "string") {
                switch ($join) {
                    case "left":
                        $joinSide = "left";
                        break;
                    case "right":
                        $joinSide = "right";
                        break;
                    default:
                        $joinSide = "inner";
                        break;
                }
            } else {
                switch ($joinSide) {
                    case "left":
                        $data = $data->leftJoin($join[0], $join[1], $join[2], $join[3]);
                        break;
                    case "right":
                        $data = $data->rightJoin($join[0], $join[1], $join[2], $join[3]);
                        break;
                    default:
                        $data = $data->join($join[0], $join[1], $join[2], $join[3]);
                        break;
                }
            }
        }
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
        $count = $data->count($count_column);
        $records = $data;
        if (isset($limit) && $limit > 0) {
            $records = $records->limit($limit);
        }
        if (isset($offset) && $offset > 0) {
            $records = $records->offset($offset);
        }
        if (isset($with)) {
            $records = $records->with($with);
        }
        if (isset($order_by)) {
            $order = isset($order) ? $order : "asc";
            $records = $records->orderBy($order_by, $order);
        }
        $records = $records->get($attributes);
        return [
            "records" => $records,
            "limit" => $limit,
            "offset" => $offset,
            "length" => $count
        ];
    }
}
