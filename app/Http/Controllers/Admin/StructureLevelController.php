<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\StructureLevel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use JamesDordoy\LaravelVueDatatable\Http\Resources\DataTableCollectionResource;
use App\Http\Controllers\Controller;

class StructureLevelController extends Controller
{
    /* base */
    private function basecolumn() {
        return $basecolumn=['description'];
    }

    private function validation($data) {
        $rules = [
            'description' => 'required|min:3',
        ];
        $v = Validator::make($data, $rules);
        if ($v->fails()) {
            return false;
        }
        return true;
    }

    private function can() {
        $levelid = auth()->payload()->get('levelid');
        if ($levelid > 2) {
            return false;
        }
        return true;
    }

    /* create */
    public function store(Request $request)
    {
        if (! $this->can()) {
            return response()->json([
                'error' => 'Tidak memiliki otorisasi',
            ], 403);
        }

        if (! $this->validation($request->all())) {
            return response()->json([
                'status' => 'error',
            ], 422);
        }

        $data = new StructureLevel();
        $basecolumn = $this->basecolumn();
        foreach ($basecolumn as $base) {
            $data->{$base} = $request->input($base);
        }
        $data->id = $request->input('id');

        try {
            $data->save();
        } catch (\Throwable $tr) {
            return Response::json([
                'status' => 'error',
                'errors' => $tr,
            ], 422);
        }

        return Response::json([
            'status' => 'success'
        ], 200);
    }

    /* read */
    public function index(Request $request) {
        $length = $request->input('length');
        $sortBy = $request->input('column');
        $orderBy = $request->input('dir');
        $searchValue = $request->input('search');
        $query = StructureLevel::eloquentQuery($sortBy, $orderBy, $searchValue);
        $data = $query->paginate($length);
        return new DataTableCollectionResource($data);
    }

    public function show($id)
    {
        $data = StructureLevel::find($id);
        if (is_null($data)) {
            return Response::json([
                'error' => 'Data tidak ditemukan'
            ], 403);
        }
        return Response::json([
            'status' => 'success',
            'data' => $data,
        ], 200);
    }

    /* update */
    public function update(Request $request, $id)
    {
        if (! $this->can()) {
            return response()->json([
                'error' => 'Tidak memiliki otorisasi',
            ], 403);
        }

        if (! $this->validation($request->all())) {
            return response()->json([
                'status' => 'error',
            ], 422);
        }

        $data = StructureLevel::find($id);
        if (is_null($data)) {
            return Response::json([
                'error' => 'Data tidak ditemukan'
            ], 403);
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
            ], 304);
        }
    }

    /* delete */
    public function delete($id) {
        if (! $this->can()) {
            return response()->json([
                'error' => 'Tidak memiliki otorisasi',
            ], 403);
        }

        $data = StructureLevel::find($id);
        if (is_null($data)) {
            return Response::json([
                'error' => 'Data tidak ditemukan'
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
            ], 304);
        }
    }

    /* custom */
}
