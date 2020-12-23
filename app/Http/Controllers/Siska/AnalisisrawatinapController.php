<?php

namespace App\Http\Controllers\Siska;

use App\Http\Controllers\Controller;
use App\Models\Admin\AksesUser;
use App\Models\Siska\AnalisisFormulir;
use App\Models\Siska\Analisisrawatinap;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use JamesDordoy\LaravelVueDatatable\Http\Resources\DataTableCollectionResource;
use function PHPUnit\Framework\isNull;

class AnalisisrawatinapController extends Controller
{
    /* base */
    private function basecolumn() {
        return $basecolumn=[
            'idranap',
            'tglkeluar',
            'tglinput',
            'dokter_id',
            'perawat_id',
//            'idformulir',
            'idstatus',
            'jatuhtempo',
            'tgllengkap',
        ];}

    private function validation($data) {
        $rules = [
            'idranap' => 'required',
            'tglkeluar' => 'required',
            'tglinput' => 'required',
            'dokter_id' => 'required',
            'perawat_id' => 'required',
//            'idformulir' => 'required',
            'idstatus' => 'required',
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

        $data = new Analisisrawatinap();
        $basecolumn = $this->basecolumn();
        foreach ($basecolumn as $base) {
            $data->{$base} = $request->input($base);
        }
        $data->save();
        $id = $data->id;
        $formulir = json_decode($request->input('formulir'), true);
//        return $formulir;
        foreach ($formulir as $a) {
            $analisisform = AnalisisFormulir::where('analisisid', '=', $id)->where('formulirid', '=', $a['id'])->first();
            if (! is_null($analisisform)) {
                $anformdelete = AnalisisFormulir::find($analisisform->id);
                $anformdelete->delete();
            }
            $analisisformulir = new AnalisisFormulir;
            $analisisformulir->analisisid = $id;
            $analisisformulir->formulirid = $a['id'];
            $analisisformulir->save();
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
        $query = Analisisrawatinap::eloquentQuery($sortBy, $orderBy, $searchValue, [
            "formulir",
            'perawat',
            'dokter',
            'ranap',
            'statuskelengkapan',
        ]);

        $data = $query->paginate($length);
        return new DataTableCollectionResource($data);
    }

    public function show($id)
    {
//        $data = Analisisrawatinap::find($id);
        $query = Analisisrawatinap::eloquentQuery('id', 'asc', '', [
            "formulir",
            'perawat',
            'dokter',
            'ranap',
            'statuskelengkapan',
        ]);

        $data = $query->where('nxt_siska_analisisrawatinap.id', '=', $id)->first();

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

        $data = Analisisrawatinap::find($id);
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

            $analisis = AnalisisFormulir::where('analisisid', '=', $id)->get();
            foreach ($analisis as $an) {
                AnalisisFormulir::find($an->id)->delete();
            }

            $formulir = json_decode($request->input('formulir'), true);
//            return $formulir;
            foreach ($formulir as $a) {
                $analisisform = AnalisisFormulir::where('analisisid', '=', $id)->where('formulirid', '=', $a['id'])->first();
                if (! is_null($analisisform)) {
                    $anformdelete = AnalisisFormulir::find($analisisform->id);
                    $anformdelete->delete();
                }
                $analisisformulir = new AnalisisFormulir;
                $analisisformulir->analisisid = $id;
                $analisisformulir->formulirid = $a['id'];
                $analisisformulir->save();
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

        $data = Analisisrawatinap::find($id);
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
