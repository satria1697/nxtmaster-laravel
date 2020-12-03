<?php

namespace App\Http\Controllers\Siska;

use App\Http\Controllers\Controller;
use App\Models\Siska\AnalisisData;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use JamesDordoy\LaravelVueDatatable\Http\Resources\DataTableCollectionResource;

class AnalisisDataController extends Controller
{
    /* base */
    private function basecolumn() {
        return $basecolumn=[
            'idanalisis',
            'idformulir',
            'idformulirdata',
            'value',
        ];
    }

    private function validation($data) {
        $rules = [
            'checkeddata' => 'required'
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

        $checkeddata = json_decode($request->input('checkeddata'), true);
        foreach ($checkeddata as $cd) {
            $cdvalue = $cd['value'];
            foreach ($cdvalue as $c) {
                $data = new AnalisisData();
                $data->idanalisis = $request->input('id');
                $data->idformulir = $cd['id'];
                $data->idformulirdata = $c['id'];
                $data->nilai = $c['nilai'];
                $data->value = $c['value'];

                /* inserted and updated */
                $user = auth()->payload()->get('username');
                $currenttime = Carbon::now();
                $data->insertedby = $user;
                $data->updatedby = $user;
                $data->insertedat = $currenttime;
                $data->updatedat = $currenttime;
                try {
                    $data->save();
                } catch (\Throwable $tr) {
                    return Response::json([
                        'status' => 'error',
                        'errors' => $tr,
                    ], 422);
                }
            }
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
        $query = AnalisisData::eloquentQuery($sortBy, $orderBy, $searchValue);
        $data = $query->paginate($length);
        return new DataTableCollectionResource($data);
    }

    public function show(Request $request, $id)
    {
//        $data = AnalisisData::find($id);
//        return $request;
        $formulirjasa = json_decode($request->input('formulirid'), true);
//        $data = AnalisisData::where('idanalisis', '=', $id)->get();
//        return $formulirid;
        $data = [];
        foreach ($formulirjasa as $fjasa) {
            $parentobj = new \stdClass();
            $dataA = $dataAnalisis = AnalisisData::where('idanalisis', '=', $id)
                ->where('idformulir', '=', $fjasa)
                ->get();
//            return $dataA;
//            return $dataA->isEmpty();
            if ($dataA->isEmpty()) {
                return Response::json([
                    'error' => 'error'
                ], 204);
            }
            $parentobj->id = $fjasa;
            $childData = [];
            foreach ($dataA as $dat) {
                $childobj = new \stdClass();
                $childobj->id = $dat['idformulirdata'];
                $childobj->nilai = $dat['nilai'];
                $childobj->value = $dat['value'];
                array_push($childData, $childobj);
//                return $dat;
            }
//            return $dataA;
            $parentobj->value = $childData;
            array_push($data, $parentobj);
        }
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

//        $data = AnalisisData::find($id);
//        $data = AnalisisData::where('analisisid','=',$id)->first();
//        if (is_null($data)) {
//            return Response::json([
//                'error' => 'Data tidak ditemukan'
//            ], 403);
//        }

        try {
            $checkeddata = json_decode($request->input('checkeddata'), true);
            foreach ($checkeddata as $cd) {
                $cdvalue = $cd['value'];
                foreach ($cdvalue as $c) {
                    $data = AnalisisData::where('idanalisis', '=', $id)
                        ->where('idformulir', '=', $cd['id'])
                        ->where('idformulirdata', '=', $c['id'])
                        ->first();
//                    $dataid = $data['id'];
//                    return $dataid;
                    $data->nilai = $c['nilai'];
                    $data->value = $c['value'];

                    /* inserted and updated */
//                    return $data->wasChanged();
//                    $data->save();
//                     return $data->isDirty();
                    if ($data->isDirty() > 0) {
//                        return 'axcsd';
                        $user = auth()->payload()->get('username');
                        $currenttime = Carbon::now();
                        $data->update([
                            'updatedby' => $user,
                            'updatedat' => $currenttime,
                        ]);
                    }

                }
            }

            return Response::json([
                'status' => 'success'
            ], 200);
        } catch(\Throwable $tr) {
            return Response::json([
                'error' => 'error_update',
            ],304);
        }
    }

    /* delete */
    public function delete($id) {
        if (! $this->can()) {
            return Response::json([
                'error' => 'Tidak memiliki otorisasi',
            ], 403);
        }

        $data = AnalisisData::find($id);
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
