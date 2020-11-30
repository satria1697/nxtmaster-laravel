<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\Akses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use JamesDordoy\LaravelVueDatatable\Http\Resources\DataTableCollectionResource;
use App\Http\Controllers\Controller;

class AksesController extends Controller
{
    /* base */
    private function basecolumn() {
        return $basecolumn=[
            'description',
            'active',
        ];
    }

    private function validation($data) {
        $rules = [
            'description' => 'required|min:3',
            'active' => 'required|numeric'
        ];
        $v = Validator::make($data, $rules);
        if ($v->fails()) {
            return [false, $v];
        }
        return [true, $v];
    }

    private function can() {
        $levelid = auth()->payload()->get('levelid');
        if ($levelid > 2) {
            return false;
        }
        return true;
    }

    /* create */
    public function register(Request $request)
    {
        $value = $this->validation($request->all());
        $status = $value[0];
        $v = $value[1];
        if (! $status) {
            return Response::json([
                'status' => 'error',
                'error' => $v->errors(),
            ], 422);
        }

        $data = new Akses();
        $basecolumn = $this->basecolumn();
        foreach ($basecolumn as $base) {
            $data->{$base} = $request->input($base);
        }

        try {
            $data->save();
        } catch (\Throwable $tr) {
            return response()->json([
                'status' => 'error',
                'errors' => $tr,
            ], 422);
        }

        return response()->json(['status' => 'success'], 200);
    }

    /* read */
    public function index(Request $request) {
        $length = $request->input('length');
        $sortBy = $request->input('column');
        $orderBy = $request->input('dir');
        $searchValue = $request->input('search');
        $query = Akses::eloquentQuery($sortBy, $orderBy, $searchValue, [
            'application'
        ]);

        $data = $query->paginate($length);
        return new DataTableCollectionResource($data);
    }

    public function show($id)
    {
        $data = Akses::find($id);
        if (is_null($data)) {
            return response()->json([
                'error' => 'Data tidak ditemukan'
            ], 404);
        }
        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    }

    /* update */
    public function update(Request $request, $id)
    {
        $value = $this->validation($request->all());
        $status = $value[0];
        $v = $value[1];
        if (! $status) {
            return Response::json([
                'status' => 'error',
                'error' => $v->errors(),
            ], 422);
        }

        $data = Akses::find($id);

        if (is_null($data)) {
            return response()->json([
                'error' => 'Data tidak ditemukan'
            ], 404);
        }

        $basecolumn = $this->basecolumn();
        try {
            foreach ($basecolumn as $base) {
                $data->update([
                    $base => $request->input($base)
                ]);
            }
            return Response::json([
                'status' => 'success'
            ], 200);
        } catch(\Throwable $tr) {
            return Response::json([
                'error' => 'error_update',
                'data' => $tr,
            ], 403);
        }
    }

    /* delete */
    public function delete($id) {
        $levelid = auth()->payload()->get('levelid');
        $data = Akses::find($id);
        if (is_null($data)) {
            return response()->json([
                'error' => 'Data tidak ditemukan'
            ], 404);
        }
        if ($levelid > 2) {
            return response()->json([
                'error' => 'Tidak memiliki otorisasi',
            ], 403);
        }
        try {
            $data->delete();
            return Response::json([
                'status' => 'success',
                'data' => 'Entry berhasil dihapus'
            ], 204);
        } catch (\Throwable $tr) {
            return Response::json([
                'error' => 'Entry gagal dihapus'
            ], 422);
        }
    }
}
