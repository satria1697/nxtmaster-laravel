<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\Application;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use JamesDordoy\LaravelVueDatatable\Http\Resources\DataTableCollectionResource;

class ApplicationController extends Controller
{
    public function Data(Request $request) {
        $length = $request->input('length');
        $sortBy = $request->input('column');
        $orderBy = $request->input('dir');
        $searchValue = $request->input('search');
        $query = Application::eloquentQuery($sortBy, $orderBy, $searchValue);

        $data = $query->paginate($length);
        return new DataTableCollectionResource($data);
    }

    public function DataId($id)
    {
        $data = Application::find($id);
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

    public function DataDelete($id) {
        $levelid = auth()->payload()->get('levelid');
        $data = Application::find($id);
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

    public function register(Request $request)
    {
        $v = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'description' => 'min:3',
            'path' => 'required',
        ]);
        if ($v->fails())
        {
            return response()->json([
                'status' => 'error',
                'errors' => $v->errors()
            ], 422);
        }

        $data = new Application();
        $data->name = $request->input('name');
        $data->description = $request->input('description');
        $data->path = $request->input('path');
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
            'name' => 'required|min:3',
            'description' => 'required|min:3',
            'path' => 'required',
        ]);
        if ($v->fails())
        {
            return response()->json([
                'status' => 'error',
                'errors' => $v->errors()
            ], 422);
        }

        $data = Application::find($id);
        $levelid = auth()->payload()->get('levelid');

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
            $data->update([
                'name' => $request->input('name'),
                'description' => $request->input('description'),
                'path' => $request->input('path')
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
