<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\RoleLevel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;

class RoleLevelController extends Controller
{
    public function Data() {
        $data = RoleLevel::all();
        return Response::json([
            'status' => 'success',
            'data' => $data,
        ]);
    }
}
