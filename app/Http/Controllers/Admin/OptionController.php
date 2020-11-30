<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Option;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class OptionController extends Controller
{
    public function Data() {
        $data = Option::all();
        foreach ($data as $d){
            if (Storage::exists($d['avatar'])) {
                $image = Storage::path($d['avatar']);
                if (pathinfo($image)['extension'] === 'jpg' or pathinfo($image)['extension'] === 'jpeg') {
                    $image64 = 'data:image/jpeg;base64,'.base64_encode(file_get_contents($image));
                } elseif (pathinfo($image)['extension'] === 'png') {
                    $image64 = 'data:image/png;base64,'.base64_encode(file_get_contents($image));
                }
                $d['avatar'] = $image64;
            }
            unset($d);
        }
        return Response::json([
            'status' => 'success',
            'data' => $data,
        ], 200);
    }

    public function Register() {
        $option = new Option();
    }
}
