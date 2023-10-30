<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CsrfController extends Controller
{
    //
    /**
     * Return an empty response simply to trigger the storage of the CSRF cookie in the browser.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function show(Request $request)
    {
        if ($request->expectsJson()) {
            return response()->json([
                "data" => csrf_token(),
                "code" => 200,
            ], 200);
        }

        return response()->json([
            "data" => csrf_token(),
            "code" => 200,
        ], 200);
    }
}
