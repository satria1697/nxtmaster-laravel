<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Option;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class OptionController extends Controller
{
    public function index() {
        $data = Option::all();
        foreach ($data as $d){
            if (Storage::exists('images/'.$d['avatar'])) {
                $image = Storage::path('images/'.$d['avatar']);
                if (pathinfo($image)['extension'] === 'jpg' or pathinfo($image)['extension'] === 'jpeg') {
                    $image64 = 'data:image/jpeg;base64,'.base64_encode(file_get_contents($image));
                } elseif (pathinfo($image)['extension'] === 'png') {
                    $image64 = 'data:image/png;base64,'.base64_encode(file_get_contents($image));
                }
                $d['avatar'] = $image64;
            } else {
                $d['avatar'] = null;
            }
            unset($d);
        }
        return Response::json([
            'status' => 'success',
            'data' => $data,
        ], 200);
    }

    public function update(Request $request, $id) {
        $data = Option::find($id);

        if ($request->avatar == "null") {
            $avatarFile = null;
        } else {
            $avatar64 = $request->avatar;
            $extension = explode('/', mime_content_type($avatar64))[1];
            if ($extension === 'jpeg' or $extension === 'jpg') {
                $avatar = base64_decode(str_replace('data:image/jpeg;base64,', '', $avatar64));
                return $avatar;
            } elseif ($extension === 'png') {
                $avatar = base64_decode(str_replace('data:image/png;base64,', '', $avatar64));
            }
            $now = Carbon::now()->timestamp;
            Storage::put('images/'.$now . '.jpg', $avatar);
            $avatarFile = $now . '.jpg';
        }

        try {
            $data->update([
                'header1' => $request->input('header1'),
                'header2' => $request->input('header2'),
                'companycode' => $request->input('companycode'),
                'companyname' => $request->input('companyname'),
                'companyaddress' => $request->input('companyaddress'),
                'companycity' => $request->input('companycity'),
                'companyphone' => $request->input('companyphone'),
                'companyfax' => $request->input('companyfax'),
                'companyemail' => $request->input('companyemail'),
                'companyfacebookid' => $request->input('companyfacebookid'),
                'companytwitterid' => $request->input('companytwiiterid'),
                'avatar' => $avatarFile,

            ]);
            Response::json([
                'status' => 'success',
            ], 200);
        } catch (\Throwable $tr) {
            Response::json([
                'error' => 'error',
                'error_status' => $tr
            ],403);
        }
    }
}
