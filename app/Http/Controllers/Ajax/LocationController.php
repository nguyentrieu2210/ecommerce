<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    //
    public function changeLocation(Request $request) {
        $classInstance = 'App\Repositories\Interfaces\\' . $request->target . 'RepositoryInterface';
        $field = strtolower($request->location) . '_code';
        $dataLocation = app($classInstance)->findByCondition([
            [$field, '=', $request->code]
        ]);
        return response()->json(['message' => 'status', 'data' => $dataLocation]);
    }
}
