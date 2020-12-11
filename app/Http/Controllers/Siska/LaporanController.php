<?php

namespace App\Http\Controllers\Siska;

use App\Http\Controllers\Controller;
use App\Models\Hospital\TenagaMedis;
use App\Models\Siska\AnalisisData;
use App\Models\Siska\AnalisisFormulir;
use App\Models\Siska\Analisisrawatinap;
use Barryvdh\DomPDF\PDF;
use Carbon\Carbon;
use Chartisan\PHP\Chartisan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use JamesDordoy\LaravelVueDatatable\Http\Resources\DataTableCollectionResource;

class LaporanController extends Controller
{
    /* base */
    private function basecolumn() {
        return $basecolumn=[
            'idranap',
            'tglinput',
            'iddokter',
            'idperawat',
//            'idformulir',
//            'idstatus',
            'jatuhtempo',
            'tgllengkap',
        ];}

    private function validation($data) {
        $rules = [
            'idranap' => 'required',
            'tglinput' => 'required',
            'iddokter' => 'required',
            'idperawat' => 'required',
//            'idformulir' => 'required',
//            'idstatus' => 'required',
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
        $sortBy = $request->input('column');
        $orderBy = $request->input('dir');
        $searchValue = $request->input('search');

        $tglawal = $request->input('tglawal');
        $tglawal = Carbon::parse($tglawal);
        $tglakhir = $request->input('tglakhir');
        $tglakhir = Carbon::parse($tglakhir)->addMonthNoOverflow();
        $diffindays = $tglakhir->diffInDays($tglawal);

        $diffinmonth =  round($diffindays/30);

        $bulantext = array();
        $bulan = array();
        for ($x = 0; $x < $diffinmonth; $x++) {
            if ($x == 0) {
                $bulanmulai = Carbon::parse($tglawal)->startOfDay()->format('Y-m-d');
            } else {
                $bulanmulai = Carbon::parse($bulan[$x-1]->bulanawal)->addMonth()->startOfDay()->format('Y-m-d');
            }
            $bulanakhir = Carbon::parse($bulanmulai)->endOfMonth()->endOfDay()->format('Y-m-d');
            $obj = new \stdClass();
            $obj->bulanawal = $bulanmulai;
            $obj->bulanakhir = $bulanakhir;
            array_push($bulantext, Carbon::parse($bulanmulai)->translatedFormat('F Y'));
            $bulan[$x] = $obj;
        }

        $pengambilandata = $request->input('pengambilanData');

        $dataBulan = array();
        for ($x = 0; $x < $diffinmonth; $x++) {
            $query = Analisisrawatinap::eloquentQuery($sortBy, $orderBy, $searchValue, [
                "formulir",
                'perawat',
                'dokter',
                'ranap',
            ]);
            $bulanawal = Carbon::parse($bulan[$x]->bulanawal);
            $bulanakhir = Carbon::parse($bulan[$x]->bulanakhir)->addDay();

            $getBulan = $query
                ->where('tglinput', '>=', $bulanawal)
                ->where('tglinput', '<', $bulanakhir)
                ->whereNotNull('tgllengkap')
                ->get();
            array_push($dataBulan, $getBulan);
        }

        $terhadap = $request->input('terhadap');
//        return $terhadap;

        $dataTotalArray = array();
        if ($terhadap == 1) {
           foreach ($dataBulan as $tiapBulan) {
               $totalNilai = 0;
               if (count($tiapBulan) !== 0) {
                   foreach ($tiapBulan as $bulan) {
                       if (! empty($bulan)) {
                           $totalNilai += (int)$bulan->nilaitotal;
                       }
                   }
                   array_push($dataTotalArray, $totalNilai);
               } else {
                   array_push($dataTotalArray, $totalNilai);
               }
           }
           $terhadaptext = "Semua Rekam Medis";
        }

        if ($terhadap == 2) {
            if ($pengambilandata == 0) {
                $dokters = TenagaMedis::where('jenis_id', '=', 1)->get();
            } else {
                $dokters = TenagaMedis::where('jenis_id', '=', 1)
                    ->where('id', '=', $request->input('dokter_id'))
                    ->get();
            }
            foreach ($dataBulan as $tiapBulan) {
                $totalNilai = 0;
                if (count($tiapBulan) !== 0) {
                    foreach ($tiapBulan as $bulan) {
                        foreach ($dokters as $dokter) {
                            if (!empty($bulan)) {
                                foreach ($bulan->formulir as $formulir) {
                                    $pivot = $formulir['pivot'];
                                    $forms = AnalisisData::where('idanalisis', '=', $pivot['analisisid'])
                                        ->where('idformulir', '=', 1)
                                        ->where('dokter_id', '=', $dokter->id)
                                        ->get();
                                    foreach ($forms as $form) {
                                        if (!empty($form)) {
                                            $totalNilai += 2;
                                        }
                                    }
                                }
                            }
                        }
                    }
                    array_push($dataTotalArray, $totalNilai);
                } else {
                    array_push($dataTotalArray, $totalNilai);
                }
            }
            $terhadaptext = "Semua Rekam Medis Dokter";
        }

        if ($terhadap == 3) {
            if ($pengambilandata == 1) {
                $perawats = TenagaMedis::where('jenis_id', '=', 2)->get();
            } else {
                $perawats = TenagaMedis::where('jenis_id', '=', 2)
                    ->where('id', '=', $request->input('perawat_id'))
                    ->get();
            }
            foreach ($dataBulan as $tiapBulan) {
                $totalNilai = 0;
                if (count($tiapBulan) !== 0) {
                    foreach ($tiapBulan as $bulan) {
                        foreach ($perawats as $perawat) {
                            if (!empty($bulan)) {
                                foreach ($bulan->formulir as $formulir) {
                                    $pivot = $formulir['pivot'];
                                    $forms = AnalisisData::where('idanalisis', '=', $pivot['analisisid'])
                                        ->where('idformulir', '=', 2)
                                        ->where('perawat_id', '=', $perawat->id)
                                        ->get();
                                    foreach ($forms as $form) {
                                        if (!empty($form)) {
                                            $totalNilai += 2;
                                        }
                                    }
                                }
                            }
                        }
                    }
                    array_push($dataTotalArray, $totalNilai);
                } else {
                    array_push($dataTotalArray, $totalNilai);
                }
            }
            $terhadaptext = "Semua Rekam Medis Perawat";
        }

//        return $dataTotalArray;

        $idperawat = $request->input('perawat_id');

        $iddokter = $request->input('dokter_id');
//        return $idperawat;


        $dataNilaiArray = array();
        if ($pengambilandata == 0 || $pengambilandata == 3) {
            if ($pengambilandata == 0 ) {
                $dokters = TenagaMedis::where('jenis_id', '=', 1)->get();
            } else {
                $dokters = TenagaMedis::where('jenis_id', '=', 1)
                    ->where('id', '=', $iddokter)
                    ->get();
            }
            foreach ($dokters as $dokter) {
                $nilaidokterbulan = array();
                foreach ($dataBulan as $tiapBulan) {
                    $nilaidokter = 0;
                    if (count($tiapBulan) !== 0) {
                        foreach ($tiapBulan as $bulan) {
                            foreach ($bulan->formulir as $formulir) {
                                $pivot = $formulir['pivot'];
                                $forms = AnalisisData::where('idanalisis', '=', $pivot['analisisid'])
                                    ->where('idformulir', '=', 1)
                                    ->where('dokter_id', '=', $dokter->id)
                                    ->get();
                                foreach ($forms as $form) {
                                    if (!empty($form)) {
                                        $nilaidokter += (int)$form->nilai;
                                    }
                                }
                            }
                        }
                    }
                    array_push($nilaidokterbulan, $nilaidokter);
                }
                array_push($dataNilaiArray, $nilaidokterbulan);
            }
            if ($pengambilandata == 0) {
                $who = "Dokter";
            } else {
                $who = $request->input('namadokter');
            }

            $whodata = $dokters;
        }



        if ($pengambilandata == 1 || $pengambilandata == 4) {
            if ($pengambilandata == 1 ) {
                $perawats = TenagaMedis::where('jenis_id', '=', 2)->get();
            } else {
                $perawats = TenagaMedis::where('jenis_id', '=', 2)
                    ->where('id', '=', $idperawat)
                    ->get();
            }
//            return $perawats;
            foreach ($perawats as $perawat) {
                $nilaiperawatbulan = array();
                foreach ($dataBulan as $tiapBulan) {
                    $nilaiperawat = 0;
                    if (count($tiapBulan) !== 0) {
                        foreach ($tiapBulan as $bulan) {
                            foreach ($bulan->formulir as $formulir) {
                                $pivot = $formulir['pivot'];
                                $forms = AnalisisData::where('idanalisis', '=', $pivot['analisisid'])
                                    ->where('idformulir', '=', 2)
                                    ->where('perawat_id', '=', $perawat->id)
                                    ->get();
                                foreach ($forms as $form) {
                                    if (!empty($form)) {
                                        $nilaiperawat += (int)$form->nilai;
                                    }
                                }
                            }
                        }
                    }
                    array_push($nilaiperawatbulan, $nilaiperawat);
                }
                array_push($dataNilaiArray, $nilaiperawatbulan);
            }
            if ($pengambilandata == 1) {
                $who = "Perawat";
            } else {
                $who = $request->input('namaperawat');
            }
            $whodata = $perawats;
        }
        if ($pengambilandata == 3) {
//            $data = $query->where('nxt_siska_analisisrawatinap.iddokter', '=', $iddokter)->get();
            $who = "Dokter";
        }
        if ($pengambilandata == 4) {
//            $data = $query->where('nxt_siska_analisisrawatinap.idperawat', '=', $idperawat)->get();
            $who = "perawat";
        }
//        return $dataNilaiArray;

        $persentaseArrayTotal = array();
        for ($x = 0; $x < count($whodata); $x++) {
            $persentaseArray = array();
            for ($y = 0; $y < count($bulantext); $y++) {
                $nilai = $dataNilaiArray[$x][$y];
                $total = $dataTotalArray[$y];
                $persentase = ($total) ? (float)round($nilai/$total*100, 2) : round(0, 2);
                array_push($persentaseArray, $persentase);
            }
            array_push($persentaseArrayTotal, $persentaseArray);
        }

        /* Pembuatan PDF */
        $dataPDF = [
            'data' => $persentaseArrayTotal,
            'who' => $who,
            'whodata' => $whodata,
            'tglawal' => $tglawal->translatedFormat('d F Y'),
            'tglakhir' => $tglakhir->subMonth()->endOfMonth()->translatedFormat('d F Y'),
            'bulan' => $bulantext,
            'terhadap' => $terhadaptext,
            'filename' => 'analisis'.$who.'_'.date('d_m_Y', strtotime($tglawal)).'-'.date('d_m_Y', strtotime($tglakhir)),
        ];
        return Response::json([
            'status' => 'success',
            'data' => $dataPDF,
        ], 200);
//        $dataPDF = [
//            'persentase' => $persentaseArrayTotal,
//            'who' => $who,
//            'whodata' => $whodata,
//            'tglawal' => $tglawal->translatedFormat('d F Y'),
//            'tglakhir' => $tglakhir->subMonth()->endOfMonth()->translatedFormat('d F Y'),
//            'bulan' => $bulantext,
//            'terhadap' => $terhadaptext,
//        ];
//        $who = preg_replace('/\s+/', '', $who);
//        $pdf = App::make('dompdf.wrapper');
//        $pdf->loadView('pdf', $dataPDF);
//        $pdf->setPaper('A4', 'landscape');
//        $pdffix = $pdf->output();
//        $pdffix = base64_encode($pdffix);
//        return Response::json([
//            'status' => 'success',
//            'data' => $pdffix,
//            'filename' => 'analisis'.$who.'_'.date('d_m_Y', strtotime($tglawal)).'-'.date('d_m_Y', strtotime($tglakhir)),
//        ], 200);
    }

    public function show($id)
    {
//        $data = Analisisrawatinap::find($id);
        $query = Analisisrawatinap::eloquentQuery('id', 'asc', '', [
            "formulir",
            'perawat',
            'dokter',
            'ranap'
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
    public function laporan(Request $request) {
        $sortBy = $request->input('column');
        $orderBy = $request->input('dir');
        $searchValue = $request->input('search');

        $tglawal = $request->input('tglawal');
        $tglawal = Carbon::parse($tglawal);
        $tglakhir = $request->input('tglakhir');
        $tglakhir = Carbon::parse($tglakhir)->addMonthNoOverflow();
        $diffindays = $tglakhir->diffInDays($tglawal);

        $diffinmonth =  round($diffindays/30);

        $bulantext = array();
        $bulan = array();
        for ($x = 0; $x < $diffinmonth; $x++) {
            if ($x == 0) {
                $bulanmulai = Carbon::parse($tglawal)->startOfDay()->format('Y-m-d');
            } else {
                $bulanmulai = Carbon::parse($bulan[$x-1]->bulanawal)->addMonth()->startOfDay()->format('Y-m-d');
            }
            $bulanakhir = Carbon::parse($bulanmulai)->endOfMonth()->endOfDay()->format('Y-m-d');
            $obj = new \stdClass();
            $obj->bulanawal = $bulanmulai;
            $obj->bulanakhir = $bulanakhir;
            array_push($bulantext, Carbon::parse($bulanmulai)->translatedFormat('F Y'));
            $bulan[$x] = $obj;
        }

        $dataBulan = array();
        for ($x = 0; $x < $diffinmonth; $x++) {
            $query = Analisisrawatinap::eloquentQuery($sortBy, $orderBy, $searchValue, [
                "formulir",
                'perawat',
                'dokter',
                'ranap',
            ]);
            $bulanawal = Carbon::parse($bulan[$x]->bulanawal);
            $bulanakhir = Carbon::parse($bulan[$x]->bulanakhir)->addDay();

            $getBulan = $query
                ->where('tglinput', '>=', $bulanawal)
                ->where('tglinput', '<', $bulanakhir)
                ->whereNotNull('tgllengkap')
                ->get();
            array_push($dataBulan, $getBulan);
        }

        $dataTotalArrayDokter = array();
        $dokters = TenagaMedis::where('jenis_id', '=', 1)->get();
        foreach ($dataBulan as $tiapBulan) {
            if (count($tiapBulan) !== 0) {
                $totalNilai = 0;
                $nilailengkap = 0;
                $nilaitidaklengkap = 0;
                foreach ($tiapBulan as $bulan) {
                    foreach ($dokters as $dokter) {
                        if (!empty($bulan)) {
                            foreach ($bulan->formulir as $formulir) {
                                $pivot = $formulir['pivot'];
                                $forms = AnalisisData::where('idanalisis', '=', $pivot['analisisid'])
                                    ->where('idformulir', '=', 1)
                                    ->where('dokter_id', '=', $dokter->id)
                                    ->get();
                                foreach ($forms as $form) {
                                    if (!empty($form)) {
                                        $totalNilai += 1;
                                        if ($form->nilai == 2) {
                                            $nilailengkap += 1;
                                        } else {
                                            $nilaitidaklengkap += 1;
                                        }
                                    }
                                }
                            }
                        }

                    }
                }
                array_push($dataTotalArrayDokter, array($nilaitidaklengkap, $nilailengkap, $totalNilai));
            } else {
                array_push($dataTotalArrayDokter, array(0,0,0));
            }
        }
//        return $dataTotalArrayDokter;

        $dataTotalArrayPerawat = array();
        $perawats = TenagaMedis::where('jenis_id', '=', 2)->get();
        foreach ($dataBulan as $tiapBulan) {
            if (count($tiapBulan) !== 0) {
                $totalNilai = 0;
                $nilailengkap = 0;
                $nilaitidaklengkap = 0;
                foreach ($tiapBulan as $bulan) {
                    foreach ($perawats as $perawat) {
                        if (!empty($bulan)) {
                            foreach ($bulan->formulir as $formulir) {
                                $pivot = $formulir['pivot'];
                                $forms = AnalisisData::where('idanalisis', '=', $pivot['analisisid'])
                                    ->where('idformulir', '=', 2)
                                    ->where('perawat_id', '=', $perawat->id)
                                    ->get();
                                foreach ($forms as $form) {
                                    if (!empty($form)) {
//                                        return $form;
                                        $totalNilai += 1;
                                        if ($form->nilai == 2) {
                                            $nilailengkap += 1;
                                        } else {
                                            $nilaitidaklengkap += 1;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                array_push($dataTotalArrayPerawat, array($nilaitidaklengkap, $nilailengkap, $totalNilai));
            } else {
                array_push($dataTotalArrayPerawat, array(0,0,0));
            }
        }

//        return $dataTotalArrayDokter;
        $datalengkap = array();
        $datatidaklengkap = array();
        $datalengkaptabel = array();
        $datatidaklengkaptabel = array();
        for ($x = 0; $x < $diffinmonth; $x++) {
            $nilailengkapdokter = $dataTotalArrayDokter[$x][0];
            $nilaitidaklengkapdokter = $dataTotalArrayDokter[$x][1];
            $nilaitotaldokter = $dataTotalArrayDokter[$x][2];

            $nilailengkapperawat = $dataTotalArrayPerawat[$x][0];
            $nilaitidaklengkapperawat = $dataTotalArrayPerawat[$x][1];
            $nilaitotalperawat = $dataTotalArrayPerawat[$x][2];

            $nilaitotalbulan = $nilaitotaldokter + $nilaitotalperawat;

            $persentaselengkapdokterbulan = ($nilaitotalbulan) ? (float)round($nilailengkapdokter / $nilaitotalbulan * 100, 2) : round(0, 2);
            $persentasetidaklengkapdokterbulan = ($nilaitotalbulan) ? (float)round($nilaitidaklengkapdokter / $nilaitotalbulan * 100, 2) : round(0, 2);

            $persentaselengkapperawatbulan = ($nilaitotalbulan) ? (float) round($nilailengkapperawat / $nilaitotalbulan * 100, 2) : round(0, 2);
            $persentasetidaklengkapperawatbulan = ($nilaitotalbulan) ? (float)round($nilaitidaklengkapperawat / $nilaitotalbulan * 100, 2) : round(0, 2);

            array_push($datalengkap, array($persentaselengkapdokterbulan, $persentaselengkapperawatbulan));
            array_push($datatidaklengkap, array($persentasetidaklengkapdokterbulan, $persentasetidaklengkapperawatbulan));
            array_push($datalengkaptabel, array($persentaselengkapdokterbulan, $persentaselengkapperawatbulan, $nilailengkapdokter, $nilailengkapperawat, $nilaitotalbulan));
            array_push($datatidaklengkaptabel, array($persentasetidaklengkapdokterbulan, $persentasetidaklengkapperawatbulan, $nilaitidaklengkapdokter, $nilaitidaklengkapperawat, $nilaitotalbulan));
        }

        if ($request->input('kelengkapan') == 1) {
            $data = $datalengkap;
            $datatabel = $datalengkaptabel;
            $text = "Kelengkapan Dokter dan Perawat";
        } else {
            $data = $datatidaklengkap;
            $datatabel = $datatidaklengkaptabel;
            $text = "Ketidaklengkapan Dokter dan Perawat";
        }

        $dataPDF = [
            'data' => $data,
            'datatabel' => $datatabel,
            'text' => $text,
            'tglawal' => $tglawal->translatedFormat('d F Y'),
            'tglakhir' => $tglakhir->subMonth()->endOfMonth()->translatedFormat('d F Y'),
            'bulan' => $bulantext,
            'filename' => 'analisiskelengkapan'.'_'.date('d_m_Y', strtotime($tglawal)).'-'.date('d_m_Y', strtotime($tglakhir)),
        ];
        return Response::json([
            'status' => 'success',
            'data' => $dataPDF,
        ], 200);
//        $pdf = App::make('dompdf.wrapper');
//        $pdf->loadView('laporankelengkapan', $dataPDF);
//        $pdf->setPaper('A4', 'landscape');
////        return $pdf->download("analisis.pdf");
//        $pdffix = $pdf->output();
//        $pdffix = base64_encode($pdffix);
//        return Response::json([
//            'status' => 'success',
//            'data' => $pdffix,
//            'filename' => 'analisiskelengkapan'.'_'.date('d_m_Y', strtotime($tglawal)).'-'.date('d_m_Y', strtotime($tglakhir)),
//        ], 200);
    }

    public function laporanMutu(Request $request) {
        $sortBy = $request->input('column');
        $orderBy = $request->input('dir');
        $searchValue = $request->input('search');

        $tglawal = $request->input('tglawal');
        $tglawal = Carbon::parse($tglawal);
        $tglakhir = $request->input('tglakhir');
        $tglakhir = Carbon::parse($tglakhir)->addMonthNoOverflow();
        $diffindays = $tglakhir->diffInDays($tglawal);

        $diffinmonth =  round($diffindays/30);

        $bulantext = array();
        $bulan = array();
        for ($x = 0; $x < $diffinmonth; $x++) {
            if ($x == 0) {
                $bulanmulai = Carbon::parse($tglawal)->startOfDay()->format('Y-m-d');
            } else {
                $bulanmulai = Carbon::parse($bulan[$x-1]->bulanawal)->addMonth()->startOfDay()->format('Y-m-d');
            }
            $bulanakhir = Carbon::parse($bulanmulai)->endOfMonth()->endOfDay()->format('Y-m-d');
            $obj = new \stdClass();
            $obj->bulanawal = $bulanmulai;
            $obj->bulanakhir = $bulanakhir;
            array_push($bulantext, Carbon::parse($bulanmulai)->translatedFormat('F Y'));
            $bulan[$x] = $obj;
        }

        $dataBulan = array();
        for ($x = 0; $x < $diffinmonth; $x++) {
            $query = Analisisrawatinap::eloquentQuery($sortBy, $orderBy, $searchValue, [
                "formulir",
                'perawat',
                'dokter',
                'ranap',
            ]);
            $bulanawal = Carbon::parse($bulan[$x]->bulanawal);
            $bulanakhir = Carbon::parse($bulan[$x]->bulanakhir)->addDay();

            $getBulan = $query
                ->where('tglinput', '>=', $bulanawal)
                ->where('tglinput', '<', $bulanakhir)
                ->whereNotNull('tgllengkap')
                ->get();
            array_push($dataBulan, $getBulan);
        }

        $dataTotalArray = array();
        foreach ($dataBulan as $tiapBulan) {
            if (count($tiapBulan) !== 0) {
                $totaldokter = 0;
                $totalperawat = 0;
                $totaltidakdokter = 0;
                $totaltidakperawat = 0;
                $totalnilaidokter = 0;
                $totalnilaiperawat = 0;
                foreach ($tiapBulan as $bulan) {
                    if (!empty($bulan)) {
                        if ($bulan['idstatus'] == 1 || $bulan['idstatus'] == 2) {
                            $formulirs = $bulan['formulir'];
                            foreach ($formulirs as $formulir) {
                                $formulirid = $formulir['pivot']['formulirid'];
                                if ($formulirid == 1) {
                                    $totaldokter+=1;
                                    $totalnilaidokter+=1;
                                }
                                if ($formulirid == 2) {
                                    $totalperawat+=1;
                                    $totalnilaiperawat+=1;
                                }
                            }
                        } else if ($bulan['idstatus'] == 3 || $bulan['idstatus'] == 4) {
                            $formulirs = $bulan['formulir'];
                            foreach ($formulirs as $formulir) {
                                $formulirid = $formulir['pivot']['formulirid'];
                                if ($formulirid == 1) {
                                    $totaltidakdokter+=1;
                                    $totalnilaidokter+=1;
                                }
                                if ($formulirid == 2) {
                                    $totaltidakperawat+=1;
                                    $totalnilaiperawat+=1;
                                }
                            }
                        }
                    }
                }
                array_push($dataTotalArray, array($totaldokter, $totaltidakdokter, $totalnilaidokter, $totalperawat, $totaltidakperawat, $totalnilaiperawat));
            } else {
                array_push($dataTotalArray, array(0,0,0,0,0,0));
            }
        }

        $datalengkap = array();
        $datatidaklengkap = array();
        $datalengkaptabel = array();
        $datatidaklengkaptabel = array();
        for ($x = 0; $x < $diffinmonth; $x++) {
            $nilailengkapdokter = $dataTotalArray[$x][0];
            $nilaitidaklengkapdokter = $dataTotalArray[$x][1];
            $nilaitotaldokter = $dataTotalArray[$x][2];

            $nilailengkapperawat = $dataTotalArray[$x][3];
            $nilaitidaklengkapperawat = $dataTotalArray[$x][4];
            $nilaitotalperawat = $dataTotalArray[$x][5];

            $persentaselengkapdokterbulan = ($nilaitotaldokter) ? (float)round($nilailengkapdokter / $nilaitotaldokter * 100, 2) : round(0, 2);
            $persentasetidaklengkapdokterbulan = ($nilaitotaldokter) ? (float)round($nilaitidaklengkapdokter / $nilaitotaldokter * 100, 2) : round(0, 2);

            $persentaselengkapperawatbulan = ($nilaitotalperawat) ? (float) round($nilailengkapperawat / $nilaitotalperawat * 100, 2) : round(0, 2);
            $persentasetidaklengkapperawatbulan = ($nilaitotalperawat) ? (float)round($nilaitidaklengkapperawat / $nilaitotalperawat * 100, 2) : round(0, 2);

//            array_push($datalengkap, array($persentaselengkapdokterbulan, $persentaselengkapperawatbulan, $nilailengkapdokter, $nilailengkapperawat, $nilaitotaldokter, $nilaitotalperawat));
//            array_push($datatidaklengkap, array($persentasetidaklengkapdokterbulan, $persentasetidaklengkapperawatbulan, $nilaitidaklengkapdokter, $nilaitidaklengkapperawat, $nilaitotaldokter, $nilaitotalperawat));
            array_push($datalengkaptabel, array($persentaselengkapdokterbulan, $persentaselengkapperawatbulan, $nilaitotaldokter, $nilaitotalperawat));
            array_push($datatidaklengkaptabel, array($persentasetidaklengkapdokterbulan, $persentasetidaklengkapperawatbulan, $nilaitotaldokter, $nilaitotalperawat));
            array_push($datalengkap, array($persentaselengkapdokterbulan, $persentaselengkapperawatbulan));
            array_push($datatidaklengkap, array($persentasetidaklengkapdokterbulan, $persentasetidaklengkapperawatbulan));
        }

        if ($request->input('kelengkapan') == 1) {
            $data = $datalengkap;
            $datatabel = $datalengkaptabel;
            $text = "Mutu Kelengkapan Dokter dan Perawat 2x24jam";
        } else {
            $data = $datatidaklengkap;
            $datatabel = $datatidaklengkaptabel;
            $text = "Mutu Ketidaklengkapan Dokter dan Perawat 2x24jam";
        }
//        return $data;

//        $chart = Chartisan::build()
//            ->labels($bulantext)
//            ->dataset('sample', $data);

        $dataPDF = [
            'data' => $data,
            'datatabel' => $datatabel,
            'text' => $text,
            'tglawal' => $tglawal->translatedFormat('d F Y'),
            'tglakhir' => $tglakhir->subMonth()->endOfMonth()->translatedFormat('d F Y'),
            'bulan' => $bulantext,
            'filename' => 'laporanmutu'.'_'.date('d_m_Y', strtotime($tglawal)).'-'.date('d_m_Y', strtotime($tglakhir)),
        ];
//        return view('laporanmutu', $dataPDF);
        return Response::json([
            'status' => 'success',
            'data' => $dataPDF,
        ], 200);
//        $pdf = App::make('dompdf.wrapper');
//        $pdf->loadView('laporanmutu', $dataPDF);
////        $pdf->setWarnings(true);
//        $pdf->setPaper('A4', 'landscape');
////        return $pdf->download("analisis.pdf");
//        $pdffix = $pdf->output();
//        $pdffix = base64_encode($pdffix);
//        return Response::json([
//            'status' => 'success',
//            'data' => $pdffix,
//            'filename' => 'laporanmutu'.'_'.date('d_m_Y', strtotime($tglawal)).'-'.date('d_m_Y', strtotime($tglakhir)),
//        ], 200);
    }
}
