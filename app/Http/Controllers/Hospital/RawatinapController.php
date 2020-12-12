<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use App\Models\Hospital\Operasi;
use App\Models\Hospital\Rawatinap;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use JamesDordoy\LaravelVueDatatable\Http\Resources\DataTableCollectionResource;

class RawatinapController extends Controller
{
    /* base */
    private function basecolumn() {
        return $basecolumn=[
            'pasien_id',
            'norm',
            'tglmasuk',
            'tglkeluar',
            'kelas_id',
            'bangsal_id',
            'kamar_id',
            'dokter_id',
            'jeniskasus',
            'tindakan',
            'caramasuk',
            'ketpulang',
            'carabayar',
            'operasi_id'
        ];
    }

    private function validation($data) {
        $rules = [
            'pasien_id' => 'required|numeric',
            'norm' => 'required',
            'tglmasuk' => 'required',
            'kelas_id' => 'required|numeric',
            'bangsal_id' => 'required|numeric',
            'kamar_id' => 'required|numeric',
            'dokter_id' => 'required|numeric',
            'caramasuk' => 'required|numeric',
            'ketpulang' => 'required|numeric',
            'carabayar' => 'required|numeric',
        ];
        $v = Validator::make($data, $rules);
        if ($v->fails()) {
            return [false, $v];
        }
        return [true, $v];
    }

    private function can() {
        $levelid = auth()->payload()->get('levelid');
        if ($levelid > 3) {
            return false;
        }
        return true;
    }

    /* create */
    public function store(Request $request)
    {
//        return $request;
        if (! $this->can()) {
            return Response::json([
                'error' => 'Tidak memiliki otorisasi',
            ], 403);
        }

        $value = $this->validation($request->all());
        $status = $value[0];
        $v = $value[1];
        if (! $status) {
            return Response::json([
                'status' => 'error',
                'error' => $v->errors(),
            ], 422);
        };

        $data = new Rawatinap();
        $basecolumn = $this->basecolumn();
        foreach ($basecolumn as $base) {
            $data->{$base} = $request->input($base);
        }

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
        ],200);
    }

    /* read */
    public function index(Request $request) {
        $length = $request->input('length');
        $sortBy = $request->input('column');
        $orderBy = $request->input('dir');
        $searchValue = $request->input('search');
        $query = Rawatinap::eloquentQuery($sortBy, $orderBy, $searchValue, [
            'kelas',
            'bangsal',
            'kamarranap',
            'dokter',
            'pasien',
            'operasi',
        ]);
        $data = $query->paginate($length);
        return new DataTableCollectionResource($data);
    }

    public function show($id)
    {
        $query = Rawatinap::eloquentQuery('id', 'asc', '', [
            'kelas',
            'bangsal',
            'kamarranap',
            'dokter',
            'pasien',
            'operasi',
        ]);
        $data = $query->where('nxt_hospital_rawatinap.id', '=', $id)->first();
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
            return Response::json([
                'error' => 'Tidak memiliki otorisasi',
            ], 403);
        }

        if (! $this->validation($request->all())) {
            return Response::json([
                'status' => 'error',
            ], 422);
        }

        $data = Rawatinap::find($id);
        if (is_null($data)) {
            return Response::json([
                'error' => 'Data tidak ditemukan'
            ], 403);
        }

        $basecolumn = $this->basecolumn();
//        return $request;
        try {
            foreach ($basecolumn as $base) {
                $data->update([
                    $base => $request->input($base)
                ]);
            }
            $operasibasecolumn=[
                'tgloperasi',
//                'tglkeluar',
                'dokter_id',
                'dokteranestesi_id',
//                'icd10_id',
                'tindakan',
                'jenisanestesi',
                'perawat_id',
            ];
            $operasidata = json_decode($request->input('operasi'), true);
            if ($request->input('operasi_id') == "undefined") {
                $operasi = new Operasi();
                foreach ($operasibasecolumn as $base) {
                    $operasi->{$base} = $operasidata[$base];
                }
                $operasi->ranap_id = $id;
                $operasi->save();
            } else {
                $operasi = Operasi::find($request->input('operasi_id'))->first();
//                return Response::json([
//                    'data1' => $operasi,
//                    'data2' => $operasidata,
//                ], 403);
                foreach ($operasibasecolumn as $base) {
//                    return $operasidata[$base];
                    $operasi->update([
                        $base => $operasidata[$base]
                    ]);
                }
            }
            return Response::json([
                'status' => 'success'
            ], 200);
        } catch(\Throwable $tr) {
            return Response::json([
                'error' => 'error_update',
                'data' => $tr,
            ],403);
        }
    }

    /* delete */
    public function delete($id) {
        if (! $this->can()) {
            return Response::json([
                'error' => 'Tidak memiliki otorisasi',
            ], 403);
        }

        $data = Rawatinap::find($id);
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
                'error' => 'Entry gagal dihapus',
                'data' => $tr
            ], 304);
        }
    }

    /* custom */
}
