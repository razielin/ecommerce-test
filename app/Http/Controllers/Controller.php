<?php

namespace App\Http\Controllers;

abstract class Controller
{
    protected function successJson($data)
    {
        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }
}
