<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\Structure;
use Illuminate\Http\Request;
use JamesDordoy\LaravelVueDatatable\Http\Resources\DataTableCollectionResource;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;

class StructureController extends Controller
{
    public function Data(Request $request) {
        $length = $request->input('length');
        $sortBy = $request->input('column');
        $orderBy = $request->input('dir');
        $searchValue = $request->input('search');
        $query = Structure::eloquentQuery(
            $sortBy,
            $orderBy,
            $searchValue,
            [
                "structurelevel",
            ]
        );
        $data = $query->paginate($length);
        foreach ($data as $d) {
            if ($d['signability'] == 1) {
                $d['signability'] = 'Dapat menandatangani dokumen';
            } else {
                $d['signability'] = 'Tidak dapat menandatangani dokumen';
            }
        }
        return new DataTableCollectionResource($data);
//        $dataRoot = Structure::where('parentid', '=', 0)->first();
//        $dataParent = Structure::where('parentid', '=', $dataRoot['id'])->get();
//        $dataRoot['children'] = $dataParent;
//        foreach ($dataParent as $dp) {
//            $dataChild = Structure::where('parentid', '=', $dp['id'])->get();
//            $dp['children'] = $dataChild;
//        }
//        return Response::json([
//            'status' => 'success',
//            'data' => $dataRoot,
//        ], 200);
    }

    public function DataId($id)
    {
        $structure = Structure::find($id);
        return response()->json([
            'status' => 'success',
            'data' => $structure,
        ]);
    }

    public function DataDelete($id) {
        $levelid = auth()->payload()->get('levelid');
        if ($levelid > 2) {
            return response()->json([
                'error' => 'Tidak memiliki otorisasi',
            ], 403);
        }

        $data = Structure::find($id);
        if (is_null($data)) {
            return response()->json([
                'error' => 'Data tidak ditemukan'
            ], 404);
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

    public function register(Request $request)
    {
        $v = Validator::make($request->all(), [
            'description' => 'required|min:3',
            'structurelevelid' => 'required',
            'parentid' => 'required',
        ]);
        if ($v->fails())
        {
            return response()->json([
                'status' => 'error',
                'errors' => $v->errors(),
            ], 422);
        }
        $data = new Structure();
        $data->description = $request->input('description');
        $data->structurelevelid = $request->input('structurelevelid');
        $data->parentid = $request->input('parentid');
        $data->signability = $request->input('signability');
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

    public function Update(Request $request, $id)
    {
        $v = Validator::make($request->all(), [
            'description' => 'required|min:3',
            'structurelevelid' => 'required',
            'parentid' => 'required',
        ]);

        if ($v->fails())
        {
            return response()->json([
                'status' => 'error',
                'errors' => $v->errors()
            ], 422);
        }

        $levelid = auth()->payload()->get('levelid');
        if ($levelid > 2) {
            return response()->json([
                'error' => 'Tidak memiliki otorisasi',
            ], 403);
        }

        $data = Structure::find($id);
        if (is_null($data)) {
            return response()->json([
                'error' => 'Data tidak ditemukan'
            ], 404);
        }

        try {
            $data->update([
                'description' => $request->input('description'),
                'structurelevelid' => $request->input('structurelevelid'),
                'parentid' => $request->input('parentid'),
                'signability' => $request->input('signability'),
            ]);
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
}
