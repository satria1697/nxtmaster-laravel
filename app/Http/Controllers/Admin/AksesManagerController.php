<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Akses;
use App\Models\Admin\AksesManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use JamesDordoy\LaravelVueDatatable\Http\Resources\DataTableCollectionResource;

class AksesManagerController extends Controller
{
    public function Data() {
        $data = AksesManager::where('roleid', '=', '5')->get();
        return Response::json([
            'status' => 'success',
            'data' => $data,
        ]);
    }
    public function DataId($id) {
        $data = AksesManager::find($id);
        $length = 5;
        $sortBy = 'id';
        $orderBy = 'asc';
        $searchValue = '';
        $query = AksesManager::eloquentQuery($sortBy, $orderBy, $searchValue, [
            'modul',
            'application',
        ]);
        $query = $query->where('nxt_rolemanagers.id', '=', $id);
        $data = $query->paginate($length);
        return new DataTableCollectionResource($data);
    }

    public function DataRootId($id) {
        $dataRoot = AksesManager::where('rolelevelid', '=', '1')->where('roleid', '=', $id)->get();
        if (is_null($dataRoot)) {
            return Response::json([
                'error' => 'not_found'
            ], 403);
        }
        foreach ($dataRoot as $dataR) {
            $dataParent = AksesManager::where('rolelevelid', '=', '2')->where('parentid', '=', $dataR['id'])->get();
            $dataR['children'] = $dataParent;
            foreach ($dataParent as $d) {
                $sortBy = 'id';
                $orderBy = 'asc';
                $searchValue = '';
                $query = AksesManager::eloquentQuery($sortBy, $orderBy, $searchValue, [
                    'modul',
                    'application',
                ]);
                $query = $query->where('rolelevelid', '=', 3)->where('parentid', '=', $d['id']);
                $childdata = $query->get();
                $d['children'] = $childdata;
            }
        }
        return Response::json([
            'status' => 'success',
            'data' => $dataRoot,
        ]);
    }

    public function DataRoot(Request $request) {
        $data = AksesManager::where('rolelevelid', '=', '1')->where('roleid', '=', $request->input('idakses'))->get();
        return Response::json([
            'status' => 'success',
            'data' => $data,
        ]);
    }

    public function DataParent(Request $request) {
        $data = AksesManager::where('rolelevelid', '=', '2')->where('roleid', '=', $request->input('idakses'))->get();
        return Response::json([
            'status' => 'success',
            'data' => $data,
        ]);
    }
    public function DataChild(Request $request) {
        $data = AksesManager::where('rolelevelid', '=', '3')->where('id', '=', $request->parentid)->get();
        return Response::json([
            'status' => 'success',
            'data' => $data,
        ]);
    }

    public function Register(Request $request) {
        $levelid = auth()->payload()->get('levelid');
        if ($levelid > 3) {
            return response()->json([
                'error' => 'Tidak memiliki otorisasi',
            ], 403);
        }

        if ($request->input('icon') == "null" || $request->input('icon') == null) {
            $icon = "";
        } else {
            $icon = $request->input('icon');
        }

        $data = new AksesManager();
        $data->text = $request->input('text');
        $data->roleid = $request->input('roleid');
        $data->rolelevelid = $request->input('rolelevelid');
        $data->icon = $icon;
        if ($request->input('rolelevelid') == 1) {
            $data->parentid = 0;
            $data->applicationid = 0;
            $data->moduleid = 0;
        }
        if ($request->input('rolelevelid') == 2) {
            $data->parentid = $request->input('parentid');
            $data->applicationid = 0;
            $data->moduleid = 0;
        }
        if ($request->input('rolelevelid') == 3) {
            $data->parentid = $request->input('parentid');
            $data->applicationid = $request->input('applicationid');
            $data->moduleid = $request->input('moduleid');
        }
        try {
            $data->save();
            return response()->json(['status' => 'success'], 200);
        } catch (\Throwable $tr) {
            return response()->json([
                'status' => 'error',
                'errors' => $tr,
            ], 304);
        }
    }

    public function Update(Request $request, $id) {
        $levelid = auth()->payload()->get('levelid');
        if ($levelid > 3) {
            return response()->json([
                'error' => 'Tidak memiliki otorisasi',
            ], 403);
        }

        $data = AksesManager::find($id);
        try {
            if ($request->input('icon') == "null" || $request->input('icon') == null) {
                $icon = "";
            } else {
                $icon = $request->input('icon');
            }
            $data->update([
                'text' => $request->input('text'),
                'roleid' => $request->input('roleid'),
                'rolelevelid' => $request->input('rolelevelid'),
                'icon' => $icon
            ]);
            if ($request->input('rolelevelid') == 1) {
                $data->update([
                    'parentid' => 0,
                    'applicationid' => 0,
                    'moduleid' => 0,
                ]);
            }
            if ($request->input('rolelevelid') == 2) {
                $data->update([
                    'parentid' => $request->input('parentid'),
                    'applicationid' => 0,
                    'moduleid' => 0,
                ]);
            }
            if ($request->input('rolelevelid') == 3) {
                $data->update([
                    'parentid' => $request->input('parentid'),
                    'applicationid' => $request->input('applicationid'),
                    'moduleid' => $request->input('moduleid'),
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

    public function DataDelete($id) {
        $data = AksesManager::find($id);

        try {
            $data->delete();
            return Response::json([
                'status' => 'success'
            ],204);
        } catch (\Throwable $tr) {
            return Response::json([
                'error' => 'error',
                'data' => $tr,
            ]);
        }
    }
}
